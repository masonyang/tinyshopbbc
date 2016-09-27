<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 25/9/16
 * Time: 下午5:49
 * adposition 广告位
 * http://a.tinyshop.com/index.php?con=api&act=index&method=adposition&apid=32&mason=1
 */
class adposition extends baseapi
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
                'colum'=>'gid',
                'content'=>'商品id',
            ),
            array(
                'colum'=>'img_path',
                'content'=>'图片地址',
            ),
        ),
    );

    public static $requestUrl = array(
        'adposition'=>'     /index.php?con=api&act=index&method=adposition'
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
        $model = new Model('adposition');


        $ad = $model->where("id = '$id'")->find();
        if($ad){
            $ad['content'] = unserialize($ad['content']);

            $gModel = new Model('goods');

            $data = array();

            $i = 0;
            foreach ($ad['content'] as $item) {
                $gData = $gModel->fields('id')->where('goods_no = "'.$item['url'].'"')->find();
                $data[$i]['gid'] =$gData['id'];
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
                        'gid'=>1,
                        'img_path'=>'http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg',
                    ),
                    array(
                        'gid'=>2,
                        'img_path'=>'http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg',
                    ),
                ),
            )
        );
//        '{"status":"succ","msg":"\u83b7\u53d6\u6210\u529f","data":[{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg"},{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/9670df531a008c75e7bed5b8967efd66.gif"}]}';
    }
}