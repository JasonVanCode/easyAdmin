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

class Index extends Controller
{

    public function test()
    {
        $ip = '114.86.230.217';
        $a = new GetIPAddress($ip,'GET');
        $a->getAddress();
    }


    public function test22()
    {
        // MyQueue::getInstance()->consumer()->listen(function (Job $job){
        //     var_dump($job);
        // });
    }

    public function onException(\Throwable $throwable): void
    {
        var_dump($throwable->getMessage());
    }




}