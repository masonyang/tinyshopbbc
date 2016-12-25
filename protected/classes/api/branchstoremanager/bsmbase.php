<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 24/12/16
 * Time: 下午12:55
 */
class basmbase extends baseapi
{

    protected $domain;

    protected $manager_id;

    public function __construct($params = array())
    {
        header('Content-type:text/html;charset=utf-8');
        header('Access-Control-Allow-Origin:*');
//        header('Access-Control-Expose-Headers:X-Reddit-Tracking, X-Moose;');

        $this->verifyDomain();

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


        $this->params = $params;

        if(isset($this->params['bmsmd5'])){
            $this->params['bmsmd5'] = base64_decode($this->params['bmsmd5']);
            list($distributor_id,$user_id) = explode('+',$this->params['bmsmd5']);

            $distrModel = new Model('distributor','zd','salve');

            $distrData = $distrModel->fields('site_url')->where('distributor_id = '.$distributor_id)->find();

            if($distrData){
                $this->domain = $distrData['site_url'];
                $this->manager_id = $user_id;
            }else{
                $this->output['msg'] = '参数不正确';
                $this->output();
                exit;
            }

        }

    }

    protected function verifyDomain()
    {
        $serverName = Tiny::getServerName();

        if(!$serverName || ($serverName['top'] != 'zd')){
            echo 'haha';
            exit;
        }


    }

}