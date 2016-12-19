<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 28/2/16
 * Time: 下午2:36
 */
class syncdata_goodsJob extends billJob
{
    //总店信息同步到分店
    protected function hbDeal()
    {

        $op_type = $this->params['action'];

        $params = $this->params['content'];

        if($params['syncdata_type'] != 'goods'){

            $this->return_msg['reason'] = '同步数据类型不对';

            return $this->return_msg;
        }

        $where = 'id in ('.$params['id'].')';

        $db = $this->params['domain'];

        unset($params['id']);

        unset($params['distributor_id']);

        unset($params['con'],$params['act']);

        if($params['products']){
            $this->dealProducts($op_type,$params['products'],$params['products'],$db);

            unset($params['products']);
        }

        if($params['spec_attr']){
            $this->dealSpecAttr($op_type,$params['spec_attr'],$params['spec_attr'],$db);

            unset($params['spec_attr']);
        }

        unset($params['branchstore_sell_price']);
        unset($params['branchstore_goods_name']);
        headToBranchJob::getInstance()->deal($op_type,$params,$where,'goods',$db);

        $this->return_msg['res'] = 'success';

        return $this->return_msg;

    }

    private function dealProducts($op_type,$params,$where,$db)
    {
        $_params = array();

        if($op_type == 'del'){
            $_where = 'goods_id in ('.$where.')';
            headToBranchJob::getInstance()->deal('del',$_params,$_where,'products',$db);
        }else{
            if($where['add']){
                foreach($where['add'] as $val){
                    if(empty($val)){
                        continue;
                    }
                    headToBranchJob::getInstance()->deal('add',$val,array(),'products',$db);
                }
            }

            if($where['update']){
                foreach($where['update'] as $w =>$val){
                    if(empty($val)){
                        continue;
                    }
                    unset($val['branchstore_sell_price']);
                    headToBranchJob::getInstance()->deal('update',$val,$w,'products',$db);
                }
            }

            if($where['del']){
                headToBranchJob::getInstance()->deal('del',array(),$where['del'],'products',$db);
            }

        }
    }

    private function dealSpecAttr($op_type,$params,$where,$db)
    {
        $_params = array();

        if($op_type == 'del'){
            $_where = 'goods_id in ('.$where.')';
            headToBranchJob::getInstance()->deal('del',$_params,$_where,'spec_attr',$db);
        }else{

            if($where['del']){
                headToBranchJob::getInstance()->deal('del',array(),$where['del'],'spec_attr',$db);
            }

            if($where['add']){
                headToBranchJob::getInstance()->deal('add',$where['add'],array(),'spec_attr',$db);
            }

        }
    }

} 