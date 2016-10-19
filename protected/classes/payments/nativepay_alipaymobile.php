<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/10/16
 * Time: 上午11:17
 * 支付宝 手机app支付方式
 */
class nativepay_alipaymobile extends PaymentPlugin
{
    //支付插件名称
    public $name = '支付宝手机支付';

    private $private_key_path = 'rsa_private_key.pem';//商户的私钥

    private $ali_public_key_path = 'rsa_public_key.pem';//支付宝公钥

    private $cacert = 'cacert.pem';//ca证书路径地址

    private $transport = 'http';

    /**
     * HTTPS形式消息验证地址
     */
    var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    /**
     * HTTP形式消息验证地址
     */
    var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';

    //提交地址
    public function submitUrl(){
        return '';
    }

    //取得配制参数
    public static function config()
    {
        return array(
            array('field'=>'partner_id','caption'=>'合作身份者id','type'=>'string'),
            array('field'=>'partner_key','caption'=>'安全检验码key','type'=>'string'),
        );
    }

    //同步处理
    public function callback($callbackData,&$paymentId,&$money,&$message,&$orderNo)
    {

        //除去待签名参数数组中的空值和签名参数
        $filter_param = $this->filterParam($callbackData);

        //对待签名参数数组排序
        $para_sort = $this->argSort($filter_param);

        //生成签名结果
//        $payment = new Payment($paymentId);
//        $payment_plugin = $payment->getPaymentNativePlugin();
//        $classConfig = $payment_plugin->getClassConfig();

        $isSign = $this->buildSign($para_sort,$this->aliPublicKeyPath(),$callbackData['sign']);

        if($isSign)
        {
            //回传数据
            $orderNo = $callbackData['out_trade_no'];
            $money   = $callbackData['total_fee'];

            if($callbackData['trade_status'] == 'TRADE_FINISHED' || $callbackData['trade_status'] == 'TRADE_SUCCESS')
            {
                return true;
            }
        }
        else
        {
            $message = '签名不正确';
        }
        return false;
    }

    //异步处理
    public function asyncCallback($callbackData,&$paymentId,&$money,&$message,&$orderNo)
    {
        //除去待签名参数数组中的空值和签名参数
        $filter_param = $this->filterParam($callbackData);

        //对待签名参数数组排序
        $para_sort = $this->argSort($filter_param);

        //生成签名结果
        $payment = new Payment($paymentId);
        $payment_plugin = $payment->getPaymentNativePlugin();
        $classConfig = $payment_plugin->getClassConfig();

        $alipayNotify = new AlipayNotify($classConfig);

        if($alipayNotify->getResponse($callbackData['notify_id'])){
            $isSign = $this->buildSign($para_sort,$this->aliPublicKeyPath(),$callbackData['sign']);

            if($isSign)
            {
                //回传数据
                $orderNo = $callbackData['out_trade_no'];
                $money   = $callbackData['total_fee'];

                if($callbackData['trade_status'] == 'TRADE_FINISHED' || $callbackData['trade_status'] == 'TRADE_SUCCESS')
                {
                    return true;
                }
            }
        }

        return false;
    }


    //打包数据
    public function packData($payment)
    {
        $return = array();

        //基本参数
        $return['service'] = 'mobile.securitypay.pay';
        $return['partner'] = $payment['M_PartnerId'];
        $return['_input_charset'] = 'utf-8';
        $return['notify_url'] = $this->asyncNativeCallbackUrl;
        $return['app_id'] = '';
        $return['appenv'] = '';
        $return['out_trade_no'] = $payment['M_OrderNO'];
        $return['subject'] = $payment['R_Name']."(订单号:".$payment['M_OrderNO'].")";
        $return['payment_type'] = 1;
        $return['seller_id'] = $payment['M_PartnerId'];
        $return['total_fee'] = number_format($payment['M_Amount'], 2, '.', '');
        $return['body'] = "0.00";//todo

        //过虑无效参数
        $filter_param = $this->filterParam($return);

        //对待签名参数数组排序
        $para_sort = $this->argSort($filter_param);

        //生成签名
        $mysign = $this->buildSign($para_sort, $this->privateKeyPath());

        //签名结果与签名方式加入请求提交参数组中
        $return['sign'] = $mysign;
        $return['sign_type'] = 'RSA';

        return $return;
    }

    private function filterParam($para)
    {
        $filter_param = array();
        foreach($para as $key => $val)
        {
            if($key == "sign" || $key == "sign_type" || $val == "")
            {
                continue;
            }
            else
            {
                $filter_param[$key] = $para[$key];
            }
        }
        return $filter_param;
    }

    private function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }

    //生成签名
    private function buildSign($sort_para,$key_path,$sign = '')
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($sort_para);

        if($sign == ''){//用于请求到支付宝时候生成签名
            $mysgin = $this->rsaSign($prestr, $key_path);
        }else{//用于支付宝返回网店 验签
            //把拼接后的字符串再与安全校验码直接连接起来
            $mysgin = $this->rsaVerify($prestr,$key_path,$sign);
        }

        return $mysgin;
    }

    //生成连接字符串
    private function createLinkstring($para){
        $arg  = "";
        foreach($para as $key => $val){
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = trim($arg,'&');
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){
            $arg = stripslashes($arg);
        }
        return $arg;
    }

    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key 商户私钥字符串
     * return 签名结果
     */
    function rsaSign($data, $private_key) {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $private_key=str_replace("-----BEGIN RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("-----END RSA PRIVATE KEY-----","",$private_key);
        $private_key=str_replace("\n","",$private_key);

        $private_key="-----BEGIN RSA PRIVATE KEY-----".PHP_EOL .wordwrap($private_key, 64, "\n", true). PHP_EOL."-----END RSA PRIVATE KEY-----";

        $res=openssl_get_privatekey($private_key);

        if($res)
        {
            openssl_sign($data, $sign,$res);
        }
        else {
            return false;
        }
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA验签
     * @param $data 待签名数据
     * @param $alipay_public_key 支付宝的公钥字符串
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    function rsaVerify($data, $alipay_public_key, $sign)  {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $alipay_public_key=str_replace("-----BEGIN PUBLIC KEY-----","",$alipay_public_key);
        $alipay_public_key=str_replace("-----END PUBLIC KEY-----","",$alipay_public_key);
        $alipay_public_key=str_replace("\n","",$alipay_public_key);

        $alipay_public_key='-----BEGIN PUBLIC KEY-----'.PHP_EOL.wordwrap($alipay_public_key, 64, "\n", true) .PHP_EOL.'-----END PUBLIC KEY-----';
        $res=openssl_get_publickey($alipay_public_key);
        if($res)
        {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }
        else {
            return false;
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * RSA解密
     * @param $content 需要解密的内容，密文
     * @param $private_key_path 商户私钥文件路径
     * return 解密后内容，明文
     */
    function rsaDecrypt($content, $private_key_path) {
        $priKey = file_get_contents($private_key_path);
        $res = openssl_pkey_get_private($priKey);
        //用base64将内容还原成二进制
        $content = base64_decode($content);
        //把需要解密的内容，按128位拆开解密
        $result  = '';
        for($i = 0; $i < strlen($content)/128; $i++  ) {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res);
            $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    function getResponse($notify_id,$partner) {
        $transport = strtolower($this->transport);
        $partner = trim($partner);
        $veryfy_url = '';
        if($transport == 'https') {
            $veryfy_url = $this->https_verify_url;
        }
        else {
            $veryfy_url = $this->http_verify_url;
        }
        $veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = $this->getHttpResponseGET($veryfy_url, $this->cacertUrl());

        return $responseTxt;
    }

    /**
     * 远程获取数据，GET模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * return 远程输出的数据
     */
    function getHttpResponseGET($url,$cacert_url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
        $responseText = curl_exec($curl);
        //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        curl_close($curl);

        return $responseText;
    }

    private function dataPath()
    {
        return dirname(__FILE__).'/../../../data/cert/nativepay_alipaymobile/';
    }

    private function cacertUrl()
    {
        return $this->dataPath().$this->cacert;
    }

    private function privateKeyPath()
    {
        return $this->dataPath().$this->private_key_path;
    }

    private function aliPublicKeyPath()
    {
        return $this->dataPath().$this->ali_public_key_path;
    }
}