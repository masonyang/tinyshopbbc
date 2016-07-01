<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/5/16
 * Time: 下午4:09
 * 客户服务 api
 */
class cservice extends baseapi
{

    public static $title = array(
        'cservice'=>'关于我们信息'
    );

    public static $lastmodify = array(
        'cservice'=>'2016-6-28',
    );

    public static $notice = array(
        'cservice'=>'',
    );

    public static $requestParams = array(
        'cservice'=>array(
            array(
                'colum'=>'无',
                'required'=>'无',
                'type'=>'无',
                'content'=>'无',
            ),
        ),
    );

    public static $responsetParams = array(
        'cservice'=>array(
            array(
                'colum'=>'mobile',
                'content'=>'电话',
            ),
            array(
                'colum'=>'wechat',
                'content'=>'微信号',
            ),
            array(
                'colum'=>'qq',
                'content'=>'qq',
            ),
            array(
                'colum'=>'content',
                'content'=>'附加信息',
            ),
        ),
    );

    public static $requestUrl = array(
        'cservice'=>'     /index.php?con=api&act=index&method=cservice'
    );

    protected $cservcieTemplate = '<p>电话:{mobile}</p>
                    <p>微信号:{wechat}</p>
                    <p>QQ:{qq}</p>
                    <p>{content}</p>';

    public function index()
    {
        $cserviceModel = new Model('cservice');

        $list = $cserviceModel->where('id=1')->find();

        if($list){

            $result['mobile'] = $list['mobile'];
            $result['wechat'] = $list['wechat'];
            $result['qq'] = $list['qq'];
            $result['content'] = $list['content'];

            $this->output['status'] = 'succ';
            $this->output['msg'] = '关于我们信息获取成功';
            $this->output($result);
        }else{
            $this->output['msg'] = '关于我们信息获取失败';
            $this->output();
        }
//        $this->getHtml($list);
    }

    public function cservice_demo()
    {

        $return = array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'关于我们信息获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'关于我们信息获取成功',
                'data'=>array(
                    'mobile'=>2141241,
                    'wechat'=>2141241,
                    'qq'=>2141241,
                    'content'=>'附加信息',
                ),
            )
        );

        return $return;
//        '{"status":"succ","msg":"\u83b7\u53d6\u6210\u529f","data":[{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg"},{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/9670df531a008c75e7bed5b8967efd66.gif"}]}';
    }

    private function getHtml($list)
    {
        $list['mobile'] = $list['mobile'] ? $list['mobile'] : '13777906569';
        $list['wechat'] = $list['wechat'] ? $list['wechat'] : '13777906569';
        $list['qq'] = $list['qq'] ? $list['qq'] : '1450739694';
        $list['content'] = $list['content'] ? $list['content'] : '有任何问题，您可以通过电话或者微信、QQ联系哦～';
        echo str_replace(array('{mobile}','{wechat}','{qq}','{content}'),array($list['mobile'],$list['wechat'],$list['qq'],$list['content']),$this->cservcieTemplate);
    }

}