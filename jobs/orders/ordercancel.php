<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/12/16
 * Time: 下午9:01
 * 定时取消订单
 */
class orders_ordercancelJob
{

    public static function run($distributor)
    {
        if(empty($distributor)){
            return false;
        }


        $create_time = time();

        foreach($distributor as $v){

            $model = new Model('order',$v['site_url']);

            $orderGoodsModel = new Model('order_goods',$v['site_url']);
            $productsModel = new Model('products',$v['site_url']);

            $ordersData = $model->fields('id,create_time')->where('pay_status = 0')->findAll();

            foreach($ordersData as $order){

                $less = ($create_time - strtotime($order['create_time']));

                if($less >= 172800){

                    $products = $orderGoodsModel->where("order_id=".$order['id'])->findAll();

                    foreach ($products as $pro) {
                        //更新货品中的库存信息
                        $goods_nums = $pro['goods_nums'];
                        $product_id = $pro['product_id'];
                        $productsModel->where("id=".$product_id)->data(array('freeze_nums'=>"`freeze_nums`-".$goods_nums))->update();
                    }

                    $model->where("id=".$order['id'])->data(array('status'=>6))->update();

                    $zdOrderModel = new Model('order','zd','salve');
                    $zdOrderModel->where("outer_id=".$order['id']." and site_url='".$v['site_url']."'")->data(array('status'=>6))->update();

                }

            }
        }


        return true;
    }

}