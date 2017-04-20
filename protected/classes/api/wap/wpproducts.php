<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 28/4/16
 * Time: 下午4:29
 */
class wpproducts extends wapbase
{
    protected $goodsModel = null;

    protected $productsModel = null;

    protected $imageSize = array(
        'width'=>'640',
        'height'=>'640',
    );

    protected $imageRecommendSize = array(
        'width'=>'280',
        'height'=>'280',
    );

    public static $title = array(
        'products'=>'商品详情',
        'recommend'=>'商品推荐',
    );

    public static $lastmodify = array(
        'products'=>'2016-6-23',
        'recommend'=>'2017-2-12',
    );

    public static $notice = array(
        'products'=>'',
        'recommend'=>'',
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
        'recommend'=>array(
            array(
                'colum'=>'type',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'推荐类型:1-按商品销售量',
            ),
            array(
                'colum'=>'sort',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'排序顺序:desc、asc',
            ),
            array(
                'colum'=>'limit',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'要求返回条数',
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
        'recommend'=>array(
            array(
                'colum'=>'gid',
                'content'=>'商品id',
            ),
            array(
                'colum'=>'name',
                'content'=>'商品名称',
            ),
            array(
                'colum'=>'price',
                'content'=>'销售价',
            ),
            array(
                'colum'=>'img',
                'content'=>'图片地址',
            ),
        ),
    );

    public static $requestUrl = array(
        'products'=>'     /index.php?con=api&act=index&method=wpproducts',
        'recommend'=>'     /index.php?con=api&act=index&method=wpproducts&source=recommend',
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

        $this->goodsModel = new Model('goods');

        $this->productsModel = new Model('products');
    }

    public function index()
    {
        if(isset($this->params['source'])){
            if($this->params['source'] == 'recommend') {
                $this->recommend();
            } else {
                $this->products();
            }
        }
    }

    protected function products()
    {
        $id = intval($this->params['id']);

        $uid = $this->params['uid'];

        $data = array();
        $goods = $this->goodsModel->fields('id,name,branchstore_goods_name,goods_no,pro_no,sell_price,branchstore_sell_price,imgs,unit,content,store_nums')->where('id='.$id)->find();

        $storenums = $this->getStoreById($id);

        if($goods){
            $products = $this->productsModel->where('goods_id='.$id)->findAll();

            $sys_attrprice = array();
            $spec_arr = array();

            $freeze = 0;
            $combine_arr = array();
            $specslist_arr = array();

            foreach($products as $pk=>$val){
                if($val['spec']){
                    $spec = unserialize($val['spec']);
//                    echo "<pre>";
//                    print_r($spec);exit;
                    if($spec){

                        $speckey = array();
                        foreach($spec as $k=>$sval){

                            $specslist_arr[$sval['id']]['id'] = $sval['id'];
                            $specslist_arr[$sval['id']]['name'] = $sval['name'];

                            $specslist_arr[$sval['id']]['child'][$sval['value'][0]] = array(
                                'id'=>$sval['value'][0],
                                'name'=>$sval['value'][2],
                            );

                            $speckey[] = $sval['value'][0];

                            $spec_arr[$k]['name'] = $sval['name'];
                            $spec_arr[$k]['id'] = $sval['id'];
                            $spec_arr[$k]['value'] = $sval['value'][2];



                            //if($freeze_nums > 0){
//                            $sys_attrprice[$pk] = array(
//                                'price'=>$price,
//                                'pro_no'=>$val['pro_no'],
//                                'store_num'=>$freeze_nums,
//                                'name'=>$sval['name'],
//                                'value'=>$sval['value'][2],
//                            );
                            //}

                        }

                        $freeze += $storenums[$val['id']]['p_freeze_nums'];

                        $freeze_nums = $storenums[$val['id']]['p_store_nums'] - $storenums[$val['id']]['p_freeze_nums'];

                        if(($val['branchstore_sell_price'] == '0.00') || ($val['branchstore_sell_price'] == '0') || ($val['branchstore_sell_price'] == '')){
                            $price = $val['sell_price'];
                        }else{
                            $price = $val['branchstore_sell_price'];
                        }

                        $__speckey = implode('_',$speckey);
                        $combine_arr[$__speckey]['price'] = $price;
                        $combine_arr[$__speckey]['pro_no'] = $val['pro_no'];
                        $combine_arr[$__speckey]['store_num'] = $freeze_nums;
                    }else{
                        $freeze += $storenums[$val['id']]['p_freeze_nums'];
                    }

                }
            }

            if($combine_arr && $specslist_arr){
                $sys_attrprice['combine_arr'] = $combine_arr;

                $i = 0;
                $_specslist_arr = array();
                foreach($specslist_arr as $val){
                    $_specslist_arr[$i]['id'] = $val['id'];
                    $_specslist_arr[$i]['name'] = $val['name'];
                    $j = 0;
                    foreach($val['child'] as $vval){
                        $_specslist_arr[$i]['child'][$j]['id'] = $vval['id'];
                        $_specslist_arr[$i]['child'][$j]['name'] = $vval['name'];

                        $j++;
                    }
                    $i++;
                }


                $sys_attrprice['specslist_arr'] = $_specslist_arr;
            }else{
                $sys_attrprice['combine_arr'] = array();
                $sys_attrprice['specslist_arr'] = array();
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

            $this->output($data);
        }else{
            $this->output['msg'] = '商品详情获取失败';
            $this->output();
        }
    }

    protected function recommend()
    {
        $type = $this->params['type'];

        if(!isset($this->params['sort']) || !in_array($this->params['sort'],array('desc','asc'))){
            $this->params['sort'] = 'desc';
        }

        switch($type){
            case 1://按商品销售量
            default:
                $filter['columns'] = 'stat_id,gid,gcount';
                $filter['tablename'] = 'goods_sales_statistics';
                $filter['order'] = 'gcount '.$this->params['sort'];
                break;
        }

        $limit = 4;

        if(isset($this->params['limit']) && (intval($this->params['limit']) > 0)){
            $limit = $this->params['limit'];
        }

        $this->params['limit'] =  $limit;

        $filter['limit'] = '0 ,'.$this->params['limit'];

        $recommendModel = new Model($filter['tablename']);

        $recommendModel->fields($filter['columns']);

        if($filter['where']){
            $recommendModel->where($filter['where']);
        }

        $recommendModel->limit($filter['limit']);

        if($filter['order']){
            $recommendModel->order($filter['order']);
        }

        $recommendData = $recommendModel->findAll();

        if($recommendData){

            $_data = array();
            $gids = array();

            foreach($recommendData as $val){
                $gids[$val['gid']] = $val['gcount'];
            }

            $goodsData = $this->goodsModel->fields('id,name,branchstore_goods_name,img,sell_price,branchstore_sell_price')->where('id in ('.implode(',',array_keys($gids)).') and is_online=0')->findAll();

            $i = 0;

            foreach($goodsData as $val){

                if(($val['branchstore_sell_price'] == '0.00') || ($val['branchstore_sell_price'] == '0') || ($val['branchstore_sell_price'] == '')){
                    $price = $val['sell_price'];
                }else{
                    $price = $val['branchstore_sell_price'];
                }

                $name = ($val['branchstore_goods_name']) ? $val['branchstore_goods_name'] : $val['name'];

                $filename = self::getApiUrl().$val['img'];

                $image = ImageClipper::getInstance()->getImage($filename,$this->imageRecommendSize['width'],$this->imageRecommendSize['height']);

                $_data['goods'][$i]['gid'] = $val['id'];
                $_data['goods'][$i]['name'] = $name;
                $_data['goods'][$i]['img'] = $image['src'];
                $_data['goods'][$i]['price'] = $price;
                $i++;

            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '商品推荐列表获取成功';
            $this->output($_data);

        }else{
            $this->output['status'] = 'succ';
            $this->output['msg'] = '暂无商品推荐';
            $this->output(array());
        }

    }

    public function recommend_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'商品推荐列表获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'商品推荐列表获取成功/暂无商品推荐',
                'data'=>array(
                    'count'=>4,
                    'goods'=>array(
                        array(
                            'gid' => 5,
                            'name' => 'KAPA服饰',
                            'price'=>1000.00,
                            'img'=>'',
                        ),
                        array(
                            'gid' => 1,
                            'name' => 'MacBook电脑',
                            'price'=>100000.00,
                            'img'=>'',
                        ),
                    ),
                ),
            )
        );
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
                        'combine_arr (规格组合后的数据)' =>array(
                            '3_5'=>array(
                                'price' => '20.00（销售价）',
                                'pro_no' => '23423423423_1（货号）',
                                'store_num' => '99（库存）',
                            ),
                            '3_4'=>array(
                                'price' => '20.00（销售价）',
                                'pro_no' => '23423423423_2（货号）',
                                'store_num' => '98（库存）',
                            ),
                            '1_5'=>array(
                                'price' => '20.00（销售价）',
                                'pro_no' => '23423423423_3（货号）',
                                'store_num' => '97（库存）',
                            ),
                            '1_4'=>array(
                                'price' => '20.00（销售价）',
                                'pro_no' => '23423423423_4（货号）',
                                'store_num' => '96（库存）',
                            ),
                        ),
                        'specslist_arr'=>array(
                            array(
                                'id'=>1,
                                'name'=>'颜色',
                                'child'=>array(
                                    array(
                                        'id'=>3,
                                        'name'=>'白色',
                                    ),
                                    array(
                                        'id'=>1,
                                        'name'=>'蓝色',
                                    ),
                                ),
                            ),
                            array(
                                'id'=>2,
                                'name'=>'尺寸',
                                'child'=>array(
                                    array(
                                        'id'=>5,
                                        'name'=>'41',
                                    ),
                                    array(
                                        'id'=>4,
                                        'name'=>'40',
                                    ),
                                ),
                            ),
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

//        $i = 0;
//        $store_nums = 0;
//        $attrprice = array();
////        $productsModel = new Model('products');
//
//
//        foreach($sys_attrprice as $val){
//
////            $product = $productsModel->where('pro_no="'.$val['pro_no'].'"')->find();
////
////            $val['store_nums'] = $product['store_nums'] - $product['freeze_nums'];// -  $cart_nums;
//
//            $store_nums += $val['store_nums'];
//
//            $attrprice[$i] = $val;
//            $i++;
//        }

        $return['store_nums'] = $goods['store_nums'];

        $return['sys_attrprice'] = $sys_attrprice;

        foreach($imgs as $k=>$val){

            $filename = self::getApiUrl().$val;

            $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);

            $return['imgs'][$k]['url'] = $image['src'];

        }

        return $return;
    }

}


