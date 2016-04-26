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

    public function __construct($params = array())
    {
        parent::__construct($params);

        //$this->catModel = new Model('goods_category');
    }
    public function index()
    {
        $output = array(
            array('tel'=>'1111','email'=>'ss@sdf.com'),
        );
        $this->output($output);
    }

}