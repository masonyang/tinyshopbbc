<?php
class syncdata_categoryJob extends billJob
{
    //总店信息同步到分店
    protected function hbDeal()
    {

        $op_type = $this->params['action'];

        $params = $this->params['content'];

        $where = 'id='.$params['id'];

        $db = $this->params['domain'];

        unset($params['id']);

        unset($params['distributor_id']);

        unset($params['con'],$params['act']);

        headToBranchJob::getInstance()->deal($op_type,$params,$where,'goods_category',$db);

        $this->return_msg['res'] = 'success';

        return $this->return_msg;

    }
}
