<?php
namespace App\Lib;

use EasySwoole\Component\Singleton;
use Swoole\WebSocket\Server as WebSocketServer;


Class WsMessageHandle{

    use Singleton;

    public function handle()
    {
        return function(WebSocketServer $server,$frame){
          // echo $worker_id.'start'.PHP_EOL;
          echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
          $server->push($frame->fd,'send push data');
          echo 'worker 进程编号:'.$server->worker_id.PHP_EOL;
          $param_data = ['fd'=>$frame->fd,'worker_id'=>$server->worker_id];
          $redis = RedisConnect::getInstance()->connect();
          $redis->publish('worker_chatchanel',json_encode($param_data));
          // var_dump($data);
        };
    }
    

}
