<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 28/2/16
 * Time: 下午2:37
 * 商品类型修改的时候 会同步 商品属性和属性值
 */
class syncdata_goodstypeJob extends billJob
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

        $this->dealAttrValue($op_type,$params['attr_value'],$params['attr_value'],$db);
        unset($params['attr_value']);

        $this->dealGoodsAttr($op_type,$params['goods_attr'],$params['goods_attr'],$db);
        unset($params['goods_attr']);

        headToBranchJob::getInstance()->deal($op_type,$params,$where,'goods_type',$db);

        $this->return_msg['res'] = 'success';

        return $this->return_msg;

    }

    private function dealAttrValue($op_type,$params,$where,$db)
    {
        $_params = array();

        if($op_type == 'del'){
            $_where = 'id in ('.$where['id'].')';
            headToBranchJob::getInstance()->deal($op_type,$_params,$_where,'attr_value',$db);
        }else{
            if($where['add']){
                foreach($where['add'] as $val){
                    if(empty($val)){
                        continue;
                    }
                    headToBranchJob::getInstance()->deal('add',$val,array(),'attr_value',$db);
                }
            }

            if($where['update']){
                foreach($where['update'] as $w =>$val){
                    if(empty($val)){
                        continue;
                    }
                    $w = 'id='.$w;
                    headToBranchJob::getInstance()->deal('update',$val,$w,'attr_value',$db);
                }
            }

            if($where['del']){
                foreach($where['del'] as $val){
                    if(empty($val)){
                        continue;
                    }
                    if(isset($val['value_ids'])){
                        if($val['value_ids'] == ''){
                            $_where = $val['use_no_value_ids_sql'];
                        }else{
                            $_where = $val['use_value_ids_sql'];
                        }

                    }else{
                        $_where = $val;
                    }
                    headToBranchJob::getInstance()->deal('del',array(),$_where,'attr_value',$db);
                }
            }

        }


    }

    private function dealGoodsAttr($op_type,$params,$where,$db)
    {
        $_params = array();

        if($op_type == 'del'){
            $_where = 'type_id in ('.$where['type_id'].')';
            headToBranchJob::getInstance()->deal('del',$_params,$_where,'goods_attr',$db);
        }else{
            if($where['add']){
                foreach($where['add'] as $val){
                    if(empty($val)){
                        continue;
                    }
                    headToBranchJob::getInstance()->deal('add',$val,array(),'goods_attr',$db);
                }
            }

            if($where['update']){
                foreach($where['update'] as $w =>$val){
                    if(empty($val)){
                        continue;
                    }
                    $w = 'id='.$w;
                    headToBranchJob::getInstance()->deal('update',$val,$w,'goods_attr',$db);
                }
            }

            if($where['del']){
                foreach($where['del'] as $val){
                    if(empty($val)){
                        continue;
                    }
                    headToBranchJob::getInstance()->deal('del',array(),$val,'goods_attr',$db);
                }
            }

        }


    }

} 