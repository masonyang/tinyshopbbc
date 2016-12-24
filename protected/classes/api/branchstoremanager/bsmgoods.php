<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 17/12/16
 * Time: 上午11:49
 * 商品相关操作
 */
class bsmgoods extends basmbase
{

    public static $title = array(
        'goodssearch'=>'商品搜索',
        'goodslist'=>'商品列表 ok',
        'goodsdetail'=>'商品详情/编辑 ok',
        'goodssave'=>'商品保存 ok',
    );

    public static $lastmodify = array(
        'goodssearch'=>'2016-12-17',
        'goodslist'=>'2016-12-17',
        'goodsdetail'=>'2016-12-17',
        'goodssave'=>'2016-12-17',
    );

    public static $notice = array(
        'goodssearch'=>'',
        'goodslist'=>'',
        'goodsdetail'=>'',
        'goodssave'=>'',
    );

    public static $requestParams = array(
        'goodssearch'=>array(

        ),
        'goodslist'=>array(
            array(
                'colum'=>'type',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'搜索类型:name - 按用户名搜索',
            ),
            array(
                'colum'=>'filter',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'搜索内容',
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
        'goodsdetail'=>array(
            array(
                'colum'=>'gid',
                'required'=>'必须',
                'type'=>'int',
                'content'=>'商品id',
            ),
        ),
        'goodssave'=>array(
            array(
                'colum'=>'gid',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'商品id',
            ),
            array(
                'colum'=>'name',
                'required'=>'必须',
                'type'=>'string',
                'content'=>'自定义商品名称',
            ),
            array(
                'colum'=>'products',
                'required'=>'必须',
                'type'=>'array',
                'content'=>'货品集合',
            ),
        ),
    );

    public static $responsetParams = array(
        'goodssearch'=>array(
            array(
                'colum'=>'name',
                'content'=>'姓名',
            ),
        ),
        'goodslist'=>array(
            array(
                'colum'=>'gid',
                'content'=>'商品id',
            ),
            array(
                'colum'=>'img',
                'content'=>'图片',
            ),
            array(
                'colum'=>'name',
                'content'=>'商品名称',
            ),
            array(
                'colum'=>'branchstore_goods_name',
                'content'=>'自定义商品名称',
            ),
            array(
                'colum'=>'sell_price',
                'content'=>'零售价',
            ),
            array(
                'colum'=>'branchstore_sell_price',
                'content'=>'自定义零售价',
            ),
            array(
                'colum'=>'goods_store',
                'content'=>'库存',
            ),
            array(
                'colum'=>'count',
                'content'=>'当传入iscount时，返回count。',
            ),
        ),
        'goodsdetail'=>array(
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
                'colum'=>'sys_attrprice',
                'content'=>'多规格商品存放',
            ),
        ),
        'goodssave'=>array(
            array(
                'colum'=>'gid',
                'content'=>'商品id',
            ),
        ),
    );

    public static $requestUrl = array(
        'goodssearch'=>'     /index.php?con=api&act=index&method=bsmgoods&source=goodssearch',
        'goodslist'=>'     /index.php?con=api&act=index&method=bsmgoods&source=goodslist',
        'goodsdetail'=>'     /index.php?con=api&act=index&method=bsmgoods&source=goodsdetail',
        'goodssave'=>'     /index.php?con=api&act=index&method=bsmgoods&source=goodssave'
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {

        switch($this->params['source']){
            case 'goodslist':
                $this->goodslist();
            break;
            case 'goodsdetail':
                $this->goodsdetail();
            break;
            case 'goodssave':
                $this->goodssave();
            break;
        }

    }

    private function goodslist()
    {
        $goodsModel = new Model('goods',$this->domain,'salve');

        $filter = $this->__filter();

        $goodsModel->fields('id,img,name,branchstore_goods_name,sell_price,branchstore_sell_price,store_nums')->where($filter['where']);

        $goodsModel->limit($filter['limit']);

        $goodsLists = $goodsModel->findAll();

        $count = false;

        if(isset($this->params['iscount']) && ($this->params['iscount'] == true)){
            $count = $goodsModel->where($filter['where'])->count();
        }

        if($goodsLists){
            $_data = array();

            if($count){
                $_data['count'] = $count;
            }

            $i = 0;
            foreach($goodsLists as $val){

                $val['img'] = self::getApiUrl().$val['img'];

                $_data['goods'][$i]['img'] = $val['img'];
                $_data['goods'][$i]['gid'] = $val['id'];
                $_data['goods'][$i]['name'] = $val['name'];
                $_data['goods'][$i]['branchstore_goods_name'] = $val['branchstore_goods_name'];
                $_data['goods'][$i]['sell_price'] = $val['sell_price'];
                $_data['goods'][$i]['branchstore_sell_price'] = $val['branchstore_sell_price'];
                $_data['goods'][$i]['goods_store'] = $val['store_nums'];
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

    private function __filter()
    {
        $return = array(
            'type'=>'',
            'order'=>'',
            'where'=>'',
            'limit'=>'',
        );

        $type = $this->params['type'];

        if($type == 'name'){ // 根据 关键字

            $return['type'] = 'search';

            $return['where'] = $this->params['filter'] ? '(name like "%'.Filter::sql($this->params['filter']).'%" or branchstore_goods_name like "%'.Filter::sql($this->params['filter']).'%")' : '';

        }

        $this->params['offset'] = ($this->params['offset'] == 0) ? 1 : $this->params['offset'];

        $offset = ($this->params['offset'] - 1) * $this->params['limit'];

        $return['limit'] = $offset.','.$this->params['limit'];

        return $return;
    }

    private function goodsdetail()
    {
        $id = intval($this->params['id']);


        $goodsModel = new Model('goods',$this->domain,'salve');

        $productsModel = new Model('products',$this->domain,'salve');

        $data = array();
        $goods = $goodsModel->fields('id,name,branchstore_goods_name,goods_no,pro_no,sell_price,branchstore_sell_price,store_nums,freeze_nums')->where('id='.$id)->find();


        if($goods){
            $products = $productsModel->where('goods_id='.$id)->findAll();

            $sys_attrprice = array();
            $spec_arr = array();

            $freeze = 0;

            foreach($products as $pk=>$val){
                if($val['spec']){
                    $spec = unserialize($val['spec']);
                    if($spec){
                        foreach($spec as $k=>$sval){
                            $spec_arr[$k]['name'] = $sval['name'];
                            $spec_arr[$k]['id'] = $sval['id'];
                            $spec_arr[$k]['value'] = $sval['value'][2];

                            $freeze_nums = $val['store_nums'] - $val['freeze_nums'];

                            $sys_attrprice[$pk] = array(
                                'branchstore_sell_price'=>$val['branchstore_sell_price'],
                                'price'=>$val['sell_price'],
                                'pro_no'=>$val['pro_no'],
                                'store_num'=>$freeze_nums,
                                'name'=>$sval['name'],
                                'value'=>$sval['value'][2],
                            );

                        }

                        $freeze += $val['freeze_nums'];

                    }else{
                        $freeze += $val['freeze_nums'];
                    }

                }
            }

            if($freeze){
                $goods['store_nums'] = $goods['store_nums'] - $freeze;
                $goods['store_nums'] = ($goods['store_nums'] > 0) ? $goods['store_nums'] : 0;
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '商品详情获取成功';
            $data = $this->geJson($goods,$spec_arr,$sys_attrprice);

            $this->output($data);
        }else{
            $this->output['msg'] = '商品详情获取失败';
            $this->output();
        }
    }

    protected function geJson($goods,$spec_arr = array(),$sys_attrprice = array())
    {
        $return = array();

        $return['gid'] = $goods['id'];
        $return['branchstore_goods_name'] = $goods['branchstore_goods_name'];
        $return['name'] = $goods['name'];
        $return['branchstore_sell_price'] = $goods['branchstore_sell_price'];
        $return['price'] = $goods['sell_price'];
        $return['goods_no'] = $goods['goods_no'];
        $return['pro_no'] = $goods['pro_no'];


        $i = 0;
        $store_nums = 0;
        $attrprice = array();
        $productsModel = new Model('products',$this->domain,'salve');

        foreach($sys_attrprice as $val){

            $product = $productsModel->where('pro_no="'.$val['pro_no'].'"')->find();

            $val['pid'] = $product['id'];

            $val['store_nums'] = $product['store_nums'] - $product['freeze_nums'];

            $store_nums += $val['store_nums'];

            $attrprice[$i] = $val;
            $i++;
        }

        $return['sys_attrprice'] = $attrprice;

        $return['store_nums'] = ($store_nums > 0) ? $store_nums : $goods['store_nums'];


        return $return;
    }

    private function goodssave()
    {
        if(empty($this->params['gid'])){
            $this->output['msg'] = '参数不正确';
            $this->output();
            exit;
        }

        $productsModel = new Model('products',$this->domain,'salve');

        $g_branchstore_sell_price = array();

        foreach($this->params['products'] as $val){

            $pdata = array(
                'branchstore_sell_price'=>$val['price'],
            );

            $g_branchstore_sell_price[] = $val['price'];

            $productsModel->data($pdata)->where('id = '.$val['id'])->update();
        }

        $goodsModel = new Model('goods',$this->domain,'salve');

        $gdata = array(
            'branchstore_goods_name'=>$this->params['name'],
            'branchstore_sell_price'=>min($g_branchstore_sell_price),
        );

        $goodsModel->data($gdata)->where('id = '.$this->params['gid'])->update();

        $this->output['status'] = 'succ';
        $this->output['msg'] = '保存成功';

        $data['gid'] = $this->params['gid'];

        $this->output($data);
    }

    public function goodssearch_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'code'=>self::CODE_FAIL,
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'code'=>self::CODE_SUCC,
                'msg'=>'获取成功',
                'data'=>array(
                    array(
                        'name'=>'测试分销商',
                    ),
                ),
            )
        );

    }

    public function goodslist_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'code'=>self::CODE_FAIL,
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'code'=>self::CODE_SUCC,
                'msg'=>'商品列表获取成功',
                'data'=>array(
                    'count'=>10,
                    'goods'=>array(
                        array(
                            'gid'=>'商品id',
                            'img'=>'图片',
                            'name'=>'商品名称',
                            'branchstore_goods_name'=>'自定义商品名称',
                            'sell_price'=>'零售价',
                            'branchstore_sell_price'=>'自定义零售价',
                            'goods_store'=>'库存',
                        ),
                    ),
                ),
            )
        );

    }

    public function goodsdetail_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'code'=>self::CODE_FAIL,
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'code'=>self::CODE_SUCC,
                'msg'=>'商品详情获取成功',
                'data'=>array(
                    'gid' => '1',
                    'name' => '哈哈',
                    'price' => '0.00',
                    'store_nums' => 1,
                    'goods_no' => '111111',
                    'pro_no' => '111111_1',
                    'sys_attrprice' => array(
                        '34(规格id)' =>array(
                            'pid' => '1（货品id）',
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

    public function goodssave_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'code'=>self::CODE_FAIL,
                'msg'=>'数据不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'code'=>self::CODE_SUCC,
                'msg'=>'保存成功',
                'data'=>array(
                    'gid'=>'1',
                ),
            )
        );

    }

}