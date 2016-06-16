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

    public static $title = array(
        'goods'=>'商品列表'
    );

    public static $lastmodify = array(
        'goods'=>'2016-6-13',
    );

    public static $requestParams = array(
        'goods'=>array(
            array(
                'colum'=>'无',
                'required'=>'无',
                'type'=>'无',
                'content'=>'无',
            ),
        ),
    );

    public static $responsetParams = array(
        'goods'=>array(
            array(
                'colum'=>'id',
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
        'advert'=>'     /index.php?con=api&act=index&method=goods'
    );

    protected $template = '<div class="card ks-facebook-card">
                <a href="product.html?id={id}" class="item-link">
                    <div class="card-content"> <img src="{img}" width="100%"/></div>
                </a>
                <a href="product.html?id={id}" class="item-link">
                <div class="card-header no-border">
                    <div class="ks-facebook-name">{name}</div>
                    <div class="ks-facebook-date">{price}</div>
                </div>
                </a>
            </div>';
//<div class="card-content"> <img src="http://a.qqcapp.com/{img}" width="100%"/></div>
//<div class="card-footer no-border"><a href="#" class="link">Like</a><a href="#" class="link">Comment</a><a href="#" class="link">Share</a></div>
    public function __construct($params = array())
    {
        parent::__construct($params);

        $this->goodsModel = new Model('goods');
    }
    public function index()
    {
        $goodsLists = $this->goodsModel->fields('id,name,branchstore_goods_name,goods_no,img,sell_price,branchstore_sell_price')->where('is_online=0')->findAll();

        if($goodsLists){
            $_data = array();
            $i = 0;
            foreach($goodsLists as $val){
                $price = ($val['branchstore_sell_price']) ? $val['branchstore_sell_price'] : $val['sell_price'];
                $name = ($val['branchstore_goods_name']) ? $val['branchstore_goods_name'] : $val['name'];
                $img = self::getApiUrl().$val['img'];
                $_data[$i]['parent_id'] = $val['id'];
                $_data[$i]['name'] = $name;
                $_data[$i]['img'] = $img;
                $_data[$i]['price'] = $price;
                $i++;
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '商品列表获取成功';
            $this->output($_data);
        }else{
            $this->output['msg'] = '商品列表获取失败';
            $this->output();
        }

//        $this->getHtml($goodsLists);
    }

    public function goods_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'商品列表获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'商品列表获取成功',
                'data'=>array(
                    array(
                        'id' => 5,
                        'name' => 'KAPA服饰',
                        'price'=>1000.00,
                        'img'=>'',
                    ),
                    array(
                        'id' => 1,
                        'name' => 'MacBook电脑',
                        'price'=>100000.00,
                        'img'=>'',
                    ),
                ),
            )
        );
//        '{"status":"succ","msg":"\u83b7\u53d6\u6210\u529f","data":[{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/b5cf5e20eda87a3ff77e4a2d33828947.jpg"},{"img_path":"http:\/\/a.tinyshop.com\/data\/uploads\/2014\/05\/13\/9670df531a008c75e7bed5b8967efd66.gif"}]}';
    }

    protected function getHtml($goods)
    {

        $html = '';

        foreach($goods as $val){
            $price = ($val['branchstore_sell_price']) ? $val['branchstore_sell_price'] : $val['sell_price'];
            $name = ($val['branchstore_goods_name']) ? $val['branchstore_goods_name'] : $val['name'];
            $img = self::getApiUrl().$val['img'];
            $id = $val['id'];
            $html .= str_replace(array('{name}','{price}','{img}','{id}'),array($name,$price,$img,$id),$this->template);
        }

        echo $html;
    }

}