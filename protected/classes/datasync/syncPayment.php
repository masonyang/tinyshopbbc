<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 27/2/16
 * Time: 下午5:37
 */

//同步支付方式
class syncPayment extends DataSync
{
    protected $syncType = 'payment';

    protected $vailRule = array(
        'id'=>'',
        'plugin_id'=>'',
        'pay_name'=>'',
        'config'=>'',
        'client_type'=>'',
        'description'=>'',
        'note'=>'',
        'pay_fee'=>'',
        'fee_type'=>'',
        'sort'=>'',
        'status'=>'',
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
        $distrInfos = $distrObj->fields('distributor_id')->where('disabled = 0')->findAll();
        //根据 分销商id 获取授权 cat_ids
        //根据cat_id 去找 goods_id 或者 product_id
        return array('distrInfos'=>$distrInfos);
    }

} 