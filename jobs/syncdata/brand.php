<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 28/2/16
 * Time: 下午2:36
 */
class syncdata_brandJob extends billJob
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

        headToBranchJob::getInstance()->deal($op_type,$params,$where,'brand',$db);

        $this->return_msg['res'] = 'success';

        return $this->return_msg;

    }
} 