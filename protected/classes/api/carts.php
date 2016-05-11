<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 30/4/16
 * Time: 下午12:02
 */
class carts extends baseapi
{

    private $cart = null;

    protected $cartIndexTemplate = '
<style>

.spinner{margin: 12px 0 0 10px; float:left; border:1px solid #ccc; border-radius:5px; overflow:hidden; *zoom:1; }
.spinner button, .spinner .value{text-align:center; display:block; float:left; height:100%; line-height:25px; margin:0;width: 30px; }
.spinner button{border:none; width:26px; height:26px; color:#666; font:22px Arial bold; padding:0; outline:none; background-color:#fff; }
.spinner .decrease{cursor:pointer;}
.spinner .decrease[disabled]{background-position:0px 0px; background-color:#e4e4e4; cursor:default; }
.spinner .increase{cursor:pointer;}
.spinner .value{background-position:0 -100px; width:34px; height:26px; border:none; border-left:1px solid #ccc; border-right:1px solid #ccc; font-family:Arial; font-size:16px; color:#333; padding:0px; }
.spinner .value:focus{border-color:#669900; }
.spinner .value.passive{background-position:0 -25px; color:#919191; }
.spinner .error, .spinner .invalid{background:#aa0000; }

</style>
<div class="list-block">
<ul><li class="swipeout">
            <div class="card ks-facebook-card item-content swipeout-content">
                <div class="card-header">
                    <div class="ks-facebook-avatar"><img src="{img}" width="60" height="60"/></div>
                    <div style="float:right;">
                        <table>
                            <tr class="col-33 spinner">
                                <td style="background-color: #919191;">
                                    <button class="spinner_decr decrease">-</button>
                                </td>
                                <td>
                                    <input class="spinnerExample spinner_text batchnum value passive" type="number" name="product_num" maxlength="2" value="{num}">
                                </td>
                                <td style="background-color: #919191;">
                                    <button class="increase spinner_incr">+</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="ks-facebook-name" style="margin-right:44px;">{name}</div>
                    <div class="ks-facebook-date" style="margin-right:44px;">{spec}</div>
                    <div class="ks-facebook-date" style="margin-right:44px;">￥{amount}</div>
                </div>
                <div class="swipeout-actions-right"><a href="#" data-confirm="确定要删除这个商品吗?" class="swipeout-delete">Delete</a></div>
            </div>
        </li>';

    protected $checkoutTemplate = '<li>
            <div class="item-content">
                <div class="item-inner">
                    <div class="item-title label">合计</div>
                    <div class="item-input right">
                        ￥{total}
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="item-content">
                <div class="item-inner">
                    <div class="item-title label">&nbsp;</div>
                    <div class="item-input right">
                        &nbsp;
                    </div>
                    <div class="item-after">
                        <a href="checkout.html" class="item-link"><span class="button">去结算</span></a>
                    </div>
                </div>
            </div>
        </li></ul>
</div>';

    protected $noCartTemplate = '<div id="wrap-nodata" class=""  style="margin-top:35%;">
    <div id="carts-img">
        <label class="cartimg font-medium" style="display: block;margin: 0 auto;width: 130px;height: 122px;background: url(\'./img/lifevc-ios-ico.png\') no-repeat -48px -2314px;background-size: 510px 2663px;"></label>
    </div>
    <div id="carts-text font-medium" style="text-align: center;color: #666666;line-height: 3rem;">
        <p>您的购物车还是空荡荡的</p>
    </div>
    <div class="content-block">
        <div class="row" style="text-align:center;">
            <div class="col-100"><a href="index.html" class="button loginout button-big button-fill color-red">赶紧去逛逛</a></div>
        </div>
    </div>
</div>';



    protected $checkoutIndexTemplate = '<div class="content-block-title">请选择收货地址</div>
            <div class="list-block">
                <ul>
                    <li>
                        <div class="card ks-facebook-card" style="margin: 0px;">
                            <div class="card-header no-border">
                                <div style="float:right;">{mobile}</div>
                                <div class="ks-facebook-name" style="margin-right:44px;">收货人: {accept_name}</div>
                                <div class="ks-facebook-date" style="margin-right:44px;">{addr}</div>

                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="content-block-title">请选择支付方式</div>
            <div class="list-block media-list">
                <ul>
                    <li>
                        <label class="label-radio item-content">
                            <input type="radio" name="ks-media-radio" value="1" checked="checked"/>
                            <div class="item-media"><img src="http://photocdn.sohu.com/20151016/mp35928456_1444959854152_1_th.jpeg" width="50"/></div>
                            <div class="item-inner">
                                <div class="item-title-row">
                                    <div class="item-title">支付宝</div>
                                </div>
                                <div class="item-text">支付金额大于订单金额</div>
                            </div>
                        </label>
                    </li>
                </ul>
            </div>

            <div class="content-block-title">商品信息</div>
            <div class="list-block">
                <ul>
                    {goods_info}


                </ul>
            </div>

            <div class="list-block">
                <ul>
                    <li class="align-top">
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">订单备注</div>
                                <div class="item-input">
                                    <textarea></textarea>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">商品金额</div>
                                <div class="item-input right">
                                    ￥{total}
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">运费</div>
                                <div class="item-input right">
                                    ￥2.00
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="list-block">
                <ul>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">合计</div>
                                <div class="item-input right">
                                    ￥14.00
                                </div>
                                <div class="item-after"><a href="#" class="button">提交订单</a></div>
                            </div>

                        </div>
                    </li>
                </ul>
            </div>';


    public function index()
    {
        switch($this->params['source']){
            case 'cindex':
                $this->cartIndex();
                break;
            case 'addcart'://添加商品到购物车
                $data = array();
                $cart = Cart::getCart();
                $num = $this->params['pro_num'];
                $pro_no = $this->params['pro_no'];

                $info = '成功加入购物车';

                $productsModel = new Model('products');
                $product = $productsModel->where('pro_no="'.$pro_no.'"')->find();

                if($product['id'] && ($num>=1)){

                    if($product['store_nums'] > 0){
                        $cart->addItem($product['id'],$num);
                    }else{
                        $info = '该商品库存不足';
                    }

                }

                $data['count'] = count($cart->all());
                $this->output['msg'] = $info;
                $this->output($data);
            break;
            case 'removecart'://从购物车删除商品

            break;
            case 'scount'://统计购物车商品数量
                $cart = Cart::getCart();
                echo count($cart->all());
            break;
            case 'checkout':
                $this->checkout();
            break;
            case 'docheckout':
                $this->docheckout();
            break;
        }
    }

    //购物车页面
    protected function cartIndex()
    {
//        $html = $this->cartIndexTemplate;
//
//        $html .= $this->checkoutTemplate;

        $cart = Cart::getCart();

        $all = $cart->all();

        $cart_count = count($all);

        if($cart_count == 0){
            $html = $this->noCartTemplate;
        }else{
            $html = '';
            $total = 0;
            foreach($all as $item){
                $total +=$item['amount'];
                $img = self::getApiUrl().$item['img'];
                $name = $item['name'];
                $num = $item['num'];
                $amount = $item['amount'];
                $spec = array();
                foreach($item['spec'] as $specs){
                    $spec[] = $specs['value'][2];
                }
                $html .= str_replace(array('{img}','{name}','{num}','{spec}','{amount}'),array($img,$name,$num,implode(',',$spec),$amount),$this->cartIndexTemplate);
            }
            $html .= str_replace(array('{total}'),array($total),$this->checkoutTemplate);
        }


        echo $html;
    }

    protected function checkout()
    {
        $userid = $this->params['userid'];

        $addressModel = new Model('address');
        $addrData = $addressModel->where('user_id='.$userid.' and is_default=1')->find();

        $mobile = $addrData['mobile'];
        $accept_name = $addrData['accept_name'];

        $addr = $this->consignee($addrData);

        $total = 0;
        $goods_info = '';
        $cart = Cart::getCart();

        $all = $cart->all();

        foreach($all as $item){
            $total +=$item['amount'];
            $img = self::getApiUrl().$item['img'];
            $name = $item['name'];
            $num = $item['num'];
            $amount = $item['amount'];
            $spec = array();
            foreach($item['spec'] as $specs){
                $spec[] = $specs['value'][2];
            }
            $goods_info .= '<li>
                        <div class="card ks-facebook-card">
                            <div class="card-header no-border">
                                <div class="ks-facebook-avatar"><img src="'.$img.'" width="34" height="34"/></div>
                                <div style="float:right;">
                                    <div class="item-title-row">
                                        <div class="item-title">￥'.$amount.'</div>
                                    </div>
                                    <div class="item-text"> X '.$num.'</div></div>
                                <div class="ks-facebook-name" style="margin-right:44px;">'.$name.'</div>
                                <div class="ks-facebook-date" style="margin-right:44px;">'.implode(',',$spec).'</div>

                            </div>
                        </div>
                    </li>';
        }

        echo str_replace(array('{mobile}','{accept_name}','{addr}','{goods_info}','{total}'),array($mobile,$accept_name,$addr,$goods_info,$total),$this->checkoutIndexTemplate);
    }

    private function consignee($addrData)
    {
        $areaModel = new Model('area','zd','salve');

        $area_ids = $addrData['province'].','.$addrData['city'].','.$addrData['county'];

        if($area_ids!='')$areas = $areaModel->where("id in ($area_ids)")->findAll();
        $parse_area = array();
        foreach ($areas as $area) {
            $parse_area[$area['id']] = $area['name'];
        }

        return $parse_area[$addrData['province']].$parse_area[$addrData['city']].$parse_area[$addrData['county']].$addrData['addr'];
    }

    //创建订单
    protected function docheckout()
    {
        try{
            $address_id = Filter::int($this->params['address_id']);
            $payment_id = Filter::int($this->params['payment_id']);
            $user_remark = Filter::txt($this->params['user_remark']);

            if(!$address_id || !$payment_id){

                if(!$address_id) $info = "必需选择收货地址，才能确认订单。";
                else if(!$payment_id)$info = "必需选择支付方式，才能确认订单。";

                $this->output['msg'] = $info;
                $this->output(array());
                exit;
            }

            //地址信息
            $address_model = new Model('address');
            $address = $address_model->where("id=$address_id and user_id=".$this->params['userid'])->find();
            if(!$address){
                $info = "选择的地址信息不正确！";
                $this->output['msg'] = $info;
                $this->output(array());
                exit;
            }

            $order_products = $this->cart;

            //检测products 是否还有数据
            if(empty($order_products)){
                $this->output['msg'] = '非法提交订单！';
                $this->output(array());
                exit;
            }

            //商品总金额,重量,积分计算
            $payable_amount = 0.00;
            $real_amount = 0.00;
            $weight=0;
            $point = 0;
            foreach ($order_products as $item) {
                $payable_amount+=$item['sell_total'];
                $real_amount+=$item['amount'];
                $weight += $item['weight']*$item['num'];
                $point += $item['point']*$item['num'];
            }

            //计算运费
            $fare = new Fare($weight);
            $payable_freight = $fare->calculate($address_id);
            $real_freight = $payable_freight;

            //计算订单优惠
            $discount_amount = 0;

            //税计算
            $tax_fee = 0;

            //计算订单总金额
            $order_amount = $real_amount + $payable_freight + $tax_fee - $discount_amount;

            //填写订单
            $data['order_no'] = Common::createOrderNo();
            $data['user_id'] = $this->params['userid'];
            $data['payment'] = $payment_id;
            $data['status'] = 2;
            $data['pay_status'] = 0;
            $data['accept_name'] = Filter::text($address['accept_name']);
            $data['phone'] = $address['phone'];
            $data['mobile'] = $address['mobile'];
            $data['province'] = $address['province'];
            $data['city'] = $address['city'];
            $data['county'] = $address['county'];
            $data['addr'] = Filter::text($address['addr']);
            $data['zip'] = $address['zip'];
            $data['payable_amount'] = $payable_amount;

            $expressModel = new Model('express_company','zd');
            $ex = $expressModel->fields('name as exname')->where('express_company_id="汇通速递"')->find();

            if(!$ex){
                $ex = $expressModel->fields('name as exname')->where('id=1')->find();
            }
            $data['express'] = $ex['id'];
            $data['payable_freight'] = $payable_freight;
            $data['real_freight'] = $real_freight;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['user_remark'] = $user_remark;
            $data['is_invoice'] = false;
            $data['invoice_title'] = '';

            $data['taxes'] = $tax_fee;


            $data['discount_amount'] = $discount_amount;

            $data['order_amount'] = $order_amount;
            $data['real_amount'] = $real_amount;

            $data['point'] = $point;
            $data['type'] = 0;
            $data['voucher_id'] = 0;
            $data['voucher'] = serialize(array());


            //var_dump($order_products);exit();

            $customerModel = new Model('customer');

            $customer = $customerModel->where('user_id='.$this->params['userid'])->find();

            $uname = $customer['real_name'];

            //写入订单数据
            $orderModel = new Model('order');
            $order_id = $orderModel->data($data)->insert();

            $orderInfo = $data;
            $orderInfo['outer_id'] = $order_id;

            $serverName = Tiny::getServerName();

            $orderInfo['uname'] = $uname;
            $orderInfo['site_url'] = $serverName['top'];

            //写入订单商品
            $tem_data = array();
            $orderItems = array();
            $orderGoodsModel = new Model('order_goods');
            foreach ($order_products as $item) {
                $tem_data['order_id'] = $order_id;
                $tem_data['goods_id'] = $item['goods_id'];
                $tem_data['product_id'] = $item['id'];
                $tem_data['goods_price'] = $item['sell_price'];
                $tem_data['real_price'] = $item['real_price'];
                $tem_data['trade_price'] = $item['trade_price'] ? $item['trade_price'] : 0;//商品批发价
                $tem_data['cost_price'] = $item['cost_price'] ? $item['cost_price'] : 0;//商品成本价
                $tem_data['goods_nums'] = $item['num'];
                $tem_data['goods_weight'] = $item['weight'];
                $tem_data['prom_goods'] = serialize($item['prom_goods']);
                $tem_data['spec'] = serialize($item['spec']);
                $orderGoodsModel->data($tem_data)->insert();
                $orderItems[] = $tem_data;
            }

            Log::orderlog($order_id,'会员:'.$uname,'创建订单','创建订单','success',$serverName['top']);

            //推送新建订单到总店后台
            $OrderNoticeService = new OrderNoticeService();
            $OrderNoticeService->sendCreateOrder($orderInfo,$orderItems);


            //清空购物车与表单缓存
            $cart = Cart::getCart();
            $cart->clear();
            Session::clear("order_status");

            $this->output['status'] = 'succ';
            $this->output['msg'] = '订单创建成功';
            $this->output(array('orderid'=>$order_id));

        }catch (Exception $e){
            echo $e->getMessage();exit;
        }
    }

}