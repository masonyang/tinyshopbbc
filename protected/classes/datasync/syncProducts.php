<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 27/2/16
 * Time: 下午5:37
 */
//同步货品
class syncProducts extends DataSync
{
    protected $syncType = 'products';

    public function sync()
    {
        if($this->syncDirect == 'headtobranch'){
            $distrInfo = $this->getDistrInfos();
            if($distrInfo['distrInfos']){
                foreach($distrInfo['distrInfos'] as $k=>$val){
                    $this->vaildParams();
                    $this->params['distributor_id'] = $val['distributor_id'];
                    parent::sync();
                }
            }
        }
    }

    protected function getDistrInfos()
    {
        //总店同步到分店
        //获取分销商列表
        $distrObj = new Model('distributor');
        $distrInfos = $distrObj->fields('catids,distributor_id')->where('disabled = 0')->findAll();
        //根据 分销商id 获取授权 cat_ids
        //根据cat_id 去找 goods_id 或者 product_id
        return array('distrInfos'=>$distrInfos);
    }

} 