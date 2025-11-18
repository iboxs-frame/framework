<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2025 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
namespace iboxs\session\driver;

use Psr\SimpleCache\CacheInterface;
use iboxs\contract\SessionHandlerInterface;
use iboxs\helper\Arr;
use iboxs\redis\Redis;

class Cache implements SessionHandlerInterface
{

    /** @var CacheInterface */
    protected $handler;

    /** @var integer */
    protected $expire;

    /** @var string */
    protected $prefix;

    public function __construct(\iboxs\Cache $cache, array $config = [])
    {
        $this->handler = $cache->store(Arr::get($config, 'store'));
        $this->expire  = Arr::get($config, 'expire', 1440);
        $this->prefix  = Arr::get($config, 'prefix', '');
    }

    public function read(string $sessionId): string
    {
        $val=Redis::basic()->get($this->prefix . $sessionId);
        if($val===null){
            return '';
        }else{
            if(is_array($val)){
                return serialize($val);
            }
            return $val;
        }

        // return (string) $this->handler->get($this->prefix . $sessionId);
    }

    public function delete(string $sessionId): bool
    {
        // return $this->handler->delete($this->prefix . $sessionId);
        return Redis::basic()->del($this->prefix . $sessionId)>0;
    }

    public function write(string $sessionId, string $data): bool
    {
        // return $this->handler->set($this->prefix . $sessionId, $data, $this->expire);
        return Redis::basic()->set($this->prefix . $sessionId,$data,$this->expire);
    }
}
