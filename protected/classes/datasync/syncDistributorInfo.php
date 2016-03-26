<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 27/2/16
 * Time: 下午5:35
 */
//同步分销商信息
class syncDistributorInfo extends DataSync
{

    protected $syncType = 'distrInfo';

    protected $vailRule = array(
        'distributor_name'=>'',
        'distributor_password'=>'',
        'distributor_id'=>'',
        'email'=>'',
        'province'=>'',
        'city'=>'',
        'county'=>'',
        'addr'=>'',
        'phone'=>'',
        'mobile'=>'',
        'site_logo'=>'',
        'site_ios_url'=>'',
        'site_android_url'=>'',
        'zip'=>'',
        'site_keyword'=>'',
        'site_description'=>'',
        'deposit'=>'',
        'disabled'=>'',
        'catids'=>'',
    );

    public function sync()
    {
        if($this->syncDirect == 'headtobranch'){
            $this->vaildParams();
            
            //$this->syncRelevantInfo();// 分销商信息变更时候,检查下 他的商品分类有没有变化
            unset($this->params['before_catids']);
            parent::sync();
        }elseif($this->syncDirect == 'branchtohead'){
            $this->vaildParams();
            //$this->params['distributor_id'] = 0;
            parent::sync();
        }

    }

    private function syncRelevantInfo()
    {
        if('update' != $this->syncAction){
            return true;
        }

        syncCategory::getInstance()->setParams($data,'update')->sync();//检查 分销商的授权分类 是否有变更 有的话 同步 add or update
        syncGoods::getInstance()->setParams($data,'update')->sync();// 如果授权分类有变更 触发变更授权分类下的商品 add or update
        syncGoodsType::getInstance()->setParams($data,'update')->sync();
        syncSpec::getInstance()->set($data,'update')->sync();

    }
    
} 