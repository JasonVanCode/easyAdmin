<?php
namespace App\Lib;

use EasySwoole\Component\Singleton;
use Swoole\WebSocket\Server as WebSocketServer;
use EasySwoole\Component\TableManager;


Class WsMessageHandle{

    use Singleton;

    public function handle()
    {

        return function(WebSocketServer $server,$frame){
          // echo $worker_id.'start'.PHP_EOL;
          // $table = TableManager::getInstance()->get('websocket_user');
          // $data = json_decode($frame->data,true);
          // if(isset($data['is_first']) && !$table->exist($data['token'])){
          //     $table->set($data['token'],['worker_id'=>$server->worker_id,'fd'=>$frame->fd]);
          // }
          echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
          // $server->push($frame->fd,'send push data');
          // echo 'worker 进程编号:'.$server->worker_id.PHP_EOL;
          // $param_data = ['fd'=>$frame->fd,'worker_id'=>$server->worker_id,];
          // $redis = RedisConnect::getInstance()->connect();
          // $redis->publish('worker_chatchanel',json_encode($param_data));
          // var_dump($data);
        };
    }

    public function handleClose()
    {
      return function(WebSocketServer $server,$fd){
        
        echo 'worker 进程编号:'.$server->worker_id.'下面得fd'.$fd.'关闭'.PHP_EOL;
      };

    }
    

}


