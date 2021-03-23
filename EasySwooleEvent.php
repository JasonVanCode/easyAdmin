<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;



use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use App\Lib\RedisConnect;
use EasySwoole\Http\Message\Status;


class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        // 初始化数据库ORM
        $configData = Config::getInstance()->getConf('MYSQL');
        $config = new \EasySwoole\ORM\Db\Config($configData);
        DbManager::getInstance()->addConnection(new Connection($config));
        //初始化redis的连接
        RedisConnect::getInstance()->connect();

        \EasySwoole\Component\Di::getInstance()->set(\EasySwoole\EasySwoole\SysConst::HTTP_GLOBAL_ON_REQUEST, function (\EasySwoole\Http\Request $request, \EasySwoole\Http\Response $response): bool {
            $allow_origin = array(
                "http://localhost:8888"
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

        // \App\Event\Event::getInstance()->set('test', function () {
        //     echo 'test event';
        // });

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