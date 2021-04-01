<?php


namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\EasySwoole\Task\TaskManager;
use App\Task\CustomTask;
use EasySwoole\Component\Di;
use App\Lib\RedisConnect;

class Index extends Controller
{
    public function test()
    {
        throw new \Exception('aaaaaaaaaaaaa');
    }

    public function onException(\Throwable $throwable): void
    {
        var_dump(2222);
        var_dump($throwable->getMessage());
    }




}