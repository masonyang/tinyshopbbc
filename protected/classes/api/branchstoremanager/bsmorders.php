<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 17/12/16
 * Time: 下午12:06
 *
 * 订单相关操作
 *
 */
class bsmorders extends basmbase
{

    public static $title = array(
        'orderssearch'=>'订单搜索',
        'orderslist'=>'订单列表 ok',
        'ordersdetail'=>'订单详情 ok',
    );

    public static $lastmodify = array(
        'orderssearch'=>'2016-12-17',
        'orderslist'=>'2016-12-17',
        'ordersdetail'=>'2016-12-17',
    );

    public static $notice = array(
        'orderssearch'=>'',
        'orderslist'=>'',
        'ordersdetail'=>'',
    );

    public static $requestParams = array(
        'orderssearch'=>array(

        ),
        'orderslist'=>array(
            array(
                'colum'=>'status',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'订单状态 (waitpay:等待付款/delivery:已支付/finish:已发货/all:所有订单)',
            ),
            array(
                'colum'=>'type',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'搜索类型:order_no - 按订单号搜索',
            ),
            array(
                'colum'=>'filter',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'搜索内容',
            ),
            array(
                'colum'=>'limit',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'每次请求加载的数据条数',
            ),
            array(
                'colum'=>'offset',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'上一页最后一条数据的索引',
            ),
            array(
                'colum'=>'iscount',
                'required'=>'可选',
                'type'=>'boolean',
                'content'=>'是否返回总数',
            ),
            array(
                'colum'=>'bmsmd5',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'加密字符串=店铺id+分店管理员id',
            ),
        ),
        'ordersdetail'=>array(
            array(
                'colum'=>'oid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'订单id',
            ),
            array(
                'colum'=>'bmsmd5',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'加密字符串=店铺id+分店管理员id',
            ),
        ),
    );

    public static $responsetParams = array(
        'orderssearch'=>array(
            array(
                'colum'=>'name',
                'content'=>'姓名',
            ),
        ),
        'orderslist'=>array(
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
                'colum'=>'create_timestamp',
                'content'=>'订单创建时间 时间戳',
            ),
            array(
                'colum'=>'status',
                'content'=>'订单状态 (等待付款/等待审核/已发货/已完成)',
            ),
            array(
                'colum'=>'orders_amounts',
                'content'=>'订单总金额',
            ),
            array(
                'colum'=>'count',
                'content'=>'当传入iscount时，返回count。',
            ),
        ),
        'ordersdetail'=>array(
            array(
                'colum'=>'order_no',
                'content'=>'订单号',
            ),
            array(
                'colum'=>'status',
                'content'=>'订单状态',
            ),
            array(
                'colum'=>'ship_addr',
                'content'=>'收货地址',
            ),
            array(
                'colum'=>'payment',
                'content'=>'支付方式',
            ),
            array(
                'colum'=>'goods_amounts',
                'content'=>'商品总金额',
            ),
            array(
                'colum'=>'orders_amounts',
                'content'=>'订单总金额',
            ),
            array(
                'colum'=>'payable_freight',
                'content'=>'运费金额',
            ),
            array(
                'colum'=>'create_time',
                'content'=>'订单创建时间',
            ),
            array(
                'colum'=>'create_timestamp',
                'content'=>'订单创建时间 时间戳',
            ),
            array(
                'colum'=>'order_remark',
                'content'=>'订单备注',
            ),
            array(
                'colum'=>'goods_item',
                'content'=>'商品明细',
            ),
        ),
    );

    public static $requestUrl = array(
        'orderssearch'=>'     /index.php?con=api&act=index&method=bsmorders&source=orderssearch',
        'orderslist'=>'     /index.php?con=api&act=index&method=bsmorders&source=orderslist',
        'ordersdetail'=>'     /index.php?con=api&act=index&method=bsmorders&source=ordersdetail',
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {

        switch($this->params['source']){
            case 'orderslist':
                if($this->params['status'] == 'waitpay'){//未付款
                    $this->getWaitPayOrders();
                }elseif($this->params['status'] == 'delivery'){//已付款
                    $this->getDeliveryOrders();
                }elseif($this->params['status'] == 'finish'){//已发货接口
                    $this->getFinishOrders();
                }else{
                    $this->getAllOrders();
                }
            break;
            case 'ordersdetail':
                $this->ordersdetail();
            break;
        }

    }

    private function getAllOrders()
    {

        $orderModel = new Model('order',$this->domain,'salve');

        $filter = $this->__filter();

        $date = $this->GetMonth();

        $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('(create_time <= "'.date('Y-m-d H:i:s').'" and create_time >="'.$date.') '.$filter['where'])->order('unix_timestamp(create_time) desc');

        $orderModel->limit($filter['limit']);

        $orders = $orderModel->findAll();


        $count = false;

        if(isset($this->params['iscount']) && ($this->params['iscount'] == true)){
            $count = $orderModel->where('(create_time <= "'.date('Y-m-d H:i:s').'" and create_time >="'.$date.') '.$filter['where'])->count();
        }

        if($orders){

            $result = array();

            if($count){
                $result['count'] = $count;
            }

            $i = 0;
            foreach($orders as $val){

                $status = $this->status($val);

                $result['orders'][$i]['oid'] = $val['id'];
                $result['orders'][$i]['order_no'] = $val['order_no'];
                $result['orders'][$i]['status'] = $status;
                $result['orders'][$i]['create_time'] = $val['create_time'];
                $result['orders'][$i]['create_timestamp'] = strtotime($val['create_time']);
                $result['orders'][$i]['order_amount'] = $val['order_amount'];
                $i++;
            }
            $this->output['status'] = 'succ';
            $this->output['msg'] = '订单获取成功';
            $this->output($result);
        }else{
            $this->output['msg'] = '暂无订单';
            $this->output();

        }

    }

    private function GetMonth($sign="1")
    {

        //得到系统的年月

        $tmp_date=date("YmdHis");

        //切割出年份

        $tmp_year=substr($tmp_date,0,4);

        //切割出月份

        $tmp_mon =substr($tmp_date,4,2);

        $tmp_nextmonth=mktime(0,0,0,$tmp_mon+1,1,$tmp_year);

        $tmp_forwardmonth=mktime(0,0,0,$tmp_mon-1,1,$tmp_year);

        if($sign==0){

            //得到当前月的下一个月

            return $tmp_nextmonth;

        }else{

            //得到当前月的上一个月

            return $tmp_forwardmonth;

        }

    }

    private function getFinishOrders()
    {
        $orderModel = new Model('order',$this->domain,'salve');

        $filter = $this->__filter();

        $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('delivery_status=1 '.$filter['where'])->order('unix_timestamp(create_time) desc');

        $orderModel->limit($filter['limit']);

        $orders = $orderModel->findAll();


        $count = false;

        if(isset($this->params['iscount']) && ($this->params['iscount'] == true)){
            $count = $orderModel->where('delivery_status=1 '.$filter['where'])->count();
        }

        if($orders){

            $result = array();

            if($count){
                $result['count'] = $count;
            }

            $i = 0;
            foreach($orders as $val){

                if(in_array($val['status'],array('6'))){
                    continue;
                }

                $status = $this->status($val);

                $result['orders'][$i]['oid'] = $val['id'];
                $result['orders'][$i]['order_no'] = $val['order_no'];
                $result['orders'][$i]['status'] = $status;
                $result['orders'][$i]['create_time'] = $val['create_time'];
                $result['orders'][$i]['create_timestamp'] = strtotime($val['create_time']);
                $result['orders'][$i]['order_amount'] = $val['order_amount'];
                $i++;
            }
            $this->output['status'] = 'succ';
            $this->output['msg'] = '订单获取成功';
            $this->output($result);
        }else{
            $this->output['msg'] = '暂无订单';
            $this->output();

        }
    }

    private function getDeliveryOrders()
    {
        $orderModel = new Model('order',$this->domain,'salve');

        $filter = $this->__filter();

        $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('pay_status=1 and delivery_status=0 '.$filter['where'])->order('unix_timestamp(pay_time) desc');

        $orderModel->limit($filter['limit']);

        $orders = $orderModel->findAll();


        $count = false;

        if(isset($this->params['iscount']) && ($this->params['iscount'] == true)){
            $count = $orderModel->where('pay_status=1 and delivery_status=0 '.$filter['where'])->count();
        }

        if($orders){

            $result = array();

            if($count){
                $result['count'] = $count;
            }

            $i = 0;
            foreach($orders as $val){

                if(in_array($val['status'],array('6'))){
                    continue;
                }

                $status = $this->status($val);

                $result['orders'][$i]['oid'] = $val['id'];
                $result['orders'][$i]['order_no'] = $val['order_no'];
                $result['orders'][$i]['status'] = $status;
                $result['orders'][$i]['create_time'] = $val['create_time'];
                $result['orders'][$i]['create_timestamp'] = strtotime($val['create_time']);
                $result['orders'][$i]['order_amount'] = $val['order_amount'];
                $i++;
            }
            $this->output['status'] = 'succ';
            $this->output['msg'] = '订单获取成功';
            $this->output($result);
        }else{
            $this->output['msg'] = '暂无订单';
            $this->output();

        }
    }

    private function getWaitPayOrders()
    {

        $orderModel = new Model('order',$this->domain,'salve');

        $filter = $this->__filter();

        $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('pay_status=0 '.$filter['where'])->order('unix_timestamp(create_time) desc');

        $orderModel->limit($filter['limit']);

        $orders = $orderModel->findAll();


        $count = false;

        if(isset($this->params['iscount']) && ($this->params['iscount'] == true)){
            $count = $orderModel->where('pay_status=0 '.$filter['where'])->count();
        }

        if($orders){

            $result = array();

            if($count){
                $result['count'] = $count;
            }

            $i = 0;
            foreach($orders as $val){

                if(in_array($val['status'],array('6'))){
                    continue;
                }

                $status = $this->status($val);

                $result['orders'][$i]['oid'] = $val['id'];
                $result['orders'][$i]['order_no'] = $val['order_no'];
                $result['orders'][$i]['status'] = $status;
                $result['orders'][$i]['create_time'] = $val['create_time'];
                $result['orders'][$i]['create_timestamp'] = strtotime($val['create_time']);
                $result['orders'][$i]['order_amount'] = $val['order_amount'];
                $i++;
            }
            $this->output['status'] = 'succ';
            $this->output['msg'] = '订单获取成功';
            $this->output($result);
        }else{
            $this->output['msg'] = '暂无订单';
            $this->output();

        }

    }

    private function __filter()
    {
        $return = array(
            'type'=>'',
            'order'=>'',
            'where'=>'',
            'limit'=>'',
        );

        $type = $this->params['type'];

        if($type == 'name'){ // 根据 关键字

            $return['type'] = 'search';

            $return['where'] = $this->params['filter'] ? 'and (order_no like "%'.Filter::sql($this->params['filter']).'%" or branchstore_goods_name like "%'.Filter::sql($this->params['filter']).'%")' : '';

        }

        $this->params['offset'] = ($this->params['offset'] == 0) ? 1 : $this->params['offset'];

        $offset = ($this->params['offset'] - 1) * $this->params['limit'];

        $return['limit'] = $offset.','.$this->params['limit'];

        return $return;
    }

    private function ordersdetail()
    {
        $orderid = $this->params['oid'];

        $orderModel = new Model('order',$this->domain,'salve');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status,province,city,county,addr,real_freight,user_id,payable_freight,accept_name,mobile')->where('id='.$orderid)->find();

        $orderDetailModel = new Model('order_goods',$this->domain,'salve');

        $goodsModel = new Model('goods',$this->domain,'salve');


        $status = $this->status($orders);

        $goods_amount = 0;


        $odDatas = $orderDetailModel->fields('product_id,goods_id,real_price,goods_nums')->where('order_id='.$orders['id'])->findAll();

        if(!$odDatas){
            $this->output['msg'] = '订单号不存在';
            $this->output();
            return ;
        }

        $productsModel = new Model('products',$this->domain,'salve');

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

            $image = $filename;//ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);


            $products[$k]['img'] = $image['src'];
            $products[$k]['sell_price'] = $vval['real_price'];
            $products[$k]['name'] = $gData['name'];
            $products[$k]['nums'] = $vval['goods_nums'];
            $products[$k]['specs'] = implode(',',$spec);

            $goods_amount += ($vval['real_price']*$vval['goods_nums']);
        }


        $area_ids = $orders['province'].','.$orders['city'].','.$orders['county'];

        $areaModel = new Model('area','zd','salve');
        if($area_ids!='')$areas = $areaModel->where("id in ($area_ids)")->findAll();
        $parse_area = array();
        foreach ($areas as $area) {
            $parse_area[$area['id']] = $area['name'];
        }

        $ship_addr = $parse_area[$orders['province']].$parse_area[$orders['city']].$parse_area[$orders['county']].' '.$orders['addr'];


        $this->output['status'] = 'succ';
        $this->output['msg'] = '订单详情获取成功';


        $data['order_no'] = $orders['order_no'];
        $data['create_time'] = $orders['create_time'];
        $data['create_timestamp'] = strtotime($orders['create_time']);
        $data['ship_addr'] = $ship_addr;
        $data['goods_amount'] = $goods_amount;
        $data['payable_freight'] = $orders['payable_freight'];
        $data['order_amount'] = $orders['order_amount'];
        //$data['payment_id'] = $orders['payment'];
        $data['payment'] = '支付宝[手机支付]';
        $data['goods_item'] = $products;
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

    public function orderssearch_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'code'=>self::CODE_FAIL,
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'code'=>self::CODE_SUCC,
                'msg'=>'获取成功',
                'data'=>array(
                    array(
                        'name'=>'测试分销商',
                    ),
                ),
            )
        );

    }

    public function orderslist_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'code'=>self::CODE_FAIL,
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'code'=>self::CODE_SUCC,
                'msg'=>'安全退出',
                'data'=>array(
                    'count'=>10,
                    'orders'=>array(
                        array(
                            'oid'=>1,
                            'order_no'=>'20160923102234312',
                            'create_time'=>'2016-09-23',
                            'create_timestamp'=>'时间戳',
                            'status'=>'已支付',
                            'orders_amounts'=>'101',
                        ),
                    ),
                ),
            )
        );

    }

    public function ordersdetail_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'code'=>self::CODE_FAIL,
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'code'=>self::CODE_SUCC,
                'msg'=>'订单详情获取成功',
                'data'=>array(
                    'order_no'=>'20160923102234312',
                    'status'=>'已支付',
                    'ship_addr'=>'上海市浦东新区',
                    'payment'=>'支付宝',
                    'goods_amounts'=>'101',
                    'orders_amounts'=>'101',
                    'payable_freight'=>'1',
                    'create_time'=>'2016-09-23',
                    'create_timestamp'=>'时间戳',
                    'order_remark'=>'我要包邮',
                    'goods_item'=>array(
                        array(
                            'name'=>'商品名称',
                            'sell_price'=>'商品单价',
                            'nums'=>'数量',
                            'img'=>'商品图片',
                            'specs'=>'规格名称',
                        ),
                    ),
                ),
            )
        );

    }


}