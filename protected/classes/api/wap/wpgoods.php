<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 26/4/16
 * Time: 下午7:42
 */
class wpgoods extends baseapi
{


    protected $goodsModel = null;

    public static $title = array(
        'goods'=>'商品列表'
    );

    public static $lastmodify = array(
        'goods'=>'2016-6-13',
    );

    public static $notice = array(
        'goods'=>'',
    );

    public static $requestParams = array(
        'goods'=>array(
            array(
                'colum'=>'type',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'搜索类型:1-按商品分类id搜索   2-按关键字搜索 现在是根据商品名进行搜索',
            ),
            array(
                'colum'=>'filter',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'筛选条件:1-按id搜索   2-按关键字搜索,',
            ),
            array(
                'colum'=>'order',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'排序方式:1-最新  2-价格  3-销量  4-人气',
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
                'content'=>'每次请求加载的数据条数',
            ),
            array(
                'colum'=>'offset',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'上一页最后一条数据的索引',
            ),
            array(
                'colum'=>'iscount',
                'required'=>'可选',
                'type'=>'boolean',
                'content'=>'是否返回总数',
            ),
        ),
    );

    public static $responsetParams = array(
        'goods'=>array(
            array(
                'colum'=>'count',
                'content'=>'当传入iscount时，返回count。',
            ),
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
        'goods'=>'     /index.php?con=api&act=index&method=wpgoods'
    );


    protected $imageSize = array(
        'width'=>'280',
        'height'=>'280',
    );


    public function __construct($params = array())
    {
        parent::__construct($params);

        $this->goodsModel = new Model('goods');
    }
    public function index()
    {

        $filter = $this->__filter();

        $this->goodsModel->fields('id,name,branchstore_goods_name,goods_no,img,sell_price,branchstore_sell_price')->where('is_online=0 '.$filter['where']);

        $this->goodsModel->limit($filter['limit']);

        if($filter['order']){
            $this->goodsModel->order($filter['order']);
        }

        $goodsLists = $this->goodsModel->findAll();

        $count = false;

        if(isset($this->params['iscount']) && ($this->params['iscount'] == true)){
            $count = $this->goodsModel->where('is_online=0 '.$filter['where'])->count();
        }

        if($goodsLists){
            $_data = array();

            if($count){
                $_data['count'] = $count;
            }

            $i = 0;
            foreach($goodsLists as $val){

                if(($val['branchstore_sell_price'] == '0.00') || ($val['branchstore_sell_price'] == '0') || ($val['branchstore_sell_price'] == '')){
                    $price = $val['sell_price'];
                }else{
                    $price = $val['branchstore_sell_price'];
                }

                $name = ($val['branchstore_goods_name']) ? $val['branchstore_goods_name'] : $val['name'];

                $filename = self::getApiUrl().$val['img'];

                $image = ImageClipper::getInstance()->getImage($filename,$this->imageSize['width'],$this->imageSize['height']);

                $_data['goods'][$i]['gid'] = $val['id'];
                $_data['goods'][$i]['name'] = $name;
                $_data['goods'][$i]['img'] = $image['src'];
                $_data['goods'][$i]['price'] = $price;
                $i++;
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '商品列表获取成功';
            $this->output($_data);
        }else{
            $this->output['status'] = 'succ';
            $this->output['msg'] = '暂无商品';
            $this->output();
        }

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
                    'count'=>10,
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


    protected function __filter($params = array())
    {

        $return = array(
            'type'=>'',
            'order'=>'',
            'where'=>'',
            'limit'=>'',
        );

        $type = $this->params['type'];

        if($type == 1){ //根据 商品分类

            $order = 'create_time desc';

            $return['type'] = 'categroy';

            if(!isset($this->params['sort']) || !in_array($this->params['sort'],array('desc','asc'))){
                $this->params['sort'] = 'desc';
            }

            switch($this->params['order']){
                case 0:
                case 1: //最新
                    $order = 'create_time '.$this->params['sort'];
                break;
                case 2://价格
                    $order = 'sell_price '.$this->params['sort'].',branchstore_sell_price '.$this->params['sort'];
                break;
//                case 3://销量
//                    $order = ''; //todo 暂无
//                break;
//                case 4:
//                    $order = '';//todo 暂无
//                break;

            }

            $return['order'] = $order;

            if($this->params['filter'] == 0){
                $return['where'] = '';
            }else{

                if($this->params['filter']){

                    $cat_ids = array();

                    $goodsCategoryModel = new Model('goods_category');

                    $cate = $goodsCategoryModel->fields('id')->where("`parent_id`!=0 and path like '%,".$this->params['filter'].",%'")->findAll();

                    if($cate){

                        foreach($cate as $val){
                            $cat_ids[] = $val['id'];
                        }

                        $return['where'] = 'and category_id in ('.implode(',',$cat_ids).')';
                    }else{
                        $return['where'] = 'and category_id = '.$this->params['filter'];
                    }

                }else{
                    $return['where'] = '';
                }



            }


        }elseif($type == 2){ // 根据 关键字

            $return['type'] = 'search';

            $return['where'] = $this->params['filter'] ? 'and (name like "%'.Filter::sql($this->params['filter']).'%" or branchstore_goods_name like "%'.Filter::sql($this->params['filter']).'%")' : '';

        }

        $this->params['offset'] = ($this->params['offset'] == 0) ? 1 : $this->params['offset'];

        $offset = ($this->params['offset'] - 1) * $this->params['limit'];

        $return['limit'] = $offset.','.$this->params['limit'];

        return $return;
    }


}