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
        'address'=>'收货地址管理',
        'addaddr'=>'获取单个收货地址信息',
        'doaddr'=>'添加/编辑/删除/设置默认收货地址',
        'uinfo'=>'获取会员信息',
    );

    public static $notice = array(
        'login'=>'',
        'register'=>'',
        'address'=>'',
        'addaddr'=>'',
        'doaddr'=>'',
        'uinfo'=>'',
    );

    public static $lastmodify = array(
        'login'=>'2016-6-13',
        'register'=>'2016-6-13',
        'address'=>'2016-6-27',
        'addaddr'=>'2016-6-27',
        'doaddr'=>'2016-6-27',
        'uinfo'=>'2016-6-27',
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
        'address'=>array(
            array(
                'colum'=>'uid',
                'required'=>'是',
                'type'=>'int',
                'content'=>'会员id',
            ),
        ),
        'addaddr'=>array(
            array(
                'colum'=>'addr_id',
                'required'=>'是',
                'type'=>'int',
                'content'=>'收货地址id',
            ),
        ),
        'uinfo'=>array(
            array(
                'colum'=>'uid',
                'required'=>'是',
                'type'=>'int',
                'content'=>'会员id',
            ),
        ),
        'doaddr'=>array(
            array(
                'colum'=>'addr_id',
                'required'=>'是',
                'type'=>'int',
                'content'=>'收货地址id',
            ),
            array(
                'colum'=>'user_id',
                'required'=>'是',
                'type'=>'int',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'name',
                'required'=>'是',
                'type'=>'int',
                'content'=>'收货人姓名',
            ),
            array(
                'colum'=>'mobile',
                'required'=>'是',
                'type'=>'string',
                'content'=>'手机号',
            ),
            array(
                'colum'=>'zip',
                'required'=>'是',
                'type'=>'string',
                'content'=>'邮编',
            ),
            array(
                'colum'=>'phone',
                'required'=>'是',
                'type'=>'int',
                'content'=>'固定电话',
            ),
            array(
                'colum'=>'province',
                'required'=>'是',
                'type'=>'int',
                'content'=>'省',
            ),
            array(
                'colum'=>'city',
                'required'=>'是',
                'type'=>'int',
                'content'=>'市',
            ),
            array(
                'colum'=>'county',
                'required'=>'是',
                'type'=>'int',
                'content'=>'区',
            ),
            array(
                'colum'=>'address',
                'required'=>'是',
                'type'=>'int',
                'content'=>'收货地址',
            ),
            array(
                'colum'=>'is_default',
                'required'=>'是',
                'type'=>'int',
                'content'=>'是否为默认 0：否 1：是',
            ),
            array(
                'colum'=>'_act',
                'required'=>'是',
                'type'=>'string',
                'content'=>'add【添加】/edit【编辑】/del【删除】/setdefault【设置为默认】',
            ),
        ),
    );

    public static $responsetParams = array(
        'login'=>array(
            array(
                'colum'=>'user_id',
                'content'=>'成功，则返回用户id',
            ),
            array(
                'colum'=>'user_name',
                'content'=>'成功，则返回用户名',
            ),
        ),
        'register'=>array(
            array(
                'colum'=>'user_id',
                'content'=>'成功，则返回用户id',
            ),
            array(
                'colum'=>'user_name',
                'content'=>'成功，则返回用户名',
            ),
        ),
        'address'=>array(
            array(
                'colum'=>'addr_id',
                'content'=>'地址id',
            ),
            array(
                'colum'=>'name',
                'content'=>'收货人姓名',
            ),
            array(
                'colum'=>'mobile',
                'content'=>'手机号',
            ),
            array(
                'colum'=>'is_default',
                'content'=>'是否为默认 1:默认 0:不是默认',
            ),
            array(
                'colum'=>'address',
                'content'=>'收货地址',
            ),
        ),
        'addaddr'=>array(
            array(
                'colum'=>'addr_id',
                'content'=>'地址id',
            ),
            array(
                'colum'=>'user_id',
                'content'=>'会员id',
            ),
            array(
                'colum'=>'name',
                'content'=>'收货人姓名',
            ),
            array(
                'colum'=>'phone',
                'content'=>'固定电话',
            ),
            array(
                'colum'=>'mobile',
                'content'=>'手机号',
            ),
            array(
                'colum'=>'is_default',
                'content'=>'是否为默认 1:默认 0:不是默认',
            ),
            array(
                'colum'=>'address',
                'content'=>'收货地址',
            ),
            array(
                'colum'=>'zip',
                'content'=>'邮编',
            ),
            array(
                'colum'=>'province',
                'content'=>'省',
            ),
            array(
                'colum'=>'city',
                'content'=>'市',
            ),
            array(
                'colum'=>'county',
                'content'=>'区',
            ),
        ),
        'doaddr'=>array(
            array(
                'colum'=>'-',
                'content'=>'-',
            ),
        ),
        'uinfo'=>array(
            array(
                'colum'=>'real_name',
                'content'=>'真实姓名',
            ),
            array(
                'colum'=>'sex',
                'content'=>'性别 1：男 0：女',
            ),
            array(
                'colum'=>'phone',
                'content'=>'固定电话',
            ),
            array(
                'colum'=>'mobile',
                'content'=>'手机号',
            ),
            array(
                'colum'=>'addr',
                'content'=>'地址',
            ),
            array(
                'colum'=>'birthday',
                'content'=>'出生年月',
            ),
            array(
                'colum'=>'province',
                'content'=>'省',
            ),
            array(
                'colum'=>'city',
                'content'=>'市',
            ),
            array(
                'colum'=>'county',
                'content'=>'区',
            ),
        ),
    );

    public static $requestUrl = array(
        'login'=>'     /index.php?con=api&act=index&method=customer&source=login',
        'register'=>'     /index.php?con=api&act=index&method=customer&source=register',
        'address'=>'     /index.php?con=api&act=index&method=customer&source=address',
        'addaddr'=>'     /index.php?con=api&act=index&method=customer&source=addaddr',
        'doaddr'=>'     /index.php?con=api&act=index&method=customer&source=doaddr',
        'uinfo'=>'     /index.php?con=api&act=index&method=customer&source=uinfo',
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



    public function index()
    {
        switch($this->params['source']){
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



    //个人资料
    protected function getUserInfo()
    {
        $data = array();

        $userid = intval($this->params['uid']);

        $userModel = new Model('user');
        $userInfo = $userModel->fields('name,email,head_pic')->where('id='.$userid)->find();

        if($userInfo){
            $customerModel = new Model('customer');
            $customerInfo = $customerModel->fields('real_name,sex,phone,mobile,addr,birthday,province,city,county,balance')->where('user_id=1')->find();

            $customerInfo['sex'] = ($customerInfo['sex'] == 1) ? '男' : '女';
            $data = array_merge($userInfo,$customerInfo);
            $this->output['status'] = 'succ';
            $this->output['msg'] = '获取成功';
        }else{
            $this->output['status'] = 'fail';
            $this->output['msg'] = '获取失败';
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
        $userid = intval($this->params['uid']);

        $addressModel = new Model('address');

        $addrLists = $addressModel->fields('id,accept_name,mobile,phone,province,city,county,zip,addr,is_default')->where('user_id='.$userid)->findAll();

        if($addrLists){
            $data = $this->getAddrHtml($addrLists);

            $this->output['status'] = 'succ';
            $this->output['msg'] = '收货地址管理获取成功';
            $this->output($data);

        }else{
            $this->output['msg'] = '还没添加收获地址';
            $this->output();
        }

    }

    //添加/编辑收获地址
    protected function addAddress()
    {
        $addrid = intval($this->params['addr_id']);

        $data = array();

        $addressModel = new Model('address');

        $addrList = $addressModel->fields('user_id,id as addr_id,accept_name as name,mobile,phone,province,city,county,zip,addr as address,is_default')->where('id='.$addrid)->find();

        if($addrList){
            $data = $addrList;
            $this->output['status'] = 'succ';
        }

        $this->output($data,'json');

    }

    protected function getAddrHtml($addrLists)
    {
        $data = array();

        $areaModel = new Model('area','zd','salve');

        foreach($addrLists as $k=>$val){
            $data[$k]['addr_id'] = $val['id'];
            $data[$k]['name'] = $val['accept_name'];
            $data[$k]['mobile'] = $val['mobile'];
            $province = $val['province'];
            $city = $val['city'];
            $county = $val['county'];
            $addr = $val['addr'];
            $data[$k]['is_default'] = $val['is_default'];

            $area_ids = $province.','.$city.','.$county;

            if($area_ids!='')$areas = $areaModel->where("id in ($area_ids)")->findAll();
            $parse_area = array();
            foreach ($areas as $area) {
                $parse_area[$area['id']] = $area['name'];
            }

            $data[$k]['address'] = $parse_area[$province].$parse_area[$city].$parse_area[$county].$addr;

//            $html .= str_replace(array('{id}','{name}','{mobile}','{address}','{is_default}'),array($id,$name,$mobile,$address,$is_default),$this->addrManageTemplate);
        }

        return $data;
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


        if(isset($this->params['vaildcode'])){
            $_vaildcode = $this->params['vaildcode'];

            $cacheModel = new Model('cache');

            $md5 = md5($this->captchaKey.$this->params['rand']);

            $code = $cacheModel->where('`key`="'.$md5.'"')->find();

            $_code = $code['content'];

            if($_vaildcode != $_code){
                $this->output['msg'] = '验证码不正确';
                $this->output();
                exit;
            }

            $cacheModel->where('`key`="'.$md5.'"')->delete();
        }

        $mobile = Filter::sql($this->params['mobile']);
        
        if(Validator::mobi($mobile)){

            $password = $this->params['password'];

            $userModel = new Model('user');

            $userData = $userModel->where('name="'.$mobile.'"')->find();

            if($userData['password'] == CHash::md5($password,$userData['validcode'])){

                $data = array();
                $data['user_id'] = $userData['id'];
                $data['user_name'] = $userData['name'];
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
                $data = array('name'=>$mobile,'password'=>CHash::md5($password,$validcode),'validcode'=>$validcode,'email'=>$mobile.'@qqcapp.com');
                $user_id = $userModel->data($data)->insert();
                if($user_id){
                    $customerModel = new Model('customer');

                    $data = array('user_id'=>$user_id,'mobile'=>$mobile,'reg_time'=>date('Y-m-d H:i:s'));

                    $customerModel->data($data)->insert();

                    $_data = array();
                    $_data['user_id'] = $user_id;
                    $data['user_name'] = $mobile;
                    $this->output['status'] = 'succ';
                    $this->output['msg'] = '注册成功';
                    $this->output($_data);
                }else{
                    $this->output['msg'] = '注册失败';
                    $this->output();
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

        $id = isset($this->params['addr_id']) ? intval($this->params['addr_id']) : 0;
        $user_id = isset($this->params['user_id']) ? $this->params['user_id'] : null;
        $accept_name = isset($this->params['name']) ? trim($this->params['name']) : '';
        $mobile = isset($this->params['mobile']) ? $this->params['mobile'] : '';
        $zip = isset($this->params['zip']) ? $this->params['zip'] : '';
        $phone = isset($this->params['phone']) ? $this->params['phone'] : '';
        $province = isset($this->params['province']) ? $this->params['province'] : '';
        $city = isset($this->params['city']) ? $this->params['city'] : '';
        $county = isset($this->params['county']) ? $this->params['county'] : '';
        $addr = isset($this->params['address']) ? trim($this->params['address']) : '';
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
                if(empty($id)){
                    $this->output['msg'] = '地址id不能为空';
                    $this->output();
                    exit;
                }
                $this->_deladdr($id);
            break;
            case 'setdefault':
                if(empty($id)){
                    $this->output['msg'] = '地址id不能为空';
                    $this->output();
                    exit;
                }
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
        }elseif(!Validator::mobi($mobile)){
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
            $data['is_default'] = $is_default;

            $res = $addressModel->data($data)->insert();

        }elseif($_act == 'edit'){

            if(empty($id)){
                $this->output['msg'] = '地址id不能为空';
                $this->output();
                exit;
            }

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
            $data['is_default'] = $is_default;

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
                    'user_name'=>'',
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
                    'user_name'=>'',
                ),
            )
        );
    }

    public function address_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'还没添加收获地址',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'收货地址管理获取成功',
                'data'=>array(
                    array(
                        'addr_id'=>1,
                        'name'=>'张三',
                        'mobile'=>13589100333,
                        'is_default'=>'1',
                        'address'=>'山东省济南市历下区山东省ddddddd',
                    ),
                ),
            )
        );
    }

    public function addaddr_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'地址id不存在',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'收货地址信息获取成功',
                'data'=>array(
                    'addr_id'=>1,
                    'user_id'=>2,
                    'phone'=>13589100475,
                    'name'=>'张三',
                    'mobile'=>13589100333,
                    'is_default'=>'1',
                    'address'=>'山东省济南市历下区山东省ddddddd',
                    'zip'=>'250000',
                    'province'=>'370000',
                    'city'=>'370100',
                    'county'=>'370102',
                ),
            )
        );
    }

    public function doaddr_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'设置失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'设置成功',
                'data'=>array(
                ),
            )
        );
    }

    public function uinfo_demo()
    {
        return array(
            'fail'=>array(
                'status'=>'fail',
                'msg'=>'获取失败',
                'data'=>array(),
            ),
            'succ'=>array(
                'status'=>'succ',
                'msg'=>'获取成功',
                'data'=>array(
                    'name'=>'用户名',
                    'email'=>'邮箱',
//                    'head_pic'=>'',
                    'real_name'=>'真实姓名',
                    'sex'=>'性别 1：男 0：女',
                    'phone'=>'固定电话',
                    'mobile'=>'手机号',
                    'addr'=>'地址',
                    'birthday'=>'出生年月',
                    'province'=>'省',
                    'city'=>'市',
                    'county'=>'区',
//                    'balance'=>''
                ),
            )
        );
    }
}