<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 5/4/17
 * Time: 下午9:32
 *
 * 应用下载地址api
 *
 */
class wpdownload extends wapbase
{

    public static $title = array(
        'sitedownload'=>'应用下载地址',
    );

    public static $lastmodify = array(
        'sitedownload'=>'2017-4-5',
    );

    public static $notice = array(
        'sitedownload'=>'',
    );

    public static $requestParams = array(
        'sitedownload'=>array(
            array(
                'colum'=>'source',
                'required'=>'可选',
                'type'=>'int',
                'content'=>'0:默认ios和android、1:ios、2:android',
            ),
        ),
    );

    public static $responsetParams = array(
        'sitedownload'=>array(
            array(
                'colum'=>'download_url',
                'content'=>'对应的下载地址',
            ),
        ),
    );

    public static $requestUrl = array(
        'sitedownload'=>'     /index.php?con=api&act=index&method=wpdownload',
    );

    public function index()
    {

        if(!isset($this->params['source'])){
            $this->params['source'] = 0;
        }elseif(!in_array($this->params['source'],array(0,1,2))){
            $this->output['status'] = 'fail';
            $this->output['msg'] = '参数错误！';
            $this->output(array());
            exit;
        }

        $serverName = Tiny::getServerName();

        $appObj = new Model('distributor','zd','salve');

        $app_info = $appObj->fields('android_appversion,ios_appversion,site_ios_url,site_android_url,ios_content,android_content')->where('site_url="'.$serverName['top'].'"')->find();


        switch($this->params['source']){
            case 1:
                $download_url = $app_info['site_ios_url'];
                break;
            case 2:
                $download_url = $app_info['site_android_url'];
                break;
            default:
                $download_url = array(
                    'site_ios_url'=>$app_info['site_ios_url'],
                    'site_android_url'=>$app_info['site_android_url'],
                );
                break;
        }

        $data['download_url'] = $download_url;

        $this->output['status'] = 'succ';

        $this->output($data);

    }

    public function sitedownload_demo()
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
                    'download_url'=>'http://ios.apple.com/sadfasfas',
                ),
            )
        );
    }

}