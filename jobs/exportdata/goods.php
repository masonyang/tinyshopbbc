<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 24/11/16
 * Time: 下午10:54
 *
 * /usr/local/Cellar/php54/5.4.45_3/bin/php job.php exportJob --exporttype=goods
 *
 *
 *      * * * * * /usr/bin/php  /var/www/html/qqc/jobs/job.php exportJob --exporttype=goods >/dev/null
 */

define('EXPORT_DIR',APP_ROOT.'data'.DIRECTORY_SEPARATOR.'export'.DIRECTORY_SEPARATOR.'goods'.DIRECTORY_SEPARATOR);

class exportdata_goodsJob
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
        'store_nums'=>'上架',
        'weight'=>'重量',
    );

    private static $goodsModel = null;

    private static $productsModel = null;

    private static $categoryModel = null;

    private static $brandModel = null;

    public static function run($data,$exportModel)
    {
        if(empty($data)){
            return false;
        }

        $filename = EXPORT_DIR.$data['export_name'].'.csv';

        $exportdata = array(
            'status'=>'ing'
        );
        $exportModel->data($exportdata)->where('queue_id='.$data['queue_id'])->update();

        $ids = unserialize($data['content']);

        self::$goodsModel = new Model('goods','zd','salve');

        self::$productsModel = new Model('products','zd','salve');

        self::$categoryModel = new Model('goods_category','zd','salve');

        self::$brandModel = new Model('brand','zd','salve');

        $title = array();

        foreach(self::$goods_columns as $k=>$v){
            $title[] = iconv('utf-8','gb2312',$v);
        }

        self::writeCsv(array($title),$filename);

        foreach($ids as $id){

            $gData = self::$goodsModel->where('id='.$id)->find();

            $goods = array();

            foreach(self::$goods_columns as $k=>$v){

                if($k == 'category_id'){

                    $catedata = self::$categoryModel->where('id='.$gData[$k])->find();

                    $gData[$k] = $catedata['name'];

                }elseif($k == 'is_online'){
                    $gData[$k] = ($gData[$k] == 0) ? '是' : '否';
                }elseif($k == 'brand_id'){

                    $branddata = self::$brandModel->where('id='.$gData[$k])->find();

                    $gData[$k] = $branddata['name'];

                }

                $gData[$k] =iconv('utf-8','gb2312',$gData[$k]);

                $goods[] = $gData[$k];
            }
            self::writeCsv(array($goods),$filename,true);

            $pData = self::$productsModel->where('goods_id='.$id)->findAll();



            foreach($pData as $k=>$v){

                $products = array();

                foreach(self::$goods_columns as $kk=>$vv){
                    if(isset($pData[$k][$kk])){
                        $products[] =iconv('utf-8','gb2312',$pData[$k][$kk]);
                    }else{
                        $products[] = '';
                    }
                }
                self::writeCsv(array($products),$filename,true);
            }


        }

        $exportdata = array(
            'status'=>'succ',
            'modify_time'=>time()
        );
        $exportModel->data($exportdata)->where('queue_id='.$data['queue_id'])->update();

    }

    private static function writeCsv($data, $filename = 'file.csv',$append = false) {

        if($append){
            $mode = 'a+';
        }else{
            $mode = 'w';
        }

        $fp = fopen($filename, $mode);
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }

}