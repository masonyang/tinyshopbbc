<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 26/4/16
 * Time: 下午7:42
 */
class goods extends baseapi
{
    protected $goodsModel = null;

    protected $template = '<div class="card ks-facebook-card">
                <a href="product.html?id={id}" class="item-link">
                <div class="card-header no-border">
                    <div class="ks-facebook-name">{name}</div>
                    <div class="ks-facebook-date">{price}</div>
                </div>
                </a>
                <a href="product.html?id={id}" class="item-link">
                    <div class="card-content"> {img}></div>
                </a>
                <div class="card-footer no-border"><a href="#" class="link">Like</a><a href="#" class="link">Comment</a><a href="#" class="link">Share</a></div>
            </div>';
//<div class="card-content"> <img src="http://a.qqcapp.com/{img}" width="100%"/></div>
    public function __construct($params = array())
    {
        parent::__construct($params);

        $this->goodsModel = new Model('goods');
    }
    public function index()
    {
        $goodsLists = $this->goodsModel->fields('id,name,branchstore_goods_name,goods_no,img,sell_price,branchstore_sell_price')->where('is_online=0')->findAll();

        $this->getHtml($goodsLists);
    }

    protected function getHtml($goods)
    {

        $html = '';

        foreach($goods as $val){
            $price = $val['price'];
            $name = $val['name'];
            $img = $val['img'];
            $id = $val['id'];
            $html .= str_replace(array('{name}','{price}','{img}','{id}'),array($name,$price,$img,$id),$this->template);
        }

        echo $html;
    }

}