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

    protected $captchaKey = 'verifyCode';

    public static $title = array(
        'login'=>'用户登录',
        'register'=>'用户注册',
    );

    public static $notice = array(
        'login'=>'',
        'register'=>'',
    );

    public static $lastmodify = array(
        'login'=>'2016-6-13',
        'register'=>'2016-6-13',
    );

    public static $requestParams = array(
        'login'=>array(
            array(
                'colum'=>'mobile',
                'required'=>'是',
                'type'=>'int',
                'content'=>'手机号',
            ),
            array(
                'colum'=>'password',
                'required'=>'是',
                'type'=>'string',
                'content'=>'密码',
            ),
            array(
                'colum'=>'vaildcode',
                'required'=>'是',
                'type'=>'string',
                'content'=>'验证码',
            ),
            array(
                'colum'=>'rand',
                'required'=>'是',
                'type'=>'string',
                'content'=>'随机串为 手机序列号',
            ),
        ),
        'register'=>array(
            array(
                'colum'=>'mobile',
                'required'=>'是',
                'type'=>'int',
                'content'=>'手机号',
            ),
            array(
                'colum'=>'password',
                'required'=>'是',
                'type'=>'string',
                'content'=>'密码',
            ),
            array(
                'colum'=>'repassword',
                'required'=>'是',
                'type'=>'string',
                'content'=>'确认密码',
            ),
            array(
                'colum'=>'vaildcode',
                'required'=>'是',
                'type'=>'string',
                'content'=>'验证码',
            ),
            array(
                'colum'=>'rand',
                'required'=>'是',
                'type'=>'string',
                'content'=>'随机串为 手机序列号',
            ),
        ),
    );

    public static $responsetParams = array(
        'login'=>array(
            array(
                'colum'=>'user_id',
                'content'=>'成功，则返回用户id',
            ),
        ),
        'register'=>array(
            array(
                'colum'=>'user_id',
                'content'=>'成功，则返回用户id',
            ),
        ),
    );

    public static $requestUrl = array(
        'login'=>'     /index.php?con=api&act=index&method=customer&source=login',
        'register'=>'     /index.php?con=api&act=index&method=customer&source=register'
    );


    protected $addrManageTemplate = '<li class="swipeout">
            <div class="swipeout-content"><a href="#" class="item-link item-content">
                <div class="item-inner">
                    <div class="item-title-row">
                        <div class="item-title">{name}</div>
                        <div class="item-after">{mobile}</div>
                    </div>
                    <div class="item-subtitle">{address}&nbsp;&nbsp;{is_default}</div>
                </div></a></div>
            <div class="swipeout-actions-right"><a href="addaddress.html?id={id}" class="link">编辑</a><a aid="{id}" class="demo-mark set-default bg-orange">设为默认</a><a href="#" data-confirm="确定要删除吗?" aid="{id}" class="swipeout-delete swipeout-overswipe addr-delete">删除</a></div>
        </li>';

    protected $myOrderListTemplate = '<div class="card ks-facebook-card item-link">
                        <a href="orderdetail.html?id={id}">
                        <div class="no-border item-inner">
                            <div class="item-title">&nbsp;&nbsp;订单 {order_no} <br/> &nbsp;&nbsp;<span class="badge bg-green">{status}</span> </div>
                            <div class="item-after">

                            </div>
                        </div>
                        </a>

                        <div data-pagination=".swiper-pagination" data-pagination-hide="true" class="swiper-container swiper-init ks-demo-slider">
                            <div class="swiper-pagination"></div>
                            <div class="swiper-wrapper">
                              {products}
                            </div>
                        </div>

                        <div class="card-footer no-border"><a href="#" class="link"><span class="badge bg-green">立即支付</span></a><a href="#" class="link"><span class="badge bg-green">再次购买</span></a></div>
                    </div>';

    protected $myOrderListNoTemplate = '<div class="card ks-facebook-card">
                        <div class="no-border item-inner">
                            <div class="item-title" style="text-align: center;">暂无订单</div>

                        </div>
                    </div>';

    public function index()
    {
        switch($this->params['source']){
            case 'morders':
                if($this->params['type'] == 'waitpay'){
                    $this->getWaitPayOrders();
                }elseif($this->params['type'] == 'delivery'){
                    $this->getDeliveryOrders();
                }elseif($this->params['type'] == 'finish'){
                    $this->getFinishOrders();
                }else{
                    $this->getMyOrders();
                }
                break;
            case 'uinfo':
                $this->getUserInfo();
                break;
            case 'douinfo':
                $this->saveUserInfo();
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
            case 'login':
                $this->login();
                break;
            case 'register':
                $this->register();
                break;
            case 'doaddr':
                $this->doaddr();
                break;
        }
    }

    //待支付订单
    protected function getWaitPayOrders()
    {
        $html = '';

        $userid = $this->params['id'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and pay_status=0')->order('unix_timestamp(create_time) desc')->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');

            $goodsModel = new Model('goods');

            foreach($orders as $val){

                $status = $this->status($val);

                $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                $products = '';

                foreach($odDatas as $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();
                    $products .= '<div class="swiper-slide"><img src="'.self::getApiUrl().$gData['img'].'" width="100" height="100" /><span style="font-size:14px;">'.$gData['name'].'<br/>￥'.$vval['real_price'].'<br/> X '.$vval['goods_nums'].'</span></div>';
                }
                $html .= str_replace(array('{id}','{order_no}','{status}','{products}'),array($val['id'],$val['order_no'],$status,$products),$this->myOrderListTemplate);
            }
        }else{
            $html = $this->myOrderListNoTemplate;

        }


        echo $html;
    }

    //待发货订单
    protected function getDeliveryOrders()
    {
        $html = '';

        $userid = $this->params['id'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and pay_status=1 and delivery_status=0')->order('unix_timestamp(pay_time) desc')->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');

            $goodsModel = new Model('goods');

            foreach($orders as $val){

                $status = $this->status($val);

                $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                $products = '';

                foreach($odDatas as $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();
                    $products .= '<div class="swiper-slide"><img src="'.self::getApiUrl().$gData['img'].'" width="100" height="100" /><span style="font-size:14px;">'.$gData['name'].'<br/>￥'.$vval['real_price'].'<br/> X '.$vval['goods_nums'].'</span></div>';
                }
                $html .= str_replace(array('{id}','{order_no}','{status}','{products}'),array($val['id'],$val['order_no'],$status,$products),$this->myOrderListTemplate);
            }
        }else{
            $html = $this->myOrderListNoTemplate;

        }


        echo $html;
    }

    //已完成订单
    protected function getFinishOrders()
    {
        $html = '';

        $userid = $this->params['id'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid.' and status=4')->order('unix_timestamp(completion_time) desc')->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');

            $goodsModel = new Model('goods');

            foreach($orders as $val){

                $status = $this->status($val);

                $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                $products = '';

                foreach($odDatas as $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();
                    $products .= '<div class="swiper-slide"><img src="'.self::getApiUrl().$gData['img'].'" width="100" height="100" /><span style="font-size:14px;">'.$gData['name'].'<br/>￥'.$vval['real_price'].'<br/> X '.$vval['goods_nums'].'</span></div>';
                }
                $html .= str_replace(array('{id}','{order_no}','{status}','{products}'),array($val['id'],$val['order_no'],$status,$products),$this->myOrderListTemplate);
            }
        }else{
            $html = $this->myOrderListNoTemplate;

        }


        echo $html;
    }

    //我的订单
    protected function getMyOrders()
    {
        $html = '';

        $userid = $this->params['id'];

        $orderModel = new Model('order');

        $orders = $orderModel->fields('id,payment,order_no,status,pay_status,create_time,order_amount,delivery_status')->where('user_id='.$userid)->order('id desc')->findAll();

        if($orders){
            $orderDetailModel = new Model('order_goods');

            $goodsModel = new Model('goods');

            foreach($orders as $val){

                $status = $this->status($val);

                $odDatas = $orderDetailModel->fields('goods_id,real_price,goods_nums')->where('order_id='.$val['id'])->findAll();

                $products = '';
//<div class="swiper-slide"><img src="http://lorempixel.com/500/500/nature/1" width="200" height="200" /></div>
                foreach($odDatas as $vval){
                    $gData = $goodsModel->fields('name,img')->where('id='.$vval['goods_id'])->find();
                    $products .= '<div class="swiper-slide"><img src="'.self::getApiUrl().$gData['img'].'" width="100" height="100" /><span style="font-size:14px;">'.$gData['name'].'<br/>￥'.$vval['real_price'].'<br/> X '.$vval['goods_nums'].'</span></div>';
                }
                $html .= str_replace(array('{id}','{order_no}','{status}','{products}'),array($val['id'],$val['order_no'],$status,$products),$this->myOrderListTemplate);
            }
        }else{
            $html = $this->myOrderListNoTemplate;

        }


        echo $html;
    }

    private function status($item = array())
    {
        if($item['status'] == '1'){
            return '等待付款';
        }elseif($item['status'] == '2'){
            if($item['pay_status'] == 1){
                return '等待审核';
            }else{
                $payment_info = Common::getPaymentInfo($item['payment']);
                if($payment_info['class_name']=='received'){
                    return '等待审核';
                }else{
                    return '等待付款';
                }
            }
        }elseif($item['status'] == '3'){
            if($item['delivery_status'] == 0){
                return '等待发货';
            }elseif($item['delivery_status'] == 1){
                return '已发货';
            }

            if($item['pay_status'] == 3){
                return '已退款';
            }

        }elseif($item['status'] == 4){
            return '已完成';
        }elseif($item['status'] == 5){
            return '已取消';
        }elseif($item['status'] == 6){
            return '已作废';
        }
    }

    //个人资料
    protected function getUserInfo()
    {
        $data = array();

        $userid = intval($this->params['id']);

        $userModel = new Model('user');
        $userInfo = $userModel->fields('name,email,head_pic')->where('id='.$userid)->find();

        if($userInfo){
            $customerModel = new Model('customer');
            $customerInfo = $customerModel->fields('real_name,sex,phone,mobile,addr,birthday,province,city,county,balance')->where('user_id=1')->find();

            $customerInfo['sex'] = ($customerInfo['sex'] == 1) ? '男' : '女';
            $data = array_merge($userInfo,$customerInfo);
            $this->output['status'] = 'succ';
        }


        $this->output($data,'json');
    }

    //保存个人信息
    protected function saveUserInfo()
    {

        $res = false;

        $data = array();

        $user_id = isset($this->params['user_id']) ? intval($this->params['user_id']) : null;

        $data['real_name'] = !empty($this->params['real_name']) ? $this->params['real_name'] : '';

        $data['phone'] = !empty($this->params['phone']) ? $this->params['phone'] : '';

        $data['mobile'] = !empty($this->params['mobile']) ? $this->params['mobile'] : '';

        $data['sex'] = ($this->params['sex'] == '男') ? 1 : 0;

        $data['province'] = $this->params['province'] ? $this->params['province'] : 0;

        $data['city'] = $this->params['city'] ? $this->params['city'] : 0;

        $data['county'] = $this->params['county'] ? $this->params['county'] : 0;

        $data['addr'] = $this->params['addr'] ? $this->params['addr'] : 0;

        $data['birthday'] = $this->params['birthday'] ? $this->params['birthday'] : '';


        if($user_id){

            if(isset($this->params['head_pic']) && !empty($this->params['head_pic'])){

                $userModel = new Model('user');

                $udata = array('head_pic'=>$this->params['head_pic']);

                $userModel->data($udata)->where('id='.$user_id)->update();

            }

            $customerModel = new Model('customer');

            $res = $customerModel->data($data)->where('user_id='.$user_id)->update();
        }


        if($res){
            $this->output['status'] = 'succ';
            $this->output['msg'] = '保存成功';
            $this->output();
        }else{
            $this->output['msg'] = '保存失败';
            $this->output();
        }

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

        $addrList = $addressModel->fields('user_id,id,accept_name,mobile,phone,province,city,county,zip,addr,is_default')->where('id='.$addrid)->find();

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

        $password = isset($this->params['password']) ? trim($this->params['password']) : '';

        $repassword = isset($this->params['repassword']) ? trim($this->params['repassword']) : '';

        $user_id = intval($this->params['user_id']) ? $this->params['user_id'] : 0;

        if($user_id){

            if($password != $repassword){
                $this->output['msg'] = '两次密码不一致';
                $this->output();
                exit;
            }

            $userModel = new Model('user');
            $validcode = CHash::random(8);
            $res = $userModel->data(array('password'=>CHash::md5($password,$validcode),'validcode'=>$validcode))->where('id='.$user_id)->update();

            if($res){
                $this->output['status'] = 'succ';
                $this->output['msg'] = '修改成功';
                $this->output();
            }else{
                $this->output['msg'] = '修改失败';
                $this->output();
            }

        }else{
            $this->output['msg'] = '用户不存在';
            $this->output();
        }

    }

    //登录
    protected function login()
    {
        $mobile = Filter::sql($this->params['mobile']);

        $_vaildcode = $this->params['vaildcode'];

        $this->params['rand'] = trim($this->params['rand']);

        $cacheModel = new Model('cache');

        $md5 = $this->captchaKey.$this->params['rand'];

        $code = $cacheModel->where('`key`="'.$md5.'"')->find();

        $_code = $code['content'];

        if($_vaildcode != $_code){
            $this->output['msg'] = '验证码不正确';
            $this->output();
            exit;
        }

        $cacheModel->where('`key`="'.$md5.'"')->delete();

        if(Validator::mobi($mobile)){

            $password = $this->params['password'];

            $userModel = new Model('user');

            $userData = $userModel->where('name="'.$mobile.'"')->find();

            if($userData['password'] == CHash::md5($password,$userData['validcode'])){

                $data = array();
                $data['user_id'] = $userData['id'];
                $this->output['status'] = 'succ';
                $this->output['msg'] = '登录成功';
                $this->output($data);
            }else{
                $this->output['msg'] = '密码不正确';
                $this->output();
            }

        }else{
            $this->output['msg'] = '手机号不存在';
            $this->output();
        }


    }

    //a.tinyshop.com/index.php?con=api&act=index&method=customer&source=register&mobile=15500001235&password=q123456&repassword=q123456&vaildcode=uoke

    //注册
    protected function register()
    {

        $mobile = Filter::sql($this->params['mobile']);

        $_vaildcode = $this->params['vaildcode'];

        $this->params['rand'] = trim($this->params['rand']);

        $cacheModel = new Model('cache');

        $md5 = $this->captchaKey.$this->params['rand'];

        $code = $cacheModel->where('`key`="'.$md5.'"')->find();

        $_code = $code['content'];

        if($_vaildcode != $_code){
            $this->output['msg'] = '验证码不正确';
            $this->output();
            exit;
        }

        $cacheModel->where('`key`="'.$md5.'"')->delete();

        if(Validator::mobi($mobile)){

            $password = $this->params['password'];

            $re_password = $this->params['repassword'];

            if($password != $re_password){
                $this->output['msg'] = '二次密码输入不一致';
                $this->output();
            }

            $userModel = new Model('user');

            $userData = $userModel->where('name="'.$mobile.'"')->find();

            if($userData){
                $this->output['msg'] = '手机号已注册';
                $this->output();
                exit;
            }else{
                $validcode = CHash::random(8);
                $data = array('name'=>$mobile,'password'=>CHash::md5($password,$validcode),'validcode'=>$validcode);
                $user_id = $userModel->data($data)->insert();
                if($user_id){
                    $customerModel = new Model('customer');

                    $data = array('user_id'=>$user_id,'mobile'=>$mobile,'reg_time'=>date('Y-m-d H:i:s'));

                    $customerModel->data($data)->insert();

                    $_data = array();
                    $_data['user_id'] = $user_id;
                    $this->output['status'] = 'succ';
                    $this->output['msg'] = '注册成功';
                    $this->output($_data);
                }
            }
        }else{
            $this->output['msg'] = '手机号格式不正确';
            $this->output();
        }
    }

    //添加收货地址
    public function doaddr()
    {

        $id = isset($this->params['id']) ? intval($this->params['id']) : 0;
        $user_id = isset($this->params['user_id']) ? $this->params['user_id'] : null;
        $accept_name = isset($this->params['accept_name']) ? trim($this->params['accept_name']) : '';
        $mobile = isset($this->params['mobile']) ? $this->params['mobile'] : '';
        $zip = isset($this->params['zip']) ? $this->params['zip'] : '';
        $phone = isset($this->params['phone']) ? $this->params['phone'] : '';
        $province = isset($this->params['province']) ? $this->params['province'] : '';
        $city = isset($this->params['city']) ? $this->params['city'] : '';
        $county = isset($this->params['county']) ? $this->params['county'] : '';
        $addr = isset($this->params['addr']) ? trim($this->params['addr']) : '';
        $is_default = isset($this->params['is_default']) ? $this->params['is_default'] : 0;
        $_act = isset($this->params['_act']) ? $this->params['_act'] : null;

        if(!$_act || !$user_id){
            $this->output['msg'] = '异常错误';
            $this->output();
            exit;
        }

        switch($_act){
            case 'add':
            case 'edit':
                $this->_doaddr($user_id,$accept_name,$mobile,$province,$city,$county,$addr,$zip,$phone,$is_default,$_act,$id);
            break;
            case 'del':
                $this->_deladdr($id);
            break;
            case 'setdefault':
                $this->_setdefaultaddr($is_default,$id,$user_id);
            break;
        }

    }

    private function _deladdr($id)
    {
        $addressModel = new Model('address');

        $res = $addressModel->where('id='.$id)->delete();

        if($res){
            $this->output['status'] = 'succ';
            $this->output['msg'] = '删除成功';
            $this->output();
        }else{
            $this->output['msg'] = '删除失败';
            $this->output();
        }
    }

    private function _setdefaultaddr($is_default,$id,$user_id)
    {
        $addressModel = new Model('address');

        if($is_default == 1){
            $addressModel->data(array('is_default'=>0))->where('user_id='.$user_id)->update();
        }

        $res = $addressModel->data(array('is_default'=>$is_default))->where('id='.$id)->update();

        if($res){
            $this->output['status'] = 'succ';
            $this->output['msg'] = '设置成功';
            $this->output();
        }else{
            $this->output['msg'] = '设置失败';
            $this->output();
        }
    }

    private function _doaddr($user_id,$accept_name,$mobile,$province,$city,$county,$addr,$zip,$phone,$is_default,$_act,$id)
    {
        $res = false;
        $act = ($_act == 'add') ? '添加':'编辑';

        if(empty($accept_name)){
            $this->output['msg'] = '收货人不能为空';
            $this->output();
            exit;
        }elseif(Validator::mobi($mobile)){
            $this->output['msg'] = '手机号格式不正确';
            $this->output();
            exit;
        }elseif(empty($province)){
            $this->output['msg'] = '省份不能为空';
            $this->output();
            exit;
        }elseif(empty($city)){
            $this->output['msg'] = '市不能为空';
            $this->output();
            exit;
        }elseif(empty($county)){
            $this->output['msg'] = '县/区不能为空';
            $this->output();
            exit;
        }elseif(empty($addr)){
            $this->output['msg'] = '地址不能为空';
            $this->output();
            exit;
        }elseif(empty($zip)){
            $this->output['msg'] = '邮编不能为空';
            $this->output();
            exit;
        }

        $addressModel = new Model('address');

        if($_act == 'add'){

            $data = array();
            $data['user_id'] = $user_id;
            $data['accept_name'] = $accept_name;
            $data['mobile'] = $mobile;
            $data['province'] = $province;
            $data['city'] = $city;
            $data['county'] = $county;
            $data['addr'] = $addr;
            $data['zip'] = $zip;
            $data['phone'] = $phone;
            //$data['is_default'] = $is_default;

            $res = $addressModel->data($data)->insert();

        }elseif($_act == 'edit'){

            $data = array();
            $data['user_id'] = $user_id;
            $data['accept_name'] = $accept_name;
            $data['mobile'] = $mobile;
            $data['province'] = $province;
            $data['city'] = $city;
            $data['county'] = $county;
            $data['addr'] = $addr;
            $data['zip'] = $zip;
            $data['phone'] = $phone;
            //$data['is_default'] = $is_default;

            $res = $addressModel->data($data)->where('id='.$id)->update();
        }

        if($res){
            $this->output['status'] = 'succ';
            $this->output['msg'] = $act.'成功';
            $this->output();
        }else{
            $this->output['msg'] = $act.'失败';
            $this->output();
        }
    }

    public function login_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'密码不正确 / 手机号不存在 / 验证码不正确',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'登录成功',
                'data'=>array(
                    'user_id'=>1,
                ),
            )
        );
    }

    public function register_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'二次密码输入不一致 / 手机号已注册 / 手机号格式不正确 / 验证码不正确',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'注册成功',
                'data'=>array(
                    'user_id'=>1,
                ),
            )
        );
    }
}