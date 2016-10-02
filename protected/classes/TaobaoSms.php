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

    private $appkey = '23352957';

    private $secret = '0eabb49be76012fcfb54d920a3cca9ab';

    private static $instance = null;

    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }


    public function send($isTest = false,$clientSign = '',$smsParams = array(),$mobile = '',$templateCode = '')
    {
        $c = new TopClient();
        $c->appkey = $this->appkey;
        $c->secretKey = $this->secret;
        $c->format = 'json';
        //全球仓
        if($isTest){
            $c->isTest = true;
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