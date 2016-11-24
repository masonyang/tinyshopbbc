<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 2/10/16
 * Time: 下午12:30
 * 淘宝大鱼短信
 */
class TaobaoSms
{

    private $appkey = '';

    private $secret = '';

    private static $instance = null;

    public static function getInstance($appkey,$secret)
    {
        if(self::$instance === null){
            self::$instance = new self($appkey,$secret);
        }

        return self::$instance;
    }

    private function __construct($appkey,$secret)
    {
        $this->appkey = $appkey;

        $this->secret = $secret;
    }

    public function send($isTest = false,$clientSign = '',$smsParams = array(),$mobile = '',$templateCode = '')
    {
        $c = new TopClient();
        $c->appkey = $this->appkey;
        $c->secretKey = $this->secret;
        $c->format = 'json';
        //手机移动商店
        if($isTest){
            $c->isTest = true;
        }

        if(empty($clientSign)){
            $clientSign = '手机移动商店';
        }

        $req = new AlibabaAliqinFcSmsNumSendRequest();
        //$req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($clientSign);
        $req->setSmsParam(json_encode($smsParams));
        $req->setRecNum($mobile);
        $req->setSmsTemplateCode($templateCode);
        $resp = $c->execute($req);
//        $resp
        return true;
    }

}