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
            array(
                'colum'=>'mobile',
                'content'=>'客服电话',
            ),
            array(
                'colum'=>'adpositions',
                'content'=>'广告位列表',
            ),
            array(
                'colum'=>'app_info',
                'content'=>'app信息',
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

        $cserviceModel = new Model('cservice');

        $list = $cserviceModel->where('id=1')->find();

        $data = array();

        if($config){

            $data['mobile'] = $list['mobile'];

            $data['site_logo'] =$config['site_logo'] ? baseapi::getApiUrl().$config['site_logo'] : '';
            $data['site_name'] =$config['site_name'];

            $serverName = Tiny::getServerName();

            $appObj = new Model('distributor','zd','salve');

            $app_info = $appObj->fields('android_appversion,ios_appversion,site_ios_url,site_android_url,ios_content,android_content')->where('site_url="'.$serverName['top'].'"')->find();

            $app_info['ios_download_url'] = $app_info['site_ios_url'];
            $app_info['android_download_url'] = $app_info['site_android_url'];

            $data['app_info'] = $app_info;

            $adModel = new Model('adposition','zd','salve');

            $ad = $adModel->findAll();

            $adpositions = array();

            foreach($ad as $val){
                $adpositions[] = $val['id'];
            }

            $data['adpositions'] = $adpositions;

            $this->output['msg'] = '获取内容成功';
        }else{
            $this->output['msg'] = '获取失败';
        }

        $this->output['status'] = 'succ';

        $this->output($data);

    }

    public function siteconf_demo()
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
                    'mobile'=>'13622718349',
                    'site_name'=>'http://www.baidu.com/123213.jpg',
                    'adpositions'=>array('1','2','3'),
                    'app_info'=>array(
                        'ios_appversion'=>'0.1',
                        'android_appversion'=>'1.0',
                        'ios_download_url'=>'http://ios.apple.com/sadfasfas',
                        'android_download_url'=>'http://android.google.com/sadfasfas',
                        'ios_content'=>'',
                        'android_content'=>'',
                    ),
                ),
            )
        );
    }

}