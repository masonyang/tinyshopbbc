<?php
/**
 * 订单管理
 *
 * @author masonyang
 * @package OrderController
 */
class OrderController extends Controller
{
	public $layout='admin';
	private $domain = null;
	private $manager = null;
	private $parse_returns_status = array(0=>'<b class="red">等待审核</b>',1=>'<b class="red">等待回寄货品</b>',2=>'<b class="red">拒绝</b>',3=>'货品回寄中',4=>'已结束');
	public $needRightActions = array('*'=>true);
	public function init()
	{
		$menu = new Menu();
		$this->assign('mainMenu',$menu->getMenu());
		$menu_index = $menu->current_menu();
		$this->assign('menu_index',$menu_index);
		$this->assign('subMenu',$menu->getSubMenu($menu_index['menu']));
		$this->assign('menu',$menu);
		$nav_act = Req::get('act')==null?$this->defaultAction:Req::get('act');
		$nav_act = preg_replace("/(_edit)$/", "_list", $nav_act);
		$this->assign('nav_link','/'.Req::get('con').'/'.$nav_act);
		$this->assign('node_index',$menu->currentNode());
		$this->safebox = Safebox::getInstance();
		$this->manager = $this->safebox->get('manager');
		$this->assign('manager',$this->manager);

		$currentNode = $menu->currentNode();
        if(isset($currentNode['name']))$this->assign('admin_title',$currentNode['name']);

        $branchStore = null;
        if(isset($_COOKIE['branchStore'])){
            $branchStore = ($_COOKIE['branchStore']== 'all') ? null :$_COOKIE['branchStore'] ;
        }

        $this->domain = $branchStore;
        $this->assign('domain',$branchStore);

	}
	public function noRight(){
		$this->layout = '';
		$this->redirect("admin/noright");
	}
	public function doc_refund_list(){
		$condition = Req::args('condition');
		$condition_str = Common::str2where($condition);
		if($condition_str)$this->assign("where",$condition_str);
		else $this->assign("where","1=1");
		$this->assign("condition",$condition);
		$this->redirect();
	}

	public function doc_refund_view()
	{
		$this->layout = "blank";
		$id = Req::args("id");
		$this->assign("id",$id);
		$this->redirect();
	}
	public function doc_refund_edit()
	{
		$this->layout = "blank";
		$id = Req::args("id");
		$this->assign("id",$id);
		$this->redirect();
	}
	public function doc_refund_save(){
		$pay_status = Req::args("pay_status");
		$model = new Model("doc_refund",$this->domain);
		$id = Req::args("id");
		$data = array('pay_status'=>$pay_status,'handling_idea'=>Req::args("handling_idea"),'handling_time'=>date('Y-m-d H:i:s'),'admin_id'=>$this->manager['id']);
		if($pay_status==2){
			$data['channel']=Req::args("channel");
			$data['bank_account'] = Req::args("bank_account");
			$data['amount'] = Req::args("amount");
			$obj = $model->table('doc_refund')->where("id=$id")->find();
			if($obj){
				$model->table('order')->data(array('pay_status'=>3))->where("id=".$obj['order_id'])->update();
			}

		}
		$model->table('doc_refund')->data($data)->where("id=$id")->update();
		echo "<script>parent.updateInfo({id:$id,pay_status:$pay_status})</script>";
	}
	public function doc_receiving_list(){
		$condition = Req::args('condition');
		$condition_str = Common::str2where($condition);
		if($condition_str)$this->assign("where",$condition_str);
		else $this->assign("where","1=1");
		$this->assign("condition",$condition);
		$this->redirect();
	}
	public function doc_receiving_view()
	{
		$this->layout = "blank";
		$id = Req::args("id");
		$this->assign("id",$id);
		$this->redirect();
	}
	public function doc_returns_list(){
		$condition = Req::args('condition');
		$condition_str = Common::str2where($condition);
		if($condition_str)$this->assign("where",$condition_str);
		else $this->assign("where","1=1");
		$this->assign('parse_status',$this->parse_returns_status);
		$this->assign("condition",$condition);
		$this->redirect();
	}
	public function doc_returns_end(){
		$id = Req::args('id');
		$handling_idea = Req::args('handling_idea');
		$info = array('status'=>'fail','msg'=>'备注信息不能为空!');
		if($handling_idea){
			$model = new Model('doc_returns',$this->domain);
			$model->data(array('handling_idea'=>$handling_idea,'status'=>4))->where("id=$id")->update();
			$info = array('status'=>'success','msg'=>'');
		}
		echo JSON::encode($info);
	}
	public function doc_returns_view()
	{
		$this->layout = "blank";
		$id = Req::args("id");
		$this->assign("id",$id);
		$this->assign('parse_status',$this->parse_returns_status);
		$this->redirect();
	}
	public function doc_returns_edit()
	{
		$this->layout = "blank";
		$id = Req::args("id");
		$this->assign("id",$id);
		$this->assign('parse_status',$this->parse_returns_status);
		$this->redirect();
	}
	public function doc_returns_save(){
		$status = Req::args("status");
		$model = new Model("doc_returns",$this->domain);
		$id = Req::args("id");
		$data = array('status'=>$status,'handling_idea'=>Req::args("handling_idea"),'admin_id'=>$this->manager['id']);
		$model->data($data)->where("id=$id")->update();
		echo "<script>parent.updateInfo({id:$id,status:$status})</script>";
	}
	public function doc_invoice_list(){
		$condition = Req::args('condition');
		$condition_str = Common::str2where($condition);
		if($condition_str)$this->assign("where",$condition_str);
		else $this->assign("where","1=1");
		$this->assign("condition",$condition);
		$this->redirect();
	}
	public function doc_invoice_view()
	{
		$this->layout = "blank";
		$id = Req::args("id");
		$this->assign("id",$id);
		$this->redirect();
	}
	public function doc_invoice_save(){
		Req::post("admin",$this->manager['name']);
		Req::post("create_time",date('Y-m-d H:i:s'));
		Req::post("invoice_no",date('YmdHis').rand(100,999));
		$order_id = Filter::int(Req::args("order_id"));
		$express_no = Filter::str(Req::args("express_no"));
		$express_company_id = Filter::int(Req::args('express_company_id'));

        //处理发货之前,先看下分销商预存款里是否有充足的余额 来扣除，有的话则 扣除预存款 并生成预存款扣除记录，然后再发货

        //订单所有产品的批发价之和  == 分销商预存款金额 比较

        //同步发货信息
        $model = new Model("doc_invoice",$this->domain);
        $order_info = $model->table("order")->where("id=$order_id")->find();

        $orderGoodsModelObj = new Model('order_goods',$this->domain);
        $managerObj = new Model('manager');//去分店 manager表中的数据
        $manager = $managerObj->fields('deposit,distributor_id,site_url')->where('roles="administrator"')->find();

        //todo 需要在order_goods 表加上trade price 批发价 字段，并在结算时候 写入trade_price
        $ordergoods = $orderGoodsModelObj->fields('sum(trade_price) as tradeprice')->where('order_id='.$order_id)->findAll();

        $depost = $manager['deposit'] - $ordergoods[0]['tradeprice'];

        if(($depost) <= 0){
            echo "<script> alert('分销商预存款不足,发货失败!'); parent.send_dialog_close();</script>";
            exit;
        }else{
            $zdModel = new Model("distributor","zd","master");
            $distrInfo = $zdModel->where("distributor_id=".$manager['distributor_id'])->find();
            $zdModel->data(array('deposit'=>$distrInfo['deposit'] + $ordergoods[0]['tradeprice']))->where("distributor_id=".$manager['distributor_id'])->update();

            //同步预存款金额到分店
            $managerObj = new Model("manager",$manager['site_url'],"master");
            $distrInfo = $managerObj->where("distributor_id=".$manager['distributor_id'])->find();
            $distrInfo['deposit'] -= $ordergoods[0]['tradeprice'];

            $managerObj->data(array('deposit'=>$distrInfo['deposit']))->where("id=".$distrInfo['id'])->update();

            $manager = $this->safebox->get('manager');
            $data['op_name'] = $manager['name'];
            $data['op_time'] = time();
            $data['op_id'] = $manager['id'];
            $data['money'] = $ordergoods[0]['tradeprice'];
            $data['action'] = 'minus';
            $data['op_ip'] = Chips::getIP();
            $data['memo'] = '操作人【'.$manager['name'].'】对订单 '.$order_info['order_bn'].'进行发货 扣除'.$ordergoods[0]['tradeprice'].'元, 充值后 预存款剩余金额:'.$distrInfo['deposit'].'元';
            Log::rechange($data,$distrInfo['site_url']);

        }


		$delivery_status = Req::args("delivery_status");
		if($delivery_status==3){
			$model->where("order_id=$order_id")->insert();
		}
		else{
			$obj = $model->where("order_id=$order_id")->find();
			if($obj){
				$model->where("order_id=$order_id")->update();
			}else{
				$model->where("order_id=$order_id")->insert();
			}
		}

		if($order_info){
			if($order_info['trading_info']!=''){
				$payment_id = $order_info['payment'];
				$payment = new Payment($payment_id);
				$payment_plugin = $payment->getPaymentPlugin();
				$express_company = $model->table('express_company')->where('id='.$express_company_id)->find();
				if($express_company) $express = $express_company['name'];
				else $express = $express_company_id;
				//处理同步发货
				$delivery = $payment_plugin->afterAsync();
				if($delivery!=null && method_exists($delivery, "send")) $delivery->send($order_info['trading_info'],$express,'express_no');
			}
		}
		$model->table("order")->where("id=$order_id")->data(array('delivery_status'=>1,'send_time'=>date('Y-m-d H:i:s')))->update();

        //处理发货之后,计算收益逻辑 并加入到预存款中，并生成一条添加预存款记录
        //todo
        //如果没有用支付宝支付的话 是不是公式就变成了收益=订单金额-分销商批发价格
        //分销商批发价格 = 所有产品的批发价之和
        //收益=订单金额-分销商批发价格-(订单金额*0.6%)

        $paymentModel = new Model("payment as pa");
        $paymentInfo = $paymentModel->fields('pa.*,pi.class_name,pi.name,pi.logo')->join("left join pay_plugin as pi on pa.plugin_id = pi.id")->where("pa.id = '".$order_info['payment']."' or pi.class_name = '".$order_info['payment']."'")->find();

        if(in_array($paymentInfo['class_name'],array('alipaydirect','alipaytrad','alipay','alipaygateway','alipaymobile'))){//支付宝支付  收益=订单金额-分销商批发价格-(订单金额*0.6%)

            $payfee = $order_info['order_amount'] * ($paymentInfo['pay_fee']/100);

            $income = $order_info['order_amount'] - $ordergoods[0]['tradeprice'] - $payfee;
        }else{ //其他方式支付 收益=订单金额-分销商批发价格
            $income = $order_info['order_amount'] - $ordergoods[0]['tradeprice'];
        }

        $zdModel = new Model("distributor","zd","master");
        $distrInfo = $zdModel->where("distributor_id=".$manager['distributor_id'])->find();
        $zdModel->data(array('deposit'=>$distrInfo['deposit'] + $income))->where("distributor_id=".$manager['distributor_id'])->update();

        $managerObj = new Model("manager",$manager['site_url'],"master");
        $distrInfo = $managerObj->where("distributor_id=".$manager['distributor_id'])->find();
        $distrInfo['deposit'] += $income;

        $managerObj->data(array('deposit'=>$distrInfo['deposit']))->where("id=".$distrInfo['id'])->update();

        $manager = $this->safebox->get('manager');
        $data['op_name'] = $manager['name'];
        $data['op_time'] = time();
        $data['op_id'] = $manager['id'];
        $data['money'] = $income;
        $data['action'] = 'add';
        $data['op_ip'] = Chips::getIP();
        $data['memo'] = '操作人【'.$manager['name'].'】对订单 '.$order_info['order_bn'].'进行发货 增加'.$income.'元, 充值后 预存款剩余金额:'.$distrInfo['deposit'].'元';
        Log::rechange($data,$distrInfo['site_url']);

		echo "<script>parent.send_dialog_close();</script>";
	}

	public function order_list(){

		$condition = Req::args("condition");
		$condition_str = Common::str2where($condition);
		if($condition_str) $this->assign("where",$condition_str);
		else{
			$status = Req::args("status");
			if($status)
				$this->assign("where","od.status=".$status);
			else
				$this->assign("where","1=1");
		}
		$this->assign("condition",$condition);
		$this->assign("status",array('0'=>'<span class="red">等待审核</span>','1'=>'<span class="red">等待审核</span>','2'=>'<span class="red">等待审核</span>','3'=>'已审核','4'=>'已完成','5'=>'已取消','6'=>'<span class="red"><s>已作废</s></span>'));
		$this->assign("pay_status",array('0'=>'<span class="red">未付款</span>','1'=>'已付款','2'=>'申请退款','3'=>'已退款'));
		$this->assign("delivery_status",array('0'=>'<span class="red">未发货</span>','1'=>'已发货','2'=>'已签收','3'=>'申请换货','4'=>'已换货'));
        $headStore = Config::getInstance('config')->get('headStore');
        $model = new Model("payment",$headStore,"salve");
		$items = $model->findAll();
		$payment = array();
		foreach ($items as $item) {
			$payment[$item['id']] = $item['pay_name'];
		}

		$this->assign("payment",$payment);
		$this->redirect();
	}

	public function order_edit()
	{
		$this->layout = "blank";
		$id = Req::args("id");
		$model = new Model("order",$this->domain);
		$order = $model->where("id=$id")->find();
		if($order){
			if($order['status']==1 || $order['status'] == 2){
				$this->assign("id",$id);
				$this->redirect();
			}
		}
	}
	public function order_view()
	{
		$this->layout = "blank";
		$id = Req::args("id");
		$model = new Model("order",$this->domain);
		$order = $model->where("id=$id")->find();
		if($order){
			$this->assign("id",$id);
			$this->redirect();

		}
	}
	public function order_send()
	{
		$this->layout = "blank";
		$id = Req::args("id");
		$model = new Model("order",$this->domain);
		$order = $model->where("id=$id")->find();
		if($order){
			if($order['status']==3){

                $expressModel = new Model('express');
                $ex = $expressModel->fields('name as exname')->where('express_company_id='.$order['express'])->find();

                $order['exname'] = $ex['exname'];

                $paymentModel = new Model('payment');
                $py = $paymentModel->fields('pay_name as payname')->where('id='.$order['payment'])->find();
                $order['payname'] = $py['payname'];

                $this->assign("orderInfo",$order);
                $this->assign("id",$id);
				$this->redirect();
			}
		}
	}
	public function order_status(){
		$parse_status = array('3'=>'审核订单','4'=>'完成订单成功','6'=>'作废订单');
		$id = Filter::int(Req::args("id"));
		$status = Req::args("status");
		$admin_remark = Req::args("remark");
		$model = new Model("order",$this->domain);
		$order = $model->where("id=$id")->find();
		$flag = false;
		$info = array();
		if($order){

			if($status){
				if($order['status']==1 || $order['status']==2){
					if($status == 3 || $status == 6) $flag = true;
				}
				if($order['status'] == 3){
					if($status == 4 || $status == 6) $flag = true;
					if($status==4)
					{
						//货到付款的订单处理
						$payment_plugin = Common::getPaymentInfo($order['payment']);
						if($payment_plugin!=null && $payment_plugin['class_name']=='received'){
							Order::updateStatus($order['order_no']);
						}
						//订单完成
						$model_tem = new Model('order',$this->domain);
						$model_tem->where("id=$id")->data(array('delivery_status'=>2,'status'=>4,'completion_time'=>date('Y-m-d H:i:s')))->update();
						//允许评价
						$model_tem = new Model('order as od',$this->domain);
			            $products = $model_tem->join('left join order_goods as og on od.id=og.order_id')->where('od.id='.$id)->findAll();
			            foreach ($products as $product) {
			                $data = array('goods_id'=>$product['goods_id'],'user_id'=>$order['user_id'],'order_no'=>$product['order_no'],'buy_time'=>$product['create_time']);
			                $model_tem->table('review')->data($data)->insert();
			            }

					}
				}
				if($order['status'] == 4 && $status == 6) $flag = true;
				if($flag){
					$model->where("id=$id")->data(array('status'=>$status,'admin_remark'=>$admin_remark))->update();
					$info  = array('status'=>'success','msg'=>$parse_status[$status]);
				}else{
					$info  = array('status'=>'fail','msg'=>$parse_status[$status]);
				}
			}else{
				$op = Req::args("op");
				if($op == 'note'){
					$model->where("id=$id")->data(array('admin_remark'=>$admin_remark))->update();
					$info  = array('status'=>'success','msg'=>'备注');
				}else if($op == 'del'){
					$model->where("id=$id")->delete();
					$info  = array('status'=>'success','msg'=>'删除');
				}
			}

		}else{
			$info  = array('status'=>'fail','msg'=>'不存在此订单');
		}
		echo JSON::encode($info);
	}
	//订单编辑
	public function order_save(){
		$model = new Model("order",$this->domain);
		$id = Req::args('id');
		$order = $model->where("id=".$id)->find();
		if($order){
			$adjust_amount = Req::args("adjust_amount");
			$voucher_fee = 0;
			if($order['voucher_id']){
				$voucher = unserialize($order['voucher']);
				$voucher_fee = $voucher['value'];
			}
			$amount = $order['real_amount']+$order['real_freight']+$order['handling_fee']+$order['taxes']-$order['discount_amount']-$voucher_fee;
			$order_amount = $amount + $adjust_amount;
			if($order_amount<0){
				$adjust_amount = 0 - $amount;
				$order_amount = 0;
			}
			Req::args("adjust_amount",$adjust_amount);
			Req::args("order_amount",$order_amount);
			$model->where("id=".$id)->update();
			Log::op($this->manager['id'],"修改订单","管理员[".$this->manager['name']."]:修改了订单 ".$order['order_no']);
		}
		echo "<script>parent.close();</script>";
	}

	public function order_del(){
        $this->redirect("order_list",false);
        exit;

		$id =  Req::args('id');
		//删除
		if(is_array($id)){
			$ids = implode(",", $id);
		}else{
			$ids = $id;
		}
		$model = new Model("order",$this->domain);
		$orders = $model->where("id in ($ids)")->findAll();
		//删除订单信息
		$flag = $model->where("id in ($ids)")->delete();
		//删除订单商品信息
		$model->table("order_goods")->where("order_id in ($ids)")->delete();
		//记录操作日志
		$order_nos = "";
		foreach ($orders as $order) {
			$order_nos .= $order['order_no']."、";
			//删除订单时，如果订单没有使用代金券，则退回。
			if($order['pay_status']==0 && $order['voucher_id']){
				$model->table("voucher")->where("id=".$order['voucher_id'])->data(array('status'=>0))->update();
			}
		}

		if($orders){
			Log::op($this->manager['id'],"删除订单","管理员[".$this->manager['name']."]:删除了订单 ".$order_nos);
			$order_nos = trim($order_nos,'、');
			if(strlen($order_nos)>66) $order_nos = substr($order_nos, 0,66)."...";
			$msg = array('success','成功删除了订单：'.$order_nos);
			$this->redirect("order_list",false,array('msg'=>$msg));
		}
		else  $this->redirect("order_list",false);
	}

	public function express_template_validator(){
		$rules = array('name:required:名称不能为空!','content:required:内容不能为空！');
		$info = Validator::check($rules);
		Req::args("content",trim(Req::args("content")));
		$model = new Model('express_template');
		if(Req::args("is_default")==1){

			$model->data(array('is_default'=>0))->update();
		}else{
			$obj = $model->where('is_default=1')->find();
			if($obj);
			else Req::args("is_default","1");
		}
		return $info;
	}
	public function ship_validator(){
		$model = new Model('ship',$this->domain);
		if(Req::args("is_default")==1){

			$model->data(array('is_default'=>0))->update();
		}else{
			$obj = $model->where('is_default=1')->find();
			if($obj);
			else Req::args("is_default","1");
		}
	}
	public function print_order(){
		$this->layout = 'blank';
		$this->title = '订单打印';
		$this->assign("id",Req::args("id"));
		$this->redirect();
	}
	public function print_product(){
		$this->layout = 'blank';
		$this->title = '购物单打印';
		$this->assign("id",Req::args("id"));
		$this->redirect();
	}
	public function print_picking(){
		$this->layout = 'blank';
		$this->title = '配货单打印';
		$this->assign("id",Req::args("id"));
		$this->redirect();
	}
	public function print_express(){
		$this->layout = 'blank';
		$this->title = '快递单打印';
		$id = Filter::int(Req::args("id"));
		if($id){
			$this->assign("id",$id);
			$template_id = Filter::int(Req::args("template_id"));
			if($template_id) $template_where = "id=$template_id";
			else $template_where = "is_default = 1";
			$ship_id = Filter::int(Req::args("ship_id"));
			if($ship_id) $ship_where = "id=$ship_id";
			else $ship_where = "is_default = 1";
			$model = new Model();
			$template = $model->table("express_template")->where($template_where)->find();
			$ship = $model->table("ship")->where($ship_where)->find();

            $orderModel = new MOdel('order',$this->domain);
			$order = $orderModel->where("id=$id")->find();

			$order_product = $orderModel->where("order_id = $id")->findAll();
			$total_weight = 0;
			$total_num = 0;
			foreach ($order_product as $key => $value) {
				$total_num++;
				$total_weight += $value['goods_weight'];
			}
			$content = '';
			$width = 840;
			$height = 480;
			$background = '';
			if($template && $ship && $order){
				$config = Config::getInstance();
				$site = $config->get('globals');
				$area_ids = array( $ship['province'], $ship['city'], $ship['county'],$order['province'], $order['city'], $order['county']);
				$area_ids = implode(",", $area_ids);
				$items = $model->table("area")->where("id in ($area_ids)")->findAll();
				$areas = array();
				foreach ($items as $item) {
					$areas[$item['id']] = $item['name'];
				}
				$content = $template['content'];
				$ship_area = $areas[$ship['province']].$areas[$ship['city']].$areas[$ship['county']];
				$order_area = $areas[$order['province']].$areas[$order['city']].$areas[$order['county']];

				$template_var = array("发货点-名称", "发货点-联系人", "发货点-地区1级", "发货点-地区2级", "发货点-地区3级", "发货点-地址", "发货点-电话", "发货点-手机", "收货人-姓名", "收货人-地区1级", "收货人-地区2级", "收货人-地区3级", "收货人-地址", "收货人-电话", "收货人-手机", "订单-订单编号", "订单-配送费用", "订单-手续费", "订单-订单总额", "订单-附言", "网站-名称", "网站-网址", "网站-联系地址", "网站-电话", "网站-邮箱", "时间-年", "时间-月", "时间-日", "时间-当前日期","订单-总商品重量","订单-总商品数量");
				$content_var = array($ship['ship_name'], $ship['ship_user_name'], $areas[$ship['province']], $areas[$ship['city']], $areas[$ship['county']], $ship_area.$ship['addr'], $ship['phone'], $ship['mobile'], $order['accept_name'], $areas[$order['province']], $areas[$order['city']], $areas[$order['county']], $order_area.$order['addr'], $order['phone'], $order['mobile'], $order['order_no'], $order['real_freight'], $order['handling_fee'], $order['order_amount'], $order['user_remark'], $site['site_name'], $site['site_url'], $site['site_addr'], $site['site_mobile'], $site['site_email'], date('Y'), date('m'), date('d'), date('Y-m-d'),$total_weight.'g',$total_num);
				$content = str_replace($template_var,$content_var, $content);
				$height = is_numeric($template['height'])?$template['height']:$height;
				$width = is_numeric($template['width'])?$template['width']:$width;
				$background = $template['background'];
			}

			$this->assign("content",$content);
			$this->assign("width",$width);
			$this->assign("height",$height);
			$this->assign("background",$background);
			$this->assign("template_id",$template_id);
			$this->assign("ship_id",$ship_id);
			$this->redirect();
		}

	}
	protected function parseOrderStatus($item)
    {
        $status = $item['status'];
        $pay_status = $item['pay_status'];
        $delivery_status = $item['delivery_status'];
        $str = '';
        switch ($status) {
            case '1':
                $str = '<span class="red">等待付款</span>';
                break;
            case '2':
                if($pay_status ==1) $str = '<span class="yellow">等待审核</span>';
                else $str = '<span class="red">等待付款</span>';
                break;
            case '3':
                if($delivery_status == 0) $str = '<span class="green">等待发货</span>';
                else if($delivery_status == 1) $str = '<span class="green">已发货</span>';
                break;
            case '4':
                $str = '<span class="gray"><b>已完成</b></span>';
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

    //批量下单功能
    public function batch_order()
    {
        $this->layout = 'blank';
        $customersModel = new Model('customer');
        $customers = $customersModel->findAll();

        $this->assign("customers",$customers);
        $this->redirect();
    }

    public function goods_select()
    {
        $this->layout = "blank";
        $s_type = Req::args("s_type");
        $s_content = Req::args("s_content");
        $where = "";
        if($s_content && $s_content!=''){
            if($s_type == 1){
                $where = " p.pro_no like '{$s_content}%'";
            }else if($s_type==2) {
                $where = " g.name like '{$s_content}%' ";
            }
        }

        $products_id = Req::args("products_id");
        if(is_array($products_id)){
            $products_id = implode(',', $products_id);
            $where .= " p.id not in($products_id)";
        }else{
            $where .= "";
        }
        $id = Req::args('id');
        if(!$id  || $id=='') $id = 0;

        $productsModel = new Model('products as p');
        $products = $productsModel->fields('p.*,g.name as gname')->join('left join goods as g on p.goods_id=g.id')->where($where)->findAll();

        $this->assign('products',$products);
        $result = array('products'=>'','s_content'=>$s_content);
        $result['products'] = '<colgroup><col width="60"/><col /><col /><col width="100"/><col width="100"/></colgroup>';

        foreach($products as $product){
            $result['products'] .= '<tr><td><input type="checkbox" name="products_id[]" value="'.$product['id'].'"></td><td>'.$product['pro_no'].'</td><td>'.$product['gname'].'</td><td>'.$product['sell_price'].'</td><td>'.$product['store_nums'].'</td></tr>';
        }
        echo json_encode($result);exit;

    }

    //批量下单处理
    public function batch_order_act()
    {

    }

}
