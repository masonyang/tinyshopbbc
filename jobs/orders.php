<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/12/16
 * Time: 下午8:59
 * 订单相关操作job
 */
class ordersJob extends syncdataJob
{
    private $orderType = null;

    private $succ_id = array();

    private $limit = 10;

    private $model = null;

    private $data = array();

    private $syncMapper = array(
        'ordercancel'=>'orders_ordercancelJob',
    );

    protected function __before()
    {
        parent::__before();
        $this->orderType = $this->getOption('ordertype');

        $this->data = $this->getList();
    }

    protected function __run()
    {

        switch($this->orderType){
            case 'ordercancel':
                $type = $this->syncMapper['ordercancel'];
                $result = $type::run($this->data);

                break;
        }
    }

    private function getList()
    {
        $distrModel = new Model('distributor','zd','master');
        return $distrModel->findAll();
    }

}
