<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 28/4/16
 * Time: 下午4:29
 */
class products extends baseapi
{
    protected $goodsModel = null;

    protected $productsModel = null;

    protected $imageSize = array(
        'width'=>'640',
        'height'=>'640',
    );

    public static $title = array(
        'products'=>'商品详情'
    );

    public static $lastmodify = array(
        'products'=>'2016-6-23',
    );

    public static $notice = array(
        'products'=>'',
    );

    public static $requestParams = array(
        'products'=>array(
            array(
                'colum'=>'id',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'商品id',
            ),
            array(
                'colum'=>'uid',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'会员id 默认: 0',
            ),
        ),
    );

    public static $responsetParams = array(
        'products'=>array(
            array(
                'colum'=>'name',
                'content'=>'商品名称',
            ),
            array(
                'colum'=>'price',
                'content'=>'销售价',
            ),
            array(
                'colum'=>'store_nums',
                'content'=>'可用库存',
            ),
            array(
                'colum'=>'goods_no',
                'content'=>'商品编号',
            ),
            array(
                'colum'=>'pro_no',
                'content'=>'货号',
            ),
            array(
                'colum'=>'unit',
                'content'=>'库存',
            ),
            array(
                'colum'=>'content',
                'content'=>'商品描述(link形式)',
            ),
            array(
                'colum'=>'content_data',
                'content'=>'商品描述',
            ),
            array(
                'colum'=>'imgs',
                'content'=>'商品图片(多图)',
            ),
            array(
                'colum'=>'sys_attrprice',
                'content'=>'多规格商品存放',
            ),
            array(
                'colum'=>'attention',
                'content'=>'是否收藏 [已收藏:fav,未收藏:nofav]（当存在会员id时候，使用）',
            ),
        ),
    );

    public static $requestUrl = array(
        'products'=>'     /index.php?con=api&act=index&method=products'
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

        $this->goodsModel = new Model('goods');

        $this->productsModel = new Model('products');
    }

    public function index()
    {
        $id = intval($this->params['id']);

        $storenums = $this->getStoreById($id);

        $uid = $this->params['uid'];

        $data = array();
        $goods = $this->goodsModel->fields('id,name,branchstore_goods_name,goods_no,pro_no,sell_price,branchstore_sell_price,imgs,unit,content,store_nums')->where('id='.$id)->find();


        if($goods){
            $products = $this->productsModel->where('goods_id='.$id)->findAll();

            $sys_attrprice = array();
            $spec_arr = array();

            $freeze = 0;

            foreach($products as $pk=>$val){
                if($val['spec']){
                    $spec = unserialize($val['spec']);
                    if($spec){
                        $i = 0;
                        foreach($spec as $k=>$sval){
                            $spec_arr[$k]['name'] = $sval['name'];
                            $spec_arr[$k]['id'] = $sval['id'];
                            $spec_arr[$k]['value'] = $sval['value'][2];

                            $freeze_nums = $storenums[$val['id']]['p_store_nums'] - $storenums[$val['id']]['p_freeze_nums'];

                            if(($val['branchstore_sell_price'] == '0.00') || ($val['branchstore_sell_price'] == '0') || ($val['branchstore_sell_price'] == '')){
                                $price = $val['sell_price'];
                            }else{
                                $price = $val['branchstore_sell_price'];
                            }

                            //if($freeze_nums > 0){
                                $sys_attrprice[$pk] = array(
                                    'price'=>$price,
                                    'pro_no'=>$val['pro_no'],
                                    'store_num'=>$freeze_nums,
                                    'name'=>$sval['name'],
                                    'value'=>$sval['value'][2],
                                );
                            //}

                        }

                        $freeze += $storenums[$val['id']]['p_freeze_nums'];

                    }else{
                        $freeze += $storenums[$val['id']]['p_freeze_nums'];
                    }

                }
            }

            if($freeze){
                $goods['store_nums'] = $storenums[0]['store_nums'] - $freeze;
                $goods['store_nums'] = ($goods['store_nums'] > 0) ? $goods['store_nums'] : 0;
            }else{
                $goods['store_nums'] = $storenums[0]['store_nums'];
            }

            $attention = 'nofav';

            if($uid){
                $attentionModel = new Model('attention');

                $at = $attentionModel->where('`user_id`='.$uid.' and goods_id='.$id)->find();

                if($at){
                    $attention = 'fav';
                }
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '商品详情获取成功';
            $data = $this->geJson($goods,$spec_arr,$sys_attrprice);

            $data['attention'] = $attention;
//echo "<pre>";
//            print_r($data);exit;
            $this->output($data);
        }else{
            $this->output['msg'] = '商品详情获取失败';
            $this->output();
        }

    }

    public function products_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'商品详情获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'商品详情获取成功',
                'data'=>array(
                        'name' => '哈哈',
                        'price' => '0.00',
                        'store_nums' => 1,
                        'goods_no' => '111111',
                        'pro_no' => '111111_1',
                        'unit' => '件',
                        'content' => '',
                        'content_data' => '',
                        'attention'=>'已收藏:fav,未收藏:nofav',
                        'imgs' => array(
                            array(
                                'url' => 'http://a.test.com/data/uploads/2014/04/30/b8f4125b967911e08f7115f8d2b3f684.jpg',
                            ),
                        ),
                        'sys_attrprice' => array(
                            '34(规格id)' =>array(
                                'price' => '12.00（销售价）',
                                'pro_no' => '111111_1（货号）',
                                'store_num' => '12（库存）',
                                'attr_id' => '34 (规格id)',
                                'name' =>'颜色（规格名称）',
                                'value' =>'蓝色 (对应规格描述)',
                            ),
                        ),
                ),
            )
        );

    }

    protected function getStoreById($id)
    {
        $inventorysModel = new Model('inventorys','zd','salve');

        $indata = $inventorysModel->where('goods_id = '.$id)->findAll();

        $return = array();

        foreach($indata as $val){
            $return[0]['store_nums'] = $val['g_store_nums'];
            $return[0]['freeze_nums'] = $val['g_freeze_nums'];
            $return[$val['product_id']] = $val;
        }

        return $return;
    }

    protected function geJson($goods,$spec_arr = array(),$sys_attrprice = array())
    {
        $return = array();

        $imgs = unserialize($goods['imgs']);

        if(($goods['branchstore_sell_price'] == '0.00') || ($goods['branchstore_sell_price'] == '0') || ($goods['branchstore_sell_price'] == '')){
            $return['price'] = $goods['sell_price'];
        }else{
            $return['price'] = $goods['branchstore_sell_price'];
        }

        $return['name'] = $goods['branchstore_goods_name'] ? $goods['branchstore_goods_name'] : $goods['name'];
//        $return['price'] = $goods['branchstore_sell_price'] ? $goods['branchstore_sell_price'] : $goods['sell_price'];
        $return['goods_no'] = $goods['goods_no'];
        $return['pro_no'] = $goods['pro_no'];
        $return['unit'] = $goods['unit'];
        $return['content'] = baseapi::getApiUrl().'index.php?con=index&act=prouduct_desc&id='.$goods['id'];
        $return['content_data'] = $goods['content']."<script src='http://libs.baidu.com/jquery/2.0.0/jquery.js'></script>
<script>
    if($('img')){
        $('img').each(function(){
            $(this).css('width','750px');
        });
    }
</script>";

        $return['imgs'] = array();

        $i = 0;
        $store_nums = 0;
        $attrprice = array();
//        $productsModel = new Model('products');


        foreach($sys_attrprice as $val){

//            $product = $productsModel->where('pro_no="'.$val['pro_no'].'"')->find();
//
//            $val['store_nums'] = $product['store_nums'] - $product['freeze_nums'];// -  $cart_nums;

            $store_nums += $val['store_nums'];

            $attrprice[$i] = $val;
            $i++;
        }

        $return['store_nums'] = ($store_nums > 0) ? $store_nums : $goods['store_nums'];

        $return['sys_attrprice'] = $attrprice;

        foreach($imgs as $k=>$val){

            $filename = self::getApiUrl().$val;

            $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);

            $return['imgs'][$k]['url'] = $image['src'];

        }

        return $return;
    }

}