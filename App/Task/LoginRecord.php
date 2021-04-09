<?php

namespace App\Task;

use EasySwoole\Task\AbstractInterface\TaskInterface;
use App\Models\AdminUser;
use App\Models\AdminLog;

class LoginRecord implements TaskInterface
{
    protected $data;

    protected $server;

    protected $headers;

    public function __construct($data,$server,$headers)
    {
        // 保存投递过来的数据
        $this->data = $data;
        $this->server = $server;
        $this->headers = $headers;
    }

    public function run(int $taskId, int $workerIndex)
    {
        //记录日志表
        $this->loginLog();
        //更新用户状态
        $this->updateUser();
    }

    public function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        // 异常处理
        \EasySwoole\EasySwoole\Logger::getInstance()->log($throwable->getMessage(),\EasySwoole\Log\LoggerInterface::LOG_LEVEL_ERROR,'error');
    }

    //记录登录时间
    public function updateUser()
    {
        AdminUser::create()->update(['last_login_time'=>date('Y-m-d H:i:s'),'last_login_addr'=>'上海'],['user_id'=>$this->data['user_id']]);
    }

    public function loginLog()
    {
        //判断是否是nginx 反向代理过来的，获取请求的真实ip地址
        $real_ip = '';
        if(isset($this->headers['x-real-ip'][0])){
            $real_ip = $this->headers['x-real-ip'][0];
        }
        
       AdminLog::create()->data([
            'description'=>'登录操作',
            'username'=>$this->data['username'],
            'start_time'=>date('Y-m-d H:i:s',$this->server['request_time']),
            'method'=>$this->server['request_method'],
            'ip'=>$real_ip?$real_ip:$this->server['remote_addr'],
            'uri'=>$this->server['request_uri'],
            'url'=>$this->server['path_info']
            ])->save();
    }
}