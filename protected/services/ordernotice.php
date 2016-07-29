<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/4/16
 * Time: 下午2:29
 * 处理订单
 *        分店到总店的推送
 *        查看分店 订单信息
 *        更新分店 订单信息
 */
class OrderNoticeService
{
    /*
     * 推送新建的订单到总店
     * */
    public function sendCreateOrder($orderInfo = array(),$orderItem = array())
    {

        $zdOrderModel = new Model('order','zd','master');
        $zdOrderGoodsModel = new Model('order_goods','zd','master');

        $order_id = $zdOrderModel->data($orderInfo)->insert();


        foreach($orderItem as $tem_data){
            $tem_data['order_id'] = $order_id;
            $zdOrderGoodsModel->data($tem_data)->insert();
        }
    }


}