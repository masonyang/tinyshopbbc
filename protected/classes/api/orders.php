<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 29/4/16
 * Time: 下午6:10
 */
class orders extends baseapi
{

    protected $imageSize = array(
        'width'=>'120',
        'height'=>'120',
    );

    public static $title = array(
        'orderdetail'=>'订单详情',
        'ordercancel'=>'订单取消',
        'morders'=>'我的订单',
    );

    public static $lastmodify = array(
        'orderdetail'=>'2016-6-28',
        'ordercancel'=>'2016-9-30',
        'morders'=>'201-6-28',
    );

    public static $notice = array(
        'orderdetail'=>'',
        'ordercancel'=>'',
        'morders'=>'',
    );

    public static $requestParams = array(
        'orderdetail'=>array(
            array(
                'colum'=>'oid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'订单id（订单主键id）',
            ),
        ),
        'ordercancel'=>array(
            array(
                'colum'=>'oid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'订单id（订单主键id）',
            ),
        ),
        'morders'=>array(
            array(
                'colum'=>'uid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'page',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'页码',
            ),
            array(
                'colum'=>'status',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'订单状态 (waitcheck:待审核,waitpay:未支付,delivery:已支付,finish:已完成,cancel:已作废,不传:全部订单)',
            ),
        ),
    );

    public static $responsetParams = array(
        'orderdetail'=>array(
            array(
                'colum'=>'oid',
                'content'=>'订单主键id',
            ),
            array(
                'colum'=>'order_no',
                'content'=>'订单号',
            ),
            array(
                'colum'=>'create_time',
                'content'=>'订单创建时间',
            ),
            array(
                'colum'=>'ship_addr',
                'content'=>'收货地址',
            ),
            array(
                'colum'=>'goods_amount',
                'content'=>'商品总金额',
            ),
            array(
                'colum'=>'real_freight',
                'content'=>'商品总重量',
            ),
            array(
                'colum'=>'payable_freight',
                'content'=>'配送费用',
            ),
            array(
                'colum'=>'order_amount',
                'content'=>'订单总金额',
            ),
            array(
                'colum'=>'accept_mobile',
                'content'=>'收货人手机号',
            ),
            array(
                'colum'=>'accept_name',
                'content'=>'收货人姓名',
            ),
            array(
                'colum'=>'payment',
                'content'=>'支付方式',
            ),
            array(
                'colum'=>'status',
                'content'=>'订单状态 (等待付款/等待审核/已发货/已完成。。。)',
            ),
            array(
                'colum'=>'orderlog',
                'content'=>'订单日志 列表 多维数组',
            ),
            array(
                'colum'=>'products',
                'content'=>'购买的商品 列表 多维数组',
            ),
        ),
        'ordercancel'=>array(
            array(
                'colum'=>'无',
                'content'=>'无',
            ),
        ),
        'morders'=>array(
            array(
                'colum'=>'oid',
                'content'=>'订单主键id',
            ),
            array(
                'colum'=>'order_no',
                'content'=>'订单号',
            ),
            array(
                'colum'=>'create_time',
                'content'=>'订单创建时间',
            ),
            array(
                'colum'=>'status',
                'content'=>'订单状态 (等待付款/等待审核/已发货/已完成)',
            ),//'goods_count'=>10,
            array(
                'colum'=>'goods_count',
                'content'=>'商品总数量',
            ),
            array(
                'colum'=>'img',
                'content'=>'商品图片',
            ),
        ),
    );

    public static $requestUrl = array(
        'orderdetail'=>'     /index.php?con=api&act=index&method=orders&source=orderdetail',
        'morders'=>'     /index.php?con=api&act=index&method=orders&source=morders',
        'ordercancel'=>'     /index.php?con=api&act=index&method=orders&source=ordercancel',
    );

    private $limit = 10;

    public function index()
    {
        switch($this->params['source']){
            case 'orderdetail':
                $this->orderDetail();
                break;
            case 'morders':

                if(!isset($this->params['page'])){
                    $this->params['page'] =  null;
                }elseif($this->params['page'] <=0){
                    $this->params['page'] = 0;
                }else{
                    $this->params['page'] = $this->limit * ($this->params['page']-1);
                }

                if($this->params['status'] == 'waitcheck'){
                    $this->getWaitCheckOrders();
                }elseif($this->params['status'] == 'waitpay'){
                    $this->getWaitPayOrders();
                }elseif($this->params['status'] == 'delivery'){
                    $this->getDeliveryOrders();
                }elseif($this->params['status'] == 'finish'){//已发货接口
                    $this->getFinishOrders();
                }elseif($this->params['status'] == 'cancel'){
                    $this->getCancelOrders();
                }else{
                    $this->getMyOrders();
                }
                break;
            case 'ordercancel':
                $this->ordercancel();
                break;
        }
    }

    protected function orderDetail()
    {
        $orderid = $this->params['oid'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status,province,city,county,addr,real_freight,user_id,payable_freight,accept_name,mobile')->where('id='.$orderid)->find();

        $orderDetailModel = new Model('order_goods');

        $goodsModel = new Model('goods');


        $status = $this->status($orders);

        $goods_amount = 0;


        $odDatas = $orderDetailModel->fields('product_id,goods_id,real_price,goods_nums')->where('order_id='.$orders['id'])->findAll();

        if(!$odDatas){
            $this->output['msg'] = '订单号不存在';
            $this->output();
            return ;
        }

        $productsModel = new Model('products');

        $products = array();

        foreach($odDatas as $k=>$vval){
            $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();

            $items= $productsModel->fields("spec")->where("id=".$vval['product_id'])->findAll();

            $spec = array();
            $specs = unserialize($items['spec']);
            foreach($specs as $_specs){
                $spec[] = $_specs['value'][2];
            }

            $filename = self::getApiUrl().$gData['img'];

            $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);


            $products[$k]['img'] = $image['src'];
            $products[$k]['sale_price'] = $vval['real_price'];
            $products[$k]['goods_name'] = $gData['name'];
            $products[$k]['gid'] = $vval['goods_id'];
            $products[$k]['goods_nums'] = $vval['goods_nums'];
            $products[$k]['specs'] = implode(',',$spec);

            $goods_amount += ($vval['real_price']*$vval['goods_nums']);
        }


        $orderlogModel = new Model('order_log');

        $log = $orderlogModel->where('order_id='.$orders['id'])->findAll();

        $orderlog = array();

        foreach($log as $k=>$val){
            $orderlog[$k]['addtime'] = $val['addtime'];
            $orderlog[$k]['note'] = $val['note'];
        }

        $area_ids = $orders['province'].','.$orders['city'].','.$orders['county'];

        $areaModel = new Model('area','zd','salve');
        if($area_ids!='')$areas = $areaModel->where("id in ($area_ids)")->findAll();
        $parse_area = array();
        foreach ($areas as $area) {
            $parse_area[$area['id']] = $area['name'];
        }

        $ship_addr = $parse_area[$orders['province']].$parse_area[$orders['city']].$parse_area[$orders['county']].' '.$orders['addr'];

        $addressModel = new Model('address');
        $addrData = $addressModel->where('user_id='.$orders['user_id'].' and is_default=1')->find();

        $this->output['status'] = 'succ';
        $this->output['msg'] = '订单详情获取成功';
        $data['orderlog'] = $orderlog;
        $data['oid'] = $orders['id'];
        $data['order_no'] = $orders['order_no'];
        $data['create_time'] = $orders['create_time'];
        $data['ship_addr'] = $ship_addr;
        $data['goods_amount'] = $goods_amount;
        $data['accept_mobile'] = $orders['mobile'] ? $orders['mobile'] : $addrData['mobile'];
        $data['accept_name'] = $orders['accept_name'] ? $orders['accept_name'] : $addrData['accept_name'];
        $data['real_freight'] = $orders['real_freight'];
        $data['payable_freight'] = $orders['payable_freight'];
        $data['order_amount'] = $orders['order_amount'];
        $data['payment_id'] = $orders['payment'];
        $data['payment'] = '支付宝[手机支付]';
        $data['products'] = $products;
        $data['status'] = $status;

        $this->output($data);
    }

    private function status($item = array())
    {
        if($item['status'] == '1'){
            return '等待付款';
        }elseif($item['status'] == '2'){
            if($item['pay_status'] == 1){
                return '等待审核';
            }else{
                $payment_info = Common::getPaymentInfo($item['payment']);
                if($payment_info['class_name']=='received'){
                    return '等待审核';
                }else{
                    return '等待付款';
                }
            }
        }elseif($item['status'] == '3'){
            if($item['delivery_status'] == 0){
                return '等待发货';
            }elseif($item['delivery_status'] == 1){
                return '已发货';
            }

            if($item['pay_status'] == 3){
                return '已退款';
            }

        }elseif($item['status'] == 4){
            return '已完成';
        }elseif($item['status'] == 5){
            return '已取消';
        }elseif($item['status'] == 6){
            return '已作废';
        }
    }

    //待支付订单
    protected function getWaitCheckOrders()
    {

        $userid = $this->params['uid'];

        $orderModel = new Model('order');

        $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and status in (1,2)')->order('unix_timestamp(create_time) desc');

        if($this->params['page'] != null){
            $orderModel->limit($this->params['page'].','.$this->limit);
        }

        $orders = $orderModel->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');
//
            $goodsModel = new Model('goods');

            $productsModel = new Model('products');

            $result = array();

            foreach($orders as $k=>$val){

                $status = $this->status($val);

                $goods_count = $orderDetailModel->fields('goods_id')->where('order_id='.$val['id'])->count();
//
                $products = array();

                $odDatas = $orderDetailModel->fields('product_id,goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                foreach($odDatas as $kk => $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();

                    $items= $productsModel->fields("spec")->where("id=".$vval['product_id'])->findAll();

                    $spec = array();
                    $specs = unserialize($items['spec']);
                    foreach($specs as $_specs){
                        $spec[] = $_specs['value'][2];
                    }

                    $filename = self::getApiUrl().$gData['img'];

                    $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);

                    $products[$kk]['img'] = $image['src'];
                    $products[$kk]['goods_name'] = $gData['name'];
                    $products[$kk]['goods_nums'] = $vval['goods_nums'];
                    $products[$kk]['sale_price'] = $vval['real_price'];
                    $products[$kk]['spec'] = implode(',',$spec);

                }


                $result[$k]['oid'] = $val['id'];
                $result[$k]['order_no'] = $val['order_no'];
                $result[$k]['status'] = $status;
                $result[$k]['create_time'] = $val['create_time'];
                $result[$k]['order_amount'] = $val['order_amount'];
                $result[$k]['goods_count'] = $goods_count;

                $result[$k]['products'] = $products;
                $result[$k]['img'] = $this->getFistOrderImg($val['id']);

            }
            $this->output['status'] = 'succ';
            $this->output['msg'] = '会员订单获取成功';
            $this->output($result);
        }else{
            $this->output['msg'] = '暂无订单';
            $this->output();

        }

    }

    //待支付订单
    protected function getWaitPayOrders()
    {

        $userid = $this->params['uid'];

        $orderModel = new Model('order');

        $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and pay_status=0')->order('unix_timestamp(create_time) desc');

        if($this->params['page'] != null){
            $orderModel->limit($this->params['page'].','.$this->limit);
        }

        $orders = $orderModel->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');
//
            $goodsModel = new Model('goods');

            $productsModel = new Model('products');

            $result = array();

            foreach($orders as $k=>$val){

                if(in_array($val['status'],array('6'))){
                    continue;
                }

                $status = $this->status($val);

                $goods_count = $orderDetailModel->fields('goods_id')->where('order_id='.$val['id'])->count();
//
                $products = array();

                $odDatas = $orderDetailModel->fields('product_id,goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                foreach($odDatas as $kk => $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();

                    $items= $productsModel->fields("spec")->where("id=".$vval['product_id'])->findAll();

                    $spec = array();
                    $specs = unserialize($items['spec']);
                    foreach($specs as $_specs){
                        $spec[] = $_specs['value'][2];
                    }


                    $filename = self::getApiUrl().$gData['img'];

                    $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);

                    $products[$kk]['img'] = $image['src'];
                    $products[$kk]['goods_name'] = $gData['name'];
                    $products[$kk]['goods_nums'] = $vval['goods_nums'];
                    $products[$kk]['sale_price'] = $vval['real_price'];
                    $products[$kk]['spec'] = implode(',',$spec);

                }

                $result[$k]['oid'] = $val['id'];
                $result[$k]['order_no'] = $val['order_no'];
                $result[$k]['status'] = $status;
                $result[$k]['create_time'] = $val['create_time'];
                $result[$k]['order_amount'] = $val['order_amount'];
                $result[$k]['goods_count'] = $goods_count;

                $result[$k]['products'] = $products;
                $result[$k]['img'] = $this->getFistOrderImg($val['id']);

            }
            $this->output['status'] = 'succ';
            $this->output['msg'] = '会员订单获取成功';
            $this->output($result);
        }else{
            $this->output['msg'] = '暂无订单';
            $this->output();

        }

    }

    //待发货订单
    protected function getDeliveryOrders()
    {

        $userid = $this->params['uid'];

        $orderModel = new Model('order');

        $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and pay_status=1 and delivery_status=0')->order('unix_timestamp(pay_time) desc');

        if($this->params['page'] != null){
            $orderModel->limit($this->params['page'].','.$this->limit);
        }

        $orders = $orderModel->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');
//
            $goodsModel = new Model('goods');

            $productsModel = new Model('products');

            $result = array();

            foreach($orders as $k=>$val){

                if(in_array($val['status'],array('6'))){
                    continue;
                }

                $status = $this->status($val);

                $goods_count = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->count();
//
                $products = array();

                $odDatas = $orderDetailModel->fields('product_id,goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                foreach($odDatas as $kk => $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();

                    $items= $productsModel->fields("spec")->where("id=".$vval['product_id'])->findAll();

                    $spec = array();
                    $specs = unserialize($items['spec']);
                    foreach($specs as $_specs){
                        $spec[] = $_specs['value'][2];
                    }

                    $filename = self::getApiUrl().$gData['img'];

                    $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);


                    $products[$kk]['img'] = $image['src'];
                    $products[$kk]['goods_name'] = $gData['name'];
                    $products[$kk]['goods_nums'] = $vval['goods_nums'];
                    $products[$kk]['sale_price'] = $vval['real_price'];
                    $products[$kk]['spec'] = implode(',',$spec);

                }

                $result[$k]['oid'] = $val['id'];
                $result[$k]['order_no'] = $val['order_no'];
                $result[$k]['status'] = $status;
                $result[$k]['create_time'] = $val['create_time'];
                $result[$k]['order_amount'] = $val['order_amount'];
                $result[$k]['goods_count'] = $goods_count;

                $result[$k]['products'] = $products;
                $result[$k]['img'] = $this->getFistOrderImg($val['id']);
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '会员订单获取成功';
            $this->output($result);

        }else{
            $this->output['msg'] = '暂无订单';
            $this->output();

        }

    }

    //已完成订单(已经改为已发货接口)
    protected function getFinishOrders()
    {

        $userid = $this->params['uid'];

        $orderModel = new Model('order');

        $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and delivery_status=1')->order('unix_timestamp(create_time) desc');

        if($this->params['page'] != null){
            $orderModel->limit($this->params['page'].','.$this->limit);
        }

        $orders = $orderModel->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');
//
            $goodsModel = new Model('goods');

            $productsModel = new Model('products');

            $result = array();

            foreach($orders as $k=>$val){

                $status = $this->status($val);

                $goods_count = $orderDetailModel->fields('goods_id')->where('order_id='.$val['id'])->count();
//
                $products = array();

                $odDatas = $orderDetailModel->fields('product_id,goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                foreach($odDatas as $kk => $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();

                    $items= $productsModel->fields("spec")->where("id=".$vval['product_id'])->findAll();

                    $spec = array();
                    $specs = unserialize($items['spec']);
                    foreach($specs as $_specs){
                        $spec[] = $_specs['value'][2];
                    }


                    $filename = self::getApiUrl().$gData['img'];

                    $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);


                    $products[$kk]['img'] = $image['src'];
                    $products[$kk]['goods_name'] = $gData['name'];
                    $products[$kk]['goods_nums'] = $vval['goods_nums'];
                    $products[$kk]['sale_price'] = $vval['real_price'];
                    $products[$kk]['spec'] = implode(',',$spec);

                }

                $result[$k]['oid'] = $val['id'];
                $result[$k]['order_no'] = $val['order_no'];
                $result[$k]['status'] = $status;
                $result[$k]['create_time'] = $val['create_time'];
                $result[$k]['order_amount'] = $val['order_amount'];
                $result[$k]['goods_count'] = $goods_count;

                $result[$k]['products'] = $products;
                $result[$k]['img'] = $this->getFistOrderImg($val['id']);
            }
            $this->output['status'] = 'succ';
            $this->output['msg'] = '会员订单获取成功';
            $this->output($result);
        }else{
            $this->output['msg'] = '暂无订单';
            $this->output();

        }

    }

    //已作废订单
    protected function getCancelOrders()
    {

        $userid = $this->params['uid'];

        $orderModel = new Model('order');

        $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and status in (5,6)')->order('unix_timestamp(create_time) desc');

        if($this->params['page'] != null){
            $orderModel->limit($this->params['page'].','.$this->limit);
        }

        $orders = $orderModel->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');
//
            $goodsModel = new Model('goods');

            $productsModel = new Model('products');

            $result = array();

            foreach($orders as $k=>$val){

                $status = $this->status($val);

                $goods_count = $orderDetailModel->fields('goods_id')->where('order_id='.$val['id'])->count();
//
                $products = array();

                $odDatas = $orderDetailModel->fields('product_id,goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                foreach($odDatas as $kk => $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();

                    $items= $productsModel->fields("spec")->where("id=".$vval['product_id'])->findAll();

                    $spec = array();
                    $specs = unserialize($items['spec']);
                    foreach($specs as $_specs){
                        $spec[] = $_specs['value'][2];
                    }

                    $filename = self::getApiUrl().$gData['img'];

                    $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);


                    $products[$kk]['img'] = $image['src'];
                    $products[$kk]['goods_name'] = $gData['name'];
                    $products[$kk]['goods_nums'] = $vval['goods_nums'];
                    $products[$kk]['sale_price'] = $vval['real_price'];
                    $products[$kk]['spec'] = implode(',',$spec);

                }

                $result[$k]['oid'] = $val['id'];
                $result[$k]['order_no'] = $val['order_no'];
                $result[$k]['status'] = $status;
                $result[$k]['create_time'] = $val['create_time'];
                $result[$k]['order_amount'] = $val['order_amount'];
                $result[$k]['goods_count'] = $goods_count;

                $result[$k]['products'] = $products;
                $result[$k]['img'] = $this->getFistOrderImg($val['id']);
            }
            $this->output['status'] = 'succ';
            $this->output['msg'] = '会员订单获取成功';
            $this->output($result);
        }else{
            $this->output['msg'] = '暂无订单';
            $this->output();

        }

    }

    //我的订单
    protected function getMyOrders()
    {

        $userid = $this->params['uid'];

        $orderModel = new Model('order');

        $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid)->order('id desc');

        if($this->params['page'] != null){
            $orderModel->limit($this->params['page'].','.$this->limit);
        }

        $orders = $orderModel->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');
//
            $goodsModel = new Model('goods');

            $productsModel = new Model('products');

            $result = array();

            foreach($orders as $k=>$val){

                $status = $this->status($val);

                $goods_count = $orderDetailModel->fields('goods_id')->where('order_id='.$val['id'])->count();
//
                $products = array();

                $odDatas = $orderDetailModel->fields('product_id,goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                foreach($odDatas as $kk => $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();

                    $items= $productsModel->fields("spec")->where("id=".$vval['product_id'])->findAll();

                    $spec = array();
                    $specs = unserialize($items['spec']);
                    foreach($specs as $_specs){
                        $spec[] = $_specs['value'][2];
                    }


                    $filename = self::getApiUrl().$gData['img'];

                    $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);


                    $products[$kk]['img'] = $image['src'];
                    $products[$kk]['goods_name'] = $gData['name'];
                    $products[$kk]['goods_nums'] = $vval['goods_nums'];
                    $products[$kk]['sale_price'] = $vval['real_price'];
                    $products[$kk]['spec'] = implode(',',$spec);

                }

                $result[$k]['oid'] = $val['id'];
                $result[$k]['order_no'] = $val['order_no'];
                $result[$k]['status'] = $status;
                $result[$k]['create_time'] = $val['create_time'];
                $result[$k]['order_amount'] = $val['order_amount'];
                $result[$k]['goods_count'] = $goods_count;

                $result[$k]['products'] = $products;

                $result[$k]['img'] = $this->getFistOrderImg($val['id']);
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '会员订单获取成功';

            $this->output($result);

        }else{
            $this->output['msg'] = '暂无订单';
            $this->output();

        }

    }

    public function getFistOrderImg($order_id){
        $orderDetailModel = new Model('order_goods');

        $goodsModel = new Model('goods');

        $odDatas = $orderDetailModel->fields('goods_id')->where('order_id='.$order_id)->find();

        $gData = $goodsModel->fields('img')->where('id='.$odDatas['goods_id'])->find();

        return self::getApiUrl().$gData['img'];
    }


    protected function ordercancel()
    {
        $oid = $this->params['oid'];

        $oid = trim($oid);

        if(empty($oid)){
            $this->output['msg'] = '订单号不存在';
            $this->output();
            exit;
        }

        $serverName = Tiny::getServerName();

        $orderGoodsModel = new Model('order_goods');
        $productsModel = new Model('products');

        $products = $orderGoodsModel->where("order_id=".$oid)->findAll();

        foreach ($products as $pro) {
            //更新货品中的库存信息
            $goods_nums = $pro['goods_nums'];
            $product_id = $pro['product_id'];
            $productsModel->where("id=".$product_id)->data(array('freeze_nums'=>"`freeze_nums`-".$goods_nums))->update();
        }

        $orderModel = new Model('order');
        $orderModel->where("id=".$oid)->data(array('status'=>6))->update();

        $zdOrderModel = new Model('order','zd','salve');
        $zdOrderModel->where("outer_id=".$oid." and site_url='".$serverName['top']."'")->data(array('status'=>6))->update();

        $this->output['status'] = 'succ';
        $this->output['msg'] = '订单取消成功';

        $this->output();
    }

    public function ordercancel_demo()
    {
        $return = array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'订单号不存在/订单取消失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'订单取消成功',
                'data'=>array(
                ),
            )
        );

        return $return;
    }

    public function orderdetail_demo()
    {

        $return = array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'订单号不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'订单详情获取成功',
                'data'=>array(
                    'oid'=>'订单主键id',
                    'order_no'=>1241242342,
                    'create_time'=>'订单创建时间',
                    'ship_addr'=>'收货地址',
                    'goods_amount'=>'商品总金额',
                    'real_freight'=>'商品总重量',
                    'payable_freight'=>'配送费用',
                    'order_amount'=>'订单总金额',
                    'payment'=>'支付宝[手机支付]',
                    'accept_mobile' => '收货人手机号',
                    'accept_name' => '收货人姓名',
                    'status'=>'订单状态 (等待付款/等待审核/已发货/已完成)',
                    'orderlog'=>array(
                        array(
                            'addtime'=>'操作时间',
                            'note'=>'事件说明',
                        ),
                    ),
                    'products'=>array(
                        array(
                            'img'=>'商品图片',
                            'sale_price'=>'商品单价',
                            'goods_name'=>'商品名称',
                            'goods_nums'=>'商品数量',
                            'specs'=>'商品规格',
                        ),
                    ),
                ),
            )
        );

        return $return;

    }


    public function morders_demo()
    {

        $return = array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'会员订单不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'会员订单获取成功',
                'data'=>array(
                    array(
                        'oid'=>'订单主键id',
                        'order_no'=>1241242342,
                        'create_time'=>'订单创建时间',
                        'order_amount'=>'订单总金额',
                        'status'=>'订单状态 (等待付款/等待审核/已发货/已完成)',
                        'goods_count'=>10,
                        'products'=>array(
                            array(
                                'img'=>'商品图片',
                                'sale_price'=>'商品单价',
                                'goods_name'=>'商品名称',
                                'goods_nums'=>'商品数量',
                            ),
                        ),
                    ),
                ),
            )
        );

        return $return;

    }

}

