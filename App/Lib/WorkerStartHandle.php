<?php
namespace App\Lib;

use EasySwoole\Component\Singleton;
use Swoole\WebSocket\Server as WebSocketServer;
use App\Lib\RedisConnect;
use EasySwoole\Component\TableManager;

Class WorkerStartHandle{

    use Singleton;

    /**
     * @description: 注册worker进程启动的回调函数
     * @param {*}
     * @return {*}
     */    
    public function handle()
    {
        return function(WebSocketServer $server,$worker_id){
            //订阅websocket连接消息
            go(function() use($worker_id,$server){
                $redis = RedisConnect::getInstance()->connect();
                $table = TableManager::getInstance()->get('websocket_user');
                $redis->subscribe(function ($redis, $pattern, $str) use ($server,$worker_id,$table) {
                    if(!$table->exist($worker_id)){
                        return;
                    }
                    //这里面存储的是该进程所有的fd连接
                    $user_fd_arrs = json_decode($table->get($worker_id)['user_fds'],true);
                    //这里面存储了，发送这条消息的worker_id 和 fd的信息数据
                    $param_data = json_decode($str,true);
                    foreach($user_fd_arrs as $v){
                        if($worker_id != $param_data['worker_id'] && $v['fd'] != $param_data['fd']){
                            $server->push($v['fd'],json_encode(['send_user_id'=>$param_data['user_id'],'msg'=>$param_data['msg']]));
                        }
                    }
                }, 'worker_chatchanel');
            });
            // echo 'worker_id:'.$worker_id.PHP_EOL;    
        };
    }

}
