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
                'colum'=>'v',
                'required'=>'可选',
                'type'=>'string',
                'content'=>'版本号',
            ),
        ),
    );

    public static $responsetParams = array(
        'advert'=>array(
            array(
                'colum'=>'url',
                'content'=>'链接地址/商品ID/商品分类ID',
            ),
            array(
                'colum'=>'img_path',
                'content'=>'轮播图 图片地址',
            ),
            array(
                'colum'=>'s_type',
                'content'=>'类型 link、goods、category',
            ),
        ),
    );

    public static $requestUrl = array(
        'advert'=>'     /index.php?con=api&act=index&method=advert'
    );

    protected $imageSize = array(
        'width'=>'650',
        'height'=>'340',
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
            $data[$i]['s_type'] =$item['s_type'];

            $filename = Url::fullUrlFormat('@'.$item['path']);


            if(isset($this->params['v'])){

                $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);

                $data[$i]['img_path'] = $image['src'];

            }else{
                $data[$i]['img_path'] = $filename;
            }


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
                        's_type'=>'link、goods、category',
                        'url'=>'http://www.baidu.com',
                        'img_path'=>'http:\/\/a.test.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg',
                    ),
                    array(
                        's_type'=>'link、goods、category',
                        'url'=>'http://www.baidu.com',
                        'img_path'=>'http:\/\/a.test.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg',
                    ),
                ),
            )
        );

    }
}