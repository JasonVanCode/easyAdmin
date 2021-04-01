<?php
namespace App\Lib;

use EasySwoole\Component\Singleton;

//配置orm数据连接
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\EasySwoole\Config;

Class RedisConnect{

    use Singleton;

    public function connect(): ?Redis
    {
        // $conf = \Yaconf::get("redis");
        $configData = Config::getInstance()->getConf('REDIS');
        try {
            //code...
            return new Redis(new RedisConfig([
                'host'      => $configData['host'],
                'port'      => $configData['port'],
                'serialize' => RedisConfig::SERIALIZE_NONE
            ]));
        } catch (\Exception $e) {
            throw $e;
        }
    
    }


}
