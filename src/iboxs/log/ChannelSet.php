<?php
// +----------------------------------------------------------------------
// | iboxsPHP [ WE CAN DO IT JUST iboxs ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2025 http://iboxsphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace iboxs\log;

use iboxs\Log;

/**
 * Class ChannelSet
 * @package iboxs\log
 * @mixin Channel
 */
class ChannelSet
{
    public function __construct(protected Log $log, protected array $channels)
    {
    }

    public function __call($method, $arguments)
    {
        foreach ($this->channels as $channel) {
            $this->log->channel($channel)->{$method}(...$arguments);
        }
    }
}
