<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 31/7/16
 * Time: 下午4:21
 */
class wparticlelist extends baseapi
{
    public static $title = array(
        'articlelist'=>'获取公告列表',
        'articledetail'=>'获取公告详情',
    );

    public static $notice = array(
        'articlelist'=>'',
        'articledetail'=>'',
    );

    public static $lastmodify = array(
        'articlelist'=>'2016-7-31',
        'articledetail'=>'2016-7-31'
    );

    public static $requestParams = array(
        'articlelist'=>array(
            array(
                'colum'=>'_params',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'_params = no_data',
            ),
        ),
        'articledetail'=>array(
            array(
                'colum'=>'source',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'来源 detail',
            ),
            array(
                'colum'=>'aid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'公告id',
            ),
        ),
    );

    public static $responsetParams = array(
        'articlelist'=>array(
            array(
                'colum'=>'id',
                'content'=>'公告id',
            ),
            array(
                'colum'=>'title',
                'content'=>'公告表态',
            ),
            array(
                'colum'=>'publish_time',
                'content'=>'公告发布时间',
            ),
        ),
        'articledetail'=>array(
            array(
                'colum'=>'id',
                'content'=>'公告id',
            ),
            array(
                'colum'=>'title',
                'content'=>'公告表态',
            ),
            array(
                'colum'=>'publish_time',
                'content'=>'公告发布时间',
            ),
            array(
                'colum'=>'link',
                'content'=>'公告内容 是一个url http://a.qqcapp.com/index.php?con=index&act=articledetail&aid=1',
            ),
        ),
    );

    public static $requestUrl = array(
        'articlelist'=>'     /index.php?con=api&act=index&method=wparticlelist'//获取列表,
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {
        if($this->params['source'] == 'detail'){
            if($this->params['aid']){

                $model = new Model('article','zd','salve');

                $article = $model->where("id = ".$this->params['aid'])->find();

                $data = array();

                $data['id'] = $article['id'];
                $data['link'] = baseapi::getApiUrl().'index.php?con=index&act=articledetail&aid='.$this->params['aid'];
                $data['title'] = $article['title'];
                $data['publish_time'] = $article['publish_time'];
                $this->output['status'] = 'succ';
                $this->output['msg'] = '获取成功';
                $this->output($data);

            }else{
                $this->output['status'] = 'fail';
                $this->output['msg'] = '异常错误';
                $this->output();
            }
        }else{

            $model = new Model('article','zd','salve');

            $article = $model->order("id desc")->findAll();

            $data = array();

            if($article){
                $i = 0;
                foreach ($article as $item) {
                    $data[$i]['id'] =$item['id'];
                    $data[$i]['title'] =$item['title'];
                    $data[$i]['publish_time'] = $item['publish_time'];
                    $i++;
                }
                $this->output['msg'] = '获取内容成功';
            }else{
                $this->output['msg'] = '暂无公告';
            }

            $this->output['status'] = 'succ';

            $this->output($data);
        }
    }

    public function articlelist_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'异常错误',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'获取内容成功/暂无公告',
                'data'=>array(
                    array(
                        'id'=>2,
                        'title'=>'标题222',
                        'publish_time'=>'2016-07-31 16:20:25'
                    ),
                    array(
                        'id'=>1,
                        'title'=>'标题111',
                        'publish_time'=>'2016-07-31 16:20:25'
                    ),
                ),
            )
        );
    }

    public function articledetail_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'异常错误',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'获取成功',
                'data'=>array(
                    array(
                        'id'=>2,
                        'title'=>'标题222',
                        'publish_time'=>'2016-07-31 16:20:25',
                        'link'=>baseapi::getApiUrl().'index.php?con=index&act=articledetail&aid=1',
                    ),
                ),
            )
        );
    }

}