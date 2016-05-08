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
                    );
                }

            }
        }


//        echo "<pre>";
//        print_r($sys_attrprice);exit;

        if(isset($this->params['gethtml'])){
            echo $this->getHtml($goods,$spec_arr);
        }else{
            $sys_item = array();
            $sys_item['price'] = $goods['branchstore_sell_price'] ? $goods['branchstore_sell_price'] : $goods['sell_price'];
            $sys_item['pro_no'] = $goods['pro_no'];
            $sys_item['store_num'] = $goods['store_nums'];
            $sys_item['sys_attrprice'] = $sys_attrprice;
            $data['sys_item'] = $sys_item;


            $this->output['status'] = 'succ';
            $this->output($data);
        }

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