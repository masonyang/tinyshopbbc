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

}