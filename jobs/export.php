<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 24/11/16
 * Time: 下午10:43
 */
class exportJob extends syncdataJob
{
    private $exportType = null;

    private $succ_id = array();

    private $limit = 10;

    private $model = null;

    private $data = array();

    private $syncMapper = array(
        'goods'=>'exportdata_goodsJob',
    );

    protected function __before()
    {
        parent::__before();
        $this->exportType = $this->getOption('exporttype');

        $this->data = $this->getList();
    }

    protected function __run()
    {

        switch($this->exportType){
            case 'goods':
                $type = $this->syncMapper['goods'];
                $result = $type::run($this->data,$this->model);

                break;
        }
    }

    private function getList()
    {
        $this->model = new Model('export_queue','zd','salve');

        return $this->model->where('export_type="'.$this->exportType.'" and status="ready"')->find();
    }

}