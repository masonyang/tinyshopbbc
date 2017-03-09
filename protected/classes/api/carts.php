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

    public static $title = array(
        'addcart'=>'单商品加入购物车 / 【购物车】中 增加商品数量',
        'docheckout'=>'创建新订单',
        'checkout'=>'结算下单页面内容',
        'scount'=>'获取会员购物车商品总数',
        'cindex'=>'购物车列表',
        'removecart'=>'从购物车中删除商品 /【购物车】中 删减商品数量',
        'changecheckout'=>'根据 变更结算页面信息时候 触发价格等变更',
    );

    public static $lastmodify = array(
        'addcart'=>'2016-6-28',
        'docheckout'=>'2016-6-28',
        'checkout'=>'2016-6-28',
        'scount'=>'2016-6-28',
        'cindex'=>'2016-6-28',
        'removecart'=>'2016-6-28',
        'changecheckout'=>'2016-9-29',
    );

    public static $notice = array(
        'addcart'=>'<span style="color: red;">已更新 7/5</span>',
        'docheckout'=>'',
        'checkout'=>'',
        'scount'=>'<span style="color: red;">已更新 7/5</span>',
        'cindex'=>'<span style="color: red;">已更新 7/5</span>',
        'removecart'=>'<span style="color: red;">已更新 7/5</span>',
        'changecheckout'=>'<span style="color: red;">已更新 9/29</span>',
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
                'colum'=>'refer',
                'required'=>'是',
                'type'=>'string',
                'content'=>'订单来源(app-android、app-ios)',
            ),
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
            array(
                'colum'=>'addr_id',
                'required'=>'可选',
                'type'=>'string',
                'content'=>'收货地址id,当会员在结算页面中更改了收货地址。需要重新请求结算页面api并带上选定的addr_id',
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
        'changecheckout'=>array(
            array(
                'colum'=>'uid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'addr_id',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'收货地址id',
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
                'colum'=>'current_goods_amount',
                'content'=>'当前商品的小计',
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
            array(
                'colum'=>'paymentid',
                'content'=>'创建订单成功返回 支付id号',
            ),
            array(
                'colum'=>'order_products',
                'content'=>'商品总数量',
            ),
            array(
                'colum'=>'create_time',
                'content'=>'创建时间',
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
                'colum'=>'current_goods_amount',
                'content'=>'当前商品的小计',
            ),
            array(
                'colum'=>'goods_amount',
                'content'=>'商品总金额',
            )
        ),
        'changecheckout'=>array(
            array(
                'colum'=>'payable_freight',
                'content'=>'配送费用',
            ),
        ),
    );

    public static $requestUrl = array(
        'addcart'=>'     /index.php?con=api&act=index&method=carts&source=addcart',
        'docheckout'=>'     /index.php?con=api&act=index&method=carts&source=docheckout',
        'checkout'=>'     /index.php?con=api&act=index&method=carts&source=checkout',
        'scount'=>'     /index.php?con=api&act=index&method=carts&source=scount',
        'cindex'=>'     /index.php?con=api&act=index&method=carts&source=cindex',
        'removecart'=>'     /index.php?con=api&act=index&method=carts&source=removecart',
        'changecheckout' =>'     /index.php?con=api&act=index&method=carts&source=changecheckout',
    );

    public function index()
    {


        switch($this->params['source']){
            case 'cindex':
                $this->cartIndex();
                break;
            case 'addcart'://添加商品到购物车

                if($this->params['uid'] == 0){
                    $data['count'] = 0;
                    $data['goods_amount'] = 0;
                    $this->output['msg'] = '请先登录';
                    $this->output($data);
                    exit;
                }

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

                    $pro = $this->getStoreByProId($product['id'],$product['goods_id'],$productsModel);

                    $less_num = $pro['store_nums'] - $pro['freeze_nums'] - $cart_nums;

//                    $less_num = $less_num - $num;
                    if($less_num <= 0){
                        $info = '该商品暂缺货';
                        $data['less_nums'] = ($less_num < 0) ? 0 : $less_num;
                    }elseif(($pro['store_nums'] > 0) && ($less_num >= $num)){
                        $cart->addItem($product['id'],$num);
                        $this->output['status'] = 'succ';

                        $buyNum = $cartSessionModel->fields('num')->where('product_id="'.$product['id'].'" and uid = '.$this->params['uid'])->find();

                        $data['buy_num'] = intval($buyNum['num']);

                    }else{
                        $info = '该商品库存不足';
                        $data['less_nums'] = ($less_num < 0) ? 0 : $less_num;
                    }

                }else{
                    $info = '数量不能小于0';
                }

                $goods_amount = 0;
                $current_goods_amount = 0;
                $all = $cart->all();

                foreach($all as $val){

                    if($product['id'] == $val['id']){
                        $current_goods_amount = $val['sell_price'] * $val['num'];
                    }

                    $goods_amount += $val['sell_total'];
                }

                $data['count'] = count($all);
                $data['current_goods_amount'] = $current_goods_amount;
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

                            $data['buy_num'] = $buyNum['num'] ? intval($buyNum['num']) : 0;

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

                $current_goods_amount = 0;

                $all = $cart->all();

                foreach($all as $val){

                    if($product['id'] == $val['id']){
                        $current_goods_amount = $val['sell_price'] * $val['num'];
                    }

                    $goods_amount += $val['sell_total'];
                }

                $data['count'] = count($all);
                $data['current_goods_amount'] = $current_goods_amount;
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
            case 'changecheckout'://根据 变更结算页面信息时候 触发价格等变更
                $this->changecheckout();
            break;
        }
    }

    protected function getStoreByProId($pid,$gid,$obj)
    {
//        $inventorysModel = new Model('inventorys','zd','salve');
//
//        $indata = $inventorysModel->where('goods_id = '.$gid.' and product_id = '.$pid)->find();

        $mapper = Config::getInstance('mapper')->get('zd');
        $prefixdb = $mapper['db']['master']['name'];

        $sql = 'select * from `'.$prefixdb.'`.tiny_inventorys where goods_id = '.$gid.' and product_id = '.$pid;

        $indata = $obj->query($sql);

        $return = array();

        $return['store_nums'] = $indata[0]['p_store_nums'];
        $return['freeze_nums'] = $indata[0]['p_freeze_nums'];

        return $return;
    }

    //购物车页面
    protected function cartIndex()
    {

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


        }

    }

    protected function checkout()
    {

        $return = array();

        $userid = $this->params['uid'];

        $addrid = $this->params['addr_id'];

        if(empty($userid)){
            $this->output['msg'] = '会员不能为空';
            $this->output();
            exit;
        }

        $addrData = false;

        $addressModel = new Model('address');

        if($addrid){
            $addrData = $addressModel->where('id='.$addrid)->find();
            $default_addr_id = $addrData['id'];
        }

        if(!$addrData){
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

            }
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

            $i++;
        }

        $fare = new Fare($weight);
        $payable_freight = $fare->calculate($default_addr_id);

        //处理订单促销逻辑
        $PromBill = new Prom($real_amount);

        $prom = $PromBill->dealPromOrderForApi($this->params['uid'],$payable_freight);

        $promlist = array();

        if($prom){
            $promlist = $prom['promlist'];
            $payable_freight = $prom['payable_freight'];
        }

        $order_amount = $payable_freight + $real_amount;

        $return['order']['order_amount'] = $order_amount;
        $return['order']['payable_freight'] = $payable_freight;

        $return['order']['promlist'] = $promlist;

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
            $this->output['msg'] = '请先去添加收货地址';
            $this->output();
        }


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

            $orderCache = new Model('cache');

            $unqiKey = 'checkout_'.$this->params['uid'];

            $cacheData = $orderCache->fields('content')->where('`key`="'.$unqiKey.'"')->find();

            $time = time();

            if($cacheData){
                if(($time - $cacheData['content']) > 30){
                    $orderCache->data(array('content'=>time()))->where('`key`="'.$unqiKey.'"')->update();
                }else{
                    $this->output['msg'] = '30秒内不能重复提交';
                    $this->output();
                    exit;
                }
            }else{
                $orderCache->data(array('key'=>$unqiKey,'content'=>time()))->insert();
            }

            //商品总金额,重量,积分计算
            $payable_amount = 0.00;
            $real_amount = 0.00;
            $weight=0;
            $point = 0;
            $errormsg = '';

            $productModel = new Model('products');
            foreach ($order_products as $item) {
                $products = $productModel->fields('pro_no,store_nums,freeze_nums')->where('id = '.$item['id'])->find();

                $pro = $this->getStoreByProId($item['id'],$item['goods_id'],$productModel);

                if($item['num'] > ($pro['store_nums'] - $pro['freeze_nums'])){
                    $errormsg .= $products['pro_no'].',';
                }

                $payable_amount+=$item['sell_total'];
                $real_amount+=$item['amount'];
                $weight += $item['weight']*$item['num'];
                $point += $item['point']*$item['num'];
            }

            if(!empty($errormsg)){
                $this->output['msg'] = rtrim($errormsg,',').'库存不足,下单失败!';
                $this->output(array());
                exit;
            }

            //计算运费
            $fare = new Fare($weight);
            $payable_freight = $fare->calculate($address_id);


            //处理订单促销逻辑
            $PromBill = new Prom($real_amount);

            $prom = $PromBill->dealPromOrderForApi($this->params['uid'],$payable_freight);

            if($prom){
                $payable_freight = $prom['payable_freight'];
            }


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
            $data['status'] = 3;  //待支付
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

            if(isset($this->params['refer']) && Order::getOrderRefer($this->params['refer'])){
                $data['order_refer'] = $this->params['refer'];
            }else{
                $data['order_refer'] = 'app-ios';
            }


            $expressModel = new Model('express_company','zd');
            $ex = $expressModel->fields('name as exname')->where('express_company_id="汇通速递"')->find();

            if(!$ex){
                $ex = $expressModel->fields('name as exname')->where('id=1')->find();
            }
            $data['express'] = $ex['id'];
            $data['payable_freight'] = $payable_freight;
            $data['real_freight'] = $real_freight;
            $data['create_time'] = $create_time = date('Y-m-d H:i:s');
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

            $uname = $customer['real_name'] ? $customer['real_name'] : $customer['mobile'];

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

            $productsInfo = array();

            $i = 0;
            $orderGoodsModel = new Model('order_goods');
            foreach ($order_products as $item) {

                $productsInfo[$i]['product_id'] = $item['id'];
                $productsInfo[$i]['goods_id'] = $item['goods_id'];
                $productsInfo[$i]['num'] = $item['num'];


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

                $i++;
            }

            //$product['freeze_nums']
            $cart = Cart::getCart($this->params['uid']);
            $order_products = $cart->all();

            //$freeze_product = array();

//            $pModel = new Model('products');

//            $i = 0;
//
//            foreach($order_products as $productid =>$item){
//                $freeze_product[$i]['product_id'] = $productid;
//                $freeze_product[$i]['goods_id'] = $item['goods_id'];
//                $freeze_product[$i]['product_id'] = $item['num'];
////                $pModel->where("id=".$productid)->data(array('freeze_nums'=>"`freeze_nums`+".$item['num']))->update();
//            }

            //清空购物车与表单缓存
//            $cart = Cart::getCart($this->params['uid'],$serverName['top']);
//            $cart->clear();
            $cartModel = new Model('cart_session');

            $cartModel->where('uid='.$this->params['uid'])->delete();

            Session::clear("order_status");

            Log::orderlog($order_id,'会员:'.$uname,'创建订单','创建订单','success',$serverName['top']);

            //推送新建订单到总店后台
            $OrderNoticeService = new OrderNoticeService();
            $OrderNoticeService->sendCreateOrder($orderInfo,$orderItems);

            $OrderNoticeService->updateFreezeNums($productsInfo,'+');

//            $OrderNoticeService->updateFreezeNumsForZdGoods($productsInfo);
//
//            $OrderNoticeService->updateFreezeNumsForBanchGoods($productsInfo,$serverName['top']);

            $this->output['status'] = 'succ';
            $this->output['msg'] = '订单创建成功';
            $this->output(array('orderid'=>$order_id,'paymentid'=>$payment_id,'order_products'=>count($order_products),'create_time'=>$create_time));
            //总金额、创建时间、商品总数量、
        }catch (Exception $e){
            $this->output['status'] = 'fail';
            $this->output['msg'] = $e->getMessage();
            $this->output(array());
        }
    }

    protected function changecheckout()
    {

        $userid = $this->params['uid'];

        $addrid = $this->params['addr_id'];

        if(empty($userid)){
            $this->output['msg'] = '会员不能为空';
            $this->output();
            exit;
        }elseif(empty($addrid)){
            $this->output['msg'] = '收货地址不能为空';
            $this->output();
            exit;
        }

        $addressModel = new Model('address');

        if($addrid){
            $addrData = $addressModel->where('id='.$addrid)->find();
            $default_addr_id = $addrData['id'];
        }else{
            $this->output['msg'] = '收货地址不存在';
            $this->output();
            exit;
        }

        $weight = 0;

        $cart = Cart::getCart($this->params['uid']);

        $all = $cart->all();

        foreach($all as $k=>$item){

            $weight += $item['weight']*$item['num'];
        }
        $fare = new Fare($weight);
        $payable_freight = $fare->calculate($default_addr_id);

        $this->output['status'] = 'succ';
        $this->output['msg'] = '计算成功';
        $this->output(array('payable_freight'=>$payable_freight));
    }

    public function changecheckout_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'会员不能为空 / 收货地址不能为空 / 收货地址不存在',
                'data'=>array(

                ),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'计算成功',
                'data'=>array(
                    'payable_freight'=>12
                ),
            )
        );
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
                    'current_goods_amount'=>'当前商品的小计',
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
                        'promlist'=>array(
                            '免运费',
                            '满减'
                        ),
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
                    'current_goods_amount'=>'当前商品的小计',
                    'goods_amount'=>'商品总金额'
                ),
            )
        );
    }

}