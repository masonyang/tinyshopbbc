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

    protected $template = '<style>

.spinner{margin: 12px 0 0 10px; float:left; border:1px solid #ccc; border-radius:5px; overflow:hidden; *zoom:1; }
.spinner button, .spinner .value{text-align:center; display:block; float:left; height:100%; line-height:25px; margin:0;width: 30px; }
.spinner button{border:none; width:26px; height:26px; color:#666; font:22px Arial bold; padding:0; outline:none; background-color:#fff; }
.spinner .decrease{cursor:pointer;}
.spinner .decrease[disabled]{background-position:0px 0px; background-color:#e4e4e4; cursor:default; }
.spinner .increase{cursor:pointer;}
.spinner .value{background-position:0 -100px; width:34px; height:26px; border:none; border-left:1px solid #ccc; border-right:1px solid #ccc; font-family:Arial; font-size:16px; color:#333; padding:0px; }
.spinner .value:focus{border-color:#669900; }
.spinner .value.passive{background-position:0 -25px; color:#919191; }
.spinner .error, .spinner .invalid{background:#aa0000; }

</style>
<div data-pagination=".swiper-pagination" data-loop="true" class="swiper-container swiper-init ks-demo-slider">
                <div class="swiper-pagination"></div>
                <div class="swiper-wrapper">
                    {imgs}
                </div>
            </div>
            <div class="col-100 tablet-50" style="font-size: 24px;">{name}</div>
            <div class="list-block sys_item_spec">
                <ul>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">销售价</div>
                                <div class="item-input">
                                    ￥<span class="sys_item_price">{sale_price}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">库存</div>
                                <div class="item-input">
                                    <span class="sys_item_store_num">{store_nums}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">编号</div>
                                <div class="item-input">
                                    {goods_no}
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">货号</div>
                                <div class="item-input">
                                    <span class="sys_item_pro_no">{pro_no}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">单位</div>
                                <div class="item-input">
                                    {unit}
                                </div>
                            </div>
                        </div>
                    </li>
                    {spec_arrs}

                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">数量</div>
                                <div class="item-input">
                                    <table>
                                        <tr class="spinner">
                                            <td style="background-color: #919191;">
                                                <button class="spinner_decr decrease">-</button>
                                            </td>
                                            <td>
                                                <input class="spinnerExample spinner_text batchnum value passive" type="number" name="product_num" id="product_num" maxlength="2" value="0">
                                            </td>
                                            <td style="background-color: #919191;">
                                                <button class="increase spinner_incr">+</button>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="pro_no" id="pro_no_hidden" value="{pro_no_hidden}">
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-100 tablet-50">{content}</div>';


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

        $data = array();
        $goods = $this->goodsModel->fields('id,name,branchstore_goods_name,goods_no,pro_no,sell_price,branchstore_sell_price,imgs,unit,content,store_nums')->where('id='.$id)->find();


        if($goods){
            $products = $this->productsModel->where('goods_id='.$id)->findAll();

            $sys_attrprice = array();
            $spec_arr = array();
            foreach($products as $val){
                if($val['spec']){
                    $spec = unserialize($val['spec']);
                    foreach($spec as $k=>$sval){
                        $spec_arr[$k]['name'] = $sval['name'];
                        $spec_arr[$k]['id'] = $sval['id'];
                        $spec_arr[$k]['value'] = $sval['value'][1];
                        $sys_attrprice[$sval['id']] = array(
                            'price'=>$val['branchstore_sell_price'] ? $val['branchstore_sell_price'] : $val['sell_price'],
                            'pro_no'=>$val['pro_no'],
                            'store_num'=>$val['store_nums'],
                            'name'=>$sval['name'],
                            'value'=>$sval['value'][1],
                        );
                    }

                }
            }


            $this->output['status'] = 'succ';
            $this->output['msg'] = '商品详情获取成功';
            $data = $this->geJson($goods,$spec_arr,$sys_attrprice);
            $this->output($data);
        }else{
            $this->output['msg'] = '商品详情获取失败';
            $this->output();
        }


//        if(isset($this->params['gethtml'])){
//            echo "<pre>";
//            print_r($this->geJson($goods,$spec_arr,$sys_attrprice));
//            exit;
//        }else{
//            $sys_item = array();
//            $sys_item['price'] = $goods['branchstore_sell_price'] ? $goods['branchstore_sell_price'] : $goods['sell_price'];
//            $sys_item['pro_no'] = $goods['pro_no'];
//            $sys_item['store_num'] = $goods['store_nums'];
//            $sys_item['sys_attrprice'] = $sys_attrprice;
//            $data['sys_item'] = $sys_item;
//
//        echo "<pre>";
//        print_r($sys_item);exit;
//            $this->output['status'] = 'succ';
//            $this->output($data);
//        }

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
                        'imgs' => array(
                            array(
                                'url' => 'http://a.tinyshop.com/data/uploads/2014/04/30/b8f4125b967911e08f7115f8d2b3f684.jpg',
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
//        '{"status":"succ","msg":"\u83b7\u53d6\u6210\u529f","data":[{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg"},{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/9670df531a008c75e7bed5b8967efd66.gif"}]}';
    }

    protected function geJson($goods,$spec_arr = array(),$sys_attrprice = array())
    {
        $return = array();

        $imgs = unserialize($goods['imgs']);

        $return['name'] = $goods['branchstore_goods_name'] ? $goods['branchstore_goods_name'] : $goods['name'];
        $return['price'] = $goods['branchstore_sell_price'] ? $goods['branchstore_sell_price'] : $goods['sell_price'];
        $return['store_nums'] = $goods['store_nums'];
        $return['goods_no'] = $goods['goods_no'];
        $return['pro_no'] = $goods['pro_no'];
        $return['unit'] = $goods['unit'];
        $return['content'] = $goods['content'];
        $return['imgs'] = array();

        $return['sys_attrprice'] = $sys_attrprice;

        foreach($imgs as $k=>$val){
            $return['imgs'][$k]['url'] = self::getApiUrl().$val;

        }

        return $return;
    }

    protected function getHtml($goods,$spec_arr = array())
    {
        $html = '';

        $imgs = unserialize($goods['imgs']);

        $name = $goods['branchstore_goods_name'] ? $goods['branchstore_goods_name'] : $goods['name'];
        $sale_price = $goods['branchstore_sell_price'] ? $goods['branchstore_sell_price'] : $goods['sell_price'];
        $store_nums = $goods['store_nums'];
        $goods_no = $goods['goods_no'];
        $pro_no = $goods['pro_no'];
        $unit = $goods['unit'];
        $content = $goods['content'];
        $loadimgs = '';

        foreach($imgs as $val){
            $loadimgs .= '<div class="swiper-slide"><img src="'.self::getApiUrl().$val.'" width="300" height="300"></div>';
        }

        $spec_arrs = '';

        foreach($spec_arr as $val){
            $spec_arrs .= '<li>
                        <div class="item-content">
                            <div class="item-inner iteminfo_parameter sys_item_specpara" data-sid="'.$val['id'].'">
                                <div class="item-title" style="width:25%">'.$val['name'].'</div>
                                <div class="item-input">
                                    <ul class="sys_spec_text">
                                        <li data-aid="'.$val['id'].'"><a href="javascript:;" title="'.$val['value'].'">'.$val['value'].'</a><i></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>';
        }

        $html .= str_replace(array('{name}','{imgs}','{sale_price}','{store_nums}','{goods_no}','{pro_no}','{unit}','{content}','{spec_arrs}','{pro_no_hidden}'),array($name,$loadimgs,$sale_price,$store_nums,$goods_no,$pro_no,$unit,$content,$spec_arrs,$pro_no),$this->template);

        return $html;
    }
}