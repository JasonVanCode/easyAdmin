<?php
namespace App\Lib;

use EasySwoole\Component\Singleton;

//配置orm数据连接
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\EasySwoole\Config;

Class RedisConnect{

    use Singleton;

    public $redis;

    public function connect(): ?Redis
    {
        // $conf = \Yaconf::get("redis");
        $configData = Config::getInstance()->getConf('REDIS');
        try {
            //code...
            $this->redis =  new Redis(new RedisConfig([
                'host'      => $configData['host'],
                'port'      => $configData['port'],
                'serialize' => RedisConfig::SERIALIZE_NONE
            ]));
            return $this->redis;
        } catch (\Exception $e) {
            throw $e;
        }
    
    }


}
