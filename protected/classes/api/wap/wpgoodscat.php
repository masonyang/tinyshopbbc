<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 24/4/16
 * Time: 上午11:30
 *
 * http://192.168.1.102/index.php?con=api&act=index&method=gcat&source=category
 */
class wpgcat extends wapbase
{
    protected $catModel = null;

    public static $title = array(
        'scategory'=>'商品分类（二级）',
        'category'=>'商品分类（一级）'
    );

    public static $lastmodify = array(
        'scategory'=>'2016-6-13',
        'category'=>'2016-6-13',
    );

    public static $notice = array(
        'scategory'=>'',
        'category'=>'',
    );

    public static $requestParams = array(
        'scategory'=>array(
            array(
                'colum'=>'无',
                'required'=>'无',
                'type'=>'无',
                'content'=>'无',
            ),
        ),
        'category'=>array(
            array(
                'colum'=>'无',
                'required'=>'无',
                'type'=>'无',
                'content'=>'无',
            ),
        ),
    );

    public static $responsetParams = array(
        'scategory'=>array(
            array(
                'colum'=>'parent_id',
                'content'=>'一级分类id',
            ),
            array(
                'colum'=>'name',
                'content'=>'分类名称',
            ),
            array(
                'colum'=>'img',
                'content'=>'分类图片',
            ),
            array(
                'colum'=>'sub_cat',
                'content'=>'sub_cat为key情况下,它下面的多维数组就是二级分类内容',
            ),

        ),
        'category'=>array(
            array(
                'colum'=>'id',
                'content'=>'一级分类id',
            ),
            array(
                'colum'=>'name',
                'content'=>'分类名称',
            ),

        ),
    );

    public static $requestUrl = array(
        'scategory'=>'     /index.php?con=api&act=index&method=wpgcat&source=scategory',
        'category'=>'     /index.php?con=api&act=index&method=wpgcat&source=category'
    );

    public function __construct($params = array())
    {
        parent::__construct($params);

        $this->catModel = new Model('goods_category');
    }
    public function index()
    {
        switch($this->params['source']){
            case 'scategory':
                $this->getCatPage();
            break;
            case 'category':
                $this->getCatIndex();
            break;
        }


    }

    //首页导航分类展示
    protected function getCatIndex()
    {
        $catData = $this->catModel->where('parent_id=0')->order('sort desc')->findAll();

        if($catData){
            $_data = array();
            $i = 0;
            foreach($catData as $val){
                $_data[$i]['id'] = $val['id'];
                $_data[$i]['name'] = $val['name'];
                $i++;
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '分类获取成功';
            $this->output($_data);
        }else{
            $this->output['msg'] = '分类获取失败';
            $this->output();
        }

    }

    //全部分类
    protected function getCatPage()
    {

        $catData = $this->catModel->order('path,sort desc')->findAll();

        $goods_category=Common::treeArray($catData);

        $tmpCat = array();

        foreach($goods_category as $val){
            if(!in_array($val['parent_id'],array_keys($tmpCat))){
                if($val['parent_id'] == 0){
                    $tmpCat[$val['id']] = $val;
                }
            }else{
                if($tmpCat[$val['parent_id']]['extends']){
                    $tmpCat[$val['parent_id']]['extends'] = array_merge($tmpCat[$val['parent_id']]['extends'],array($val));
                }else{
                    $tmpCat[$val['parent_id']]['extends'][] = $val;
                }

            }

        }

        if($tmpCat){
            $_data = array();
            $i = 0;
            foreach($tmpCat as $val){
                $_data[$i]['parent_id'] = $val['id'];
                $_data[$i]['name'] = $val['name'];
                $_data[$i]['img'] = $val['img'];
                foreach($val['extends'] as $kk=> $eval){
                    $_data[$i]['sub_cat'][$kk]['id'] = $eval['id'];
                    $_data[$i]['sub_cat'][$kk]['name'] = $eval['name'];
                    $_data[$i]['sub_cat'][$kk]['img'] = $eval['img'];
                }
                $i++;
            }

            $this->output['status'] = 'succ';
            $this->output['msg'] = '分类获取成功';
            $this->output($_data);
        }else{
            $this->output['msg'] = '分类获取失败';
            $this->output();
        }


    }


    public function scategory_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'分类获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'分类获取成功',
                'data'=>array(
                    array(
                        'parent_id' => 5,
                        'name' => '服饰1',
                        'img' => '',
                        'sub_cat' => array(
                            array(
                                'id' => 6,
                                'name' => '女装',
                                'img' => '',
                            ),
                            array(
                                'id' => 8,
                                'name' => '男式',
                                'img' =>'',
                            ),
                        ),
                    ),
                    array(
                        'parent_id' => 1,
                        'name' => '电脑、手机',
                        'img' => '',
                        'sub_cat' => array(
                            array(
                                'id' => 2,
                                'name' => '手机',
                                'img' => '',
                            ),
                            array(
                                'id' => 3,
                                'name' => '笔记本',
                                'img' =>'',
                            ),
                            array(
                                'id' => 4,
                                'name' => '平板',
                                'img' =>'',
                            ),
                        ),
                    ),
                ),
            )
        );

    }

    public function category_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'分类获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'分类获取成功',
                'data'=>array(
                    array(
                        'id' => 5,
                        'name' => '服饰1',
                    ),
                    array(
                        'parent_id' => 1,
                        'name' => '电脑、手机',
                    ),
                ),
            )
        );

    }

}