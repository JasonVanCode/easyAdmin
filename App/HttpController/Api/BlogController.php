<?php
namespace App\HttpController\Api;

use App\HttpController\Base;
use App\Models\BlogArticles;
use App\Lib\ValidateCheck;

class BlogController extends Base
{
  
    public function save()
    {
        // var_dump($this->request()->getRequestParam());
        $params = $this->request()->getRequestParam();
        $vali = new ValidateCheck();
        $vali = $vali->validateRule('blog');
        $res = $vali->validate($params);





    }


}