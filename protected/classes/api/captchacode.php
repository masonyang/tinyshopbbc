<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 29/4/16
 * Time: 下午5:42
 */
class captchacode extends baseapi
{
    //验证码变量名
    protected $captchaKey = 'verifyCode';

    protected $safebox;

    public static $title = array(
        'captchacode'=>'验证码显示'
    );

    public static $notice = array(
        'captchacode'=>'',
    );

    public static $lastmodify = array(
        'captchacode'=>'2016-6-13'
    );

    public static $requestParams = array(
        'captchacode'=>array(
            array(
                'colum'=>'rand',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'手机的序列号，用手机的序列号作为存储验证码时候 的key。在提交验证码 表单信息的 时候 也要传这个 手机序列号 来作为验证依据',
            ),
        ),
    );

    public static $responsetParams = array(
        'captchacode'=>array(
            array(
                'colum'=>'无',
                'content'=>'无',
            ),
        ),
    );

    public static $requestUrl = array(
        'captchacode'=>'     /index.php?con=api&act=index&method=captchacode'
    );

    public function __construct($params)
    {
        header('Content-type:text/html;charset=utf-8');
        header('Access-Control-Allow-Origin:*');

        $this->params = $params;
    }

    public function index()
    {
        ob_start();
        $this->layout = null;

        $w=$this->params['w']===null?120:intval($this->params['w']);
        $h=$this->params['h']===null?50:intval($this->params['h']);
        $l=$this->params['l']===null?4:intval($this->params['l']);
        $bc=$this->params['bc']===null?array(255,255,255):'#'.$this->params['bc'];
        $c=$this->params['c']===null?null:'#'.$this->params['c'];
        $captcha = new Captcha($w,$h,$l,$bc,$c);
        $captcha->createImage($code);

        $rand = $this->captchaKey.$this->params['rand'];

        $cacheModel = new Model('cache');

        $as = $cacheModel->where('`key`="'.$rand.'"')->find();

        if($as){
            $cacheModel->data(array('content'=>$code))->where('`key`="'.$rand.'"')->update();
        }else{
            $cacheModel->data(array('key'=>$rand,'content'=>$code))->insert();
        }


        ob_end_flush();
    }

    public function captchacode_demo()
    {
        return '直接输出 验证码图片';
    }

}