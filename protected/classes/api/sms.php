<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 2/10/16
 * Time: 下午5:02
 * 短信验证码接口
 */
class sms extends baseapi
{
    //验证码变量名
    protected $safebox;

    public static $title = array(
        'sms'=>'短信验证码'
    );

    public static $notice = array(
        'sms'=>'',
    );

    public static $lastmodify = array(
        'sms'=>'2016-10-2'
    );

    public static $requestParams = array(
        'sms'=>array(
            array(
                'colum'=>'mobile',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'手机号',
            ),
            array(
                'colum'=>'rand',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'手机的序列号，用手机的序列号作为存储验证码时候 的key。在提交验证码 表单信息的 时候 也要传这个 手机序列号 来作为验证依据',
            ),
        ),
    );

    public static $responsetParams = array(
        'sms'=>array(
            array(
                'colum'=>'无',
                'content'=>'无',
            ),
        ),
    );

    public static $requestUrl = array(
        'sms'=>'     /index.php?con=api&act=index&method=sms'
    );

//    public function __construct($params)
//    {
//        header('Content-type:text/html;charset=utf-8');
//        header('Access-Control-Allow-Origin:*');
//
//        $this->params = $params;
//    }
//a.qqcapp.com/index.php?con=api&act=index&method=sms&mason=1&mobile=13191868165&rand=13191868165
    public function index()
    {

        if(!Validator::mobi($this->params['mobile'])){
            $this->output['msg'] = '请确认手机号填写正确';
            $this->output();
            exit;
        }

        $code = $this->GetRandomCaptchaText(4);

        if(in_array($this->params['source_type'],array('reg','resetpwd'))){
            switch($this->params['source_type']){
                case 'reg'://注册验证码
                    $templateCode = 'SMS_25715133';//注册验证码
                break;
                case 'resetpwd'://重置密码
                    $templateCode = 'SMS_25815032';//重置密码
                break;
            }
        }else{
            $this->output['msg'] = '来源有误';
            $this->output();
            exit;
        }


        $istest = false;//是否走测试环境

        $result = TaobaoSms::getInstance()->send($istest,'手机app',array('code'=>$code),$this->params['mobile'],$templateCode);

        if($result){
            $rand = 'smscode'.$this->params['rand'];

            $cacheModel = new Model('cache');

            $as = $cacheModel->where('`key`="'.$rand.'"')->find();
//verifyCode3a466d6d-ce54-4ae0-875d-8ee1c5ff6411
            if($as){
                $cacheModel->data(array('content'=>$code))->where('`key`="'.$rand.'"')->update();
            }else{
                $cacheModel->data(array('key'=>$rand,'content'=>$code))->insert();
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '短信验证码发送成功';
            $this->output();

        }else{
            $this->output['msg'] = '短信验证码发送失败';
            $this->output();
            exit;
        }

    }

    private function GetRandomCaptchaText($length = null)
    {

        $chars = array(
            "0", "1", "2","3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i=0; $i<$length; $i++)
        {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    public function sms_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'短信验证码发送失败/请确认手机号填写正确',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'短信验证码发送成功',
                'data'=>array(
                ),
            )
        );
    }

}