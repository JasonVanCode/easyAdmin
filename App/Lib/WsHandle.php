<?php
/*
 * @Author: your name
 * @Date: 2021-03-30 05:19:47
 * @LastEditTime: 2021-06-29 00:56:29
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: /opt/easyAdmin/App/Lib/WsMessageHandle.php
 */
namespace App\Lib;

use EasySwoole\Component\Singleton;
use Swoole\WebSocket\Server as WebSocketServer;
use EasySwoole\Component\TableManager;


Class WsHandle{

    use Singleton;

    /**
     * @description: 处理websocket传输过来的消息 
     * @param {*}
     * @return {*}
     */    
    public function handle()
    {
        return function(WebSocketServer $server,$frame){
          echo '接收到进程编号为'.$server->worker_id.'描述符fd：'.$frame->fd.'的数据：'.$frame->data.PHP_EOL;
          $table = TableManager::getInstance()->get('websocket_user');
          $data = json_decode($frame->data,true);
          if(isset($data['is_first_connect'])){
              if(!$table->exist($server->worker_id)){
                $table->set( $server->worker_id,['user_fds'=>json_encode([['fd'=>$frame->fd,'user_id'=>$data['user_id']]])]);
                 return;
              }
              $user_fd_arrs = json_decode($table->get($server->worker_id)['user_fds'],true);
              $user_fd_arrs[] = ['fd'=>$frame->fd,'user_id'=>$data['user_id']];
              $table->set($server->worker_id,['user_fds'=>json_encode($user_fd_arrs)]);
              return;
          }
          // $server->push($frame->fd,'send push data');
          $param_data = ['fd'=>$frame->fd,'worker_id'=>$server->worker_id,'user_id'=>$data['user_id'],'msg'=>$data['data']['text']];
          $redis = RedisConnect::getInstance()->connect();
          $redis->publish('worker_chatchanel',json_encode($param_data));
        };
    }

    /**
     * @description: websocket连接关闭处理的回调函数
     * @param {*}
     * @return {*}
     */    
    public function handleClose()
    {
      return function(WebSocketServer $server,$fd){
        $table = TableManager::getInstance()->get('websocket_user');
        if(!$table->exist($server->worker_id)){
           return;
        }
        $user_fd_arrs = json_decode($table->get($server->worker_id)['user_fds'],true);
        foreach($user_fd_arrs as $k => $v){
            if($v['fd'] == $fd){
              array_splice($user_fd_arrs,$k,1);
              break;
            }
        }
        $table->set($server->worker_id,$user_fd_arrs);
        echo 'worker 进程编号:'.$server->worker_id.'下面得fd'.$fd.'关闭'.PHP_EOL;
      };

    }

}


