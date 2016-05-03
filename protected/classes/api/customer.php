<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 24/4/16
 * Time: 下午5:14
 * 192.168.1.100/index.php?con=api&act=index&method=customer&source=uinfo
 */
class customer extends baseapi
{

    protected $addrManageTemplate = '<li class="swipeout">
            <div class="swipeout-content"><a href="#" class="item-link item-content">
                <div class="item-inner">
                    <div class="item-title-row">
                        <div class="item-title">{name}</div>
                        <div class="item-after">{mobile}</div>
                    </div>
                    <div class="item-subtitle">{address}&nbsp;&nbsp;{is_default}</div>
                </div></a></div>
            <div class="swipeout-actions-right"><a href="addaddress.html?id={id}" class="link">编辑</a><a href="#" class="demo-mark bg-orange">设为默认</a><a href="#" data-confirm="Are you sure you want to delete this item?" class="swipeout-delete swipeout-overswipe">删除</a></div>
        </li>';

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

    public function index()
    {
        switch($this->params['source']){
            case 'morders':
                $this->getMyOrders();
                break;
            case 'uinfo':
                $this->getUserInfo();
                break;
            case 'address':
                $this->getAddress();
                break;
            case 'addaddr':
                $this->addAddress();
                break;
            case 'cpwd':
                $this->changePwd();
                break;
            case 'login':
                $this->login();
                break;
            case 'register':
                $this->register();
                break;
        }
    }

    //我的订单
    protected function getMyOrders()
    {
        $html = '';

        $userid = $this->params['id'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid)->limit(2)->findAll();

        $orderDetailModel = new Model('order_goods');

        $goodsModel = new Model('goods');

        foreach($orders as $val){

            $status = $this->status($val);

            $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

            $products = '';
//<div class="swiper-slide"><img src="http://lorempixel.com/500/500/nature/1" width="200" height="200" /></div>
            foreach($odDatas as $vval){
                $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();
                $products .= '<div class="swiper-slide"><img src="'.self::APIURL.$gData['img'].'" width="100" height="100" /><span style="font-size:14px;">'.$gData['name'].'<br/>￥'.$vval['real_price'].'<br/> X '.$vval['goods_nums'].'</span></div>';
            }
            $html .= str_replace(array('{id}','{order_no}','{status}','{products}'),array($val['id'],$val['order_no'],$status,$products),$this->myOrderListTemplate);
        }

        echo $html;
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

    //个人资料
    protected function getUserInfo()
    {
        $data = array();

        $userModel = new Model('user');
        $userInfo = $userModel->fields('name,email,head_pic')->where('id=1')->find();

        if($userInfo){
            $customerModel = new Model('customer');
            $customerInfo = $customerModel->fields('real_name,sex,phone,mobile,addr,birthday,province,city,county,balance')->where('user_id=1')->find();

            $customerInfo['sex'] = ($customerInfo['sex'] == 1) ? '男' : '女';
            $data = array_merge($userInfo,$customerInfo);
            $this->output['status'] = 'succ';
        }


        $this->output($data,'json');
    }

    //收货地址管理
    protected function getAddress()
    {
        $userid = intval($this->params['id']);

        $addressModel = new Model('address');

        $addrLists = $addressModel->fields('id,accept_name,mobile,phone,province,city,county,zip,addr,is_default')->where('user_id='.$userid)->findAll();

        if($addrLists){
            $this->getAddrHtml($addrLists);

        }else{
            echo '';
        }

    }

    //添加/编辑收获地址
    protected function addAddress()
    {
        $addrid = intval($this->params['id']);

        $data = array();

        $addressModel = new Model('address');

        $addrList = $addressModel->fields('id,accept_name,mobile,phone,province,city,county,zip,addr,is_default')->where('id='.$addrid)->find();

        if($addrList){
            $data = $addrList;
            $this->output['status'] = 'succ';
        }

        $this->output($data,'json');

    }

    protected function getAddrHtml($addrLists)
    {
        $html = '';

        $areaModel = new Model('area','zd','salve');

        foreach($addrLists as $val){
            $id = $val['id'];
            $name = $val['accept_name'];
            $mobile = $val['mobile'];
            $province = $val['province'];
            $city = $val['city'];
            $county = $val['county'];
            $addr = $val['addr'];
            $is_default = ($val['is_default'] == 1) ? '<span class="badge bg-gray">默认</span>' : '';

            $area_ids = $province.','.$city.','.$county;

            if($area_ids!='')$areas = $areaModel->where("id in ($area_ids)")->findAll();
            $parse_area = array();
            foreach ($areas as $area) {
                $parse_area[$area['id']] = $area['name'];
            }

            $address = $parse_area[$province].$parse_area[$city].$parse_area[$county].$addr;

            $html .= str_replace(array('{id}','{name}','{mobile}','{address}','{is_default}'),array($id,$name,$mobile,$address,$is_default),$this->addrManageTemplate);
        }

        echo $html;
    }

    //修改密码
    protected function changePwd()
    {

    }

    //登录
    protected function login()
    {
        print_r($this->params);
    }

    //注册
    protected function register()
    {

    }
}