<?php
/**
 * 分店商品销售量统计
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 12/2/17
 * Time: 下午3:49
 */
class statistics_goodsrecommendJob
{

    public static function run($distributor)
    {
        if(empty($distributor)){
            return false;
        }

        $create_time = time();

        foreach($distributor as $v){

            $goods_sales = OrdercountBill::getOrderGoodsSalesNumsBySiteUrl($v['site_url'],$create_time);

            if($goods_sales){

                foreach($goods_sales as $val){
                    $data = array();
                    $data['gid'] = $val['gid'];
                    $data['gcount'] = $val['gcount'];
                    $data['create_time'] = $create_time;

                    $model = Model::getInstance('goods_sales_statistics',$v['site_url']);

                    $_data = $model->fields('stat_id')->where('gid = '.$val['gid'])->find();

                    if($_data['stat_id']){
                        $stat_id = $_data['stat_id'];
                    }

                    if(isset($stat_id)){
                        $model->data($data)->where('stat_id = '.$stat_id)->update();
                    }else{
                        $model->data($data)->insert();
                    }
                }

            }


        }

        return true;
    }

}
