<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 29/4/16
 * Time: 下午1:53
 */
class arealist extends baseapi
{

    public function __construct($params = array())
    {
        parent::__construct($params);

    }

    public function index()
    {

        $result = $this->areas();

        echo ($result);
    }

    private function areas()
    {
        $cache = CacheFactory::getInstance();
        $items = $cache->get("_AreaData");
        $items = null;
        if($items == null)
        {
            $items = JSON::encode($this->_AreaInit(0));
            $cache->set("_AreaData",$items,315360000);
        }
        return $items;
    }

    private function _AreaInit($id, $level = '0') {
        $model = new Model('area','zd','salve');
        $result = $model->where("parent_id=".$id)->order('sort')->findAll();
        $list = array();
        if($result) {

            foreach($result as $value) {
                $id = "o_".$value['id'];
                $list["$id"]['t'] = $value['name'];
                if($level<2)$list[$id]['c'] = $this->_AreaInit($value['id'], $level + 1);
            }
        }
        return $list;
    }
}