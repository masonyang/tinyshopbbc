<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 24/4/16
 * Time: 上午11:30
 *
 * http://192.168.1.102/index.php?con=api&act=index&method=gcat&source=category
 */
class gcat extends baseapi
{
    protected $catModel = null;

    public function __construct($params = array())
    {
        parent::__construct($params);

        $this->catModel = new Model('goods_category');
    }
    public function index()
    {
        switch($this->params['source']){
            case 'category':
                $this->getCatPage();
            break;
            case 'index':
                $this->getCatIndex();
            break;
        }


    }

    //首页导航分类展示
    protected function getCatIndex()
    {

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

        $this->getHtml($tmpCat);

    }

    private function getHtml($goods_category)
    {

        $html = '';

        foreach($goods_category as $val){
            $html .= '<li class="accordion-item"><a href="#" class="item-link item-content">
                        <div class="item-inner">
                            <div class="item-title">'.$val['name'].'</div>
                        </div></a>
                        <div class="accordion-item-content">
                            <div class="list-block">
                                <ul>';
            foreach($val['extends'] as $eval){
                $html .= '<li>
                                        <div class="item-content">
                                            <div class="item-inner">
                                                <div class="item-title">'.$eval['name'].'</div>
                                            </div>

                                        </div>
                                    </li>';
            }
            $html .= '</ul>
                            </div>
                        </div>
                    </li>';
        }

        echo $html;
    }
}