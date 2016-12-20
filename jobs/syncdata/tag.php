<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 28/2/16
 * Time: 下午2:38
 */
class syncdata_tagJob extends billJob
{
    //总店信息同步到分店
    protected function hbDeal()
    {

        $op_type = $this->params['action'];

        $params = $this->params['content'];

        if($params['syncdata_type'] != 'tags'){

            $this->return_msg['reason'] = '同步数据类型不对';

            return $this->return_msg;
        }

        $where = 'id='.$params['id'];

        $db = $this->params['domain'];

        if($op_type != 'add'){
            unset($params['id']);
        }

        unset($params['syncdata_type']);

        unset($params['distributor_id']);

        headToBranchJob::getInstance()->deal($op_type,$params,$where,'tags',$db);

        $this->return_msg['res'] = 'success';

        return $this->return_msg;

    }
} 