<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 21/9/16
 * Time: 下午10:47
 * 会员商品收藏api
 */
class attention extends baseapi
{
    public static $title = array(
        'list'=>'关注商品列表',
        'add'=>'添加关注',
        'cancel'=>'取消关注',
    );

    public static $lastmodify = array(
        'list'=>'2016-9-21',
        'add'=>'2016-9-21',
        'cancel'=>'2016-9-21',
    );

    public static $notice = array(
        'list'=>'',
        'add'=>'',
        'cancel'=>'',
    );

    public static $requestParams = array(
        'list'=>array(
            array(
                'colum'=>'uid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'p',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'页数',
            )
        ),
        'add'=>array(
            array(
                'colum'=>'uid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'gid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'商品id',
            )
        ),
        'cancel'=>array(
            array(
                'colum'=>'uid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'gid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'商品id',
            )
        ),
    );

    public static $responsetParams = array(
        'list'=>array(
            array(
                'colum'=>'name',
                'content'=>'商品名称',
            ),
            array(
                'colum'=>'img',
                'content'=>'商品图片地址',
            ),
            array(
                'colum'=>'gid',
                'content'=>'商品id',
            ),
        ),
    );

    public static $requestUrl = array(
        'list'=>'     /index.php?con=api&act=index&method=attention&source=list',
        'add'=>'     /index.php?con=api&act=index&method=attention&source=add',
        'cancel'=>'     /index.php?con=api&act=index&method=attention&source=cancel',
    );

    public function index()
    {
        switch($this->params['source']){
            case 'list':
                $this->attentionList();
                break;
            case 'add':
                $this->attentionAdd();
                break;
            case 'cancel':
                $this->attentionCancel();
                break;
        }
    }

    private function attentionList()
    {

        if(intval($this->params['uid']) == 0){
            $this->output['msg'] = '会员不能为空';
            $this->output();
        }

        $page = $this->params['p'] ? $this->params['p'] : 0;
        $model = new Model();
        $attention = $model->table("attention as at")->fields("at.*,go.name,go.store_nums,go.img,go.sell_price,go.id as gid")->join("left join goods as go on at.goods_id = go.id")->where("at.user_id = ".$this->params['uid'])->findPage($page);

        if($attention){
            $this->output['status'] = 'succ';
            $this->output['msg'] = '获取成功';
            $this->output($attention);
        }else{
            $this->output['status'] = 'succ';
            $this->output['msg'] = '暂无收藏商品';
            $this->output();
        }
    }

    private function attentionAdd()
    {
        if(intval($this->params['uid']) == 0){
            $this->output['msg'] = '会员不能为空';
            $this->output();
        }

        if(empty($this->params['gid'])){
            $this->output['msg'] = '商品不能为空';
            $this->output();
        }

        $attentionModel = new Model('attention');

        $at = $attentionModel->where('`user_id`='.$this->params['uid'].' and goods_id='.$this->params['gid'])->find();

        if($at){
            $this->output['msg'] = '已收藏该商品';
            $this->output();
            exit;
        }else{
            $data = array('user_id'=>$this->params['uid'],'goods_id'=>$this->params['gid'],'time'=>date('Y-m-d H:i:s'));

            $attentionModel->data($data)->insert();
            $this->output['status'] = 'succ';
            $this->output['msg'] = '收藏成功';
            $this->output();
        }

    }

    private function attentionCancel()
    {
        if(intval($this->params['uid']) == 0){
            $this->output['msg'] = '会员不能为空';
            $this->output();
        }

        if(empty($this->params['gid'])){
            $this->output['msg'] = '商品不能为空';
            $this->output();
        }

        $attentionModel = new Model('attention');

        $at = $attentionModel->where('`user_id`='.$this->params['uid'].' and goods_id='.$this->params['gid'])->find();

        if($at){
//            $data = array('user_id'=>$this->params['uid'],'goods_id'=>$this->params['gid'],'time'=>date('Y-m-d H:i:s'));

            $attentionModel->where("user_id=".$this->params['uid'].' and goods_id='.$this->params['gid'])->delete();
            $this->output['status'] = 'succ';
            $this->output['msg'] = '取消收藏成功';
            $this->output();

        }else{
            $this->output['status'] = 'succ';
            $this->output['msg'] = '已取消收藏';
            $this->output();
        }

    }

    public function add_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'会员不能为空/商品不能为空/取消收藏成功/已取消收藏',
            )
        );
    }

    public function cancel_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'会员不能为空/商品不能为空/取消收藏成功/已取消收藏',
            )
        );
    }

    public function list_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'获取成功/暂无收藏商品',
                'data'=>array(
                    array(
                        'name'=>'商品名称',
                        'img'=>'商品图片地址',
                        'gid'=>'商品id',
                    ),
                    array(
                        'name'=>'商品名称',
                        'img'=>'商品图片地址',
                        'gid'=>'商品id',
                    ),
                ),
            )
        );
    }

}