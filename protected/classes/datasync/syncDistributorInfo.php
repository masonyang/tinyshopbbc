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
        if($this->syncDirect == 'hb'){
            $this->vaildParams();
            parent::sync();
        }elseif($this->syncDirect == 'bh'){
            $this->vaildParams();
            $this->params['distributor_id'] = 0;
            parent::sync();
        }

    }
} 