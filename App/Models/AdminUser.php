<?php
namespace App\Models;

use EasySwoole\ORM\AbstractModel;

class AdminUser extends AbstractModel{

    /**
      * @var string 
    */
     protected $tableName = 'admin_user';
    
     public function roles()
     {
        $this->belongsToMany(AdminRole::class,'admin_user_role','user_id','role_id');
     }

     //获取用户得信息
     static public function getUserList()
     {
        $user_list = self::create()->all();
        $result_data = [];
        foreach( $user_list as $val){
            $result_data[] = ['id'=>$val->user_id,'username'=>$val->username,'avatar'=>$val->avatar];
        }
        return $result_data;
     }
}
