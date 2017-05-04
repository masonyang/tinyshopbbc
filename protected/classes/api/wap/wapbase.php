<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 18/1/17
 * Time: 下午11:25
 *
 * 分店h5 基类
 *
 */
class wapbase extends baseapi
{

    public function __construct($params = array())
    {
        header('Content-type:text/html;charset=utf-8');
        header('Access-Control-Allow-Origin:*');
//        header('Access-Control-Expose-Headers:X-Reddit-Tracking, X-Moose;');


        if(self::$test || isset($params['mason'])){

        }else{
            if($params['sign']){

                $json = $this->verifySign($params['sign']);

                if(!$json){
                    $this->output['msg'] = '验签失败';
                    $this->output();
                    exit;
                }

                $params = array_merge($params,$json);

            }else{
                $this->output['msg'] = '验签失败';
                $this->output();
                exit;
            }
        }

        $this->_verifyDomain($params);

        $this->params = $params;
        $this->data = new InputData($params);
    }

    protected function _verifyDomain($params)
    {
        $serverName = Tiny::getServerName();

        if(!$serverName || (($serverName['top'] == 'zd') && ($params['source']!= 'paylinkv'))){
            echo 'haha';
            exit;
        }


    }

}