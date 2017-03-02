<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 25/9/16
 * Time: 下午5:49
 * adposition 广告位
 * http://a.test.com/index.php?con=api&act=index&method=adpositionv1&apid=32&mason=1
 */
class adpositionv1 extends adposition
{
    public static $title = array(
        'adpositionv1'=>'广告位 (1.0版本)'
    );

    public static $lastmodify = array(
        'adpositionv1'=>'2017-3-2',
    );

    public static $notice = array(
        'adpositionv1'=>'',
    );

    public static $requestParams = array(
        'adpositionv1'=>array(
            array(
                'colum'=>'apid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'广告位id 多个用逗号分割',
            ),
        ),
    );

    public static $responsetParams = array(
        'adpositionv1'=>array(
            array(
                'colum'=>'apid',
                'content'=>'广告位id',
            ),
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
        'adpositionv1'=>'     /index.php?con=api&act=index&method=adpositionv1'
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {
        $ids = $this->params['apid'];

        $ids = explode(',',$ids);

        $count = count($ids);

        if($count > 1){
            $_ids = $ids;
        }elseif($count == 1){
            $_ids = $ids;
        }else{
            $this->output['msg'] = '异常出错';
            $this->output();
            exit;
        }

        $model = new Model('adposition','zd','salve');


        $ads = $model->where("id in (".implode(',',$_ids).")")->findAll();

        if($ads){

            $data = array();

            foreach($ads as $ad){
                $ad['content'] = unserialize($ad['content']);

                $i = 0;
                foreach ($ad['content'] as $item) {
                    $data[$ad['id']][$i]['apid'] =$ad['id'];
                    $data[$ad['id']][$i]['url'] =$item['url'];
                    $data[$ad['id']][$i]['s_type'] =$item['s_type'];
                    $data[$ad['id']][$i]['img_path'] = Url::fullUrlFormat('@'.$item['path']);
                    $i++;
                }
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '获取成功';
            $this->output($data);
        }else{
            $this->output['msg'] = '数据不存在';
            $this->output();
        }


    }

    public function adpositionv1_demo()
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
                    '1 (广告位 id)'=>array(
                        array(
                            'ap_id' =>'广告位id',
                            's_type'=>'link、goods、category',
                            'url'=>'http://www.baidu.com',
                            'img_path'=>'http:\/\/a.test.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg',
                        ),
                        array(
                            'ap_id' =>'广告位id',
                            's_type'=>'link、goods、category',
                            'url'=>'http://www.baidu.com',
                            'img_path'=>'http:\/\/a.test.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg',
                        ),
                    ),
                    '2 (广告位 id)'=>array(
                        array(
                            'ap_id' =>'广告位id',
                            's_type'=>'link、goods、category',
                            'url'=>'http://www.baidu.com',
                            'img_path'=>'http:\/\/a.test.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg',
                        ),
                        array(
                            'ap_id' =>'广告位id',
                            's_type'=>'link、goods、category',
                            'url'=>'http://www.baidu.com',
                            'img_path'=>'http:\/\/a.test.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg',
                        ),
                    ),
                ),
            )
        );

    }
}