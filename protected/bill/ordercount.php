<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/12/16
 * Time: 下午3:27
 */
class OrdercountBill
{

    public static function getList($params = array())
    {
        $cal = self::calendar($params);
        $stime = $cal['start'];
        $etime = $cal['end'];
        $s_time = $cal['str'];

        $where = "create_time >= ".strtotime($stime)." and create_time <=".strtotime($etime);

        if(isset($params['storename']) && ($params['storename'] != 'all')){
            $where .= " and site_url = '".$params['storename']."'";
        }

        $model = new Model("order_count_stat");
        $rows = $model->fields("new_orders_num,order_amounts")->where($where)->findAll();

        $new_orders_num = 0;

        $mapdata = array();
        foreach ($rows as $row) {
            $new_orders_num += $row['new_orders_num'];
            $mapdata[] = $row['order_amounts'];
        }



        return array(
            'mapdata'=>implode(',',$mapdata),
            's_time'=>$s_time,
            'new_orders_num'=>$new_orders_num,
        );

    }

    private static function calendar($params){
        $cal = array();
        $s_time = $params["s_time"];
        if(!$s_time){
            $s_time = date("Y-m-d -- Y-m-d");
        }
        $date = explode(' -- ', $s_time);
        $stime = date('Y-m-d 00:00:00',strtotime($date[0]));
        $etime = date('Y-m-d 00:00:00',strtotime($date[1].'+1day'));
        $cle = strtotime($etime) - strtotime($stime);
        $num = ceil($cle/86400);
        $cal['start'] = $stime;
        $cal['end'] = $etime;
        $cal['days'] = $num;
        $cal['str'] = $s_time;
        return $cal;
    }

    public static function getOrderAmountBySiteUrl($siteurl = 'all',$time){

        $orderModel = new Model('order',$siteurl);

        $data = $orderModel->fields('sum(order_amount) as order_amount')->where("pay_time between '$time' and '$time' and pay_status=1")->find();

        return $data['order_amount'] ? $data['order_amount'] : 0;
    }

    public static function getNewOrderNumsBySiteUrl($siteurl = 'all',$time)
    {
        $orderModel = new Model('order',$siteurl);

        $data = $orderModel->fields('count(id) as id')->where("create_time between '$time' and '$time'")->find();

        return $data['id'] ? $data['id'] : 0;
    }

}

//DROP TABLE IF EXISTS `tiny_order_count_stat`;
//CREATE TABLE `tiny_order_count_stat` (
// `stat_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '统计id',
//  `site_url` varchar(255) NOT NULL COMMENT '站点网址',
//  `new_orders_num` int(11) DEFAULT NULL COMMENT '新增订单数量',
//  `order_amounts` int(11) DEFAULT NULL COMMENT '营业额',
//  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
//  PRIMARY KEY (`stat_id`)
//) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单统计';
//ALTER TABLE `tiny_order_count_stat` add INDEX idx_s_c (site_url,create_time);

