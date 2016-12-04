<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/12/16
 * Time: 下午12:43
 * 订单分布统计图 Bill层
 */
class OrderareastatBill
{

    public static function getList($params = array())
    {
        $cal = self::calendar($params);
        $stime = $cal['start'];
        $etime = $cal['end'];
        $s_time = $cal['str'];

        $where = "create_time between '$stime' and '$etime'";

        if(isset($params['storename']) && ($params['storename'] != 'all')){
            $where .= " and site_url = '".$params['storename']."'";
        }

        $model = new Model("order as od");
        $rows = $model->fields("count(*) as num, province,ae.name as pro_name")->join("left join area as ae on od.province = ae.id ")->where($where)->group("od.province")->query();
        //$rows = $model->fields("count(*) as num, province,ae.name as pro_name")->join("left join area as ae on od.province = ae.id ")->group("od.province")->query();

        $mapdata = array();
        foreach ($rows as $row) {
            $mapdata[] = "'".preg_replace("/(\s|省|市)/", '', $row['pro_name'])."'".':'.$row['num'];
        }

        return array(
            'mapdata'=>implode(',',$mapdata),
            's_time'=>$s_time,
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

//    public static function
}

