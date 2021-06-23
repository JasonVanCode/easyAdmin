<?php
namespace App\HttpController\Api;

use App\Models\AdminUser as User;
use App\HttpController\Base;
use App\Lib\ValidateCheck;
use App\Models\AdminUserRole;
use Throwable;

class UserController extends Base
{

    public function getlist()
    {
        $params = $this->request()->getRequestParam();
        $page = isset($params['page'])?$params['page']:1;
        $perpage = isset($params['perpage'])?$params['perpage']:10;
        $model  = User::create()
                ->alias('us')
                ->join('admin_user_role as ro','ro.user_id = us.user_id','LEFT')
                ->order('user_id')
                ->limit($perpage * ($page - 1), $perpage)
                ->group('user_id')
                ->field(['us.user_id','username','password','realname','avatar','phone','email','sex','locked','ctime','group_concat(ro.role_id) as role_id'])
                ->withTotalCount();
        $list = $model->all(null);
        // 总条数
        $total = $model->lastQueryResult()->getTotalCount();
        $finalresult = ['current_page'=>$page,'total'=>$total,'data'=>$list];
        return $this->writeJson(200, $finalresult,'获取数据成功');        
    }

    /**
     * @description: 创建用户
     * @param {*}
     * @return {*}
     */    
    public function save()
    {
        $file = $this->request()->getUploadedFile('file');
        $form = $this->request()->getRequestParam()['form'];
        // 将json转数组
        $form = json_decode($form,true);
        //判断必填字段是否
        $vali = new ValidateCheck();
        $vali = $vali->validateRule('usersave');
        $res = $vali->validate($form);
        if(!$res){
            return $this->writeJson(200,['status'=>'error'],$vali->getError()->__toString());
        }
        $role_id = $form['role_id'];
        $user_id = $form['user_id'];
        unset($form['user_id'],$form['role_id']);

        if(!$user_id){
            //判断新增的用户名是否存在
            $is_exists_name = User::create()->get(['username'=>$form['username']]);
            if($is_exists_name){
                return $this->writeJson(200,['status'=>'error'],'该用户名已经存在，请修改后重新提交！');
            }

            $user_id = $this->addData($form,$file);
        }else{
            $user_id = $this->updateData($form,$file,$user_id);
        }
        if(!$user_id){
            return $this->writeJson(200,['status'=>'error'],'用户创建或修改失败！');
        }

        //保存用户与权限对应的数据关系
        foreach($role_id as $v){
            $savedata[] = ['user_id' => $user_id,'role_id'=>$v];
        } 
        $finalresult = AdminUserRole::create()->saveAll($savedata,true);
        return $this->writeJson(200,['status'=>$finalresult?'success':'error'],$finalresult?'成功':'失败');
    }

    /**
     * @description: 处理用户新增的数据
     * @param {Array} $formdata
     * @param {Object} $file
     * @return {*}
     */    
    public function addData(Array $form,Object $file)
    {
        if($file){
            $form['avatar'] = $this->savefile($file);
        }
        //处理用户存储的密码
        $form['salt'] = $this->generateSalt();
        $form['password'] = $this->generateHashPassword($form['password'],$form['salt']); 
        //用户创建时间  
        $form['ctime'] = date('Y-m-d H:i:s');
        $user_id = User::create()->data($form,false)->save();
        return $user_id?$user_id:false;
    }

    /**
     * @description: 处理用户更新的数据
     * @param {Array} $form
     * @param {Object} $file
     * @param {*} $user_id
     * @return {*}
     */    
    public function updateData(Array $form,Object $file,$user_id)
    {
        if($file){
            $form['avatar'] = $this->savefile($file);
        }else{
            unset($form['avatar']);
        }
        try{
            // 开启事务
            \EasySwoole\ORM\DbManager::getInstance()->startTransaction();
            User::create()->update($form,['user_id',$user_id]);
            AdminUserRole::create()->destroy(['user_id'=>$user_id]);
            // 提交事务
            \EasySwoole\ORM\DbManager::getInstance()->commit();
            return $user_id;
        }catch(Throwable $e){
            // 回滚事务
            \EasySwoole\ORM\DbManager::getInstance()->rollback();
            return false;
        }
    }

    /**
     * @description: 处理图片上传 
     * @param {Object} $file
     * @return {*}
     */    
    public function savefile(Object $file)
    {
        $head_config = \EasySwoole\EasySwoole\Config::getInstance()->getConf('HEAD_IMAGE_DIR');
        // //判断该文件夹是否存在
        if(!file_exists($head_config['save_dir'])){
            mkdir ($head_config['save_dir'],0777,true);
        }
        $mediatype =  $file->getClientMediaType();
        $suffix = explode('/',$mediatype)[1];
        if(!in_array($suffix,$head_config['ext'])){
            return '';
        }
        $newname = md5(time() . mt_rand(1,1000000)).'.'.$suffix;
        try{
            $file->moveTo($head_config['save_dir'] . $newname);
        }catch(\EasySwoole\Http\Exception\FileException $e){
            return '';
        }
        return $head_config['show_dir'].$newname;
    }

    public function del()
    {
        $params = $this->request()->getRequestParam();
        $id = isset($params['id'])?$params['id']:null;
        try {    
            if($id){
                User::create()->destroy($id);
            }
        } catch (\Throwable $e) {
            return $this->writeJson(200,['status'=>'error'],$e->getMessage());
        }
        return $this->writeJson(200,['status'=>'success'],'删除成功');
    }

    /**
     * @description: 随机生成4位的盐值
     * @param {*}
     * @return {*}
     */
    //盐值生成
    public function generateSalt():string
    {
        $str = '';
        // 使用随机方式生成一个四位字符
        $chars = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        for ($i = 0; $i < 4; $i++) {
            $str .= $chars[mt_rand(0, count($chars) - 1)];
        }
        return $str;
    }

    /**
     * @description: 对用户的密码进行加密处理
     * @param {*} $password
     * @param {*} $salt
     * @return {*}
     */    
    public function generateHashPassword(String $password, String $salt):string
    {
        return md5(sha1($password) . $salt);
    }


}