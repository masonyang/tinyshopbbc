<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 29/4/16
 * Time: 下午6:10
 */
class orders extends baseapi
{

    protected $myOrderListTemplate = '<div class="card ks-facebook-card item-link">
                        <a href="orderdetail.html?id={id}">
                        <div class="no-border item-inner">
                            <div class="item-title">&nbsp;&nbsp;订单 {order_no} <br/> &nbsp;&nbsp;<span class="badge bg-green">{status}</span> </div>
                            <div class="item-after">

                            </div>
                        </div>
                        </a>

                        <div data-pagination=".swiper-pagination" data-pagination-hide="true" class="swiper-container swiper-init ks-demo-slider">
                            <div class="swiper-pagination"></div>
                            <div class="swiper-wrapper">
                              {products}
                            </div>
                        </div>

                        <div class="card-footer no-border"><a href="#" class="link"><span class="badge bg-green">立即支付</span></a><a href="#" class="link"><span class="badge bg-green">再次购买</span></a></div>
                    </div>';

    protected $myOrderListNoTemplate = '<div class="card ks-facebook-card">
                        <div class="no-border item-inner">
                            <div class="item-title" style="text-align: center;">暂无订单</div>

                        </div>
                    </div>';

    public static $title = array(
        'orderdetail'=>'订单详情'
    );

    public static $lastmodify = array(
        'orderdetail'=>'2016-6-28',
    );

    public static $notice = array(
        'orderdetail'=>'',
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
    );

    public static $requestUrl = array(
        'orderdetail'=>'     /index.php?con=api&act=index&method=orders&source=orderdetail'
    );

    public function index()
    {
        switch($this->params['source']){
            case 'orderdetail':
                $this->orderDetail();
                break;
            case 'morders':
                if($this->params['type'] == 'waitpay'){
                    $this->getWaitPayOrders();
                }elseif($this->params['type'] == 'delivery'){
                    $this->getDeliveryOrders();
                }elseif($this->params['type'] == 'finish'){
                    $this->getFinishOrders();
                }else{
                    $this->getMyOrders();
                }
                break;
        }
    }

    protected function orderDetail()
    {
        $orderid = $this->params['oid'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status,province,city,county,addr,real_freight,user_id')->where('id='.$orderid)->find();

        $orderDetailModel = new Model('order_goods');

        $goodsModel = new Model('goods');


        $status = $this->status($orders);

        $goods_amount = 0;


        $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$orders['id'])->findAll();

        if(!$odDatas){
            $this->output['msg'] = '订单号不存在';
            $this->output();
            return ;
        }

        $products = array();

        foreach($odDatas as $k=>$vval){
            $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();

            $products[$k]['img'] = self::getApiUrl().$gData['img'];
            $products[$k]['sale_price'] = $vval['real_price'];
            $products[$k]['goods_name'] = $gData['name'];
            $products[$k]['goods_nums'] = $vval['goods_nums'];

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
        $data['accept_mobile'] = $addrData['mobile'];
        $data['accept_name'] = $addrData['accept_name'];
        $data['real_freight'] = $orders['real_freight'];
        $data['order_amount'] = $orders['order_amount'];
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
    protected function getWaitPayOrders()
    {
        $html = '';

        $userid = $this->params['uid'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and pay_status=0')->order('unix_timestamp(create_time) desc')->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');

            $goodsModel = new Model('goods');

            foreach($orders as $val){

                $status = $this->status($val);

                $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                $products = '';

                foreach($odDatas as $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();
                    $products .= '<div class="swiper-slide"><img src="'.self::getApiUrl().$gData['img'].'" width="100" height="100" /><span style="font-size:14px;">'.$gData['name'].'<br/>￥'.$vval['real_price'].'<br/> X '.$vval['goods_nums'].'</span></div>';
                }
                $html .= str_replace(array('{id}','{order_no}','{status}','{products}'),array($val['id'],$val['order_no'],$status,$products),$this->myOrderListTemplate);
            }
        }else{
            $html = $this->myOrderListNoTemplate;

        }


        echo $html;
    }

    //待发货订单
    protected function getDeliveryOrders()
    {
        $html = '';

        $userid = $this->params['uid'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and pay_status=1 and delivery_status=0')->order('unix_timestamp(pay_time) desc')->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');

            $goodsModel = new Model('goods');

            foreach($orders as $val){

                $status = $this->status($val);

                $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                $products = '';

                foreach($odDatas as $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();
                    $products .= '<div class="swiper-slide"><img src="'.self::getApiUrl().$gData['img'].'" width="100" height="100" /><span style="font-size:14px;">'.$gData['name'].'<br/>￥'.$vval['real_price'].'<br/> X '.$vval['goods_nums'].'</span></div>';
                }
                $html .= str_replace(array('{id}','{order_no}','{status}','{products}'),array($val['id'],$val['order_no'],$status,$products),$this->myOrderListTemplate);
            }
        }else{
            $html = $this->myOrderListNoTemplate;

        }


        echo $html;
    }

    //已完成订单
    protected function getFinishOrders()
    {
        $html = '';

        $userid = $this->params['uid'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and status=4')->order('unix_timestamp(completion_time) desc')->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');

            $goodsModel = new Model('goods');

            foreach($orders as $val){

                $status = $this->status($val);

                $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                $products = '';

                foreach($odDatas as $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();
                    $products .= '<div class="swiper-slide"><img src="'.self::getApiUrl().$gData['img'].'" width="100" height="100" /><span style="font-size:14px;">'.$gData['name'].'<br/>￥'.$vval['real_price'].'<br/> X '.$vval['goods_nums'].'</span></div>';
                }
                $html .= str_replace(array('{id}','{order_no}','{status}','{products}'),array($val['id'],$val['order_no'],$status,$products),$this->myOrderListTemplate);
            }
        }else{
            $html = $this->myOrderListNoTemplate;

        }


        echo $html;
    }

    //我的订单
    protected function getMyOrders()
    {
        $html = '';

        $userid = $this->params['uid'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid)->order('id desc')->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');

            $goodsModel = new Model('goods');

            foreach($orders as $val){

                $status = $this->status($val);

                $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                $products = '';
//<div class="swiper-slide"><img src="http://lorempixel.com/500/500/nature/1" width="200" height="200" /></div>
                foreach($odDatas as $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();
                    $products .= '<div class="swiper-slide"><img src="'.self::getApiUrl().$gData['img'].'" width="100" height="100" /><span style="font-size:14px;">'.$gData['name'].'<br/>￥'.$vval['real_price'].'<br/> X '.$vval['goods_nums'].'</span></div>';
                }
                $html .= str_replace(array('{id}','{order_no}','{status}','{products}'),array($val['id'],$val['order_no'],$status,$products),$this->myOrderListTemplate);
            }
        }else{
            $html = $this->myOrderListNoTemplate;

        }


        echo $html;
    }

//    private function status($item = array())
//    {
//        if($item['status'] == '1'){
//            return '等待付款';
//        }elseif($item['status'] == '2'){
//            if($item['pay_status'] == 1){
//                return '等待审核';
//            }else{
//                $payment_info = Common::getPaymentInfo($item['payment']);
//                if($payment_info['class_name']=='received'){
//                    return '等待审核';
//                }else{
//                    return '等待付款';
//                }
//            }
//        }elseif($item['status'] == '3'){
//            if($item['delivery_status'] == 0){
//                return '等待发货';
//            }elseif($item['delivery_status'] == 1){
//                return '已发货';
//            }
//
//            if($item['pay_status'] == 3){
//                return '已退款';
//            }
//
//        }elseif($item['status'] == 4){
//            return '已完成';
//        }elseif($item['status'] == 5){
//            return '已取消';
//        }elseif($item['status'] == 6){
//            return '已作废';
//        }
//    }

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
                        ),
                    ),
                ),
            )
        );

        return $return;

    }

}