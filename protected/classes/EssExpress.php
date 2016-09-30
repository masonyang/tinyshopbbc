<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 30/9/16
 * Time: 下午5:10
 * 快递鸟 物流公司配置
 */
//电商ID
defined('EBusinessID') or define('EBusinessID', '1263044');
//电商加密私钥，快递鸟提供，注意保管，不要泄漏
defined('AppKey') or define('AppKey', '01dbee88-be7b-4763-a222-d2463aa1634e');

class EssExpress
{
    private static $testApiUrl = 'http://testapi.kdniao.cc:8081/api/eorderservice';//测试环境地址

    private static $productionApiUrl = 'http://api.kdniao.cc/api/eorderservice';//测试环境地址

    private static $instance = null;

    private static $paramsConfig = array(
        'test'=>array(
            'EMS'=>array(
                'CustomerName'=>'',
                'CustomerPwd'=>'',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '顺丰快递'=>array(
                'CustomerName'=>'',
                'CustomerPwd'=>'',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '圆通速递'=>array(
                'CustomerName'=>'商户代码',
                'CustomerPwd'=>'',
                'MonthCode'=>'密钥串',
                'SendSite'=>'',
            ),
            '百世快递'=>array(
                'CustomerName'=>'操作编码',
                'CustomerPwd'=>'秘钥',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '中通速递'=>array(
                'CustomerName'=>'商家ID',
                'CustomerPwd'=>'商家接口密码',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '韵达快递'=>array(
                'CustomerName'=>'客户ID',
                'CustomerPwd'=>'接口联调密码',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '申通快递'=>array(
                'CustomerName'=>'客户简称',
                'CustomerPwd'=>'客户密码',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '德邦'=>array(
                'CustomerName'=>'客户编码',
                'CustomerPwd'=>'',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '优速快递'=>array(
                'CustomerName'=>'客户编号',
                'CustomerPwd'=>'密钥',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '宅急送'=>array(
                'CustomerName'=>'标识',
                'CustomerPwd'=>'秘钥',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
        ),
        'production'=>array(
            'EMS'=>array(
                'CustomerName'=>'大客户号',
                'CustomerPwd'=>'APP_SECRET',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '顺丰快递'=>array(
                'CustomerName'=>'',
                'CustomerPwd'=>'',
                'MonthCode'=>'月结号(选填)',
                'SendSite'=>'',
            ),
            '圆通速递'=>array(
                'CustomerName'=>'商户代码',
                'CustomerPwd'=>'',
                'MonthCode'=>'密钥串',
                'SendSite'=>'',
            ),
            '百世快递'=>array(
                'CustomerName'=>'操作编码',
                'CustomerPwd'=>'秘钥',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '中通速递'=>array(
                'CustomerName'=>'商家ID',
                'CustomerPwd'=>'商家接口密码',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '韵达快递'=>array(
                'CustomerName'=>'客户ID',
                'CustomerPwd'=>'接口联调密码',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '申通快递'=>array(
                'CustomerName'=>'客户简称',
                'CustomerPwd'=>'客户密码',
                'MonthCode'=>'',
                'SendSite'=>'网点名称',
            ),
            '德邦'=>array(
                'CustomerName'=>'客户编码',
                'CustomerPwd'=>'',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '优速快递'=>array(
                'CustomerName'=>'客户编号',
                'CustomerPwd'=>'密钥',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
            '宅急送'=>array(
                'CustomerName'=>'标识',
                'CustomerPwd'=>'秘钥',
                'MonthCode'=>'',
                'SendSite'=>'',
            ),
        ),
    );

    private static $expressCode = array(
        array('AJ','安捷快递'),
        array('ANE','安能物流'),
        array('AXD','安信达快递'),
        array('BQXHM','北青小红帽'),
        array('BFDF','百福东方'),
//        array('BTWL','百世快运'),
        array('CCES','CCES快递'),
        array('CITY100','城市100'),
        array('COE','COE东方快递'),
        array('CSCY','长沙创一'),
        array('CDSTKY','成都善途速运'),
        array('DBL','德邦'),
        array('DSWL','D速物流'),
        array('DTWL','大田物流'),
        array('EMS','EMS'),
        array('FAST','快捷速递'),
        array('FEDEX','FEDEX联邦(国内件）'),
        array('FEDEX_GJ','FEDEX联邦(国际件）'),
        array('FKD','飞康达'),
        array('GDEMS','广东邮政'),
        array('GSD','共速达'),
        array('GTO','国通快递'),
        array('GTSD','高铁速递'),
        array('HFWL','汇丰物流'),
        array('HHTT','天天快递'),
        array('HLWL','恒路物流'),
        array('HOAU','天地华宇'),
        array('hq568','华强物流'),
        array('HTKY','百世快递'),
        array('HXLWL','华夏龙物流'),
        array('HYLSD','好来运快递'),
        array('JGSD','京广速递'),
        array('JIUYE','九曳供应链'),
        array('JJKY','佳吉快运'),
        array('JLDT','嘉里物流'),
        array('JTKD','捷特快递'),
        array('JXD','急先达'),
        array('JYKD','晋越快递'),
        array('JYM','加运美'),
        array('JYWL','佳怡物流'),
        array('KYWL','跨越物流'),
        array('LB','龙邦快递'),
        array('LHT','联昊通速递'),
        array('MHKD','民航快递'),
        array('MLWL','明亮物流'),
        array('NEDA','能达速递'),
        array('PADTF','平安达腾飞快递'),
        array('QCKD','全晨快递'),
        array('QFKD','全峰快递'),
        array('QRT','全日通快递'),
        array('RFD','如风达'),
        array('SAD','赛澳递'),
        array('SAWL','圣安物流'),
        array('SBWL','盛邦物流'),
        array('SDWL','上大物流'),
        array('SF','顺丰快递'),
        array('SFWL','盛丰物流'),
        array('SHWL','盛辉物流'),
        array('ST','速通物流'),
        array('STO','申通快递'),
        array('STWL','速腾快递'),
        array('SURE','速尔快递'),
        array('TSSTO','唐山申通'),
        array('UAPEX','全一快递'),
        array('UC','优速快递'),
        array('WJWL','万家物流'),
        array('WXWL','万象物流'),
        array('XBWL','新邦物流'),
        array('XFEX','信丰快递'),
        array('XYT','希优特'),
        array('XJ','新杰物流'),
        array('YADEX','源安达快递'),
        array('YCWL','远成物流'),
        array('YD','韵达快递'),
        array('YDH','义达国际物流'),
        array('YFEX','越丰物流'),
        array('YFHEX','原飞航物流'),
        array('YFSD','亚风快递'),
        array('YTKD','运通快递'),
        array('YTO','圆通速递'),
        array('YXKD','亿翔快递'),
        array('YZPY','邮政平邮/小包'),
        array('ZENY','增益快递'),
        array('ZHQKD','汇强快递'),
        array('ZJS','宅急送'),
        array('ZTE','众通快递'),
        array('ZTKY','中铁快运'),
        array('ZTO','中通速递'),
        array('ZTWL','中铁物流'),
        array('ZYWL','中邮物流'),
        array('AMAZON','亚马逊物流'),
        array('SUBIDA','速必达物流'),
        array('RFEX','瑞丰速递'),
        array('QUICK','快客快递'),
        array('CJKD','城际快递'),
        array('CNPEX','CNPEX中邮快递'),
        array('HOTSCM','鸿桥供应链'),
        array('HPTEX','海派通物流公司'),
        array('AYCA','澳邮专线'),
        array('PANEX','泛捷快递'),
        array('PCA','PCA Express'),
        array('UEQ','UEQ Express'),
    );

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    //获取物流编码列表
    public static function _getAreaType($env = 'production')
    {
        return self::paramsConfig($env);
    }

    //获取配置信息
    public static function paramsConfig($env = 'production')
    {
        $expressCode = self::$expressCode;

        foreach($expressCode as $k =>$val){
            foreach(self::$paramsConfig[$env] as $kk =>$vval){
                if($val[1] == $kk){
                    $expressCode[$k]['CustomerName'] = $vval['CustomerName'];
                    $expressCode[$k]['CustomerPwd'] = $vval['CustomerPwd'];
                    $expressCode[$k]['MonthCode'] = $vval['MonthCode'];
                    $expressCode[$k]['SendSite'] = $vval['SendSite'];
                }
            }
        }

        return $expressCode;
    }

    //请求获取电子面单接口
    public function submitEOrder($eorder = array(),$isTest = false)
    {
        //调用电子面单
        $jsonParam = $this->c_json($eorder);

        $jsonResult = $this->_submitEOrder($jsonParam,$isTest);

        $result = json_decode($jsonResult, true);
//        echo "<br/><br/>返回码:".$result["ResultCode"];
        if(!empty($result['Order']['LogisticCode']) && !empty($result['PrintTemplate'])) {
            return $result;
        }

        return false;
    }

    private function c_json($array) {
        $this->arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
    }

    private function _submitEOrder($requestData = '',$isTest = false)
    {

//        $ReqURL = $isTest ? self::$testApiUrl : self::$productionApiUrl;

        $ReqURL = self::$testApiUrl;

        $datas = array(
            'EBusinessID' => EBusinessID,
            'RequestType' => '1007',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, AppKey);
        $result= $this->sendPost($ReqURL, $datas);

        //根据公司业务处理返回的信息......

        return $result;
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if($url_info['port']=='')
        {
            $url_info['port']=80;
        }
//        echo $url_info['port'];
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
    /**************************************************************
     *
     *  使用特定function对数组中所有元素做处理
     *  @param  string  &$array     要处理的字符串
     *  @param  string  $function   要执行的函数
     *  @return boolean $apply_to_keys_also     是否也应用到key上
     *  @access public
     *
     *************************************************************/
    function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }

            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        $recursive_counter--;
    }

    public function getExpressCodeByName($corpName = '')
    {
        if(empty($corpName)){
            return '';
        }

        $copCode = '';

        foreach(self::$expressCode as $val){
            if($val[1] == $corpName){
                $copCode = $val[0];
                continue;
            }
        }

        return $copCode;
    }
}