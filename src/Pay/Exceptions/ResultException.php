<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/11   15:32
 */

namespace bydls\pays\Pay\Exceptions;


class ResultException extends Exception
{
    /**返回结果异常异常
     * InvalidSignException constructor.
     * @param String $message
     */
    public function __construct(String $message,$raw = [])
    {
        parent::__construct('RESULT_ERROR: '.$message, self::RESULT_ERROR,$raw = []);
    }
}