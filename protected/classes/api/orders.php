<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 29/4/16
 * Time: 下午6:10
 */
class orders extends baseapi
{
    protected $orderDetailTemplate = '
    <div class="list-block">
        <ul>
            <li>
                <div class="item-content">
                    <div class="item-inner">
                        <div class="item-title label">状态</div>
                        <div class="item-input">
                            <span class="badge bg-green">{status}</span>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-inner">
                        <div class="item-title label">订单号</div>
                        <div class="item-input">
                            {order_no}
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-inner">
                        <div class="item-title label">下单时间</div>
                        <div class="item-input">
                            {create_time}
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-inner">
                        <div class="item-title label">收货地址</div>
                        <div class="item-input">
                            {ship_addr}
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <a class="item-link open-popup" href="#" data-popup=".demo-popup">
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-after" style="padding-bottom: 60px;">{lastlog}</div>
                        </div>

                    </div></a>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-inner">
                        <div class="col-50"><a href="#" class="button demo-actions">取消订单</a></div><div class="col-50"><a href="#" class="button demo-actions">立即支付</a></div>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <div class="list-block">
        <ul>
            {products}

        </ul>
    </div>

    <div class="list-block">
        <ul>
            <li>
                <div class="item-content">
                    <div class="item-inner">
                        <div class="item-title label">商品金额</div>
                        <div class="item-input right">
                            ￥{goods_amount}
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-inner">
                        <div class="item-title label">运费</div>
                        <div class="item-input right">
                            ￥{real_freight}
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item-content">
                    <div class="item-inner">
                        <div class="item-title label">应付款</div>
                        <div class="item-input right">
                            ￥{order_amount}
                        </div>
                    </div>
                </div>
            </li>

        </ul>
    </div>


        <div class="list-block">
            <ul>
                <li>
                    &nbsp;
                </li>
            </ul>
        </div>';


    public function index()
    {
        switch($this->params['source']){
            case 'orderdetail':
                $this->orderDetail();
                break;
        }
    }

    protected function orderDetail()
    {
        $orderid = $this->params['id'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status,province,city,county,addr,real_freight')->where('id='.$orderid)->find();

        $orderDetailModel = new Model('order_goods');

        $goodsModel = new Model('goods');


        $status = $this->status($orders);

        $detail = '';

        $orderlog = '<div class="list-block">
        <ul>';

        $goods_amount = 0;

        $lastlog = '';

        $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$orders['id'])->findAll();

        $products = '';

        foreach($odDatas as $vval){
            $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();

            $products .= '<li>
                <div class="card ks-facebook-card">
                    <div class="card-header no-border">
                        <div class="ks-facebook-avatar"><img src="'.self::APIURL.$gData['img'].'" width="34" height="34"/></div>
                        <div style="float:right;">￥'.$vval['real_price'].'</div>
                        <div class="ks-facebook-name" style="margin-right:44px;">'.$gData['name'].'</div>
                        <div class="ks-facebook-date" style="margin-right:44px;"> X '.$vval['goods_nums'].'</div>

                    </div>
                </div>
            </li>';
            $goods_amount += ($vval['real_price']*$vval['goods_nums']);
        }


        $orderlogModel = new Model('order_log');

        $log = $orderlogModel->where('order_id='.$orders['id'])->findAll();

        foreach($log as $val){
            $orderlog .= '
            <li>
                <div class="item-content">
                    <div class="item-inner">
                        <div class="left">'.$val['addtime'].'<br/>'.$val['note'].'</div>
                    </div>
                </div>
            </li>';
            $lastlog = $val['note'].'<br/>('.$val['addtime'].')';
        }

        $orderlog .= '</ul>
    </div>';

        $area_ids = $orders['province'].','.$orders['city'].','.$orders['county'];

        $areaModel = new Model('area','zd','salve');
        if($area_ids!='')$areas = $areaModel->where("id in ($area_ids)")->findAll();
        $parse_area = array();
        foreach ($areas as $area) {
            $parse_area[$area['id']] = $area['name'];
        }

        $ship_addr = $parse_area[$orders['province']].$parse_area[$orders['city']].$parse_area[$orders['county']].' '.$orders['addr'];

        $detail .= str_replace(array('{order_no}','{create_time}','{ship_addr}','{goods_amount}','{real_freight}','{order_amount}','{products}','{status}','{lastlog}'),array($orders['order_no'],$orders['create_time'],$ship_addr,$goods_amount,$orders['real_freight'],$orders['order_amount'],$products,$status,$lastlog),$this->orderDetailTemplate);

        $this->output['status'] = 'succ';

        $this->output(array('orderlog'=>$orderlog,'detail'=>$detail));
    }

    private function status($item = array())
    {
        if($item['status'] == '1'){
            return '等待付款';
        }elseif($item['status'] == '2'){
            if($item['pay_status'] == 1){
                return '等待审核';
            }else{
                $payment_info = Common::getPaymentInfo($item['payment']);
                if($payment_info['class_name']=='received'){
                    return '等待审核';
                }else{
                    return '等待付款';
                }
            }
        }elseif($item['status'] == '3'){
            if($item['delivery_status'] == 0){
                return '等待发货';
            }elseif($item['delivery_status'] == 1){
                return '已发货';
            }

            if($item['pay_status'] == 3){
                return '已退款';
            }

        }elseif($item['status'] == 4){
            return '已完成';
        }elseif($item['status'] == 5){
            return '已取消';
        }elseif($item['status'] == 6){
            return '已作废';
        }
    }

}