<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 22/4/16
 * Time: 下午11:05
 *
 * https://github.com/phonegap/phonegap-plugin-barcodescanner
 */
class baseapi
{
    protected $params = array();

    const APIURL = 'http://192.168.1.101/';//192.168.1.100

    protected $output = array(
        'status'=>'fail',
        'msg'=>'',
        'data'=>array()
    );

    public function __construct($params = array())
    {
        header('Content-type:text/html;charset=utf-8');
        header('Access-Control-Allow-Origin:*');
//        header('Access-Control-Expose-Headers:X-Reddit-Tracking, X-Moose;');

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

        $this->params = $params;

    }

    public function index()
    {

    }

    public function output($params = array(),$output = 'json')
    {
        switch($output){
            case 'json':
                 $this->output['data'] = $params;
                 echo json_encode($this->output);
            break;
            case 'html':
                echo $params;
            break;
        }
    }

    protected static function getApiUrl()
    {
        return 'http://'.$_SERVER['HTTP_HOST'].'/';
    }

    public function verifySign($sign)
    {
        $des = new DES3();

        $verify = $des->decrypt($sign);


        if(!$verify){
            return false;
        }

        return json_decode($verify,1);
    }

}

