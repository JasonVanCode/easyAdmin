<?php
namespace App\Lib;

use EasySwoole\EasySwoole\Config;


Class GetIPAddress{
    
    private $host;

    private $method;

    private $headers = [];

    private $ip;

    private $appcode;


    public function __construct($ip,$method)
    {
        $ip_conf = Config::getInstance()->getConf('IPAPI');
        $this->host =$ip_conf['host'];
        $this->appcode = $ip_conf['app_code'];
        $this->ip = $ip;
        $this->method = $method;
    }

    public function getAddress()
    {
        $path = "/ip";
        array_push($this->headers, "Authorization:APPCODE " . $this->appcode);
        $querys = "ip=".$this->ip;
        $url = $this->host . $path . "?" . $querys;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//执行成功之后，返回返回的结果
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$this->host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $content = curl_exec($curl);
        if(!$content){
            \EasySwoole\EasySwoole\Logger::getInstance()->log("ip地址是{$this->ip},获取地理位置失败",\EasySwoole\Log\LoggerInterface::LOG_LEVEL_INFO,'info');
        }
        curl_close($curl);
        return $content;
    }

}
