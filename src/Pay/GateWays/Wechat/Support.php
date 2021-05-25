<?php

namespace bydls\pays\Pay\GateWays\Wechat;

use bydls\pays\Pay\Events\ApiRequested;
use bydls\pays\Pay\Events\SignFailed;
use bydls\Utils;
use Exception;
use bydls\pays\Pay\Events\Events;
use bydls\pays\Pay\Exceptions\ResultException;
use bydls\pays\Pay\Exceptions\GatewayException;
use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\pays\Pay\Exceptions\InvalidSignException;
use bydls\pays\Pay\GateWays\Wechat;
use bydls\pays\Log\Log;
use bydls\Support\Collection;
use bydls\pays\Pay\Config\Config;
use bydls\pays\Traits\HasHttpRequest;

/**
 *
 * @property string appid
 * @property string app_id
 * @property string miniapp_id
 * @property string sub_appid
 * @property string sub_app_id
 * @property string sub_miniapp_id
 * @property string mch_id
 * @property string sub_mch_id
 * @property string key
 * @property string return_url
 * @property string cert_client
 * @property string cert_key
 * @property array log
 * @property array http
 * @property string mode
 */
class Support
{
    use HasHttpRequest;

    /**
     * Wechat gateway.
     *
     * @var string
     */
    protected $baseUri;

    /**
     * Config.
     * @var Config
     */
    protected $config;

    /**
     * Instance.
     *
     * @var Support
     */
    private static $instance;

    /**
     * Bootstrap.
     *
     */
    private function __construct(Config $config)
    {
        $this->baseUri = Wechat::URL[$config->get('mode', Wechat::MODE_NORMAL)];
        $this->config = $config;

        $this->setHttpOptions();
    }

    /**
     * __get.
     *
     *
     * @param $key
     *
     * @return mixed|Config|null
     */
    public function __get($key)
    {
        return $this->getConfig($key);
    }

    /**
     * create.
     *
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     *
     * @return Support
     */
    public static function create(Config $config)
    {
        if ('cli' === php_sapi_name() || !(self::$instance instanceof self)) {
            self::$instance = new self($config);

            self::setDevKey();
        }

        return self::$instance;
    }

    /**
     * getInstance.
     *
     *
     * @throws InvalidArgumentException
     *
     * @return Support
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            throw new InvalidArgumentException('You Should [Create] First Before Using');
        }

        return self::$instance;
    }

    /**
     * clear.
     *
     */
    public static function clear()
    {
        self::$instance = null;
    }

    /**
     * @param $endpoint
     * @param $data
     * @param bool $cert
     * @return Collection
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     * @throws ResultException
     */
    public static function requestApi($endpoint, $data, $cert = false): Collection
    {
        Events::dispatch(new ApiRequested('Wechat', '', self::$instance->getBaseUri().$endpoint, $data));

        $result = self::$instance->post(
            $endpoint,
            self::toXml($data),
            $cert ? [
                'cert' => self::$instance->cert_client,
                'ssl_key' => self::$instance->cert_key,
            ] : []
        );
        $result = is_array($result) ? $result : self::fromXml($result);

        Events::dispatch(new ApiRequested('Wechat', '', self::$instance->getBaseUri().$endpoint, $result));

        return self::processingApiResult($endpoint, $result);
    }


    /**
     * Filter payload.
     * @param $payload
     * @param $params
     * @param bool $preserve_notify_url
     * @return array
     * @throws InvalidArgumentException
     */
    public static function filterPayload($payload, $params, $preserve_notify_url = false): array
    {
        $type = self::getTypeName($params['type'] ?? '');

        $payload = array_merge(
            $payload,
            is_array($params) ? $params : ['out_trade_no' => $params]
        );
        $payload['appid'] = self::$instance->getConfig($type, '');

        if (Wechat::MODE_SERVICE === self::$instance->getConfig('mode', Wechat::MODE_NORMAL)) {
            $payload['sub_appid'] = self::$instance->getConfig('sub_'.$type, '');
        }

        unset($payload['trade_type'], $payload['type']);
        if (!$preserve_notify_url) {
            unset($payload['notify_url']);
        }

        $payload['sign'] = self::generateSign($payload);

        return $payload;
    }


    /**
     *  Generate wechat sign.
     * @param $data
     * @return string
     * @throws InvalidArgumentException
     */
    public static function generateSign($data): string
    {
        $key = self::$instance->key;

        if (is_null($key)) {
            throw new InvalidArgumentException('Missing Wechat Config -- [key]');
        }

        ksort($data);

        $string = md5(self::getSignContent($data).'&key='.$key);

        Log::debug('Wechat Generate Sign Before UPPER', [$data, $string]);

        return strtoupper($string);
    }


    /**
     * Generate sign content.
     * @param $data
     * @return string
     */
    public static function getSignContent($data): string
    {
        $buff = '';

        foreach ($data as $k => $v) {
            $buff .= ('sign' != $k && '' != $v && !is_array($v)) ? $k.'='.$v.'&' : '';
        }

        Log::debug('Wechat Generate Sign Content Before Trim', [$data, $buff]);

        return trim($buff, '&');
    }

    /**
     * Decrypt refund contents
     * @param $contents
     * @return string
     */
    public static function decryptRefundContents($contents): string
    {
        return openssl_decrypt(
            base64_decode($contents),
            'AES-256-ECB',
            md5(self::$instance->key),
            OPENSSL_RAW_DATA
        );
    }

    /**
     * Convert array to xml.
     * @param $data
     * @return string
     * @throws InvalidArgumentException
     */
    public static function toXml($data): string
    {
        if (!is_array($data) || count($data) <= 0) {
            throw new InvalidArgumentException('Convert To Xml Error! Invalid Array!');
        }

        $xml = '<xml>';
        foreach ($data as $key => $val) {
            $xml .= is_numeric($val) ? '<'.$key.'>'.$val.'</'.$key.'>' :
                                       '<'.$key.'><![CDATA['.$val.']]></'.$key.'>';
        }
        $xml .= '</xml>';

        return $xml;
    }

    /**Convert xml to array.
     * @param $xml
     * @return array
     * @throws InvalidArgumentException
     */
    public static function fromXml($xml): array
    {
        if (!$xml) {
            throw new InvalidArgumentException('Convert To Array Error! Invalid Xml!');
        }

        libxml_disable_entity_loader(true);

        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * Get service config.
     *
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return mixed|null
     */
    public function getConfig($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->config->all();
        }

        if ($this->config->has($key)) {
            return $this->config[$key];
        }

        return $default;
    }

    /**
     * Get app id according to param type.
     * @param string $type
     * @return string
     */
    public static function getTypeName($type = ''): string
    {
        switch ($type) {
            case '':
                $type = 'app_id';
                break;
            case 'app':
                $type = 'appid';
                break;
            default:
                $type = $type.'_id';
        }

        return $type;
    }

    /**
     * Get Base Uri.
     *
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * processingApiResult
     * @param $endpoint
     * @param array $result
     * @return Collection
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     * @throws ResultException
     */
    protected static function processingApiResult($endpoint, array $result)
    {
        if (!isset($result['return_code']) || 'SUCCESS' != $result['return_code']) {
            throw new GatewayException('Get Wechat API Error:'.($result['return_msg'] ?? $result['retmsg'] ?? ''), $result);
        }

        if (isset($result['result_code']) && 'SUCCESS' != $result['result_code']) {
            throw new ResultException('Wechat Business Error: '.$result['err_code'].' - '.$result['err_code_des'], $result);
        }

        if ('pay/getsignkey' === $endpoint ||
            false !== strpos($endpoint, 'mmpaymkttransfers') ||
            self::generateSign($result) === $result['sign']) {
            return new Collection($result);
        }

        Events::dispatch(new SignFailed('Wechat', '', $result));

        throw new InvalidSignException('Wechat Sign Verify FAILED', $result);
    }

    /**
     * setDevKey.
     *
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     * @throws Exception
     *
     * @return Support
     */
    private static function setDevKey()
    {
        if (Wechat::MODE_DEV == self::$instance->mode) {
            $data = [
                'mch_id' => self::$instance->mch_id,
                'nonce_str' => Utils::random(16),
            ];
            $data['sign'] = self::generateSign($data);

            $result = self::requestApi('pay/getsignkey', $data);

            self::$instance->config->put('key', $result['sandbox_signkey']);
        }

        return self::$instance;
    }

    /**
     * Set Http options.
     */
    private function setHttpOptions(): self
    {
        if ($this->config->has('http') && is_array($this->config->get('http'))) {
            $this->config->forget('http.base_uri');
            $this->httpOptions = $this->config->get('http');
        }

        return $this;
    }
}
