<?php
namespace App\Lib;
use EasySwoole\Validate\Validate;

Class ValidateCheck{

    public function validateRule(?string $action): ?Validate
    {
        $v = new Validate();
        switch ($action){
            case 'login':{
                $v->addColumn('name','登录名')->required('登录名不能为空')->notEmpty('不能为空');
                $v->addColumn('password','登录密码')->required('密码不能为空')->notEmpty('不能为空');
                break;
            }
            case 'usersave':{
                $v->addColumn('username','账号')->required('账号不能为空')->notEmpty('账号不能为空');
                // $v->addColumn('password','密码')->required('密码不能为空')->notEmpty('密码不能为空');
                $v->addColumn('realname','姓名')->required('姓名不能为空')->notEmpty('姓名不能为空');
                $v->addColumn('phone','手机号')->required('手机号不能为空')->notEmpty('手机号不能为空');
                $v->addColumn('email','邮箱')->required('邮箱不能为空')->notEmpty('邮箱不能为空');
                $v->addColumn('role_id','权限')->required('权限不能为空')->notEmpty('权限不能为空');
                break;
            }
            case 'blog':{
                $v->addColumn('name','文章标题')->required('文章标题不能为空')->notEmpty('不能为空');
                $v->addColumn('type','文章类型')->required('文章类型不能为空')->notEmpty('不能为空');
                $v->addColumn('content','文章内容')->required('文章内容不能为空')->notEmpty('不能为空');
                break;
            }
        }
        return $v;
    }

}
