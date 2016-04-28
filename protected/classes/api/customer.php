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

    protected $addrManageTemplate = '<li class="swipeout">
            <div class="swipeout-content"><a href="#" class="item-link item-content">
                <div class="item-inner">
                    <div class="item-title-row">
                        <div class="item-title">{name}</div>
                        <div class="item-after">{mobile}</div>
                    </div>
                    <div class="item-subtitle">{address}&nbsp;&nbsp;{is_default}</div>
                </div></a></div>
            <div class="swipeout-actions-right"><a href="addaddress.html?id={id}" class="link">编辑</a><a href="#" class="demo-mark bg-orange">设为默认</a><a href="#" data-confirm="Are you sure you want to delete this item?" class="swipeout-delete swipeout-overswipe">删除</a></div>
        </li>';


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
            case 'addaddr':
                $this->addAddress();
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
        $userid = intval($this->params['id']);

        $addressModel = new Model('address');

        $addrLists = $addressModel->fields('id,accept_name,mobile,phone,province,city,county,zip,addr,is_default')->where('user_id='.$userid)->findAll();

        if($addrLists){
            $this->getAddrHtml($addrLists);

        }else{
            echo '';
        }

    }

    //添加/编辑收获地址
    protected function addAddress()
    {
        $addrid = intval($this->params['id']);

        $data = array();

        $addressModel = new Model('address');

        $addrList = $addressModel->fields('id,accept_name,mobile,phone,province,city,county,zip,addr,is_default')->where('id='.$addrid)->find();

        if($addrList){
            $data = $addrList;
            $this->output['status'] = 'succ';
        }


        $this->output($data,'json');

    }

    protected function getAddrHtml($addrLists)
    {
        $html = '';

        $areaModel = new Model('area','zd','salve');

        foreach($addrLists as $val){
            $id = $val['id'];
            $name = $val['accept_name'];
            $mobile = $val['mobile'];
            $province = $val['province'];
            $city = $val['city'];
            $county = $val['county'];
            $addr = $val['addr'];
            $is_default = ($val['is_default'] == 1) ? '<span class="badge bg-gray">默认</span>' : '';

            $area_ids = $province.','.$city.','.$county;

            if($area_ids!='')$areas = $areaModel->where("id in ($area_ids)")->findAll();
            $parse_area = array();
            foreach ($areas as $area) {
                $parse_area[$area['id']] = $area['name'];
            }

            $address = $parse_area[$province].$parse_area[$city].$parse_area[$county].$addr;

            $html .= str_replace(array('{id}','{name}','{mobile}','{address}','{is_default}'),array($id,$name,$mobile,$address,$is_default),$this->addrManageTemplate);
        }

        echo $html;
    }

    //修改密码
    protected function changePwd()
    {

    }
}