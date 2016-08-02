<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 2/8/16
 * Time: 上午11:48
 */
class siteconf extends baseapi
{
    public static $title = array(
        'siteconf'=>'获取店铺基本信息',
    );

    public static $notice = array(
        'siteconf'=>'',
    );

    public static $lastmodify = array(
        'siteconf'=>'2016-7-31'
    );

    public static $requestParams = array(
        'siteconf'=>array(
            array(
                'colum'=>'_params',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'_params = no_data',
            ),
        ),
    );

    public static $responsetParams = array(
        'siteconf'=>array(
            array(
                'colum'=>'site_logo',
                'content'=>'网店logo',
            ),
            array(
                'colum'=>'site_name',
                'content'=>'网店名称',
            ),
        ),
    );

    public static $requestUrl = array(
        'siteconf'=>'     /index.php?con=api&act=index&method=siteconf'
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {

        $model = new Model('manager');

        $config = $model->fields('site_name,site_logo')->where('id=1')->find();

        $data = array();

        if($config){

            $data['site_logo'] =$config['site_logo'] ? baseapi::getApiUrl().$config['site_logo'] : '';
            $data['site_name'] =$config['site_name'];

            $this->output['msg'] = '获取内容成功';
        }else{
            $this->output['msg'] = '获取失败';
        }

        $this->output['status'] = 'succ';

        $this->output($data);

    }

    public function articlelist_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'获取成功',
                'data'=>array(
                    'site_logo'=>'网上商城',
                    'site_name'=>'http://www.baidu.com/123213.jpg'
                ),
            )
        );
    }

}