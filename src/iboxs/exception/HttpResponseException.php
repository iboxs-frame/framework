<?php
// +----------------------------------------------------------------------
// | iboxsPHP [ WE CAN DO IT JUST iboxs IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2025 http://iboxsphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace iboxs\exception;

use iboxs\Response;

/**
 * HTTP响应异常
 */
class HttpResponseException extends \RuntimeException
{
    public function __construct(protected Response $response)
    {
    }

    public function getResponse()
    {
        return $this->response;
    }

}
