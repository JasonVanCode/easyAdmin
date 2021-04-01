<?php
namespace App\Lib;

use EasySwoole\Component\Singleton;
use Swoole\WebSocket\Server as WebSocketServer;
use App\Lib\RedisConnect;

Class WorkerStartHandle{

    use Singleton;

    public function handle()
    {
        return function(WebSocketServer $server,$worker_id){
            go(function() use($worker_id,$server){
                $redis = RedisConnect::getInstance()->connect();
                $redis->subscribe(function ($redis, $pattern, $str) use ($server,$worker_id) {
                    $param_data = json_decode($str,true);
                    if( $param_data['worker_id'] == $worker_id){
                        $server->push($param_data['fd'],'hahahahahah');
                    }
                }, 'worker_chatchanel');
            });
            // echo 'worker_id:'.$worker_id.PHP_EOL;    
        };
    }

}
