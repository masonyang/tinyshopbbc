<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 25/9/16
 * Time: 下午5:49
 * adposition 广告位
 * http://a.test.com/index.php?con=api&act=index&method=adposition&apid=32&mason=1
 */
class wpadposition extends wapbase
{
    public static $title = array(
        'adposition'=>'广告位'
    );

    public static $lastmodify = array(
        'adposition'=>'2016-9-25',
    );

    public static $notice = array(
        'adposition'=>'',
    );

    public static $requestParams = array(
        'adposition'=>array(
            array(
                'colum'=>'apid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'广告位id',
            ),
        ),
    );

    public static $responsetParams = array(
        'adposition'=>array(
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
        'adposition'=>'     /index.php?con=api&act=index&method=wpadposition'
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {
        $id = $this->params['apid'];

        if(empty($id)){
            $this->output['msg'] = '异常出错';
            $this->output();
            exit;
        }
        $model = new Model('adposition','zd','salve');


        $ad = $model->where("id = '$id'")->find();
        if($ad){
            $ad['content'] = unserialize($ad['content']);


            $data = array();

            $i = 0;
            foreach ($ad['content'] as $item) {
                $data[$i]['url'] =$item['url'];
                $data[$i]['s_type'] =$item['s_type'];
                $data[$i]['img_path'] = Url::fullUrlFormat('@'.$item['path']);
                $i++;
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '获取成功';
            $this->output($data);
        }else{
            $this->output['msg'] = '数据不存在';
            $this->output();
        }


    }

    public function adposition_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'数据不存在',
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
                ),
            )
        );

    }
}