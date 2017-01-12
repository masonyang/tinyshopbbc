<?php
//订单处理类
class Order{
	
	/**
	 * 发货状态(未发货)
	 */
	const DELIVERY_STATUS_NOT_SHIPPED = 0;
	
	/**
	 * 发货状态(已发货)
	 */
	const DELIVERY_STATUS_SHIPPED = 1;
	
	/**
	 * 发货状态(已签收)
	 */
	const DELIVERY_STATUS_RECEIVED = 2;
	
	/**
	 * 发货状态(申请换货)
	 */
	const DELIVERY_STATUS_APPLY_REFUND = 3;
	
	/**
	 * 发货状态(已换货)
	 */
	const DELIVERY_STATUS_HAS_REFUNDED = 4;
	
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

            $serverName = Tiny::getServerName();
            if($serverName['top'] != 'zd'){
                $zdModel = new Model('order','zd','master');
                $zdModel->data($data)->where('outer_id='.$order['id'].' and site_url="'.$serverName['top'].'"')->update();
            }

			//商品中优惠券的处理
			$products = $model->table("order_goods")->where("order_id=".$order['id'])->findAll();
//			$goods_ids = array();
//			foreach ($products as $pro) {
//				$prom = unserialize($pro['prom_goods']);
//				if(isset($prom['prom'])){
//					$prom = $prom['prom'];
//					//商品中优惠券的处理
//					if(isset($prom['type']) && $prom['type']==3 && $order['type']==0){
//						$voucher_template_id = $prom['expression'];
//						$voucher_template = $model->table("voucher_template")->where("id=".$voucher_template_id)->find();
//						Common::paymentVoucher($voucher_template,$order['user_id']);
//						//优惠券发放日志
//					}
//				}
////				//更新货品中的库存信息
////				$goods_nums = $pro['goods_nums'];
////				$product_id = $pro['product_id'];
////				$model->table("products")->where("id=".$product_id)->data(array('store_nums'=>"`store_nums`-".$goods_nums,'freeze_nums'=>"`freeze_nums`-".$goods_nums))->update();
////				$goods_ids[$pro['goods_id']] = $pro['goods_id'];
//			}


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

//			//更新商品表里的库存信息
//			foreach ($goods_ids as $id) {
//				$objs = $model->table('products')->fields('sum(store_nums) as store_nums')->where('goods_id='.$id)->query();
//				if($objs){
//					$num = $objs[0]['store_nums'];
//					$model->table('goods')->data(array('store_nums'=>$num))->where('id='.$id)->update();
//				}
//			}

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

            if($serverName['top'] != 'zd'){

                $productsInfo = self::getOrderProductsInfo($order['id'],$serverName['top']);

                $OrderNoticeService = new OrderNoticeService();

                $OrderNoticeService->updateRealAndFreezeNums($productsInfo,'-');
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
	
	/**
	 * 获取订单信息
	 * @param $orderNo
	 * @return Obj
	 */
	public static function getOrderInfo( $orderNo )
	{
		return Model::getInstance( 'order' )
			->where("order_no='".$orderNo."'")
			->find();
	}

	/**
	 * 获取订单的电子面单
	 * @param $orderNo
	 * @return string
	 */
	public static function getEssTemplate( $orderNo )
	{
		$template = Model::getInstance('doc_invoice')
			->where("order_no='".$orderNo."'")
			->find();
        return $template['ess_template'] ? base64_decode($template['ess_template']) : '';
	}
	
	/**
	 * TODO
	 * @param $orderNo
	 * @param array $params
	 * @return bool|string
	 */
	public static function genEssOrder( $orderNo , $params = array() )
	{
		if( $essTemplate = self::getEssTemplate( $orderNo ) )
		{
			return $essTemplate;
		}
		
		// 订单没选发货公司
		if( !($orderInfo = self::getOrderInfo( $orderNo ))
			|| (empty( $orderInfo['express'] ))
		)
		{
			return false;
		}
		
		//todo
        $expressModel = new Model('express_company');

        $express = $expressModel->fields('*')->where('id='.$orderInfo['express'])->find();

        if($express['is_ess'] == 0){
            return false;
        }

        $eorder = array();
        $eorder["ShipperCode"] = $express['name'];//"SF";
        $eorder["OrderCode"] = $orderNo;
        $eorder["PayType"] = 1;
        $eorder["ExpType"] = 1;

        if($express['customer_name']){
            $eorder["CustomerName"] = $express['customer_name'];
        }

        if($express['customer_pwd']){
            $eorder["CustomerPwd"] = $express['customer_pwd'];
        }

        if($express['month_code']){
            $eorder["MonthCode"] = $express['month_code'];
        }

        if($express['send_site']){
            $eorder["SendSite"] = $express['send_site'];
        }

        $config = Config::getInstance();

        $globals = $config->get('globals');

        $sender["ProvinceName"] = $globals['province'];
        $sender["CityName"] = $globals['city'];
        $sender["ExpAreaName"] = $globals['county'];
        $sender["Name"]= $globals['site_contacter'];
        $sender["Mobile"] = $globals['site_phone'] ?  $globals['site_phone'] : $globals['site_mobile'];

        $sender["Address"] = $globals['site_addr'];

        $receiver = array();//收货人

        $receiver["ProvinceName"] = $orderInfo['province'];
        $receiver["CityName"] = $orderInfo['city'];
        $receiver["ExpAreaName"] = $orderInfo['county'];

        $receiver["Name"] = $orderInfo['accept_name'];
        $receiver["Mobile"] = $orderInfo['mobile'];

        $receiver["Address"] = $orderInfo['addr'];

        $commodityOne = array();
        $commodityOne["GoodsName"] = '家居用品';
        $commodity = array();
        $commodity[] = $commodityOne;

        $eorder["Sender"] = $sender;
        $eorder["Receiver"] = $receiver;
        $eorder["Commodity"] = $commodity;
        $eorder["IsReturnPrintTemplate"] = 1;

        $result = EssExpress::getInstance()->submitEOrder($eorder,true);//todo

        if($result){

            $invoiceSiteModel = new Model('doc_invoice',$orderInfo['site_url']);

            $set = array(
                'invoice_no'=>$result['Order']['LogisticCode'],
                'express_no'=>$result['Order']['LogisticCode'],
            );
            $invoiceSiteModel->data($set)->where('order_id='.$orderInfo['id'])->update();

            $invoiceModel = new Model('doc_invoice');

            $set = array(
                'invoice_no'=>$result['Order']['LogisticCode'],
                'express_no'=>$result['Order']['LogisticCode'],
                'ess_template'=>base64_encode($result['PrintTemplate']),
            );
            $invoiceModel->data($set)->where('order_id='.$orderInfo['id'])->update();
        }

        return '';
	}

    //打印电子面单
    public static function print_ess($ids = array())
    {
        $error_orders = array();

        $print_template = array();

        $orderModel = new Model('order');

        foreach($ids as $id){
            $order = $orderModel->where('id='.$id)->find();

            if($order['delivery_status'] == 1){
                $_printT = self::genEssOrder($order['order_no']);
                if($_printT){
                    $origin = array(
                        '时间：',
                        '<td width="61" rowspan="2"></td>',
                        '已验视',
                    );
                    $replace = array(
                        '时间：'.date('Y-m-d'),
                        '<td width="61" rowspan="2">家居用品</td>',
                        '订单号:'.$order['order_no'].'&nbsp;&nbsp;已验视',
                    );
                    $print_template[] = str_replace($origin,$replace,$_printT);
                }else{
                    $error_orders[] = $order['order_no'].'未获取到物流单号';
                }
            }else{
                $error_orders[] = $order['order_no'].'未发货';
            }
            sleep(1);
        }

        if(empty($print_template)){
            $error_orders[] = "打印失败：该订单没开启电子面单打印功能 或者 物流单号不存在";
        }

        return array(
            $error_orders,
            $print_template
        );
    }

    //订单发货公共方法
    public static function delivery($safeBoxManager,$order_id,$express_no = '',$express_company_id,$remark = '',$accept_name,$province,$city,$county,$zip,$addr,$phone,$mobile,$delivery_status)
    {

        $invoice_no = date('YmdHis').rand(100,999);

        //处理发货之前,先看下分销商预存款里是否有充足的余额 来扣除，有的话则 扣除预存款 并生成预存款扣除记录，然后再发货

        //订单所有产品的批发价之和  == 分销商预存款金额 比较

        //同步发货信息

        $orderModel = new Model('order');
        $order_info = $orderModel->where("id=$order_id")->find();

        $express_company_id  = $express_company_id ? $express_company_id : '';

        $remark = $remark ? $remark : '';

        $accept_name = $accept_name ? $accept_name : $order_info['accept_name'];

        $province = $province ? $province : $order_info['province'];

        $city = $city ? $city : $order_info['city'];

        $county = $county ? $county : $order_info['county'];

        $zip = $zip ? $zip : $order_info['zip'];

        $addr = $addr ? $addr : $order_info['addr'];

        $phone = $phone ? $phone : $order_info['phone'];

        $mobile = $mobile ? $mobile : $order_info['mobile'];

        $delivery_status = $delivery_status ? $delivery_status : $order_info['delivery_status'];

        $paymentModel = new Model("payment as pa");
        $paymentInfo = $paymentModel->fields('pa.*,pi.class_name,pi.name,pi.logo')->join("left join pay_plugin as pi on pa.plugin_id = pi.id")->where("pa.id = '".$order_info['payment']."' or pi.class_name = '".$order_info['payment']."'")->find();

        if(in_array($paymentInfo['class_name'],array('alipaydirect','alipaytrad','alipay','alipaygateway','alipaymobile'))){//支付宝支付  收益=订单金额-分销商批发价格-(订单金额*0.6%)
            $is_onlinepay = true;
        }else{
            $is_onlinepay = false;
        }

        if(!$is_onlinepay){
            $orderGoodsModelObj = new Model('order_goods');
            $managerObj = new Model('manager',$order_info['site_url']);//去分店 manager表中的数据
            $manager = $managerObj->fields('deposit,distributor_id,site_url')->where('roles="administrator"')->find();

            //todo 需要在order_goods 表加上trade price 批发价 字段，并在结算时候 写入trade_price
            $ordergoods = $orderGoodsModelObj->fields('goods_nums,trade_price')->where('order_id='.$order_id)->findAll();

            $tradeprice = 0;
            foreach($ordergoods as $og){
                $tradeprice += $og['trade_price'] * $og['goods_nums'];
            }

            $depost = $manager['deposit'] - $tradeprice;

            if(($depost) <= 0){

                return array(
                    'res'=>'fail',
                    'type'=>'no_depost',
                    'msg'=>'分销商预存款不足,发货失败!',
                    'order_no'=>$order_info['order_no'],
                );

            }else{
                $zdModel = new Model("distributor","zd","master");
                $distrInfo = $zdModel->where("distributor_id=".$manager['distributor_id'])->find();
                $zdModel->data(array('deposit'=>$distrInfo['deposit'] + $tradeprice))->where("distributor_id=".$manager['distributor_id'])->update();

                //同步预存款金额到分店
                $managerObj = new Model("manager",$manager['site_url'],"master");
                $distrInfo = $managerObj->where("distributor_id=".$manager['distributor_id'])->find();
                $distrInfo['deposit'] -= $tradeprice;

                $managerObj->data(array('deposit'=>$distrInfo['deposit']))->where("id=".$distrInfo['id'])->update();

                $manager = $safeBoxManager;
                $data['op_name'] = $manager['name'];
                $data['op_time'] = time();
                $data['op_id'] = $manager['id'];
                $data['money'] = $tradeprice;
                $data['action'] = 'minus';
                $data['op_ip'] = Chips::getIP();
                $data['memo'] = '操作人【'.$manager['name'].'】对订单 '.$order_info['order_no'].'进行发货 扣除'.$tradeprice.'元, 充值后 预存款剩余金额:'.$distrInfo['deposit'].'元';
                Log::rechange($data,$distrInfo['site_url']);

            }
        }


        $model = new Model("doc_invoice",$order_info['site_url']);

        if($delivery_status==Order::DELIVERY_STATUS_APPLY_REFUND){
            $data = array();
            $data['invoice_no'] = $invoice_no;
            $data['order_id'] = $order_info['outer_id'];
            $data['order_no'] = $order_info['order_no'];
            $data['admin'] = $safeBoxManager['name'];
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['express_no'] = $express_no;
            $data['express_company_id'] = $express_company_id;
            $data['remark'] = $remark;
            $data['accept_name'] = $accept_name;
            $data['province'] = $province;
            $data['city'] = $city;
            $data['county'] = $county;
            $data['zip'] = $zip;
            $data['addr'] = $addr;
            $data['phone'] = $phone;
            $data['mobile'] = $mobile;
            $model->data($data)->insert();
        }
        else{

            $data = array();

            $data['admin'] = $safeBoxManager['name'];
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['express_no'] = $express_no;
            $data['express_company_id'] = $express_company_id;
            $data['remark'] = $remark;
            $data['accept_name'] = $accept_name;
            $data['province'] = $province;
            $data['city'] = $city;
            $data['county'] = $county;
            $data['zip'] = $zip;
            $data['addr'] = $addr;
            $data['phone'] = $phone;
            $data['mobile'] = $mobile;

            $obj = $model->where("order_id=".$order_info['outer_id'])->find();
            if($obj){
                $model->data($data)->where("order_id=".$order_info['outer_id'])->update();
            }else{
                $data['invoice_no'] = $invoice_no;
                $data['order_id'] = $order_info['outer_id'];
                $data['order_no'] = $order_info['order_no'];
                $model->data($data)->insert();
            }
        }

        if($order_info){
            if($order_info['trading_info']!=''){
                $payment_id = $order_info['payment'];
                $payment = new Payment($payment_id);
                $payment_plugin = $payment->getPaymentPlugin();
                $exModel = new Model('express_company');
                $express_company = $exModel->where('id='.$express_company_id)->find();
                if($express_company) $express = $express_company['name'];
                else $express = $express_company_id;
                //处理同步发货
                $delivery = $payment_plugin->afterAsync();
                if($delivery!=null && method_exists($delivery, "send")) $delivery->send($order_info['trading_info'],$express,'express_no');
            }
        }
        $send_time = date('Y-m-d H:i:s');
        $zdOrderModel = new Model('order');
        $zdOrderModel->data(array('delivery_status'=>1,'send_time'=>$send_time))->where("id=".$order_info['id'])->update();

        $odModel = new Model("order",$order_info['site_url']);

        $odModel->where("id=".$order_info['outer_id'])->data(array('delivery_status'=>1,'send_time'=>$send_time))->update();

        //处理发货之后,计算收益逻辑 并加入到预存款中，并生成一条添加预存款记录

        /*
         * new
         *
         * 线上支付：
         * 分销商批发价格 = 所有产品的批发价之和1
	     * 发货时候，计算收益＝订单金额*（1-0.006）-分销商批发价
         * 线下支付：
	     * 发货时候，先看下分销商预存款里是否有充足的余额 来扣除，有的话则 扣除预存款 并生成预存款扣除记录，然后再发货。（订单所有产品的批发价之和  == 分销商预存款金额 比较）
         * */
        $orderGoodsModelObj = new Model('order_goods');
        $ordergoods = $orderGoodsModelObj->fields('goods_nums,trade_price')->where('order_id='.$order_id)->findAll();

        $tradeprice = 0;
        foreach($ordergoods as $og){
            $tradeprice += $og['trade_price'] * $og['goods_nums'];
        }

        $manager = $safeBoxManager;

        if($is_onlinepay){
            //$payfee = 1 - ($paymentInfo['pay_fee']/100);

            //$income = ($order_info['order_amount'] * $payfee) - $tradeprice;

            $income = $order_info['order_amount'] - $order_info['payable_freight'] - $tradeprice;

            $managerObj = new Model('manager',$order_info['site_url']);//去分店 manager表中的数据
            $fxmanager = $managerObj->fields('deposit,distributor_id,site_url,id')->where('roles="administrator"')->find();

            $zdModel = new Model("distributor","zd","master");
            $distrInfo = $zdModel->where("distributor_id=".$fxmanager['distributor_id'])->find();
            $zdModel->data(array('deposit'=>$distrInfo['deposit'] + $income))->where("distributor_id=".$fxmanager['distributor_id'])->update();

            $fxmanager['deposit'] += $income;

            $testObj = new Model('manager',$order_info['site_url']);
            $testObj->data(array('deposit'=>$fxmanager['deposit']))->where("id=".$fxmanager['id'])->update();

            $data['op_name'] = $manager['name'];
            $data['op_time'] = time();
            $data['op_id'] = $manager['id'];
            $data['money'] = $income;
            $data['action'] = 'add';
            $data['op_ip'] = Chips::getIP();
            $data['memo'] = '操作人【'.$manager['name'].'】对订单 '.$order_info['order_no'].'进行发货 增加'.$income.'元, 充值后 预存款剩余金额:'.$fxmanager['deposit'].'元';
            Log::rechange($data,$distrInfo['site_url']);
        }



        Log::orderlog($order_info['outer_id'],'操作人:'.$manager['name'],'订单已完成发货','订单已发货','success',$order_info['site_url']);

        $orderInvoiceModel = new Model('order_invoice');
        $oi = array();
        $oi['order_no'] = $order_info['order_no'];
        $oi['express_no'] = $express_no;
        $oi['site_url'] = $distrInfo['site_url'];
        $oiData = $orderInvoiceModel->fields('id')->where('order_no="'.$order_info['order_no'].'" and site_url="'.$distrInfo['site_url'].'"')->find();
        if($oiData){
            $orderInvoiceModel->data(array('express_no="'.$express_no.'"'))->where('id='.$oiData['id'])->update();
        }else{
            $orderInvoiceModel->data($oi)->insert();
        }

        //发货回写快递鸟生成 电子面单模板和物流单号
        // TODO
        Order::genEssOrder($order_info['order_no']);

        return array(
            'res'=>'succ',
            'type'=>'ok',
            'msg'=>'发货完成',
            'order_no'=>$order_info['order_no'],
        );
    }


    public static function getOrderProductsInfo($orderid,$siteurl)
    {
        $orderGoodsModel = new Model('order_goods',$siteurl);

        $products = $orderGoodsModel->where("order_id=".$orderid)->findAll();

        $productsInfo = array();

        $i = 0;
        foreach ($products as $pro) {

            $productsInfo[$i]['num'] = $pro['goods_nums'];
            $productsInfo[$i]['product_id'] = $pro['product_id'];
            $productsInfo[$i]['goods_id'] = $pro['goods_id'];

        }

        return $productsInfo;
    }


}
