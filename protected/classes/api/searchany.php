<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 30/4/16
 * Time: 下午10:34
 */
class searchany extends baseapi
{

    protected $goodsModel = null;

    protected $template = '
            <div class="card ks-facebook-card">
                <a href="product.html?id={id}" class="item-link">
                    <div class="card-content"> <img src="{img}" width="100%"/></div>
                </a>
                <a href="product.html?id={id}" class="item-link">
                <div class="card-header no-border">
                    <div class="ks-facebook-name">{name}</div>
                    <div class="ks-facebook-date">销售价: {price}</div>
                </div>
                </a>

            </div>
            ';
//<div class="card-footer no-border"><a href="#" class="link">Like</a><a href="#" class="link">Comment</a><a href="#" class="link">Share</a></div>
    public function __construct($params = array())
    {
        parent::__construct($params);

        $this->goodsModel = new Model('goods');
    }

    public function index()
    {
        switch($this->params['source']){
            case 'goods':
                $this->searchGoods();
            break;
        }
    }

    public function searchGoods()
    {
        $name = $this->params['searchany'];

        $goodsLists = $this->goodsModel->fields('id,name,branchstore_goods_name,goods_no,img,sell_price,branchstore_sell_price')->where('(name like "'.$name.'%" or branchstore_goods_name like "'.$name.'%") and is_online=0')->findAll();

        $this->getHtml($goodsLists);

    }

    protected function getHtml($goods)
    {

        $html = '';
        if($goods){
            foreach($goods as $val){
                $price = ($val['branchstore_sell_price']) ? $val['branchstore_sell_price'] : $val['sell_price'];
                $name = ($val['branchstore_goods_name']) ? $val['branchstore_goods_name'] : $val['name'];
                $img = self::APIURL.$val['img'];
                $id = $val['id'];
                $html .= str_replace(array('{name}','{price}','{img}','{id}'),array($name,$price,$img,$id),$this->template);
            }
        }else{
            $html .='<div class="list-block">
        <ul>
            <li class="item-content">
                <div class="item-inner">
                    <div class="item-title">没有找到商品</div>
                </div>
            </li>
        </ul>
    </div>';
        }


        echo $html;
    }
}