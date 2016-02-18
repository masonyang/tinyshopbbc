<?php
//订单处理类
class Order{

	public static function updateStatus($orderNo,$payment_id=0,$callback_info=null){
		$model = new Model("order");
		$order = $model->where("order_no='".$orderNo."'")->find();
		if(isset($callback_info['trade_no'])) $trading_info = $callback_info['trade_no'];
		else $trading_info = '';
		if(empty($order)) return false;
		if($order['pay_status']==1){
			return $order['id'];
		}else if($order['pay_status']==0){
			//更新订单信息
			$data = array(
				'status'     => 3,
				'pay_time'   => date('Y-m-d H:i:s'),
				'trading_info'=>$trading_info,
				'pay_status' => 1,
			);
			//修改用户最后选择的支付方式
			if($payment_id!=0){
				$data['payment'] = $payment_id;
			}else{
				$payment_id = $order['payment'];
			}
			//更新订单支付状态
			$model->table("order")->data($data)->where("id=".$order['id'])->update();

			//商品中优惠券的处理
			$products = $model->table("order_goods")->where("order_id=".$order['id'])->findAll();
			$goods_ids = array();
			foreach ($products as $pro) {
				$prom = unserialize($pro['prom_goods']);
				if(isset($prom['prom'])){
					$prom = $prom['prom'];
					//商品中优惠券的处理
					if(isset($prom['type']) && $prom['type']==3 && $order['type']==0){
						$voucher_template_id = $prom['expression'];
						$voucher_template = $model->table("voucher_template")->where("id=".$voucher_template_id)->find();
						Common::paymentVoucher($voucher_template,$order['user_id']);
						//优惠券发放日志
					}
				}
				//更新货品中的库存信息
				$goods_nums = $pro['goods_nums'];
				$product_id = $pro['product_id'];
				$model->table("products")->where("id=".$product_id)->data(array('store_nums'=>"`store_nums`-".$goods_nums))->update();
				$goods_ids[$pro['goods_id']] = $pro['goods_id'];
			}


			//发货提醒
			$template_data = $order;
			$area_parse = array();
			$area_model = new Model('area');
			$areas = $area_model->where("id in(".$order['province'].",".$order['city'].",".$order['county'].")")->findall();
			foreach ($areas as $area) {
				$area_parse[$area['id']] = $area['name'];
			}
			$template_data['address'] = $area_parse[$order['province']].$area_parse[$order['city']].$area_parse[$order['county']].$order['addr'];
            $NoticeService = new NoticeService();
            $NoticeService->send('payment_order',$template_data);

			//更新商品表里的库存信息
			foreach ($goods_ids as $id) {
				$objs = $model->table('products')->fields('sum(store_nums) as store_nums')->where('goods_id='.$id)->query();
				if($objs){
					$num = $objs[0]['store_nums'];
					$model->table('goods')->data(array('store_nums'=>$num))->where('id='.$id)->update();
				}
			}

			//普通订单的处理
			if($order['type']==0){
				//订单优惠券活动事后处理
				$prom = unserialize($order['prom']);
				if(!empty($prom) && $prom['type']==3){
					$voucher_template_id = $prom['expression'];
						$voucher_template = $model->table("voucher_template")->where("id=".$voucher_template_id)->find();
						Common::paymentVoucher($voucher_template,$order['user_id']);
				}
			}else if($order['type']==1){
				//更新团购信息
				$prom = unserialize($order['prom']);
				if(isset($prom['id'])){
					$groupbuy = $model->table("groupbuy")->where("id=".$prom['id'])->find();
					if($groupbuy){
						$goods_num = $groupbuy['goods_num'];
						$order_num = $groupbuy['order_num'];
						$max_num = $groupbuy['max_num'];
						$end_time = $groupbuy['end_time'];
						$time_diff = time()-strtotime($end_time);
						foreach ($products as $pro){
						$data = array('goods_num'=>($goods_num+$pro['goods_nums']),'order_num'=>$order_num+1);
						}
						if($time_diff>=0 || $max_num<=$data['goods_num']) $data['is_end'] = 1;
						$model->table("groupbuy")->where("id=".$prom['id'])->data($data)->update();
					}
				}
			}else if($order['type']==2){
				//更新抢购信息
				$prom = unserialize($order['prom']);
				if(isset($prom['id'])){
					$flashbuy = $model->table("flash_sale")->where("id=".$prom['id'])->find();
					if($flashbuy){
						$goods_num = $flashbuy['goods_num'];
						$order_num = $flashbuy['order_num'];
						$max_num = $flashbuy['max_num'];
						$end_time = $flashbuy['end_time'];
						$time_diff = time()-strtotime($end_time);
						foreach ($products as $pro){
						$data = array('goods_num'=>($goods_num+$pro['goods_nums']),'order_num'=>$order_num+1);
						}
						if($time_diff>=0 || $max_num<=$data['goods_num']) $data['is_end'] = 1;
						$model->table("flash_sale")->where("id=".$prom['id'])->data($data)->update();
					}
				}
			}
			//送积分
			Pointlog::write($order['user_id'], $order['point'], '购买商品，订单：'.$order['order_no'].' 赠送'.$order['point'].'积分');

			//对使用代金券的订单，修改代金券的状态
			if($order['voucher_id']){
				$model->table("voucher")->where("id=".$order['voucher_id'])->data(array('status'=>1))->update();
			}

			//生成收款单
			$receivingData = array(
				'order_id'=>$order['id'],
				'user_id'=>$order['user_id'],
				'amount'=>$order['order_amount'],
				'create_time'=>date('Y-m-d H:i:s'),
				'payment_time'=>date('Y-m-d H:i:s'),
				'doc_type'=>0,
				'payment_id'=>$payment_id,
				'pay_status'=>1
			);
			$model->table("doc_receiving")->data($receivingData)->insert();
			//统计会员规定时间内的消费金额,进行会员升级。
			$config = Config::getInstance();
			$config_other = $config->get('other');
			$grade_days = isset($config_other['other_grade_days'])?intval($config_other['other_grade_days']):365;
			$time = date("Y-m-d H:i:s",strtotime("-".$grade_days." day"));
			$obj = $model->table("doc_receiving")->fields("sum(amount) as amount")->where("user_id=".$order['user_id']." and doc_type=0 and payment_time > '$time'")->query();
			if(isset($obj[0])){
				$amount = $obj[0]['amount'];
				$grade = $model->table('grade')->where('money < '.$amount)->order('money desc')->find();
				if($grade){
					$model->table('customer')->data(array('group_id'=>$grade['id']))->where("user_id=".$order['user_id'])->update();
				}
			}
			return $order['id'];
		}else{
			return false;
		}
	}

	//充值
	public static function recharge($recharge_no,$payment_id=0,$callback_info=null){
		$model = new Model("recharge");
		$recharge = $model->where("recharge_no='".$recharge_no."'")->find();
		if(empty($recharge)){
			return false;
		}
		if($recharge['status']==1){
			return $recharge['id'];
		}else{
			//更新充值订单信息
			$model->data(array('status'=>1))->where("recharge_no='".$recharge_no."'")->update();
			$account = $recharge['account'];
			$user_id = $recharge['user_id'];
			//给用户充值
			$result = $model->table("customer")->data(array('balance'=>"`balance`+".$account))->where("user_id=".$user_id)->update();
			if($result){
				//填写收款单
				$receivingData = array(
					'order_id'=>$recharge['id'],
					'user_id'=>$user_id,
					'amount'=>$account,
					'create_time'=>date('Y-m-d H:i:s'),
					'payment_time'=>date('Y-m-d H:i:s'),
					'doc_type'=>1,
					'payment_id'=>$payment_id,
					'pay_status'=>1
				);
				$model->table("doc_receiving")->data($receivingData)->insert();

				//写充值日志
				Log::balance($account,$user_id,'用户充值,充值编号：'.$recharge_no,1);
				return $recharge['id'];
			}
			return false;
		}
	}

}
