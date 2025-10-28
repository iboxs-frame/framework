<?php
// +----------------------------------------------------------------------
// | iboxsPHP [ WE CAN DO IT JUST iboxs ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 http://lyweb.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: itlattice <notice@itgz8.com>
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace iboxs\facade;

use iboxs\Facade;
use iboxs\log\Channel;
use iboxs\log\ChannelSet;

/**
 * @see \iboxs\Log
 * @package iboxs\facade
 * @mixin \iboxs\Log
 * @method static string|null getDefaultDriver() 默认驱动
 * @method static mixed getConfig(null|string $name = null, mixed $default = null) 获取日志配置
 * @method static array getChannelConfig(string $channel, null $name = null, null $default = null) 获取渠道配置
 * @method static Channel|ChannelSet channel(string|array $name = null) driver() 的别名
 * @method static mixed createDriver(string $name)
 * @method static \iboxs\Log clear(string|array $channel = '*') 清空日志信息
 * @method static \iboxs\Log close(string|array $channel = '*') 关闭本次请求日志写入
 * @method static array getLog(string $channel = null) 获取日志信息
 * @method static bool save() 保存日志信息
 * @method static \iboxs\Log record(mixed $msg, string $type = 'info', array $context = [], bool $lazy = true) 记录日志信息
 * @method static \iboxs\Log write(mixed $msg, string $type = 'info', array $context = []) 实时写入日志信息
 * @method static Event listen($listener) 注册日志写入事件监听
 * @method static void log(string $level, string|\Stringable $message, array $context = []) 记录日志信息
 * @method static void emergency(string|\Stringable $message, array $context = []) 记录emergency信息
 * @method static void alert(string|\Stringable $message, array $context = []) 记录警报信息
 * @method static void critical(string|\Stringable $message, array $context = []) 记录紧急情况
 * @method static void error(string|\Stringable $message, array $context = []) 记录错误信息
 * @method static void warning(string|\Stringable $message, array $context = []) 记录warning信息
 * @method static void notice(string|\Stringable $message, array $context = []) 记录notice信息
 * @method static void info(string|\Stringable $message, array $context = []) 记录一般信息
 * @method static void debug(string|\Stringable $message, array $context = []) 记录调试信息
 * @method static void sql(string|\Stringable $message, array $context = []) 记录sql信息
 * @method static mixed __call($method, $parameters)
 */
class Log extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'log';
    }
}
