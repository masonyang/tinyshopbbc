<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 22/4/16
 * Time: 下午11:05
 */
class baseapi
{
    protected $params = array();

    protected $output = array(
        'status'=>'fail',
        'msg'=>'',
        'data'=>array()
    );

    public function __construct($params = array())
    {
        header('Access-Control-Allow-Origin:*;');
        header('Access-Control-Expose-Headers:X-Reddit-Tracking, X-Moose;');

        $this->params = $params;
    }

    public function index()
    {

    }

    public function output($params,$output = 'json')
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

}