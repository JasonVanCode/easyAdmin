<?php
namespace App\Process;

use EasySwoole\Component\Process\AbstractProcess;
use Swoole\Process;
use App\Lib\RedisQueue;
use EasySwoole\Queue\Job;

class RedisQueueProcess extends AbstractProcess
{
    protected function run($arg)
    {
        go(function (){
            RedisQueue::getInstance()->consumer()->listen(function (Job $job){
                var_dump(2222222222222);
                var_dump($job->getJobData());
            });
        });
    }

    protected function onPipeReadable(Process $process)
    {
        // 该回调可选
        // 当主进程对子进程发送消息的时候 会触发
        // $recvMsgFromMain = $process->read(); // 用于获取主进程给当前进程发送的消息
        // var_dump('收到主进程发送的消息: ');
        // var_dump($recvMsgFromMain);
    }

    protected function onException(\Throwable $throwable, ...$args)
    {
        // 该回调可选
        // 捕获 run 方法内抛出的异常
        // 这里可以通过记录异常信息来帮助更加方便地知道出现问题的代码
    }

    protected function onShutDown()
    {
        // 该回调可选
        // 进程意外退出 触发此回调
        // 大部分用于清理工作
    }

    protected function onSigTerm()
    {
        // 当进程接收到 SIGTERM 信号触发该回调
    }
}