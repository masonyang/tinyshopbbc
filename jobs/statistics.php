<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/12/16
 * Time: 下午4:20
 * 报表统计 job
 *
 * php /jobs/job.php statistics --stattype=goodsrecommend
 *
 */
class statisticsJob extends syncdataJob
{
    private $statType = null;

    private $succ_id = array();

    private $limit = 10;

    private $model = null;

    private $data = array();

    private $syncMapper = array(
        'ordercount'=>'statistics_ordercountJob',//分店订单量统计
        'goodsrecommend'=>'statistics_goodsrecommendJob',//分店商品销售量统计
    );

    protected function __before()
    {
        parent::__before();
        $this->statType = $this->getOption('stattype');

        $this->data = $this->getList();
    }

    protected function __run()
    {

        switch($this->statType){
            case 'ordercount':
                $type = $this->syncMapper['ordercount'];
                $result = $type::run($this->data);

                break;
            case 'goodsrecommend':
                $type = $this->syncMapper['goodsrecommend'];
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