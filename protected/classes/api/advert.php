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
    public static $title = '首页广告轮播图';

    public static $lastmodify = '2016-6-13';

    public static $requestParams = array(
//        array(
//            'colum'=>'无',
//            'required'=>'可选',
//            'type'=>'无',
//            'content'=>'说明',
//        ),
    );

    public static $responsetParams = array(
        array(
            'colum'=>'img_path',
            'content'=>'轮播图 图片地址',
        ),
    );

    public static $results = '';

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

        foreach ($ad['content'] as $item) {
            $data[]['img_path'] = Url::fullUrlFormat('@'.$item['path']);
        }

        $this->output['msg'] = '获取成功';
        $this->output($data);
    }

    public function demo()
    {
        return '{"status":"fail","msg":"\u83b7\u53d6\u6210\u529f","data":[{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg"},{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/9670df531a008c75e7bed5b8967efd66.gif"}]}';
    }
}