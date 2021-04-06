<?php
namespace App\HttpController\Api;

use App\HttpController\Base;
use App\Models\BlogArticles;
use App\Lib\ValidateCheck;

class BlogController extends Base
{
  
    public function getlist()
    {
        $params = $this->request()->getRequestParam();
        $model = BlogArticles::create()->limit($params['perpage'] * ($params['page'] - 1))->withTotalCount();
        $list = $model->all(null);
        $total = $model->lastQueryResult()->getTotalCount();
        $finalresult = ['current_page'=>$params['page'],'total'=>$total,'data'=>$list];
        return $this->writeJson(200, $finalresult,'获取数据成功');    
    }


    public function save()
    {
        $params = $this->request()->getRequestParam();
        $vali = new ValidateCheck();
        $vali = $vali->validateRule('blog');
        $res = $vali->validate($params);
        if(!$res){
            return $this->writeJson('200','',$vali->getError()->__toString());
        }
        $savedata = ['user_id'=>$this->userinfo['user_id'],'article_title'=>$params['name'],'article_content'=>$params['content'],'article_date'=>date('Y-m-d H:i:s'),'article_type'=>$params['type']];
        $result = BlogArticles::create()->data($savedata)->save();
        return $this->writeJson('200','',$result?'success':'fail');
    }


}