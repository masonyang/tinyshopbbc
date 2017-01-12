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

    //更新总店订单 对应的商品 冻结库存
//    public function updateFreezeNumsForZdGoods($productsInfo = array(),$op = '+')
//    {
//        $goodsModel = new Model('goods','zd','master');
//
//        $productsModel = new Model('products','zd','master');
//
//        foreach($productsInfo as $val){
//            $productsModel->where("id=".$val['product_id'])->data(array('freeze_nums'=>"`freeze_nums`".$op.$val['freeze_num']))->update();
//            $goodsModel->where("id=".$val['goods_id'])->data(array('freeze_nums'=>"`freeze_nums`".$op.$val['freeze_num']))->update();
//        }
//
//    }
//
//    //更新分店订单 对应的商品 冻结库存
//    public function updateFreezeNumsForBanchGoods($productsInfo = array(),$site_url,$op = '+')
//    {
//
//        $distributorModel = new Model('distributor','zd','master');
//
//        $distributorDatas = $distributorModel->fields('site_url')->findAll();
//
//        foreach($distributorDatas as $val){
//
//            if($val['site_url'] == $site_url){
//                continue;
//            }
//
//            $goodsModel = new Model('goods',$val['site_url'],'master');
//
//            $productsModel = new Model('products',$val['site_url'],'master');
//
//            foreach($productsInfo as $vval){
//                $productsModel->where("id=".$val['product_id'])->data(array('freeze_nums'=>"`freeze_nums`".$op.$vval['freeze_num']))->update();
//                $goodsModel->where("id=".$val['goods_id'])->data(array('freeze_nums'=>"`freeze_nums`".$op.$vval['freeze_num']))->update();
//            }
//
//        }
//
//    }

    //更新冻结库存(新)
    public function updateFreezeNums($productsInfo = array(),$op = '+')
    {
        $inventorysModel = new Model('inventorys','zd','master');

        $goods_ids = array();

        foreach($productsInfo as $pro){

            $indata = $inventorysModel->where('goods_id = '.$pro['goods_id'].' and product_id = '.$pro['product_id'])->find();

            if($op == '-'){
                $p_freeze_num = $indata['p_freeze_nums'] - $pro['num'];

                $p_freeze_num = ($p_freeze_num > 0) ? $p_freeze_num : 0;

            }elseif($op == '+'){
                $p_freeze_num = $indata['p_freeze_nums'] + $pro['num'];

            }else{
                break;
            }

            $inventorysModel->data(array('p_freeze_num'=>$p_freeze_num))->where('goods_id = '.$pro['goods_id'].' and product_id = '.$pro['product_id'])->update();

            $goods_ids[] = $pro['goods_id'];

        }
        $gids = array_unique($goods_ids);

        $inDatas = $inventorysModel->where('goods_id in ('.implode(',',$gids).')')->findAll();

        $tmp = array();

        foreach($inDatas as $val){

            if(isset($tmp[$val['goods_id']]['g_store_nums'])){
                $tmp[$val['goods_id']]['g_store_nums'] += $val['p_store_nums'];
            }else{
                $tmp[$val['goods_id']]['g_store_nums'] = $val['p_store_nums'];
            }

            if(isset($tmp[$val['goods_id']]['g_freeze_nums'])){
                $tmp[$val['goods_id']]['g_freeze_nums'] += $val['p_freeze_nums'];
            }else{
                $tmp[$val['goods_id']]['g_freeze_nums'] = $val['p_freeze_nums'];
            }

        }

        foreach($tmp as $goods_id =>$val){

            $data = array();

            $data['g_store_nums'] = $val['g_store_nums'];
            $data['g_freeze_nums'] = $val['g_freeze_nums'];

            $inventorysModel->data($data)->where('goods_id = '.$goods_id)->update();
        }

    }

    //更新总库存、冻结库存(新)
    public function updateRealAndFreezeNums($productsInfo = array(),$op = '+')
    {
        $inventorysModel = new Model('inventorys','zd','master');

        $goods_ids = array();

        foreach($productsInfo as $pro){

            $indata = $inventorysModel->where('goods_id = '.$pro['goods_id'].' and product_id = '.$pro['product_id'])->find();

            if($op == '-'){
                $p_freeze_num = $indata['p_freeze_nums'] - $pro['num'];

                $p_freeze_num = ($p_freeze_num > 0) ? $p_freeze_num : 0;

                $p_store_nums = $indata['p_store_nums'] - $pro['num'];

                $p_store_nums = ($p_store_nums > 0) ? $p_store_nums : 0;

            }elseif($op == '+'){
                $p_freeze_num = $indata['p_freeze_nums'] + $pro['num'];

                $p_store_nums = $indata['p_store_nums'] + $pro['num'];

            }else{
                break;
            }

            $inventorysModel->data(array('p_freeze_num'=>$p_freeze_num,'p_store_nums'=>$p_store_nums))->where('goods_id = '.$pro['goods_id'].' and product_id = '.$pro['product_id'])->update();

            $goods_ids[] = $pro['goods_id'];

        }
        $gids = array_unique($goods_ids);

        $inDatas = $inventorysModel->where('goods_id in ('.implode(',',$gids).')')->findAll();

        $tmp = array();

        foreach($inDatas as $val){

            if(isset($tmp[$val['goods_id']]['g_store_nums'])){
                $tmp[$val['goods_id']]['g_store_nums'] += $val['p_store_nums'];
            }else{
                $tmp[$val['goods_id']]['g_store_nums'] = $val['p_store_nums'];
            }

            if(isset($tmp[$val['goods_id']]['g_freeze_nums'])){
                $tmp[$val['goods_id']]['g_freeze_nums'] += $val['p_freeze_nums'];
            }else{
                $tmp[$val['goods_id']]['g_freeze_nums'] = $val['p_freeze_nums'];
            }

        }

        foreach($tmp as $goods_id =>$val){

            $data = array();

            $data['g_store_nums'] = $val['g_store_nums'];
            $data['g_freeze_nums'] = $val['g_freeze_nums'];

            $inventorysModel->data($data)->where('goods_id = '.$goods_id)->update();
        }
    }

}