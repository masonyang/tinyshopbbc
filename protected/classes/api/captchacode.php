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

    public static $lastmodify = array(
        'captchacode'=>'2016-6-13'
    );

    public static $requestParams = array(
        'captchacode'=>array(
            array(
                'colum'=>'无',
                'required'=>'无',
                'type'=>'无',
                'content'=>'无',
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

        $this->safebox = Safebox::getInstance();
        $this->safebox->set($this->captchaKey,$code);
        ob_end_flush();
    }

    public function captchacode_demo()
    {
        return '直接输出 验证码图片';
    }

}