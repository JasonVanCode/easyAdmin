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

    protected $redis;

    protected function onRequest(?string $action): ?bool
    {
        //登录不需要验证
        $this->redis = RedisConnect::getInstance()->connect();
        //退出或者登录不需要下面的验证
        if($action == 'login' || $action == 'loginOut'){
            return true;
        }
        //下面就是验证用户是否登录
        $token = $this->request()->getHeader('authorization');
        if(!isset($token[0])){
            $this->response()->withStatus(401);
            return false;
        }
        $userrinfo = $this->redis->get($token[0]);
        if(!$userrinfo){
            $this->response()->withStatus(401);
            return false;
        }
        $this->token = $token[0];
        $this->userinfo = json_decode($userrinfo,true);
        return true;
    }






}