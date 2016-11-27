<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 26/11/16
 * Time: 下午10:45
 */
class importJob extends syncdataJob
{
    private $importType = null;

    private $succ_id = array();

    private $limit = 10;

    private $model = null;

    private $data = array();

    private $syncMapper = array(
        'goods'=>'importdata_goodsJob',
    );

    protected function __before()
    {
        parent::__before();
        $this->importType = $this->getOption('importtype');

        $this->data = $this->getList();
    }

    protected function __run()
    {

        switch($this->importType){
            case 'goods':
                $type = $this->syncMapper['goods'];
                $result = $type::run($this->data,$this->model);

                break;
        }
    }

    private function getList()
    {
        $this->model = new Model('import_queue','zd','salve');

        return $this->model->where('import_type="'.$this->importType.'" and status="ready"')->find();
    }

}