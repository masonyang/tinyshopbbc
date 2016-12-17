<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 28/2/16
 * Time: 下午2:38
 */
class syncdata_goodsspecJob extends billJob
{
    //总店信息同步到分店
    protected function hbDeal()
    {

        $op_type = $this->params['action'];

        $params = $this->params['content'];

        if($params['syncdata_type'] != 'goods_spec'){

            $this->return_msg['reason'] = '同步数据类型不对';

            return $this->return_msg;
        }

        $where = 'id in ('.$params['id'].')';

        $db = $this->params['domain'];

        unset($params['id']);

        unset($params['distributor_id']);

        unset($params['con'],$params['act']);

        $this->dealSpecValue($op_type,$params['spec_value'],$params['spec_value'],$db);

        unset($params['spec_value']);

        headToBranchJob::getInstance()->deal($op_type,$params,$where,'goods_spec',$db);

        $this->return_msg['res'] = 'success';

        return $this->return_msg;

    }

    private function dealSpecValue($op_type,$params,$where,$db)
    {
        $_params = array();

        if($op_type == 'del'){
            $_where = 'spec_id in ('.$where.')';
            headToBranchJob::getInstance()->deal('del',$_params,$_where,'spec_value',$db);
        }else{
            if($where['add']){
                foreach($where['add'] as $val){
                    if(empty($val)){
                        continue;
                    }
                    headToBranchJob::getInstance()->deal('add',$val,array(),'spec_value',$db);
                }
            }

            if($where['update']){
                foreach($where['update'] as $w =>$val){
                    if(empty($val)){
                        continue;
                    }
                    $w = 'id='.$w;
                    headToBranchJob::getInstance()->deal('update',$val,$w,'spec_value',$db);
                }
            }

            if($where['del']){
                foreach($where['del'] as $val){
                    if(empty($val)){
                        continue;
                    }
                    headToBranchJob::getInstance()->deal('del',array(),$val,'spec_value',$db);
                }
            }

        }
    }

} 