<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 17/2/16
 * Time: 下午11:31
 */
//扫描枪设置
class ScannerSetting
{
    private static $scannerInfo = array(
        '出单枪'=>array(
            '1号枪'
        ),
        '配货枪'=>array(
            '2号枪',
        ),
        '验货枪'=>array(
            '3号枪',
        ),
        '发货枪'=>array(
            '4号枪',
        ),
    );

    private static $scannernotogun = array(
        '1号枪'=>'wait_warehouse',
        '2号枪'=>'wait_picking',
        '3号枪'=>'wait_inspection',
        '4号枪'=>'wait_delivery',
    );

    private static $public_status = array(
        '出单枪'=>'wait_warehouse',

        '配货枪'=>'wait_picking',

        '验货枪'=>'wait_inspection',

        '发货枪'=>'wait_delivery',
    );

    public static function getScannerType($params){
        return self::$scannernotogun[$params];
    }

    public static function getScanNo($params = array()){

        $scannerInfo = array();

        if(empty($params)){
            $scannerInfo = self::$scannerInfo;
        }

        return $scannerInfo;
    }
} 