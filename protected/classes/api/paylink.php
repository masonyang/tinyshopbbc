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

    public function index()
    {
        $userid = $this->params['userid'];

        $orderid = $this->params['orderid']; // order id

        $paymentid = $this->params['paymentid'];

        $extendDatas = $this->params;

        $data = array();

        if($userid && $paymentid && $orderid){
            $this->genatePayLink($paymentid,$orderid,$extendDatas);
        }else{
            echo '未登录';
            exit;
        }
    }

    protected function genatePayLink($paymentid,$orderid,$extendDatas)
    {
        $payment = new Payment($paymentid);
        $paymentPlugin = $payment->getPaymentPlugin();
        $payment_info = $payment->getPayment();
        
        $model = new Model('order');
        $order = $model->where('id='.$orderid)->find();
        if($order){
            if($order['order_amount']==0 && $payment_info['class_name']!='balance'){
                echo '0元订单，仅限预付款支付，请选择预付款支付方式。';
                exit;
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
            if(true){   //todo
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
                                echo '支付晚了，'.$prom_name."活动已结束。";
                                exit;
                            }
                        }
                    }

                    $packData = $payment->getPaymentInfo('order',$orderid);
                    $packData = array_merge($extendDatas,$packData);
                    $sendData = $paymentPlugin->packData($packData);
                    if(!$paymentPlugin->isNeedSubmit()){
                        echo($sendData);
                        exit();
                    }
                }else{
                    $model->table("order")->data(array('status'=>6))->where('id='.$orderid)->update();

                    $zdOrderModel = new Model('order','zd','master');

                    $serverName = Tiny::getServerName();

                    $zdOrderModel->data(array('status'=>6))->where('outer_id='.$orderid.' and site_url="'.$serverName['top'].'"')->update();

                    echo '支付晚了，库存已不足。';
                    exit;
                }

            }else{
                $model->data(array('status'=>6))->where('id='.$orderid)->update();

                $zdOrderModel = new Model('order','zd','master');

                $serverName = Tiny::getServerName();

                $zdOrderModel->data(array('status'=>6))->where('outer_id='.$orderid.' and site_url="'.$serverName['top'].'"')->update();

                echo '订单超出了规定时间内付款，已作废.';
                exit;
            }

            if(!empty($sendData)){
                $action = $paymentPlugin->submitUrl();
                $method = $paymentPlugin->method;

                $_sendData = '';

                foreach($sendData as $key=>$item){
                    $_sendData .= "<input type='hidden' name='".$key."' value='".$item."' />";
                }
                echo str_replace(array('{action}','{method}','{sendData}'),array($action,$method,$_sendData),$this->payFormTemplate);
            }else{
                echo '需要支付的订单已经不存在。';exit;
            }

        }else{
            echo '订单不存在';
            exit;
        }


    }

}