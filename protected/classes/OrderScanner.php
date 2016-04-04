<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 18/2/16
 * Time: 下午2:44
 */
//扫描枪与订单实现方法
class OrderScanner
{
    //定义扫描订单的状态
    private static $os_status = array(
        'wait_warehouse'=>1,//待出库订单
        //已出库订单
        'wait_picking'=>2,//待配货订单
        //已进入配货
        'wait_inspection'=>3,//待验货订单
        //此单已验货完毕，等待发货
        'wait_delivery'=>4,//待发送订单
        //已送货

        'wait_distribution'=>5,//代配货枪

        'wait_logistics'=>6,//代配货枪

    );

    //定义扫描状态和扫描枪的对应关系
    private static $scanner_orderstatus = array(
        'wait_warehouse'=>'已出单',

        'wait_picking'=>'已进入配货',

        'wait_inspection'=>'此单已验货完毕，等待发货',

        'wait_delivery'=>'已送货',

        'wait_distribution'=>'已进入配货',

        'wait_logistics'=>'已送货',

    );


    private static $next_scannergun = array(
        'wait_warehouse'=>'请使用配货枪进行扫描',

        'wait_picking'=>'请使用验货枪进行扫描',

        'wait_inspection'=>'请使用发货枪进行扫描',

        'wait_delivery'=>'订单扫描已完成，',

        'wait_distribution'=>'请使用验货枪进行扫描',

        'wait_logistics'=>'订单扫描已完成，'

    );

    private static $instance = null;

    private $orderscannerModel = null;

    private $scannersettingModel = null;

    public static function getInstance()
    {
        if(null === self::$instance){
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function __construct()
    {
        $this->orderscannerModel = new Model("orderscanner");

        $this->scannersettingModel = new Model("scannersetting");
    }

    public function getScannerStatus($order_id = null){

        $row = $this->orderscannerModel->fields('status')->where('order_id = '.trim($order_id))->find();

        if(empty($row)){
            $result['sanner_status'] = 'wait_warehouse';
            $result['status'] = '等待待出库扫描';
        }else{
            $result['sanner_status'] = $row['status'];
            $result['status'] = self::$scanner_orderstatus[$row['status']];
        }

        return $result;
    }

    //员工号检测
    public function docheckauth($data = array()){
        $result = array('res'=>'false','msg'=>'数据获取失败','mutiScanner'=>'false');

        $row = $this->scannersettingModel->fields('scanner_id,scanner_name,scan_no,scanner_type')->where('scanner_number = '.$data['scanner_number'].' and status="false"')->find();

        if(trim($data['scanner_number']) == ''){
            $result['msg'] = '员工号为空';
        }elseif(!$row){
            $result['msg'] = '员工号不存在';
        }else{
            $result['res'] = 'true';
            if(in_array($row['scanner_type'],array('wait_warehouse','wait_delivery')))
            {
                $result['mutiScanner'] = 'true';
                $result['orderNums'] = '20';
            }elseif('wait_distribution' == $row['scanner_type']){
                $result['mutiScanner'] = 'true';
                $result['orderNums'] = '40';
            }

            $result['scanner_type'] = $row['scanner_type'];

            $result['msg'] = '<dl class="lineD"><dt>员工姓名：</dt><dd>'.$row['scanner_name'].'<input type="hidden" name="scanner_id" id="scanner_id" value="'.$row['scanner_id'].'"></dd></dl>';
            $result['msg'] .= '<dl class="lineD"><dt>扫描枪号：</dt><dd>'.$row['scan_no'].'<input type="hidden" name="scanner_type" id="scanner_type" value="'.$row['scanner_type'].'"></dd></dl>';

        }

        return $result;
    }

    //扫描基本信息监测
    public function scannorderInfo($scanner_type = null,$order_id = null){
        $result = array('res'=>'false','msg'=>'fail');
        $error = false;

        if($scanner_type == 'wait_logistics'){
            $deliveryObj = new Model('order_invoice');
            $deliveryData = $deliveryObj->where('express_no="'.$order_id.'"')->find();

            if(!$deliveryData){
                $result['msg'] = '物流单号不存在';
            }else{
                $result['res']='true';
            }

            return $result;
        }

        $ordersObj = new Model('order');
        $orderdata = $ordersObj->fields("id,delivery_status")->where('order_no = '.$order_id)->find();

        if(!$orderdata){
            $result['msg'] = '订单号不存在';
            $error = true;
        }elseif($orderdata['delivery_status'] != '1'){
            $result['msg'] = '请先对该订单进行发货,才能扫描';
            $error = true;
        }


        if($error){
            return $result;
        }

        $result = $this->normalScanorder($order_id,$scanner_type);

        return $result;
    }


    public function doscannorders($params = array()){
        $result = array('res'=>'false','msg'=>'操作失败');

        $row = $this->scannersettingModel->fields('scanner_id,scanner_name,scan_no,scanner_type')->where('scanner_id = '.$params['scanner_id'].' and status = "false"')->find();

        $text = '订单号';

        if($row['scanner_type'] == 'wait_logistics'){
            $text = '物流单号';
            $field = 'express_no';
        }else{
            $field = 'order_no';
        }

        $orderInvoiceModel = new Model('order_invoice');
        $oi = $orderInvoiceModel->where($field.'='.$params['order_id'])->find();

        $domain = $oi['site_url'];


        $aData = array(
            'scanner_id'=>$params['scanner_id'],
            'order_no'=>$oi['order_no'],
            'scan_no'=>$row['scan_no'],
            'scanner_time'=>$params['scanner_time'],
            'memo'=>$text.':'.$params['order_id'].self::$scanner_orderstatus[$row['scanner_type']].'.  操作人:'.$row['scanner_name'],
            'status'=>$row['scanner_type'],
            'scanner_name'=>$row['scanner_name'],
            'domain'=>$domain,
        );

        if($this->savescanner($aData)){

            $result['res'] = 'true';
            $result['msg'] = '保存成功';
        }

        return $result;
    }

    private function savescanner($params = array()){
        $orderModel = new Model('order');
        $order = $orderModel->where('order_no="'.$params['order_no'].'"')->find();
        $order_id = $order['id'];
        $aData = array(
            'scanner_id'=>$params['scanner_id'],
            'order_id'=>$params['order_no'],
            'scan_no'=>$params['scan_no'],
            'scanner_time'=>$params['scanner_time'],
            'memo'=>$params['memo'],
            'status'=>$params['status'],
        );

        $exists = $this->orderscannerModel->where('scanner_id = '.$params['scanner_id'].' AND order_id = '.$params['order_id'].' AND status = "'.$params['status'].'"')->find();
        if(!$exists){
            $orderscanner = $this->orderscannerModel->data($aData)->add();
            if( $orderscanner ){

                Log::orderlog($order_id,'扫描人员:'.$params['scanner_name'],$params['memo'],'订单扫描','success',$params['domain']);
                return true;
            }else{
                return false;
            }
        }
        return true;
    }

    //正常扫单流程
    public function normalScanorder($order_id,$scanner_type){

        $result = array();

        $result['res']='true';//不需要验证打单流程
        return $result;

        //需要验证打单流程
        $row = $this->orderscannerModel->fields("status")->where('order_id = '.$order_id)->order('scanner_time desc')->find();

        if(empty($row)){
            if($scanner_type == 'wait_warehouse'){
                $order_status = self::$os_status['wait_warehouse'];
                $tmp_status = 'wait_warehouse';
            }else{
                $result['msg'] = '该订单还没打单';
                return $result;
            }
        }else{
            $result['res']='true';
            return $result;
        }

        $scangun_status = self::$os_status[$scanner_type];

        if($order_status == $scangun_status){ //订单状态等于扫描枪状态
            $result['res'] = 'true';
        }elseif($scangun_status > $order_status){  //扫描枪状态大于订单状态
            if( (($scangun_status - $order_status) == 1) || (3 == $scangun_status)){
                $result['res'] = 'true';
            }else{
                $result['res'] = 'false';
                $result['msg'] = '当前订单状态处于'.self::$scanner_orderstatus[$tmp_status];
            }
        }elseif($order_status > $scangun_status){  //订单状态大于扫描枪状态
            $result['res'] = 'false';
            $result['msg'] = '当前订单状态处于'.self::$scanner_orderstatus[$tmp_status].',请选用正确的扫描枪进行操作';
        }

        return $result;
    }

} 