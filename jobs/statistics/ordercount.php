<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/12/16
 * Time: 下午4:22
 *
 * 分店订单量统计
 *
 */
class statistics_ordercountJob
{

    public static function run($distributor)
    {
        if(empty($distributor)){
            return false;
        }

        $model = new Model('order_count_stat','zd');

        $create_time = time();

        $statData = array();

        foreach($distributor as $v){

            $new_orders_num = OrdercountBill::getNewOrderNumsBySiteUrl($v['site_url'],$create_time);
            $order_amounts = OrdercountBill::getOrderAmountBySiteUrl($v['site_url'],$create_time);

            $statData[$v['site_url']]['site_url'] = $v['site_url'];
            $statData[$v['site_url']]['new_orders_num'] = $new_orders_num;
            $statData[$v['site_url']]['order_amounts'] = $order_amounts;
        }

        if(!$statData){
            return false;
        }

        foreach($statData as $stat){

            $_data = $model->fields('stat_id')->where('site_url = "'.$stat['site_url'].'" and create_time = '.$create_time)->find();

            if($_data['stat_id']){
                $stat_id = $_data['stat_id'];
            }

            $data = array();
            $data['site_url'] = $stat['site_url'];
            $data['new_orders_num'] = $stat['new_orders_num'];
            $data['order_amounts'] = $stat['order_amounts'];
            $data['create_time'] = $create_time;

            if(isset($stat_id)){
                $model->data($data)->where('stat_id = '.$stat_id)->update();
            }else{
                $model->data($data)->insert();
            }
        }

        return true;
    }
}