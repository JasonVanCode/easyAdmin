<?php


namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {
        /*
          * eg path : /router/index.html  ; /router/ ;  /router
         */
        $this->setGlobalMode(true);
        $routeCollector->addGroup('/api',function (RouteCollector $collector){
            //登录的控制器路由
            $collector->post('/login', '/Api/LoginController/login');
            $collector->get('/menulist', '/Api/LoginController/getMenulist');
            //人员管理控制器路由
            $collector->get('/user', '/Api/UserController/getlist');
            $collector->post('/user/save', '/Api/UserController/save');
            $collector->post('/user/del', '/Api/UserController/del');
            //权限管理的权限
            $collector->get('/auth', '/Api/AuthController/getlist');
            $collector->get('/auth/getrolelist', '/Api/AuthController/getrolelist');
            $collector->get('/auth/getrolemenulist', '/Api/AuthController/getroleMenulist');
            $collector->post('/auth/save', '/Api/AuthController/save');
            //获取switch游戏数据
            $collector->get('/switch/getlist', '/Api/SwitchController/getlist');

            //上传文件
            $collector->post('/file/upload', '/Api/UploadController/login');
            //退出登录
            $collector->post('/loginout', '/Api/LoginController/loginOut');

            $collector->get('/test11', '/Index/test');

            //文章模块
            $collector->post('/blog/save', '/Api/BlogController/save');

        });
        /*
         * eg path : /closure/index.html  ; /closure/ ;  /closure
         */
        // $routeCollector->get('/closure',function (Request $request,Response $response){
        //     $response->write('this is closure router');
        //     //不再进入控制器解析
        //     return false;
        // });
    }
}