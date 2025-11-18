<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2025 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace iboxs\cache\driver;

use DateInterval;
use DateTimeInterface;
use iboxs\cache\Driver;
use iboxs\exception\InvalidCacheException;
use iboxs\redis\Redis as IboxsRedis;

class Redis extends Driver
{
    /**
     * 判断缓存
     * @access public
     * @param string $name 缓存变量名
     * @return bool
     */
    public function has($name): bool
    {
        return IboxsRedis::basic()->exists($name);
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name    缓存变量名
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function get($name, $default = null): mixed
    {
        return IboxsRedis::basic()->get($name,$default);
    }

    /**
     * 写入缓存
     * @access public
     * @param string                                 $name   缓存变量名
     * @param mixed                                  $value  存储数据
     * @param integer|DateInterval|DateTimeInterface $expire 有效时间（秒）
     * @return bool
     */
    public function set($name, $value, $expire = null): bool
    {
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }

        return IboxsRedis::basic()->set($name,$value,$expire);
    }

    /**
     * 自增缓存（针对数值缓存）
     * @access public
     * @param string $name 缓存变量名
     * @param int    $step 步长
     * @return false|int
     */
    public function inc($name, $step = 1)
    {
        return IboxsRedis::basic()->inc($name,$step);
    }

    /**
     * 自减缓存（针对数值缓存）
     * @access public
     * @param string $name 缓存变量名
     * @param int    $step 步长
     * @return false|int
     */
    public function dec($name, $step = 1)
    {
        return IboxsRedis::basic()->dec($name,$step);
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return bool
     */
    public function delete($name): bool
    {
        return IboxsRedis::basic()->del($name)>0;
    }

    /**
     * 清除缓存
     * @access public
     * @return bool
     */
    public function clear(): bool
    {
        return IboxsRedis::sysmatic()->flushDb();
    }

    /**
     * 删除缓存标签
     * @access public
     * @param array $keys 缓存标识列表
     * @return void
     */
    public function clearTag($keys): void
    {
        IboxsRedis::basic()->del($keys);
    }

    /**
     * 追加TagSet数据
     * @access public
     * @param string $name  缓存标识
     * @param mixed  $value 数据
     * @return void
     */
    public function append($name, $value): void
    {
        IboxsRedis::Set()->add($name,$value);
    }

    /**
     * 获取标签包含的缓存标识
     * @access public
     * @param string $tag 缓存标签
     * @return array
     */
    public function getTagItems(string $tag): array
    {
       return IboxsRedis::Set()->members($tag);
    }
}
