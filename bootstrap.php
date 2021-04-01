<?php
//全局bootstrap事件
date_default_timezone_set('Asia/Shanghai');
//注册自定义命令
\EasySwoole\Command\CommandManager::getInstance()->addCommand(new \App\Command\Test());
//注册自定义进程
$processConfig = new \EasySwoole\Component\Process\Config([
    'processName' => 'CustomProcess', // 设置 进程名称为 TickProcess
    'processGroup' => 'Custom', // 设置 进程组名称为 Tick
    'arg' => [
        'arg1' => 'this is arg1!',
    ], // 传递参数到自定义进程中
    'enableCoroutine' => true, // 设置 自定义进程自动开启协程环境
]);

// 【推荐】使用 \EasySwoole\Component\Process\Manager 类注册自定义进程
$customProcess = (new \App\Process\CustomProcess($processConfig));
// 【可选操作】把 tickProcess 的 Swoole\Process 注入到 Di 中，方便在后续控制器等业务中给自定义进程传输信息(即实现主进程与自定义进程间通信)
\EasySwoole\Component\Di::getInstance()->set('customSwooleProcess', $customProcess->getProcess());
// 注册进程
\EasySwoole\Component\Process\Manager::getInstance()->addProcess($customProcess);


