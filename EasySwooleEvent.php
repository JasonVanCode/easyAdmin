<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use Co\Scheduler;
use EasySwoole\Component\Di;
use EasySwoole\Component\Process\Exception;
use EasySwoole\Component\TableManager;
use EasySwoole\EasySwoole\Http\Dispatcher;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\FastCache\Cache;
use EasySwoole\FastCache\Exception\RuntimeError;
use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\FastCache\CacheProcessConfig;
use EasySwoole\FastCache\SyncData;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Spl\SplArray;
use EasySwoole\Utility\File;
use Siam\Plugs\common\PlugsContain;
use Siam\Plugs\common\utils\PlugsHook;
use Siam\Plugs\PlugsInitialization;
use Swoole\Table;
use EasySwoole\Component\Process\Manager;
use App\Process\WebSocketProcess;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        // 初始化数据库ORM
        $configData = Config::getInstance()->getConf('MYSQL');
        $config = new \EasySwoole\ORM\Db\Config($configData);
        DbManager::getInstance()->addConnection(new Connection($config));

        \App\Event\Event::getInstance()->set('test', function () {
            echo 'test event';
        });

        // // 插件Basic初始化
        // TableManager::getInstance()->add("plugs_status", [
        //     'init_ed' => ['type'=>Table::TYPE_INT,'size'=>2]
        // ], 1);
        // TableManager::getInstance()->get("plugs_status")->set('1', [
        //     'init_ed' => 0
        // ]);
        // Dispatcher::getInstance()->setOnRouterCreate(function(AbstractRouter $router){
        //     PlugsHook::getInstance()->add("ROUTER_CREATE", function (AbstractRouter $router){
        //         PlugsContain::$router = $router;
        //         PlugsInitialization::initPlugsRouter($router);
        //         PlugsInitialization::initPlugsSystem();
        //     });
        //     PlugsHook::getInstance()->hook("ROUTER_CREATE", $router);
        // });
    }

    
    public static function mainServerCreate(EventRegister $register)
    {
        // $processConfig= new \EasySwoole\Component\Process\Config();
        // $processConfig->setProcessName('WebSocket');//设置进程名称
        // $processConfig->setArg(['a'=>123]);//传参
        // $processConfig->setRedirectStdinStdout(false);//是否重定向标准io
        // $processConfig->setPipeType($processConfig::PIPE_TYPE_SOCK_DGRAM);//设置管道类型
        // $processConfig->setEnableCoroutine(true);//是否自动开启协程
        // $processConfig->setMaxExitWaitTime(3);//最大退出等待时间
        // Manager::getInstance()->addProcess(new WebSocketProcess($processConfig));




        // ***************** 注册fast-cache *****************
        // 每隔5秒将数据存回文件
        // try {
        //     Cache::getInstance()->setTickInterval(5 * 1000);//设置定时频率
        //     Cache::getInstance()->setOnTick(function (SyncData $SyncData, CacheProcessConfig $cacheProcessConfig) {
        //         $data = [
        //             'data'  => $SyncData->getArray(),
        //             'queue' => $SyncData->getQueueArray(),
        //         ];
        //         $path = EASYSWOOLE_TEMP_DIR.'/FastCacheData/'.$cacheProcessConfig->getProcessName();
        //         File::createFile($path, serialize($data));
        //     });
        // } catch (RuntimeError $e) {
        //     echo "[Warn] --> fast-cache注册onTick失败\n";
        // }

        // // 启动时将存回的文件重新写入
        // try {
        //     Cache::getInstance()->setOnStart(function (CacheProcessConfig $cacheProcessConfig) {
        //         $path = EASYSWOOLE_TEMP_DIR.'/FastCacheData/'.$cacheProcessConfig->getProcessName();
        //         if (is_file($path)) {
        //             $data     = unserialize(file_get_contents($path));
        //             $syncData = new SyncData();
        //             $syncData->setArray($data['data']);
        //             $syncData->setQueueArray($data['queue']);
        //             return $syncData;
        //         }
        //         $syncData = new SyncData();
        //         $syncData->setArray(new SplArray());
        //         $syncData->setQueueArray(new SplArray());
        //         return $syncData;
        //     });
        // } catch (RuntimeError $e) {
        //     echo "[Warn] --> fast-cache注册onStart失败\n";
        // }

        // // 在守护进程时,php easyswoole stop 时会调用,落地数据
        // try {
        //     Cache::getInstance()->setOnShutdown(function (SyncData $SyncData, CacheProcessConfig $cacheProcessConfig) {
        //         $data = [
        //             'data'  => $SyncData->getArray(),
        //             'queue' => $SyncData->getQueueArray(),
        //         ];
        //         $path = EASYSWOOLE_TEMP_DIR.'/FastCacheData/'.$cacheProcessConfig->getProcessName();
        //         File::createFile($path, serialize($data));
        //     });
        // } catch (RuntimeError $e) {
        //     echo "[Warn] --> fast-cache注册onShuatdown失败\n";
        // }

        // try {
        //     Cache::getInstance()->setTempDir(EASYSWOOLE_TEMP_DIR)
        //         ->setServerName("easySiam_fast_cache")
        //         ->setProcessNum(3)
        //         ->attachToServer(ServerManager::getInstance()->getSwooleServer());
        // } catch (Exception $e) {
        //     echo "[Warn] --> fast-cache注册失败\n";
        // } catch (RuntimeError $e) {
        //     echo "[Warn] --> fast-cache注册失败\n";
        // }



    }

    public static function onRequest(Request $request, Response $response): bool
    {
        $allow_origin = array(
            // "http://www.siammm.cn",
            // 为了安全，应该配置指定域名才允许跨域
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

        // try {
        //     PlugsHook::getInstance()->hook('ON_REQUEST', $request, $response);
        // } catch (\Throwable $e) {
        //     echo $e->getMessage()."\n";
        //     return false;
        // }

        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // try {
        //     PlugsHook::getInstance()->hook('AFTER_REQUEST', $request, $response);
        // } catch (\Throwable $e) {
        //     echo $e->getMessage()."\n";
        // }
    }
}