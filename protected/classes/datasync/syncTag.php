<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 28/2/16
 * Time: 上午12:28
 */
//同步标签
class syncTag extends DataSync
{
    protected $syncType = 'tag';

    protected $vailRule = array(
        'id'=>'',
        'name'=>'',
        'num'=>'',
        'sort'=>'',
        'is_hot'=>'',
    );

    public function sync()
    {
        if($this->syncDirect == 'hb'){
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