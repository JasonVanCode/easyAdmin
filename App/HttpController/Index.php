<?php


namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\EasySwoole\Task\TaskManager;
use App\Task\CustomTask;
use EasySwoole\Component\Di;
use App\Lib\RedisConnect;
use EasySwoole\Http\GlobalParam\Hook;
use EasySwoole\Queue\Job;
use App\Lib\RedisQueue as MyQueue;
use App\Lib\GetIPAddress;
use App\Models\AdminUser;

use EasySwoole\Component\TableManager;


class Index extends Controller
{

    public function test()
    {
        $user_list = AdminUser::create()->all();
        $result_data = [];
        foreach( $user_list as $val){
            $result_data[] = ['id'=>$val->user_id,'username'=>$val->username,'avatar'=>$val->avatar];
        }
        var_dump($result_data);
    }

    public function test22()
    {
        return $this->writeJson(200,[],'success');
        // MyQueue::getInstance()->consumer()->listen(function (Job $job){
        //     var_dump($job);
        // });
    }

    public function onException(\Throwable $throwable): void
    {
        var_dump($throwable->getMessage());
    }




}