<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 26/4/16
 * Time: 上午12:15
 * http://192.168.1.100/index.php?con=api&act=index&method=advert
 */
class advert extends baseapi
{
    public static $title = array(
        'advert'=>'首页广告轮播图'
    );

    public static $lastmodify = array(
        'advert'=>'2016-6-13',
    );

    public static $notice = array(
        'advert'=>'',
    );

    public static $requestParams = array(
        'advert'=>array(
            array(
                'colum'=>'无',
                'required'=>'无',
                'type'=>'无',
                'content'=>'无',
            ),
        ),
    );

    public static $responsetParams = array(
        'advert'=>array(
            array(
                'colum'=>'url',
                'content'=>'链接地址',
            ),
            array(
                'colum'=>'img_path',
                'content'=>'轮播图 图片地址',
            ),
        ),
    );

    public static $requestUrl = array(
        'advert'=>'     /index.php?con=api&act=index&method=advert'
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {
        $id = 'qgiowmka-us4k-p0up-vs3c-blkqmtb7';

        $model = new Model('ad','zd','salve');
        $time = date('Y-m-d');

        $ad = $model->where("number = '$id' and start_time<='$time' and end_time >='$time'")->find();
        if($ad==null) return;
        if($ad['is_open']==0 || $ad['type']==5) return;
        $ad['content'] = unserialize($ad['content']);

        $data = array();

        $i = 0;
        foreach ($ad['content'] as $item) {
            $data[$i]['url'] =$item['url'];
            $data[$i]['img_path'] = Url::fullUrlFormat('@'.$item['path']);
            $i++;
        }

        $this->output['status'] = 'succ';
        $this->output['msg'] = '获取成功';
        $this->output($data);
    }

    public function advert_demo()
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
                    array(
                        'url'=>'http://www.baidu.com',
                        'img_path'=>'http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg',
                    ),
                    array(
                        'url'=>'http://www.baidu.com',
                        'img_path'=>'http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg',
                    ),
                ),
            )
        );
//        '{"status":"succ","msg":"\u83b7\u53d6\u6210\u529f","data":[{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg"},{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/9670df531a008c75e7bed5b8967efd66.gif"}]}';
    }
}