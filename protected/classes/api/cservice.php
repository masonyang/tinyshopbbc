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

    protected $cservcieTemplate = '<p>电话:{mobile}</p>
                    <p>微信号:{wechat}</p>
                    <p>QQ:{qq}</p>
                    <p>{content}</p>';

    public function index()
    {
        $cserviceModel = new Model('cservice');

        $list = $cserviceModel->where('id=1')->find();

        $this->getHtml($list);
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