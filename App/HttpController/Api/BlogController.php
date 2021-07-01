<?php
namespace App\HttpController\Api;

use App\HttpController\Base;
use App\Models\BlogArticles;
use App\Lib\ValidateCheck;
use Throwable;

class BlogController extends Base
{
  
    /**
     * @description: 获取文章列表信息
     * @param {*}
     * @return {*}
     */    
    public function getlist()
    {
        $params = $this->request()->getRequestParam();
        $model = BlogArticles::create()->limit($params['perpage'] * ($params['page'] - 1))->withTotalCount();
        $list = $model->all(null);
        $total = $model->lastQueryResult()->getTotalCount();
        $finalresult = ['current_page'=>$params['page'],'total'=>$total,'data'=>$list];
        return $this->writeJson(200, $finalresult,'获取数据成功');    
    }

    /**
     * @description: 保存文章数据
     * @param {*}
     * @return {*}
     */    
    public function save()
    {
        $params = $this->request()->getRequestParam();
        $vali = new ValidateCheck();
        $vali = $vali->validateRule('blog');
        $res = $vali->validate($params);
        if(!$res){
            return $this->writeJson(200,['message'=>$vali->getError()->__toString()],'fail');
        }
        $savedata = ['user_id'=>$this->userinfo['user_id'],'article_title'=>$params['name'],'article_content'=>$params['content'],'article_date'=>date('Y-m-d H:i:s'),'article_type'=>$params['type']];
        if($params['editid']){
            $result = $this->handleEdit($params['editid'],$savedata);
        }else{
            $result = $this->handleSave($savedata);
        }
        return $this->writeJson(200,!$result?['message'=>'操作失败']:'',$result?'success':'fail');
    }

    public function handleSave($data)
    {
        return  BlogArticles::create($data)->save();
    }

    public function handleEdit($id,$data)
    {
        return BlogArticles::create()->update($data, ['article_id' => $id]);
    }


    /**
     * @description: 获取单条数据的信息 
     * @param {*}
     * @return {*}
     */    
    public function geteditlist()
    {
        $params = $this->request()->getRequestParam();
        if(!isset($params['id']) ){
            return $this->writeJson(200,'','fail');
        }
        $data = BlogArticles::create()->get(['article_id'=>$params['id']]);
        return $this->writeJson(200,$data,'success');
    }

    /**
     * @description: 删除数据 
     * @param {*}
     * @return {*}
     */    
    public function dellist()
    {
        $params = $this->request()->getRequestParam();
        $res = BlogArticles::create()->destroy($params['id']);
        return $this->writeJson(200,['status'=>$res?'success':'fail'],$res?'删除成功':'删除失败');
    }

    /**
     * @description: 保存文章上传的图片 
     * @param {*}
     * @return {*}
     */    
    public function blogimgsave()
    {
        $request = $this->request();
        // 获取一个上传文件，客户端上传的文件字段名为 'file'
        $file = $request->getUploadedFile('file');

        $blog_config = \EasySwoole\EasySwoole\Config::getInstance()->getConf('BLOG_IMAGE_DIR');
        // //判断该文件夹是否存在
        if(!file_exists($blog_config['save_dir'])){
            mkdir ($blog_config['save_dir'],0777,true);
        }
        $mediatype =  $file->getClientMediaType();
        $suffix = explode('/',$mediatype)[1];
        if(!in_array($suffix,$blog_config['ext'])){
            return  $this->writeJson(200,['message'=>'上传的图片格式不支持'],'fail');
        }
        $newname = md5(time() . mt_rand(1,1000000)).'.'.$suffix;
        try{
            $file->moveTo($blog_config['save_dir'] . $newname);
        }catch(Throwable $e){
            return $this->writeJson(200,['message'=>$e->getMessage()],'fail');
        }
        return $this->writeJson(200,['imgurl'=>$blog_config['show_dir'].$newname],'success');
    }

}