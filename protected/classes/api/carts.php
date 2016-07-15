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
                                <input type="hidden" name="address_id" id="address_id" value="{address_id}">
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
                                    <input type="hidden" name="payment_id" id="payment_id" value="{payment_id}">
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
                                    <textarea name="user_remark" id="user_remark"></textarea>
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
                                    ￥{payable_freight}
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
                                    ￥{order_amount}
                                </div>
                                <div class="item-after"><input type="submit" class="button loginout button-big button-fill color-red add-chekcout-click" value="提交订单"></div>
                            </div>

                        </div>
                    </li>
                </ul>
            </div>';


    public static $title = array(
        'addcart'=>'单商品加入购物车 / 【购物车】中 增加商品数量',
        'docheckout'=>'创建新订单',
        'checkout'=>'结算下单页面内容',
        'scount'=>'获取会员购物车商品总数',
        'cindex'=>'购物车列表',
        'removecart'=>'从购物车中删除商品 /【购物车】中 删减商品数量',
    );

    public static $lastmodify = array(
        'addcart'=>'2016-6-28',
        'docheckout'=>'2016-6-28',
        'checkout'=>'2016-6-28',
        'scount'=>'2016-6-28',
        'cindex'=>'2016-6-28',
        'removecart'=>'2016-6-28',
    );

    public static $notice = array(
        'addcart'=>'<span style="color: red;">已更新 7/5</span>',
        'docheckout'=>'',
        'checkout'=>'',
        'scount'=>'<span style="color: red;">已更新 7/5</span>',
        'cindex'=>'<span style="color: red;">已更新 7/5</span>',
        'removecart'=>'<span style="color: red;">已更新 7/5</span>',
    );

    public static $requestParams = array(
        'addcart'=>array(
            array(
                'colum'=>'uid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'pro_num',
                'required'=>'是',
                'type'=>'int',
                'content'=>'购买数量',
            ),
            array(
                'colum'=>'pro_no',
                'required'=>'是',
                'type'=>'string',
                'content'=>'货号',
            ),
        ),
        'docheckout'=>array(
            array(
                'colum'=>'addr_id',
                'required'=>'是',
                'type'=>'int',
                'content'=>'收货地址id',
            ),
            array(
                'colum'=>'paymentid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'支付方式id',
            ),
            array(
                'colum'=>'user_remark',
                'required'=>'否',
                'type'=>'string',
                'content'=>'买家备注',
            ),
            array(
                'colum'=>'uid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'会员id',
            ),
        ),
        'checkout'=>array(
            array(
                'colum'=>'uid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'会员id',
            ),
        ),
        'scount'=>array(
            array(
                'colum'=>'uid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'会员id',
            ),
        ),
        'cindex'=>array(
            array(
                'colum'=>'uid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'会员id',
            ),
        ),
        'removecart'=>array(
            array(
                'colum'=>'uid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'pro_num',
                'required'=>'可选',
                'type'=>'int',
                'content'=>'购买数量【如果彻底删除某个货号, pro_num不传。否则认为是删减某个货号数量】',
            ),
            array(
                'colum'=>'pro_no',
                'required'=>'是',
                'type'=>'string',
                'content'=>'货号',
            ),
        ),
    );

    public static $responsetParams = array(
        'addcart'=>array(
            array(
                'colum'=>'count',
                'content'=>'已加入购物车的商品总数',
            ),
            array(
                'colum'=>'less_nums',
                'content'=>'如果 提示：该商品库存不足，less_nums为该商品剩余数量',
            ),
            array(
                'colum'=>'buy_num',
                'content'=>'已选购数量',
            ),
            array(
                'colum'=>'goods_amount',
                'content'=>'商品总金额',
            )
        ),
        'docheckout'=>array(
            array(
                'colum'=>'orderid',
                'content'=>'创建订单成功返回 订单号',
            ),
        ),
        'checkout'=>array(
            array(
                'colum'=>'shouhuo',
                'content'=>'shouhuo  为收货人信息',
            ),
            array(
                'colum'=>'goods_item',
                'content'=>'goods_item  为已选购商品信息',
            ),
            array(
                'colum'=>'order',
                'content'=>'order  为订单主体信息',
            ),
            array(
                'colum'=>'payment',
                'content'=>'payment  为支付方式信息',
            ),
        ),
        'scount'=>array(
            array(
                'colum'=>'count',
                'content'=>'会员购物车商品总数',
            ),
        ),
        'cindex'=>array(
            array(
                'colum'=>'total',
                'content'=>'商品总金额',
            ),
            array(
                'colum'=>'goods_count',
                'content'=>'商品总数',
            ),
            array(
                'colum'=>'gitem/goods_name',
                'content'=>'goods_name  为商品名称',
            ),
            array(
                'colum'=>'gitem/product_id',
                'content'=>'product_id  为货号id',
            ),
            array(
                'colum'=>'gitem/goods_id',
                'content'=>'goods_id  为商品id',
            ),
            array(
                'colum'=>'gitem/product_no',
                'content'=>'product_id  为货号',
            ),
            array(
                'colum'=>'gitem/goods_no',
                'content'=>'goods_no  为商品编号',
            ),
            array(
                'colum'=>'gitem/num',
                'content'=>'num  为已购数量',
            ),
            array(
                'colum'=>'gitem/amount',
                'content'=>'amount  为该商品总额',
            ),
            array(
                'colum'=>'gitem/goods_img',
                'content'=>'goods_img  为该商品图片',
            ),
            array(
                'colum'=>'gitem/goods_spec',
                'content'=>'goods_spec  为商品规格',
            ),
        ),
        'removecart'=>array(
            array(
                'colum'=>'count',
                'content'=>'已加入购物车的商品总数',
            ),
            array(
                'colum'=>'buy_num',
                'content'=>'已选购数量',
            ),
            array(
                'colum'=>'goods_amount',
                'content'=>'商品总金额',
            )
        ),
    );

    public static $requestUrl = array(
        'addcart'=>'     /index.php?con=api&act=index&method=carts&source=addcart',
        'docheckout'=>'     /index.php?con=api&act=index&method=carts&source=docheckout',
        'checkout'=>'     /index.php?con=api&act=index&method=carts&source=checkout',
        'scount'=>'     /index.php?con=api&act=index&method=carts&source=scount',
        'cindex'=>'     /index.php?con=api&act=index&method=carts&source=cindex',
        'removecart'=>'     /index.php?con=api&act=index&method=carts&source=removecart',
    );

    public function index()
    {


        switch($this->params['source']){
            case 'cindex':
                $this->cartIndex();
                break;
            case 'addcart'://添加商品到购物车
                $data = array();
                $cart = Cart::getCart($this->params['uid']);
                $num = $this->params['pro_num'];
                $pro_no = $this->params['pro_no'];

                $info = '成功加入购物车';

                $productsModel = new Model('products');
                $product = $productsModel->where('pro_no="'.$pro_no.'"')->find();

                if($product['id'] && ($num>=1)){

                    $cartSessionModel = new Model('cart_session');
                    $carts = $cartSessionModel->fields('num')->where('product_id="'.$product['id'].'"')->findAll();

                    $cart_nums = 0;

                    if($carts){
                        foreach($carts as $c){
                            $cart_nums += $c['num'];
                        }
                    }

                    $less_num = $product['store_nums'] - $cart_nums;

                    if(($product['store_nums'] > 0) && ($less_num > 0)){
                        $cart->addItem($product['id'],$num);
                        $this->output['status'] = 'succ';

                        $buyNum = $cartSessionModel->fields('num')->where('product_id="'.$product['id'].'" and uid = '.$this->params['uid'])->find();

                        $data['buy_num'] = $buyNum['num'];

                    }else{
                        $info = '该商品库存不足';
                        $data['less_nums'] = $less_num;
                    }

                }else{
                    $info = '数量不能小于0';
                }

                $goods_amount = 0;
                $all = $cart->all();

                foreach($all as $val){
                    $goods_amount += $val['sell_total'];
                }

                $data['count'] = count($all);
                $data['goods_amount'] = $goods_amount;
                $this->output['msg'] = $info;
                $this->output($data);
            break;
            case 'removecart'://从购物车删除商品
                $data = array();
                $cart = Cart::getCart($this->params['uid']);
                $num = $this->params['pro_num'];
                $pro_no = $this->params['pro_no'];


                $productsModel = new Model('products');
                $product = $productsModel->where('pro_no="'.$pro_no.'"')->find();

                if(isset($num)){
                    if($product['id'] && ($num>=1)){

                        if($product['store_nums'] > 0){
                            $cart->decNum($product['id'],$num);
                            $this->output['status'] = 'succ';
                            $info = '操作成功';

                            $cartSessionModel = new Model('cart_session');

                            $buyNum = $cartSessionModel->fields('num')->where('product_id="'.$product['id'].'" and uid = '.$this->params['uid'])->find();

                            $data['buy_num'] = $buyNum['num'] ? $buyNum['num'] : 0;

                        }else{
                            $info = '该商品库存不足';
                        }

                    }else{
                        $info = '数量不能小于0';
                    }
                }else{
                    $cart->delItem($product['id']);

                    $this->output['status'] = 'succ';
                    $info = '操作成功';

                }

                $goods_amount = 0;

                $all = $cart->all();

                foreach($all as $val){
                    $goods_amount += $val['sell_total'];
                }

                $data['count'] = count($all);
                $data['goods_amount'] = $goods_amount;
                $this->output['msg'] = $info;
                $this->output($data);
            break;
            case 'scount'://统计购物车商品数量
                $cart = Cart::getCart($this->params['uid']);
                if($cart){
                    $data = array('count'=>count($cart->all()));
                    $this->output['status'] = 'succ';
                    $this->output['msg'] = '获取成功';
                    $this->output($data);
                }else{
                    $this->output['msg'] = '获取失败';
                    $this->output();
                }
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

        $cart = Cart::getCart($this->params['uid']);

        $all = $cart->all();

        $cart_count = count($all);

        if($cart_count == 0){
            $this->output['msg'] = '购物车中没有商品';
            $this->output();
        }else{
            $result = array();
            $goods_count = array();
            $total = 0;
            $i = 0;
            foreach($all as $k=>$item){
                $total +=$item['amount'];
                $img = self::getApiUrl().$item['img'];
                $name = $item['name'];
                $num = $item['num'];
                $amount = $item['amount'];
                $spec = array();
                foreach($item['spec'] as $specs){
                    $spec[] = $specs['value'][2];
                }
//                $html .= str_replace(array('{img}','{name}','{num}','{spec}','{amount}'),array($img,$name,$num,implode(',',$spec),$amount),$this->cartIndexTemplate);

                $result['gitem'][$i]['product_no'] = $item['product_no'];
                $result['gitem'][$i]['goods_no'] = $item['goods_no'];
                $result['gitem'][$i]['product_id'] = $item['id'];
                $result['gitem'][$i]['goods_id'] = $item['goods_id'];
                $result['gitem'][$i]['goods_name'] = $name;
                $result['gitem'][$i]['num'] = $num;
                $result['gitem'][$i]['amount'] = $amount;
                $result['gitem'][$i]['goods_img'] = $img;
                $result['gitem'][$i]['goods_spec'] = implode(',',$spec);

                $goods_count[$item['product_no']] = 1;
                $i++;
            }

            $result['total'] = $total;
            $result['goods_count'] = count($goods_count);

            $this->output['status'] = 'succ';
            $this->output['msg'] = '购物车列表获取成功';
            $this->output($result);

//            $html .= str_replace(array('{total}'),array($total),$this->checkoutTemplate);


        }

    }

    protected function checkout()
    {

        $return = array();

        $userid = $this->params['uid'];

        if(empty($userid)){
            $this->output['msg'] = '会员不能为空';
            $this->output();
            exit;
        }

        $addressModel = new Model('address');
        $addrData = $addressModel->where('user_id='.$userid)->findAll();

        if($addrData){
            $default_addr_id = $addrData[0]['id'];
            foreach($addrData as $k=>$val){

                if($val['is_default'] == 1){
                    $default_addr_id = $val['id'];
                    $return['shouhuo'][0]['mobile'] = $val['mobile'];
                    $return['shouhuo'][0]['accept_name'] = $val['accept_name'];
                    $return['shouhuo'][0]['addr_id'] = $val['id'];
                    $return['shouhuo'][0]['is_default'] = $val['is_default'];
                    $addr = $this->consignee($val);

                    $return['shouhuo'][0]['address'] = $addr;
                    break;
                }else{
                    $return['shouhuo'][0]['mobile'] = $val['mobile'];
                    $return['shouhuo'][0]['accept_name'] = $val['accept_name'];
                    $return['shouhuo'][0]['addr_id'] = $val['id'];
                    $return['shouhuo'][0]['is_default'] = $val['is_default'];
                    $addr = $this->consignee($val);

                    $return['shouhuo'][0]['address'] = $addr;
                }


            }

        }else{
            $this->output['msg'] = '请先去添加收货地址';
            $this->output();
            exit;
        }

        $total = 0;
        $goods_info = '';
        $weight = 0;
        $real_amount = 0;
        $cart = Cart::getCart($this->params['uid']);

        $all = $cart->all();

        if(empty($all)){
            $this->output['msg'] = '购物车中没有商品';
            $this->output();
            exit;
        }

        $i = 0;
        foreach($all as $k=>$item){
            $total +=$item['amount'];
            $img = self::getApiUrl().$item['img'];
            $name = $item['name'];
            $num = $item['num'];
            $amount = $item['amount'];
            $real_amount += $item['amount'];
            $weight += $item['weight']*$item['num'];
            $spec = array();
            foreach($item['spec'] as $specs){
                $spec[] = $specs['value'][2];
            }

            $return['goods_item'][$i]['img'] = $img;
            $return['goods_item'][$i]['goods_name'] = $name;
            $return['goods_item'][$i]['num'] = $num;
            $return['goods_item'][$i]['amount'] = $amount;
            $return['goods_item'][$i]['spec'] = implode(',',$spec);
//            $goods_info .= '<li>
//                        <div class="card ks-facebook-card">
//                            <div class="card-header no-border">
//                                <div class="ks-facebook-avatar"><img src="'.$img.'" width="34" height="34"/></div>
//                                <div style="float:right;">
//                                    <div class="item-title-row">
//                                        <div class="item-title">￥'.$amount.'</div>
//                                    </div>
//                                    <div class="item-text"> X '.$num.'</div></div>
//                                <div class="ks-facebook-name" style="margin-right:44px;">'.$name.'</div>
//                                <div class="ks-facebook-date" style="margin-right:44px;">'.implode(',',$spec).'</div>
//
//                            </div>
//                        </div>
//                    </li>';
            $i++;
        }


        $fare = new Fare($weight);
        $payable_freight = $fare->calculate($default_addr_id);
        $order_amount = $payable_freight + $real_amount;

        $return['order']['order_amount'] = $order_amount;
        $return['order']['payable_freight'] = $payable_freight;

        $paymentModel = new Model('payment','zd','salve');
        $payment = $paymentModel->where('pay_name = "支付宝[手机支付]"')->find();

        if($payment){

            $return['payment']['paymentid'] = $payment['id'];
            $return['payment']['payment_name'] = '支付宝[手机支付]';
        }


        if($addrData){
            $this->output['status'] = 'succ';
            $this->output['msg'] = '获取成功';
            $this->output($return);
        }else{
            $this->output['msg'] = '获取失败';
            $this->output();
        }

//        echo str_replace(array('{payment_id}','{address_id}','{mobile}','{accept_name}','{addr}','{goods_info}','{total}','{payable_freight}','{order_amount}'),array($payment_id,$addrData['id'],$mobile,$accept_name,$addr,$goods_info,$total,$payable_freight,$order_amount),$this->checkoutIndexTemplate);
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

        if(empty($this->params['uid'])){
            $this->output['msg'] = '会员不能为空';
            $this->output();
            exit;
        }

        $orderCache = new Model('cache');

        $unqiKey = 'checkout_'.$this->params['uid'];

        $cacheData = $orderCache->fields('content')->where('`key`="'.$unqiKey.'"')->find();

        $time = time();

        if($cacheData){
            if(($time - $cacheData['content']) > 30){
                $orderCache->data(array('content'=>time()))->where('`key`="'.$unqiKey.'"')->update();
            }else{
                $this->output['msg'] = '不能重复提交订单';
                $this->output();
                exit;
            }
        }else{
            $orderCache->data(array('key'=>$unqiKey,'content'=>time()))->insert();
        }

        try{
            $address_id = Filter::int($this->params['addr_id']);
            $payment_id = Filter::int($this->params['paymentid']);
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
            $address = $address_model->where("id=$address_id and user_id=".$this->params['uid'])->find();
            if(!$address){
                $info = "选择的地址信息不正确！";
                $this->output['msg'] = $info;
                $this->output(array());
                exit;
            }

            $cart = Cart::getCart($this->params['uid']);
            $order_products = $cart->all();

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
            $data['user_id'] = $this->params['uid'];
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
            $data['user_remark'] = $user_remark ? $user_remark : '';
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

            $customer = $customerModel->where('user_id='.$this->params['uid'])->find();

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
            $cart = Cart::getCart($this->params['uid']);
            $cart->clear();
            Session::clear("order_status");

            $this->output['status'] = 'succ';
            $this->output['msg'] = '订单创建成功';
            $this->output(array('orderid'=>$order_id));

        }catch (Exception $e){
            $this->output['status'] = 'fail';
            $this->output['msg'] = $e->getMessage();
            $this->output(array());
        }
    }

    public function addcart_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'数量不能小于0 / 仅当提示“该商品库存不足“ 返回less_nums字段',
                'data'=>array(
                    'less_nums'=>12,
                ),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'成功加入购物车',
                'data'=>array(
                    'count'=>12,
                    'buy_num'=>'已选购数量',
                    'goods_amount'=>'商品总金额'
                ),
            )
        );
    }

    public function docheckout_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'创建订单失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'订单创建成功',
                'data'=>array(
                    'orderid'=>12,
                ),
            )
        );
    }

    public function checkout_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'如果一个收货地址都没有 msg 返回“请先去添加收货地址”',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'获取成功',
                'data'=>array(
                    'shouhuo'=>array(
                        array(
                            'mobile'=>'收货人手机号',
                            'accept_name'=>'收货人名称',
                            'addr_id'=>'收货地址id',
                            'address'=>'收货地址描述',
                            'is_default'=>'是否是默认收货地址 1:默认 0:否',
                        ),
                    ),
                    'goods_item'=>array(
                        array(
                            'img'=>'商品图片 http://www.baidu.com/asdfsaf',
                            'goods_name'=>'商品名称',
                            'num'=>'已购买数量',
                            'amount'=>'单商品总价',
                            'spec'=>'规格描述',
                        ),
                    ),
                    'order'=>array(
                        'order_amount'=>'订单总金额 120',
                        'payable_freight'=>'配送费',
                    ),
                    'payment'=>array(
                        'paymentid'=>'支付方式id ',
                        'payment_name'=>'支付方式名称 ',
                    ),
                ),
            )
        );
    }

    public function scount_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'获取成功',
                'data'=>array(
                    'count'=>12,
                ),
            )
        );
    }

    public function cindex_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'购物车中没有商品',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'购物车列表获取成功',
                'data'=>array(
                    'gitem'=>array(
                        array(
                            'product_no'=>'货号',
                            'goods_no'=>'商品编号',
                            'product_id'=>'货号id',
                            'goods_name'=>'商品名1',
                            'goods_id'=>'商品id',
                            'num'=>2,
                            'amount'=>24,
                            'goods_img'=>'http://www.baidu.com/HSADF343/AS3DF13A5S2F.jpg',
                            'goods_spec'=>'商品规格',
                        ),
                    ),
                    'total'=>24,
                    'goods_count'=>100
                ),
            )
        );
    }

    public function removecart_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'该商品库存不足/数量不能小于0',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'操作成功',
                'data'=>array(
                    'count'=>12,
                    'buy_num'=>'已选购数量',
                    'goods_amount'=>'商品总金额'
                ),
            )
        );
    }

}