<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 26/11/16
 * Time: 下午10:46
 *
 * /usr/local/Cellar/php54/5.4.45_3/bin/php job.php importJob --importtype=goods
 *
 *
 */
define('IMPORT_DIR',APP_ROOT.'data'.DIRECTORY_SEPARATOR.'import'.DIRECTORY_SEPARATOR.'goods'.DIRECTORY_SEPARATOR);

class importdata_goodsJob
{

    private static $goods_columns = array(
        'goods_no'=>'商品编号',
        'pro_no'=>'货号',
        'name'=>'商品名称',
        'category_id'=>'分类',
        'trade_price'=>'批发价',
        'cost_price'=>'成本价',
        'sell_price'=>'销售价',
        'brand_id'=>'品牌',
        'is_online'=>'上架',
        'store_nums'=>'库存',
        'weight'=>'重量',
    );

    private static $products_columns = array(
        'pro_no'=>'货号',
        'trade_price'=>'批发价',
        'cost_price'=>'成本价',
        'sell_price'=>'销售价',
        'store_nums'=>'库存',
        'weight'=>'重量',
    );

    private static $goodsModel = null;

    private static $productsModel = null;

    private static $categoryModel = null;

    private static $brandModel = null;
    
    private static $msg = array();
    
    public static function run($data,$importModel)
    {
        if(empty($data)){
            return false;
        }

        $filename = IMPORT_DIR.$data['import_name'].'.csv';

        $csvData = self::readCsv($filename);

        if(empty($csvData)){
            return false;
        }

        $importdata = array(
            'status'=>'ing'
        );
        $importModel->data($importdata)->where('queue_id='.$data['queue_id'])->update();



        foreach($csvData as $val){

            $params = array();
            $params['goods_no'] = empty($val[0]) ? 0 :$val[0];
            $params['pro_no'] = empty($val[1]) ? 0 : $val[1];
            $params['name'] = empty($val[2]) ? '' : $val[2];
            $params['category_id'] = empty($val[3]) ? 0 : $val[3];
            $params['trade_price'] = empty($val[4]) ? 0 : $val[4];
            $params['cost_price'] = empty($val[5]) ? 0 : $val[5];
            $params['sell_price'] = empty($val[6]) ? 0 : $val[6];
            $params['brand_id'] = empty($val[7]) ? 0 : $val[7];
            $params['is_online'] = empty($val[8]) ? '' :$val[8];
            $params['store_nums'] = empty($val[9]) ? 0 : $val[9];
            $params['weight'] = empty($val[10]) ? 0 : $val[10];

            self::updateGoods($params);

        }

        $importdata = array(
            'status'=>'succ',
            'modify_time'=>time(),
            //'content'=>self::$msg
        );
        $importModel->data($importdata)->where('queue_id='.$data['queue_id'])->update();

        return true;
    }

    private static function readCsv($filename)
    {
        $list = array();

        $file = fopen($filename,'r');
        while ($data = fgetcsv($file)) {
            $list[] = $data;
        }

        fclose($file);

        array_shift($list);
//        print_r($list);exit;
        return $list;
    }

    private static function getGoods($filter = array())
    {

        if(empty($filter)){
            return false;
        }

        self::$goodsModel = new Model('goods','zd','salve');

        $where = array();

        foreach($filter as $k=>$v){
            $where[] = '`'.$k.'`='.$v;
        }

        $where = implode($where,' and ');

        return self::$goodsModel->where($where)->find();
    }

    private static function getProducts($filter = array())
    {

        if(empty($filter)){
            return false;
        }

        self::$productsModel = new Model('products','zd','salve');

        $where = array();

        foreach($filter as $k=>$v){
            $where[] = '`'.$k.'`='.$v;
        }

        $where = implode($where,' and ');

        return self::$productsModel->where($where)->find();

    }

    private static function getCategory($filter = array())
    {

        if(empty($filter)){
            return false;
        }

        self::$categoryModel = new Model('goods_category','zd','salve');

        $where = array();

        foreach($filter as $k=>$v){
            $where[] = '`'.$k.'`='.$v;
        }

        $where = implode($where,' and ');

        return self::$categoryModel->where($where)->find();

    }

    private static function getBand($filter = array())
    {

        if(empty($filter)){
            return false;
        }

        self::$brandModel = new Model('brand','zd','salve');

        $where = array();

        foreach($filter as $k=>$v){
            $where[] = '`'.$k.'`='.$v;
        }

        $where = implode($where,' and ');

        return self::$brandModel->where($where)->find();
    }
    
    private static function updateGoods($params = array())
    {
        if($params['goods_no'] && $params['pro_no']){
            $gno = self::getGoods(array('goods_no'=>'"'.$params['goods_no'].'"'));

            if(!$gno){
                return false;
            }

            $goods_params = array();

        }elseif(!$params['goods_no'] && $params['pro_no']){
            $pno = self::getProducts(array('pro_no'=>'"'.$params['pro_no'].'"'));

            if(!$pno){
                return false;
            }

            $product_params = array();
        }else{
            return false;
        }

        if(isset($goods_params)){

            $params['name'] = iconv('gb2312','utf-8',$params['name']);

            $params['is_online'] = iconv('gb2312','utf-8',$params['is_online']);

            $params['category_id'] = iconv('gb2312','utf-8',$params['category_id']);

            $params['brand_id'] = iconv('gb2312','utf-8',$params['brand_id']);


            $params['is_online'] = ($params['is_online'] == '是') ? 0 : 1;

            $category = self::getCategory(array('name'=>'"'.$params['category_id'].'"'));

            $params['category_id'] = $category['id'] ? $category['id'] : 0;


            $brands = self::getBand(array('name'=>'"'.$params['brand_id'].'"'));

            $params['brand_id'] = $brands['id'] ? $brands['id'] : 0;

            $goods_params = array(
                'goods_no'=>$params['goods_no'],
                'pro_no'=>$params['pro_no'],
                'name'=>$params['name'],
                'category_id'=>$params['category_id'],
                'trade_price'=>$params['trade_price'],
                'cost_price'=>$params['cost_price'],
                'sell_price'=>$params['sell_price'],
                'brand_id'=>$params['brand_id'],
                'is_online'=>$params['is_online'],
                'store_nums'=>$params['store_nums'],
                'weight'=>$params['weight'],
            );

            self::$goodsModel->data($goods_params)->where('id='.$gno['id'])->update();

            $gparams = array(
                'id'=>$gno['id'],
                'goods_no'=>$params['goods_no'],
                'pro_no'=>$params['pro_no'],
                'name'=>$params['name'],
                'category_id'=>$params['category_id'],
                'trade_price'=>$params['trade_price'],
                'cost_price'=>$params['cost_price'],
                'sell_price'=>$params['sell_price'],
                'brand_id'=>$params['brand_id'],
                'is_online'=>$params['is_online'],
                'store_nums'=>$params['store_nums'],
                'weight'=>$params['weight'],
            );

            syncGoods::getInstance()->setParams($gparams,'update','normal','headtobranch','zd')->sync();

        }

        if(isset($product_params)){

            $product_params = array(
                'pro_no'=>$params['pro_no'],
                'trade_price'=>$params['trade_price'],
                'cost_price'=>$params['cost_price'],
                'sell_price'=>$params['sell_price'],
                'store_nums'=>$params['store_nums'],
                'weight'=>$params['weight'],
            );
            self::$productsModel->data($product_params)->where('id='.$pno['id'])->update();

            $pparams = array(
                'id'=>$pno['id'],
                'pro_no'=>$params['pro_no'],
                'trade_price'=>$params['trade_price'],
                'cost_price'=>$params['cost_price'],
                'sell_price'=>$params['sell_price'],
                'store_nums'=>$params['store_nums'],
                'weight'=>$params['weight'],
            );

            syncProducts::getInstance()->setParams($pparams,'update','normal','headtobranch','zd')->sync();
        }

        return true;
    }

}