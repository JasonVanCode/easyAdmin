<?php
/*
 * @Author: your name
 * @Date: 2021-06-08 09:15:15
 * @LastEditTime: 2021-06-23 07:59:37
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: /opt/easyAdmin/App/HttpController/Api/OnlineChatController.php
 */

namespace App\HttpController\Api;

use App\HttpController\Base;
use App\Models\AdminUser;

class OnlineChatController extends Base{

    public function get_chat_userlist()
    {
        $user_list = AdminUser::getUserList();
        $me = [];
        $other = [];
        //找出当前用户的数据
        if(array_key_exists($this->userinfo['user_id'],$user_list)){
            $me = $user_list[$this->userinfo['user_id']];
            unset($user_list[$this->userinfo['user_id']]);
        }
        $other = array_values($user_list);
        return $this->writeJson(200,['me'=>$me,'other'=>$other],'success');
    }







}