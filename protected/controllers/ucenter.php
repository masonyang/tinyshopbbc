<?php
/**
 * description...
 *
 * @author Tinyhu
 * @package SimpleController
 */
class UcenterController extends Controller
{

    public $layout='index';
    public $safebox = null;
    private $model = null;
    private $category = array();
    private $cookie_time = 31622400;
    public  $sidebar = array(
        '交易管理'=>array(
            '我的订单'=>'order',
            '退款申请'=>'refund',
            '我的关注'=>'attention',
        ),
        '客户服务'=>array(
            '商品咨询'=>'consult',
            '商品评价'=>'review',
            '我的消息'=>'message',
        ),
        '账户管理'=>array(
            '个人资料'=>'info',
            '收货地址'=>'address',
            '我的优惠券'=>'voucher',
            '账户金额'=>'account',
            '修改密码'=>'password_change',
            '我的积分'=>'point',
        )

    );

    public function init()
    {
        header("Content-type: text/html; charset=".$this->encoding);
        $this->model = new Model();
        $this->safebox =  Safebox::getInstance();
        $this->user = $this->safebox->get('user');
        if($this->user==null){
            $this->user = Common::autoLoginUserInfo();
            $this->safebox->set('user',$this->user);
        }
        $category = Category::getInstance();

        $this->category = $category->getCategory();
        $cart = Cart::getCart();
        $action = Req::args("act");
        switch ($action) {
            case 'order_detail':
                $action = 'order';
                break;
            case 'refund_detail':
                $action = 'refund';
                break;
        }
        $this->assign("actionId",$action);
        $this->assign("cart",$cart->all());
        $this->assign("sidebar",$this->sidebar);
        $this->assign("category",$this->category);
        $this->assign("seo_title","用户中心");
    }

    public function checkRight($actionId)
    {
        if(isset($this->user['name']) && $this->user['name']!=null) return true;
        else return false;
    }
    public function noRight()
    {
        $this->redirect("/simple/login");
    }

    public function withdraw()
    {
        Filter::form();
        $account = floatval(Req::args('account'));
        $name = Filter::txt(Req::args('name'));
        $type_name = Filter::txt(Req::args('type_name'));
        $account = Filter::txt(Req::args('account'));
        $amount = Filter::float(Req::args('amount'));
        $info = array('status'=>'success','msg'=>'申请成功.');

        $model = new Model('customer');
        $customer = $model->where("user_id=".$this->user['id'])->find();
        if($customer['balance']<$amount){
            $info = array('status'=>'fail','msg'=>'提现金额超出的账户余额');
        }
        $obj = $model->table("withdraw")->where("user_id=".$this->user['id'].' and status=0')->find();
        if($obj){
            $info = array('status'=>'fail','msg'=>'上次申请的提现，还未处理，处理后才可再申请。');
        }else{
            $data = array('name'=>$name,'type_name'=>$type_name,'account'=>$account,'amount'=>$amount,'time'=>date('Y-m-d H:i:s'),'user_id'=>$this->user['id']);
            $model->table("withdraw")->data($data)->insert();

            //发送提现申请提醒
            $NoticeService = new NoticeService();
            $template_data = array('user'=>$this->user['name'],'amount'=>$amount,'account'=>$type_name.'('.$account.')','name'=>$name);
            $NoticeService->send('withdrawal_application',$template_data);
        }
        echo JSON::encode($info);

    }
    public function point()
    {
        $customer = $this->model->table("customer")->where("user_id=".$this->user['id'])->find();
        $this->assign("customer",$customer);
        $this->redirect();
    }
    public function point_exchange()
    {
        $id = Filter::int(Req::args('id'));
        $voucher = $this->model->table("voucher_template")->where("id=$id")->find();
        if($voucher){
           $use_point = 0 - $voucher['point'];
            $result = Pointlog::write($this->user['id'], $use_point, '积分兑换代金券，扣除了'.$use_point.'积分');
            if(true===$result){
                Common::paymentVoucher($voucher,$this->user['id']);
                $info = array('status'=>'success');
            }else{
                $info = array('status'=>'fail','msg'=>$result['msg']);
            }
            echo JSON::encode($info);
        }else{
            $info = array('status'=>'fail','msg'=>'你要兑换的代金券，不存在！');
            echo JSON::encode($info);
        }
    }
    public function password_save()
    {
        if(!Tiny::app()->checkToken())$this->redirect("password_change");
        $oldpassword = Req::post('oldpassword');
        $password = Req::post('password');
        $repassword = Req::post('repassword');
        $obj = $this->model->table("user")->where("id=".$this->user['id'])->find();
        if($password && $password==$repassword){
            if($obj['password'] == CHash::md5($oldpassword,$obj['validcode'])){
                $validcode = CHash::random(8);
                $data = array('password'=>CHash::md5($password,$validcode),'validcode'=>$validcode);
                $obj = $this->model->table("user")->where("id=".$this->user['id'])->data($data)->update();
                $this->redirect("password_change",false,array('msg'=>array("success","密码修改成功！")));
            }else{
                $this->redirect("password_change",false,array('msg'=>array("fail","原密码不正确！")));
            }
        }else{
            $this->redirect("password_change",false,array('msg'=>array("fail","两次密码不一致！")));
        }

    }
    public function upload_head()
    {
        $upfile_path = Tiny::getPath("uploads")."/head/";
        $upfile_url = preg_replace("|".APP_URL."|",'',Tiny::getPath("uploads_url")."head/",1);
        //$upfile_url = strtr(Tiny::getPath("uploads_url")."head/",APP_URL,'');
        $upfile = new UploadFile('imgFile',$upfile_path,'500k','','hash',$this->user['id']);
        $upfile->save();
        $info = $upfile->getInfo();
        $result = array();
        if($info[0]['status']==1){
            $result = array('error'=>0,'url'=>$upfile_url.$info[0]['path']);
            $image_url = $upfile_url.$info[0]['path'];
            $image = new Image();
            $image->suffix = '';
            $image->thumb(APP_ROOT.$image_url,100,100);
            $model = new Model('user');
            $model->data(array('head_pic'=>$image_url))->where("id=".$this->user['id'])->update();

            $safebox =  Safebox::getInstance();
            $user = $this->user;
            $user['head_pic'] = $image_url;
            $safebox->set('user',$user);
        }
        else{
            $result = array('error'=>1,'message'=>$info[0]['msg']);
        }
        echo JSON::encode($result);
    }
    public function account()
    {
        $customer = $this->model->table("customer")->where("user_id=".$this->user['id'])->find();
        $this->assign("customer",$customer);
        $this->redirect();
    }
    public function refund_act()
    {
        $order_no = Filter::sql(Req::args('order_no'));
        $order = $this->model->table("order")->where("order_no='$order_no' and user_id = ".$this->user['id'])->find();
        if($order){
            if($order['pay_status']==1){
                $refund = $this->model->table("doc_refund")->where("order_no='$order_no' and user_id = ".$this->user['id'])->find();
                if($refund){
                    $this->redirect("refund",false,array('msg'=>array("warning","不能重复申请退款！")));
                }
                else{
                    Filter::form(array('text'=>'account_name|refund_account|account_bank|content','int'=>'order_no|refund_type'));
                    $data = array(
                        'account_name'=>Req::args('account_name'),
                        'refund_account'=>Req::args('refund_account'),
                        'account_bank'=>Req::args('account_bank'),
                        'order_no'=>Req::args('order_no'),
                        'refund_type'=>Req::args('refund_type'),
                        'create_time'=>date('Y-m-d H:i:s'),
                        'user_id'=>$this->user['id'],
                        'order_id'=>$order['id'],
                        'content'=>Req::args('content'),
                        'pay_status'=>0
                        );
                    $this->model->table("doc_refund")->data($data)->insert();
                    $this->redirect("refund",false,array('msg'=>array("success","申请已经成功提交,请等候处理！")));
                }
            }else{
                $this->redirect("refund",false,array('msg'=>array("warning","此订单还未支付，无法申请退款！")));
            }

        }else{
            $this->redirect("refund",false,array('msg'=>array("warning","此订单编号不存在！")));
        }
    }
    public function refund_detail()
    {
        $id = Filter::int(Req::args('id'));
        $refund = $this->model->table("doc_refund")->where("id=$id and user_id=".$this->user['id'])->find();
        if($refund){
            $this->assign("refund",$refund);
            $this->redirect();
        }
        else{
            Tiny::Msg($this, 404);
        }
    }
    public function refund_del()
    {
        $order_no = Filter::sql(Req::args('order_no'));
        $obj = $this->model->table("doc_refund")->where("order_no='$order_no' and  pay_status=0 and user_id = ".$this->user['id'])->delete();
        $this->redirect("refund");
    }
    public function voucher_activated()
    {
        if(!Tiny::app()->checkToken())$this->redirect("voucher");
        $rules = array('account:required:账号不能为空!','password:required:密码不能为空！');
        $info = Validator::check($rules);
        if(!is_array($info) && $info==true) {
            Filter::form(array('sql'=>'account'));
            $account = Filter::sql(Req::args("account"));
            $voucher = $this->model->table("voucher")->where("account='$account' and is_send = 0")->find();
            if($voucher && $voucher['password'] == Req::args("password")){
                if(strtotime($voucher['end_time']) > time()){
                    if($voucher['status'] ==0){
                        $this->model->table("voucher")->data(array('user_id'=>$this->user['id'],'is_send'=>1,'status'=>0))->where("account='$account'")->update();
                        $this->redirect("voucher",false,array('msg'=>array("success","优惠券成功激活！")));
                    }else{
                        $this->redirect("voucher",false,array('msg'=>array("warning","此优惠券已使用过！")));
                    }
                }
                else{
                    //过期
                    $this->redirect("voucher",false,array('msg'=>array("warning","优惠券已过期！")));
                }

            }else{
                //不存在此优惠券
                 $this->redirect("voucher",false,array('msg'=>array("error","优惠券账号或密码错误！")));
            }
        }else{
            //输入信息有误
             $this->redirect("voucher",false,array('msg'=>array("info","输入的信息不格式不正确")));
        }
    }
    public function get_consult()
    {
        $page = Filter::int(Req::args("page"));
        $type = Filter::int(Req::args("type"));
        $status = Req::args("status");
        $where = "ak.user_id = ".$this->user['id'];
        switch ($status) {
            case 'n':
                $where .= " and ak.status = 0";
                break;
            case 'y':
                $where .= " and ak.status = 1";
                break;
            default:
                break;
        }
        $ask = $this->model->table("ask as ak")->join("left join goods as go on ak.goods_id = go.id")->fields("ak.*,go.name,go.id as gid,go.img,go.sell_price")->where($where)->order("ak.id desc")->findPage($page,10,$type,true);
        foreach ($ask['data'] as $key => $value) {
           $ask['data'][$key]['img'] = Common::thumb($value['img'],100,100);
        }
        $ask['status'] = "success";
        echo JSON::encode($ask);
    }
    //获取商品评价
    public function get_review()
    {
        $page = Filter::int(Req::args("page"));
        $type = Filter::int(Req::args("type"));
        $status = Req::args("status");
        $where = "re.user_id = ".$this->user['id'];
        switch ($status) {
            case 'n':
                $where .= " and re.status = 0";
                break;
            case 'y':
                $where .= " and re.status = 1";
                break;
            default:
                break;
        }
        $review = $this->model->table("review as re")->join("left join goods as go on re.goods_id = go.id")->fields("re.*,go.name,go.id as gid,go.img as img,go.sell_price")->where($where)->order("re.id desc")->findPage($page,10,$type,true);
        $data = $review['data'];
        foreach ($data as $key => $value) {
            $value['point'] = ($value['point']/5)*100;
            $data[$key] = $value;
        }
        $review['status'] = "success";
        $review['data'] = $data;
        echo JSON::encode($review);
    }
    //获取商品评价
    public function get_message()
    {
        $page = Filter::int(Req::args("page"));
        $type = Filter::int(Req::args("type"));
        $status = Req::args("status");
        $where = "";
        $customer = $this->model->table("customer")->where("user_id=".$this->user['id'])->find();
        $message_ids = '';
        if($customer){
            $message_ids = ','.$customer['message_ids'].',';
            switch ($status) {
                case 'y':
                    $message_ids = preg_replace('/,\d+,/i', ',', $message_ids);
                    $message_ids = preg_replace('/-/i','',$message_ids);
                    break;
                case 'n':
                    $message_ids = preg_replace('/,-\d+,/i', ',', $message_ids);
                    break;
                default:
                    break;
            }
            $message_ids = trim($message_ids,',');
        }

        $message  = array();
        if($message_ids!=''){
            $message = $this->model->table("message")->where("id in ($message_ids)")->order("id desc")->findPage($page,10,$type,true);
        }
        $message['status'] = "success";
        echo JSON::encode($message);
    }

    public function message_read()
    {
        $id = Filter::int(Req::args("id"));
        $customer = $this->model->table("customer")->where("user_id=".$this->user['id'])->find();
        $message_ids = ','.$customer['message_ids'].',';
        $message_ids = str_replace(",$id,",',-'.$id.',',$message_ids);
        $message_ids = trim($message_ids,',');
        $this->model->table("customer")->where("user_id=".$this->user['id'])->data(array('message_ids'=>$message_ids))->update();
        echo JSON::encode(array("status"=>'success'));
    }
    public function message_del()
    {
        $id = Filter::int(Req::args("id"));
        $customer = $this->model->table("customer")->where("user_id=".$this->user['id'])->find();
        $message_ids =','.$customer['message_ids'].',';
        $message_ids = str_replace(",-$id,",',',$message_ids);
        $message_ids = rtrim($message_ids,',');
        $this->model->table("customer")->where("user_id=".$this->user['id'])->data(array('message_ids'=>$message_ids))->update();
        echo JSON::encode(array("status"=>'success'));
    }
    public function get_voucher()
    {
        $page = Filter::int(Req::args("page"));
        $pagetype = Filter::int(Req::args("pagetype"));
        $status = Req::args("status");
        $where = "user_id = ".$this->user['id']." and is_send = 1";
        switch ($status) {
            case 'n':
                $where .= " and status = 0 and '".date("Y-m-d H:i:s")."' <=end_time";
                break;
            case 'u':
                $where .= " and status = 1";
                break;
            case 'p':
                $where .= " and status = 0 and '".date("Y-m-d H:i:s")."' > end_time";
                break;
            default:
                break;
        }
        $voucher = $this->model->table("voucher")->where($where)->order("id desc")->findPage($page,10,$pagetype,true);
        $data = $voucher['data'];
        foreach ($data as $key => $value) {
            $value['start_time'] = substr($value['start_time'],0,10);
            $value['end_time'] = substr($value['end_time'],0,10);
            $data[$key] = $value;
        }
        $voucher['data'] = $data;
        $voucher['status'] = "success";
        echo JSON::encode($voucher);
    }
    public function info()
    {
        if(ControllerExt::$isMobile){
            $this->layout = 'index';
            $this->assign("footer","user");
        }

        $info = $this->model->table("customer as cu ")->fields("cu.*,us.email,us.name,gr.name as gname")->join("left join user as us on cu.user_id = us.id left join grade as gr on cu.group_id = gr.id")->where("cu.user_id = ".$this->user['id'])->find();
        if($info){
            $this->assign("info",$info);
            $info = array_merge($info,Req::args());
            $this->redirect("info",false,$info);
        }else Tiny::Msg($this, 404);

    }
    public function info_save()
    {
        $rules = array('name:required:昵称不能为空!','real_name:required:真实姓名不能为空!','sex:int:性别必需选择！','birthday:date:生日日期格式不正确！','mobile:mobi:手机格式不正确','province:[1-9]\d*:选择地区必需完成','city:[1-9]\d*:选择地区必需完成','county:[1-9]\d*:选择地区必需完成');
        $info = Validator::check($rules);
        if(is_array($info)){
            $this->redirect("info",false,array('msg'=>array("info",$info['msg'])));
        }else{
            $data = array(
                    'name'=>Filter::txt(Req::args('name')),
                    'real_name'=>Filter::text(Req::args('real_name')),
                    'sex'=>Filter::int(Req::args('sex')),
                    'birthday'=>Filter::sql(Req::args('birthday')),
                    'mobile'=>Filter::int(Req::args('mobile')),
                    'phone'=>Filter::sql(Req::args('phone')),
                    'province'=>Filter::int(Req::args('province')),
                    'city'=>Filter::int(Req::args('city')),
                    'county'=>Filter::int(Req::args('county')),
                    'addr'=>Filter::text(Req::args('addr'))
                );
            $name = Filter::sql(Req::args("name"));
            $id = $this->user['id'];
            $this->model->table("user")->data(array("name"=>$name))->where("id=$id")->update();

            $this->model->table("customer")->data($data)->where("user_id=$id")->update();
            $obj = $this->model->table("user as us")->join("left join customer as cu on us.id = cu.user_id")->fields("us.*,cu.group_id,cu.login_time")->where("us.id=$id")->find();
                        $this->safebox->set('user',$obj,$this->cookie_time);
            $this->redirect("info",false,array('msg'=>array("success","保存成功！")));
        }
    }
    public function attention()
    {

        if(ControllerExt::$isMobile){
            $this->layout = 'index';
            $this->assign("footer","user");
        }

        $page = Filter::int(Req::args('p'));
        $attention = $this->model->table("attention as at")->fields("at.*,go.name,go.store_nums,go.img,go.sell_price,go.id as gid")->join("left join goods as go on at.goods_id = go.id")->where("at.user_id = ".$this->user['id'])->findPage($page);
        $this->assign("attention",$attention);
        $this->redirect();
    }
    public function attention_del()
    {
        $id = Filter::int(Req::args("id"));
        if(is_array($id)){
            $ids = implode(",", $id);
        }else{
            $ids = $id;
        }
        $this->model->table("attention")->where("id in($ids) and user_id=".$this->user['id'])->delete();
        $this->redirect("attention");
    }

    public function order()
    {
        $status = Req::get("status");
        if($status){
            $_status = '';
            switch($status){
                case 1:
                case 2:
                    $_status = '1,2';
                break;
                case 3:
                    $_status = '3';
                break;
                case 4:
                    $_status = '4';
                break;
                case 5:
                case 6:
                    $_status = '5,6';
                break;
                default:
                    $_status = '1,2';
                    $status = 1;
                break;
            }
            $this->assign("where","status in(".$_status.") and user_id=".$this->user['id']);
            $this->assign("status",$status);
        }else{
            $this->assign("where","status in (1,2) and user_id=".$this->user['id']);
            $this->assign("status",1);
        }
        $this->redirect();
    }

    public function order_detail()
    {
        if(ControllerExt::$isMobile){
            $this->layout = 'index';
            $this->assign("footer","user");
        }

        $id = Filter::int(Req::args("id"));
        $orderModel = new Model("order as od");
        $order = $orderModel->fields("od.*,pa.pay_name")->join("left join payment as pa on od.payment = pa.id")->where("od.id = $id and od.user_id=".$this->user['id'])->find();

        if($order){
            $orderGoodsModel = new Model("order_goods as og ");
            $docInvoiceModel = new Model("doc_invoice as di");
            $invoice = $docInvoiceModel->fields("di.*,ec.code as ec_code,ec.name as ec_name,ec.alias as ec_alias")->join("left join express_company as ec on di.express_company_id = ec.id")->where("di.order_id=".$id)->find();
            $order_goods = $orderGoodsModel->join("left join goods as go on og.goods_id = go.id left join products as pr on og.product_id = pr.id")->where("og.order_id=".$id)->findAll();
            $area_ids = $order['province'].','.$order['city'].','.$order['county'];

            $headStore = Config::getInstance('config')->get('headStore');
            $areaModel = new Model('area',$headStore,'salve');
            if($area_ids!='')$areas = $areaModel->where("id in ($area_ids)")->findAll();
            $parse_area = array();
            foreach ($areas as $area) {
                $parse_area[$area['id']] = $area['name'];
            }
            $this->assign("parse_area",$parse_area);
            $this->assign("order_goods",$order_goods);
            $this->assign("invoice",$invoice);
            $this->assign("order",$order);
            $this->redirect();
        }else{
            Tiny::Msg($this, 404);
        }
    }
    //订单签收
    public function order_sign()
    {
        $id = Filter::int(Req::args("id"));
        $info = array('status'=>'fail');
        $result = $this->model->table('order')->where("id=$id and user_id=".$this->user['id']." and status=3 and pay_status=1 and delivery_status=1")->data(array('delivery_status'=>2,'status'=>4,'completion_time'=>date('Y-m-d H:i:s')))->update();

        $serverName = Tiny::getServerName();

        Log::orderlog($id,'会员:'.$this->user['name'],'订单已签收','订单已签收','success',$serverName['top']);

        if($result){
            $info = array('status'=>'success');
            //提取购买商品信息
            $products = $this->model->table('order as od')->join('left join order_goods as og on od.id=og.order_id')->where('od.id='.$id)->findAll();
            foreach ($products as $product) {
                $data = array('goods_id'=>$product['goods_id'],'user_id'=>$this->user['id'],'order_no'=>$product['order_no'],'buy_time'=>$product['create_time']);
                $this->model->table('review')->data($data)->insert();
            }
        }
        echo JSON::encode($info);
    }
    public function address()
    {
        if(ControllerExt::$isMobile){
            $this->layout = 'index';
            $this->assign("footer","user");
        }

        $model = new Model("address");
        $address = $model->where("user_id=".$this->user['id'])->order("id desc")->findAll();
        $area_ids = array();
        foreach ($address as $addr) {
            $area_ids[$addr['province']] = $addr['province'];
            $area_ids[$addr['city']] = $addr['city'];
            $area_ids[$addr['county']] = $addr['county'];
        }
        $area_ids = implode(',', $area_ids);
        $areas = array();
        $headStore = Config::getInstance('config')->get('headStore');
        $areaModel = new Model('area',$headStore,'salve');
        if($area_ids!='')$areas = $areaModel->where("id in ($area_ids)")->findAll();
        $parse_area = array();
        foreach ($areas as $area) {
            $parse_area[$area['id']] = $area['name'];
        }
        $this->assign("address",$address);
        $this->assign("parse_area",$parse_area);
        $this->redirect();
    }
    public function address_del()
    {
        $id = Filter::int(Req::args("id"));
        $this->model->table("address")->where("id=$id and user_id=".$this->user['id'])->delete();
        $this->redirect("address");
    }
    public function index()
    {
        $id = $this->user['id'];

        if(ControllerExt::$isMobile){
            $this->layout = 'index';
            $this->assign("footer","user");
        }

        $customer = $this->model->table("customer as cu")->fields("cu.*,gr.name as gname")->join("left join grade as gr on cu.group_id = gr.id")->where("cu.user_id = $id")->find();
        $orders = $this->model->table("order")->where("user_id = $id")->findAll();
        $order = array('amount'=>0,'pending'=>0);
        foreach ($orders as $obj) {
            if($obj['status']==4)$order['amount'] += $obj['order_amount'];
            else if($obj['status']<3)$order['pending']++;
        }
        $comment = $this->model->table("review")->fields("count(*) as num")->where("user_id = $id and status=0")->find();
        $this->assign("comment",$comment);
        $this->assign("order",$order);
        $this->assign("customer",$customer);
        $this->assign("footer","user");
        $this->redirect();
    }

    protected function order_status($item)
    {
        $status = $item['status'];
        $pay_status = $item['pay_status'];
        $delivery_status = $item['delivery_status'];
        $str = '';
        switch ($status) {
            case '1':
                $str = '<span class="red">等待付款</span> <a href="'.Url::urlFormat("/simple/order_status/order_id/$item[id]").'" class="btn btn-mini">付款</a>';
                break;
            case '2':
                if($pay_status ==1) $str = '<span class="yellow">等待审核</span>';
                else {
                    $payment_info = Common::getPaymentInfo($item['payment']);
                    if($payment_info['class_name']=='received')
                        $str = '<span class="red">等待审核 <a href="'.Url::urlFormat("/simple/order_status/order_id/$item[id]").'" class="btn btn-mini">另选支付</a></span>';
                    else
                    $str = '<span class="red">等待付款 <a href="'.Url::urlFormat("/simple/order_status/order_id/$item[id]").'" class="btn btn-mini">付款</a></span>';
                }
                break;
            case '3':
                if($delivery_status == 0) $str = '<span class="green">等待发货</span>';
                else if($delivery_status == 1) $str = '<span class="green">已发货</span> <a href="javascript:;" class="btn btn-mini" onclick="order_sign('.$item['id'].')">已收货</a>';
                if($pay_status==3)$str = '<span class="green">已退款</span>';
                break;
            case '4':
                $str = '<span class="gray"><b>已完成</b></span>';
                if($pay_status==3)$str = '<span class="green">已退款</span>';
                break;
            case '5':
                $str = '<span class="gray"><s>已取消</s></span>';
                break;
            case '6':
                $str = '<span class="gray"><s>已作废</s></span>';
                break;
            default:
                # code...
                break;
        }
        return $str;
    }

    //检测用户是否在线
    private function checkOnline()
    {
        if(isset($this->user)&& $this->user['name']!=null)
            return true;
        else return false;
    }
}
