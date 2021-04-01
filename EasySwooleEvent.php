<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Http\Message\Status;
use EasySwoole\Log\LoggerInterface;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\EasySwoole\Task\TaskManager;
use App\Lib\WsMessageHandle;
use App\Lib\WorkerStartHandle;
use App\Lib\RedisConnect;


class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // 初始化数据库ORM
        $configData = Config::getInstance()->getConf('MYSQL');
        $config = new \EasySwoole\ORM\Db\Config($configData);
        DbManager::getInstance()->addConnection(new Connection($config));

        \EasySwoole\Component\Di::getInstance()->set(\EasySwoole\EasySwoole\SysConst::HTTP_GLOBAL_ON_REQUEST, function (\EasySwoole\Http\Request $request, \EasySwoole\Http\Response $response): bool {
            $allow_origin = array(
                "http://localhost:8888",
                "http://192.168.137.34:8888",
                "http://192.168.8.102:8888"
            );
            $origin = $request->getHeader('origin');
            if ($origin !== []){
                $origin = $origin[0];
                if(empty($allow_origin) || in_array($origin, $allow_origin)){
                    $response->withHeader('Access-Control-Allow-Origin', $origin);
                    $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
                    $response->withHeader('Access-Control-Allow-Credentials', 'true');
                    $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, token');
                    if ($request->getMethod() === 'OPTIONS') {
                        $response->withStatus(Status::CODE_OK);
                        return false;
                    }
                }
            }
            $response->withHeader('Content-type', 'application/json;charset=utf-8');
            return true;
        });
    }


    public static function mainServerCreate(EventRegister $register)
    {
        //添加workerstart事件
        $register->add($register::onWorkerStart,WorkerStartHandle::getInstance()->handle());
        //添加message事件
        $register->add($register::onMessage,WsMessageHandle::getInstance()->handle());

    }

}