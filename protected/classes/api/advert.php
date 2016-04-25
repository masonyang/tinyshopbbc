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

        $html = '';

        foreach ($ad['content'] as $item) {
            $html .= '<div class="swiper-slide"><img src="'.Url::fullUrlFormat('@'.$item['path']).'" width="320" height="200" /></div>';
        }

        echo $html;
    }

}