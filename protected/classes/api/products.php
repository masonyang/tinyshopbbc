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

    protected $template = '<div data-pagination=".swiper-pagination" data-loop="true" class="swiper-container swiper-init ks-demo-slider">
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
                                    ￥{sale_price}
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">库存</div>
                                <div class="item-input">
                                    {store_nums}
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
                                    {pro_no}
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
                    <li>
                        <div class="item-content">
                            <div class="item-inner iteminfo_parameter sys_item_specpara" data-sid="1">
                                <div class="item-title" style="width:25%">颜色</div>
                                <div class="item-input">
                                    <ul class="sys_spec_img">
                                        <li data-aid="3"><a href="javascript:;" title="白色"><img src="img/1.png" alt="白色" /></a><i></i></li>
                                        <li data-aid="4"><a href="javascript:;" title="粉色"><img src="img/2.png" alt="粉色" /></a><i></i></li>
                                        <li data-aid="8"><a href="javascript:;" title="蓝色"><img src="img/3.png" alt="蓝色" /></a><i></i></li>
                                        <li data-aid="9"><a href="javascript:;" title="绿色"><img src="img/4.png" alt="绿色" /></a><i></i></li>
                                        <li data-aid="10"><a href="javascript:;" title="黄色"><img src="img/5.png" alt="黄色" /></a><i></i></li>
                                        <li data-aid="12"><a href="javascript:;" title="灰色"><img src="img/6.png" alt="灰色" /></a><i></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="item-content">
                            <div class="item-inner iteminfo_parameter sys_item_specpara" data-sid="2">
                                <div class="item-title" style="width:25%">尺码</div>
                                <div class="item-input">
                                    <ul class="sys_spec_text">
                                        <li data-aid="13"><a href="javascript:;" title="S">S</a><i></i></li>
                                        <li data-aid="14"><a href="javascript:;" title="M">M</a><i></i></li>
                                        <li data-aid="16"><a href="javascript:;" title="L">L</a><i></i></li>
                                        <li data-aid="17"><a href="javascript:;" title="XL">XL</a><i></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">数量</div>
                                <div class="item-input spinner">
                                    <button class="spinner_decr decrease">-</button><input type="text" size="1" value="0" class="spinnerExample spinner_text"><button class="increase spinner_incr">+</button>
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

        $goods = $this->goodsModel->fields('id,name,branchstore_goods_name,goods_no,pro_no,sell_price,branchstore_sell_price,imgs,unit,content,store_nums')->where('id='.$id)->find();
        $this->getHtml($goods);
    }

    protected function getHtml($goods,$products = array())
    {
        $html = '';

        $imgs = unserialize($goods['imgs']);

        $name = $goods['name'];
        $sale_price = $goods['sell_price'];
        $store_nums = $goods['store_nums'];
        $goods_no = $goods['goods_no'];
        $pro_no = $goods['pro_no'];
        $unit = $goods['unit'];
        $content = $goods['content'];
        $loadimgs = '';

        foreach($imgs as $val){
            $loadimgs .= '<div class="swiper-slide"><img src="http://192.168.1.101/'.$val.'" width="300" height="300"></div>';
        }

        $html .= str_replace(array('{name}','{imgs}','{sale_price}','{store_nums}','{goods_no}','{pro_no}','{unit}','{content}'),array($name,$loadimgs,$sale_price,$store_nums,$goods_no,$pro_no,$unit,$content),$this->template);

        echo $html;
    }
}