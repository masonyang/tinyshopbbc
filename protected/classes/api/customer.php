<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 24/4/16
 * Time: 下午5:14
 * 192.168.1.100/index.php?con=api&act=index&method=customer&source=uinfo
 */
class customer extends baseapi
{

    public function index()
    {
        switch($this->params['source']){
            case 'morders':
                $this->getMyOrders();
                break;
            case 'uinfo':
                $this->getUserInfo();
                break;
            case 'address':
                $this->getAddress();
                break;
            case 'cpwd':
                $this->changePwd();
                break;
        }
    }

    //我的订单
    protected function getMyOrders()
    {

    }

    //个人资料
    protected function getUserInfo()
    {
        $data = array();

        $userModel = new Model('user');
        $userInfo = $userModel->fields('name,email,head_pic')->where('id=1')->find();

        if($userInfo){
            $customerModel = new Model('customer');
            $customerInfo = $customerModel->fields('real_name,sex,phone,mobile,addr,birthday,province,city,county,balance')->where('user_id=1')->find();

            $customerInfo['sex'] = ($customerInfo['sex'] == 1) ? '男' : '女';
            $data = array_merge($userInfo,$customerInfo);
            $this->output['status'] = 'succ';
        }


        $this->output($data,'json');
    }

    //收货地址管理
    protected function getAddress()
    {

    }

    //修改密码
    protected function changePwd()
    {

    }
}