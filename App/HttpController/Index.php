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

class Index extends Controller
{

    public function test()
    {
        // 创建任务
        $job = new Job();
        // 设置任务数据
        $job->setJobData("this is my job data time time " . date('Ymd h:i:s'));
        // // $job->setDelayTime(5);
        //  // 生产普通任务
        $a =  MyQueue::getInstance()->producer()->push($job);
        var_dump($a);
        //  if($produceRes){
        //     echo 'push success'.PHP_EOL;
        //  }
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