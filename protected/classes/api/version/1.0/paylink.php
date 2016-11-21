<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 3/10/16
 * Time: 上午11:22
 * 支付前验证订单信息，并返回支付相关信息 以便前端调用支付方式
 */
class paylinkv1 extends paylink
{

    public static $title = array(
        'paylinkv'=>'支付宝手机支付native方式,支付前验证订单信息，并返回支付相关信息 以便前端调用支付方式',
        'syncdopay'=>'支付宝手机支付native方式，支付同步方法',
    );

    public static $lastmodify = array(
        'paylinkv'=>'2016-10-3',
        'syncdopay'=>'2016-10-4',
    );

    public static $notice = array(
        'paylinkv'=>'',
        'syncdopay'=>'',
    );

    public static $requestParams = array(
        'paylinkv'=>array(
            array(
                'colum'=>'uid',
                'required'=>'是',
                'type'=>'int',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'oid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'订单号id',
            ),
            array(
                'colum'=>'paymentid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'支付方式id',
            ),
        ),
        'syncdopay'=>array(
            array(
                'colum'=>'paymentid',
                'required'=>'是',
                'type'=>'string',
                'content'=>'支付方式id',
            ),
        ),
    );

    public static $responsetParams = array(
        'paylinkv'=>array(
            array(
                'colum'=>'pay_data',
                'content'=>'支付宝相关参数',
            ),
        ),
        'syncdopay'=>array(
            array(
                'colum'=>'pay_data',
                'content'=>'支付宝相关参数',
            ),
        ),
    );

    public static $requestUrl = array(
        'paylinkv'=>'     /index.php?con=api&act=index&method=paylinkv1&source=paylinkv',
        'syncdopay'=>'     /index.php?con=api&act=index&method=paylinkv1&source=syncdopay',
    );

    public function index()
    {

        switch($this->params['source']){
            case 'paylinkv':
                $this->paylinkv();
            break;
            case 'syncdopay':
                $this->syncdopay();
            break;
        }

    }

    protected function explode_result($result,$resultStatus)
    {

        $__list = array('resultStatus'=>$resultStatus);

        $lists = explode('&',$result);

        foreach($lists as $val){
            list($k,$v) = explode('=',$val);
            $__list[$k] = trim($v,'"');
        }

        return $__list;
    }

    protected function syncdopay()
    {

//        $this->params['paymentid'] = 7;
//        $this->params['pay_data'] =
//            array (
//                'resultStatus' => '9000',
//                'result' => 'partner="2088602138538983"&seller_id="18632155211"&out_trade_no="1477834035083"&subject="订单支付：20161030212713587854"&body="109电子商务系统(订单号:20161030212713587854)"&total_fee="0.01"&notify_url="http://a.qqcapp.com/index.php?con=payment&act=async_native_callback&payment_id=7"&service="mobile.securitypay.pay"&payment_type="1"&_input_charset="utf-8"&it_b_pay="30m"&return_url="m.alipay.com"&success="true"&sign_type="RSA"&sign="WqAUDyaVwIt9F/v2jIKg0csIQmomIqbTp7omEg6hZsTXvICMCe1EEg9tTlUJNHv2lohqRBaIBESWtSOHBqHuy2gjBs/KynQ+VqbvVLXlTem3TM+dcOjWDEJYfxJH1LXAOe5TuUQOZYgXQ37FYK3HfftTpqTk/Z9dT4poy3rgH8g="',
//                'memo' => '订单支付成功',
//            );

//        error_log(var_export($this->params,1),3,TINY_ROOT.'../data/syncdopay.log');

        //从URL中获取支付方式
        $payment_id      = Filter::int($this->params['paymentid']);
        $payment = new Payment($payment_id);
        $paymentPlugin = $payment->getPaymentNativePlugin();

        if(!is_object($paymentPlugin))
        {
            $this->output['msg'] = '支付方式不存在！';
            $this->output(array());
            exit;
        }

        //初始化参数
        $money   = '';
        $message = '支付失败';
        $orderNo = '';

        //执行接口回调函数
        $callbackData = $this->explode_result($this->params['pay_data']['result'],$this->params['pay_data']['resultStatus']);//array_merge($_POST,$_GET);


        //unset($callbackData['con']);
        unset($callbackData['act']);
        unset($callbackData['paymentid']);
        //unset($callbackData['tiny_token_redirect']);
        $return = $paymentPlugin->callback($callbackData,$payment_id,$money,$message,$orderNo);

        //支付成功
        if($return == 1)
        {

            $order_id = Order::updateStatus($orderNo,$payment_id,$callbackData);
            if($order_id)
            {

                $serverName = Tiny::getServerName();

                Log::orderlog($order_id,'会员:'.$this->user['name'],'订单已完成在线支付','订单已支付','success',$serverName['top']);

//                $model = new Model("order");
//                $order = $model->where("order_no='".$orderNo."'")->find();

//                $msg = array('type'=>'success','msg'=>'支付成功！','content'=>'订单号：'.$orderNo.',已支付金额：'.$order['order_amount'],'redirect'=>'');

                $this->output['status'] = 'succ';
                $this->output['msg'] = '支付成功';
                $this->output(array());
                exit;
            }

            $this->output['msg'] = '订单修改失败！';
            $this->output(array());
            exit;
        }
        //支付失败
        else
        {
//            $msg = array('type'=>'fail','msg'=>'支付失败！','content'=>$orderNo.',订单支付失败','redirect'=>'');

            $this->output['msg'] = '支付失败！';
            $this->output(array());
            exit;
        }
    }

    protected function paylinkv()
    {

//        error_log(var_export($this->params,1),3,TINY_ROOT.'../data/paylinkv.log');

        $userid = $this->params['uid'];

        $orderid = $this->params['oid']; // order id

        $paymentid = $this->params['paymentid'];

        $extendDatas = $this->params;

        $data = array();
//        $this->output['msg'] = $extendDatas;
//        $this->output(array());
//        exit;
//        error_log(var_export($extendDatas,1),3,TINY_ROOT.'data/extendDatas.log');
//        exit;

        if($userid && $paymentid && $orderid){
            $return = $this->genatePayLink($paymentid,$orderid,$extendDatas,$msg,$payData);

            if($return){
                $this->output['status'] = 'succ';
                $this->output['msg'] = '支付获取成功';
                $this->output($payData);
            }else{
                $this->output['msg'] = $msg;
                $this->output(array());
            }
        }else{
            $this->output['msg'] = '未登录';
            $this->output(array());
        }
    }

    protected function genatePayLink($paymentid,$orderid,$extendDatas,&$msg = '',&$payData = array())
    {
        $payment = new Payment($paymentid);
        $paymentPlugin = $payment->getPaymentNativePlugin();
        $payment_info = $payment->getPayment();

        $model = new Model('order');
        $order = $model->where('id='.$orderid)->find();
        if($order){
            if($order['order_amount']==0 && $payment_info['class_name']!='balance'){
                $msg = '0元订单，仅限预付款支付，请选择预付款支付方式。';
                return false;
            }
            //获取订单可能延时时长，0不限制
            $config = Config::getInstance();
            $config_other = $config->get('other');
            switch ($order['type']) {
                case '1':
                    $order_delay = isset($config_other['other_order_delay_group'])?intval($config_other['other_order_delay_group']):120;
                    break;
                case '2':
                    $order_delay = isset($config_other['other_order_delay_flash'])?intval($config_other['other_order_delay_flash']):120;
                    break;
                case '3':
                    $order_delay = isset($config_other['other_order_delay_bund'])?intval($config_other['other_order_delay_bund']):0;
                    break;

                default:
                    $order_delay = isset($config_other['other_order_delay'])?intval($config_other['other_order_delay']):0;
                    break;
            }

            $time = strtotime("-".$order_delay." Minute");
            $create_time = strtotime($order['create_time']);
            if($create_time>=$time){
                //取得所有订单商品
                $order_goods = $model->table('order_goods')->fields("product_id,goods_nums")->where('order_id='.$orderid)->findAll();
                $product_ids = array();
                $order_products = array();
                foreach ($order_goods  as $value) {
                    $product_ids[] = $value['product_id'];
                    $order_products[$value['product_id']] = $value['goods_nums'];
                }
                $product_ids = implode(',', $product_ids);

                $products = $model->table('products')->fields("id,store_nums")->where("id in ($product_ids)")->findAll();
                $products_list = array();
                foreach ($products as $value) {
                    $products_list[$value['id']]=$value['store_nums'];
                }
                $flag = true;
                foreach ($order_goods as $value) {
                    if($order_products[$value['product_id']]>$products_list[$value['product_id']]){
                        $flag = false;
                        break;
                    }
                }

                $flag = true;//todo
                //检测库存是否还能满足订单
                if($flag){
                    //团购订单
                    if($order['type']==1 || $order['type']==2){
                        if($order['type']==1){
                            $prom_name='团购';
                            $prom_table = "groupbuy";
                        }else{
                            $prom_name='抢购';
                            $prom_table = "flash_sale";
                        }
                        $prom = $model->table($prom_table)->where("id=".$order['prom_id'])->find();
                        if($prom){
                            if(time() > strtotime($prom['end_time']) || $prom['max_num']<=$prom["goods_num"]){
                                $model->table("order")->data(array('status'=>6))->where('id='.$orderid)->update();
                                $msg = '支付晚了，'.$prom_name."活动已结束。";
                                return false;
                            }
                        }
                    }

                    $packData = $payment->getPaymentInfo('order',$orderid);
                    $packData = array_merge($extendDatas,$packData);
                    $sendData = $paymentPlugin->packData($packData);
                    if(!$paymentPlugin->isNeedSubmit()){
                        $msg = $sendData;
                        return true;
                    }
                }else{
                    $model->table("order")->data(array('status'=>6))->where('id='.$orderid)->update();

                    $zdOrderModel = new Model('order','zd','master');

                    $serverName = Tiny::getServerName();

                    $zdOrderModel->data(array('status'=>6))->where('outer_id='.$orderid.' and site_url="'.$serverName['top'].'"')->update();

                    $msg = '支付晚了，库存已不足。';
                    return false;
                }

            }else{
                $model->data(array('status'=>6))->where('id='.$orderid)->update();

                $orderGoodsModel = new Model('order_goods');
                $productsModel = new Model('products');

                $products = $orderGoodsModel->where("order_id=".$orderid)->findAll();

                foreach ($products as $pro) {
                    //更新货品中的库存信息
                    $goods_nums = $pro['goods_nums'];
                    $product_id = $pro['product_id'];
                    $productsModel->where("id=".$product_id)->data(array('freeze_nums'=>"`freeze_nums`-".$goods_nums))->update();
                }

                $zdOrderModel = new Model('order','zd','master');

                $serverName = Tiny::getServerName();

                $zdOrderModel->data(array('status'=>6))->where('outer_id='.$orderid.' and site_url="'.$serverName['top'].'"')->update();

                $msg = '订单超出了规定时间内付款，已作废.';
                return false;
            }

            if(!empty($sendData)){

                $msg = '';

                $sendData['payment_id'] = $paymentid;

                $payData['pay_data'] = $sendData;

                return true;
            }else{
                $msg = '需要支付的订单已经不存在。';
                return false;
            }

        }else{
            $msg = '订单不存在';
            return false;
        }


    }

    public function syncdopay_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'未登录 / 需要支付的订单已经不存在 / 订单不存在 / 订单超出了规定时间内付款，已作废. ...',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'支付获取成功',
                'data'=>array(
                    'pay_data'=>array(),
                ),
            )
        );
    }

    public function paylinkv_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'未登录 / 需要支付的订单已经不存在 / 订单不存在 / 订单超出了规定时间内付款，已作废. ...',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'支付获取成功',
                'data'=>array(
                    'pay_data'=>array(),
                ),
            )
        );
    }

}