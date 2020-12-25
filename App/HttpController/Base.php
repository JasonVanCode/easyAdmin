<?php
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use App\Lib\RedisConnect;
use App\Models\AdminLog;
use App\Event\Event;

class Base extends Controller
{

    protected $userinfo;

    protected $token;

    protected function onRequest(?string $action): ?bool
    {
        // Event::getInstance()->hook('test');
        return true;
        if($action == 'login'){
            return true;
            $server_list = $this->request()->getServerParams();
            return $this->loginLog($server_list);
        }
        //下面就是验证用户是否登录
        $token = $this->request()->getHeader('authorization');
        if(!isset($token[0])){
            $this->response()->withStatus(401);
            return false;
        }
        $obj = RedisConnect::getInstance()->connect();
        if(!$obj->get($token[0])){
            $this->response()->withStatus(401);
            return false;
        }
        $this->token = $token[0];
        $this->userinfo = json_decode($obj->get($token[0]),true);
        return true;
    }

    public function loginLog($server_list)
    {
        $res = AdminLog::create()->data([
            'description'=>'登录操作',
            'username'=>'admin',
            'start_time'=>date('Y-m-d H:i:s',$server_list['request_time']),
            'method'=>$server_list['request_method'],
            'ip'=>$server_list['remote_addr'],
            'uri'=>$server_list['request_uri'],
            'url'=>$server_list['path_info']
            ])->save();
        return $res?true:false;
    }


}