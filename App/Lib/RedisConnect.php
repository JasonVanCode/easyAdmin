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
        try {
            return new Redis($this->getRedisConfig());
        } catch (\Exception $e) {
            throw $e;
        }
    
    }

    public function getRedisConfig()
    {
        $configData = Config::getInstance()->getConf('REDIS');
        return new RedisConfig([
            'host'      => $configData['host'],
            'port'      => $configData['port'],
            'serialize' => RedisConfig::SERIALIZE_NONE,
            'auth'      => $configData['auth']
        ]);
    }


}
