<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/5/16
 * Time: 下午5:57
 */
class paylink extends baseapi
{

    protected $payFormTemplate = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head></head>
<body>
    <p>Please Wait...</p>
    <form id="paysubmit" name="paysubmit" action="{action}" method="{method}">
        {sendData}
    </form>
    <script type="text/javascript">
    document.forms["paysubmit"].submit();
    </script>
</body>
</html>
';

    public static $title = array(
        'paylink'=>'支付宝手机支付页面'
    );

    public static $lastmodify = array(
        'paylink'=>'2016-6-28',
    );

    public static $notice = array(
        'paylink'=>'',
    );

    public static $requestParams = array(
        'paylink'=>array(
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
    );

    public static $responsetParams = array(
        'paylink'=>array(
            array(
                'colum'=>'alipay_url',
                'content'=>'返回支付宝支付url',
            ),
        ),
    );

    public static $requestUrl = array(
        'paylink'=>'     /index.php?con=api&act=index&method=paylink'
    );

    public function index()
    {
        $userid = $this->params['uid'];

        $orderid = $this->params['oid']; // order id

        $paymentid = $this->params['paymentid'];

        $extendDatas = $this->params;

        $data = array();

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
        $paymentPlugin = $payment->getPaymentPlugin();
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
                $action = $paymentPlugin->submitUrl();
                $method = $paymentPlugin->method;

                $url = '';

                $msg = '';


                foreach($sendData as $key=>$item){
                    $url .= "&".$key."=".$item;
                }
                $payData['alipay_url'] = baseapi::getApiUrl().'index.php?con=payment&act=paymobile&payment_id='.$paymentid.'&order_id='.$orderid;
//                $msg = str_replace(array('{action}','{method}','{sendData}'),array($action,$method,$_sendData),$this->payFormTemplate);
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

    public function paylink_demo()
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
                    'alipay_url'=>'',
                ),
            )
        );
    }

}