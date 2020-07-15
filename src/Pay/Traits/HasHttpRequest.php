<?php

namespace bydls\pays\Traits;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait HasHttpRequest.
 *
 * @property string $baseUri
 * @property float  $timeout
 * @property float  $connectTimeout
 */
trait HasHttpRequest
{
    /**
     * Http client.
     *
     * @var Client|null
     */
    protected $httpClient = null;

    /**
     * Http client options.
     *
     * @var array
     */
    protected $httpOptions = [];


    /**发送一个get请求
     * @param string $endpoint
     * @param array $query
     * @param array $headers
     * @return array|string
     */
    public function get(string $endpoint, array $query = [], array $headers = [])
    {
        return $this->request('get', $endpoint, [
            'headers' => $headers,
            'query' => $query,
        ]);
    }

    /**发送一个post请求
     * @param string $endpoint
     * @param $data
     * @param array $options
     * @return array|string
     */
    public function post(string $endpoint, $data, array $options = [])
    {
        if (!is_array($data)) {
            $options['body'] = $data;
        } else {
            $options['form_params'] = $data;
        }

        return $this->request('post', $endpoint, $options);
    }


    /**发送请求
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return array|string
     */
    public function request(string $method, string $endpoint, array $options = [])
    {
        return $this->unwrapResponse($this->getHttpClient()->{$method}($endpoint, $options));
    }

    /**
     * Set http client.
     * @return $this
     */
    public function setHttpClient(Client $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Return http client.
     */
    public function getHttpClient(): Client
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = $this->getDefaultHttpClient();
        }

        return $this->httpClient;
    }

    /**
     * Get default http client.
     */
    public function getDefaultHttpClient(): Client
    {
        return new Client($this->getOptions());
    }

    /**
     * setBaseUri.
     * @return $this
     */
    public function setBaseUri(string $url): self
    {
        if (property_exists($this, 'baseUri')) {
            $parsedUrl = parse_url($url);

            $this->baseUri = ($parsedUrl['scheme'] ?? 'http').'://'.
                $parsedUrl['host'].(isset($parsedUrl['port']) ? (':'.$parsedUrl['port']) : '');
        }

        return $this;
    }

    /**
     * getBaseUri.
     */
    public function getBaseUri(): string
    {
        return property_exists($this, 'baseUri') ? $this->baseUri : '';
    }

    public function getTimeout(): float
    {
        return property_exists($this, 'timeout') ? $this->timeout : 5.0;
    }

    public function setTimeout(float $timeout): self
    {
        if (property_exists($this, 'timeout')) {
            $this->timeout = $timeout;
        }

        return $this;
    }

    public function getConnectTimeout(): float
    {
        return property_exists($this, 'connectTimeout') ? $this->connectTimeout : 3.0;
    }

    public function setConnectTimeout(float $connectTimeout): self
    {
        if (property_exists($this, 'connectTimeout')) {
            $this->connectTimeout = $connectTimeout;
        }

        return $this;
    }

    /**
     * Get default options.
     */
    public function getOptions(): array
    {
        return array_merge([
            'base_uri' => $this->getBaseUri(),
            'timeout' => $this->getTimeout(),
            'connect_timeout' => $this->getConnectTimeout(),
        ], $this->getHttpOptions());
    }

    /**
     * setOptions.
     * @return $this
     */
    public function setOptions(array $options): self
    {
        return $this->setHttpOptions($options);
    }

    public function getHttpOptions(): array
    {
        return $this->httpOptions;
    }

    public function setHttpOptions(array $httpOptions): self
    {
        $this->httpOptions = $httpOptions;

        return $this;
    }

    /**
     * Convert response.
     * @return array|string
     */
    public function unwrapResponse(ResponseInterface $response)
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $contents = $response->getBody()->getContents();

        if (false !== stripos($contentType, 'json') || stripos($contentType, 'javascript')) {
            return json_decode($contents, true);
        } elseif (false !== stripos($contentType, 'xml')) {
            return json_decode(json_encode(simplexml_load_string($contents, 'SimpleXMLElement', LIBXML_NOCDATA), JSON_UNESCAPED_UNICODE), true);
        }

        return $contents;
    }
}
