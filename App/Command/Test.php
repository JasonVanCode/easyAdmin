<?php
namespace App\Command;

use EasySwoole\Command\AbstractInterface\CommandInterface;
use EasySwoole\Command\AbstractInterface\CommandHelpInterface;

class Test implements CommandInterface{

    public function commandName(): string{

        return 'test';
    }

    public function exec(): ?string{

        echo 'exec method';

        return '';
    }

    public function help(CommandHelpInterface $commandHelp): CommandHelpInterface
    {
        // 添加 自定义action(action 名称及描述)
        $commandHelp->addAction('echo_string', 'print the string');
        $commandHelp->addAction('echo_date', 'print the date');
        $commandHelp->addAction('echo_logo', 'print the logo');
        // 添加 自定义action 可选参数
        $commandHelp->addActionOpt('--str=str_value', 'the string to be printed ');
        return $commandHelp;
    }

    public function desc(): string{

        return 'this is test command!';
    }



}