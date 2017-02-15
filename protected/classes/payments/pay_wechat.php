<?php
/**
 * 微信支付相关类
 * @author  maskwang
 * @since   2017/2/10 下午10:05
 */
class pay_wechat extends PaymentPlugin
{
	public $name = '微信公众号支付';
	
	/**
	 * 应答数据
	 * @var null
	 */
	private $_replyData = null;

    /**
     * @brief 重写构造函数
     */
    public function __construct($paymentId=0){

        parent::__construct($paymentId);

        //异步回调地址
        $this->asyncCallbackUrl = str_replace('plugins/','',Url::fullUrlFormat("/payment/async_callback/payment_id/{$paymentId}",true));

    }

	/**
	 * 取得配置参数
	 * @return array
	 */
	public static function config()
	{
		return array(
			array('field'=>'partner_id','caption'=>'合作身份者id','type'=>'string'),
			array('field'=>'partner_key','caption'=>'安全检验码key','type'=>'string'),
			array('field' => 'app_id', 'caption' => '公众平台应用ID', 'type' => 'string') ,
			array('field' => 'app_secret', 'caption' => '公众平台应用秘钥', 'type' => 'string') ,
		);
	}
	
	/**
	 * 终止异步处理
	 */
	public function asyncStop()
	{
		Wechat::replyWxPayNotify($this->_replyData);
		exit();
	}
	
	/**
	 * 异步处理
	 * @param $callbackData
	 * @param $paymentId
	 * @param $money
	 * @param $message
	 * @param $orderNo
	 * @return bool
	 */
	public function callback( $callbackData , &$paymentId , &$money , &$message , &$orderNo )
	{
		$server = $this->_getServer(Payment::getInstance($paymentId)->getConfig());
		
		list($res, $notifyData, $this->_replyData) = $server->progressWxPayNotify();
		
		if(!$res)
		{
			return false;
		}
		
		$orderNo = $notifyData['out_trade_no'];
		$money = $notifyData['total_fee'];
		$message = $this->_replyData['return_msg'];
		
		return true;
	}
	
	/**
	 * 获取打包数据
	 * @param array $payment
	 * @return array|bool
	 * @throws AppException
	 */
	public function packData($payment)
	{
		$server = $this->_getServer(Payment::getInstance($payment['paymentid'])->getConfig());
		
		$openId = $server->getOpenId($payment['uid']);
		
		if(!$openId)
		{
			throw new AppException(Exception_Base::STATUS_CUSTOM_ERROR , '获取openId失败');
		}
		
		$wxOrder = $server->createUnifiedOrder(
			array(
				'orderId' => $payment['M_OrderNO'] ,
				'body' => $payment['R_Name']."(订单号:".$payment['M_OrderNO'].")" ,
				'totalFee' => number_format($payment['M_Amount'], 2, '.', '') ,
				'openId' => $openId ,
			)
		);
		
		if(!$wxOrder)
		{
			throw new AppException(Exception_Base::STATUS_CUSTOM_ERROR, '创建微信预订单失败');
		}
		
		return $server->getWxPayJsApiParameters($wxOrder['prepay_id']);
	}
	
	/**
	 * 获取server
	 * @param $payment
	 * @return Wechat
	 */
	private function _getServer($payment)
	{
		return Wechat::getInstance(
			array(
				'appId' => $payment['app_id'] ,
				'appSecret' => $payment['app_secret'] ,
				'mchId' => $payment['partner_id'] ,
				'key' => $payment['partner_key'] ,
			)
		)->setRedirectUrl(
			'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
		)->setNotifyUrl(
			$this->asyncCallbackUrl
		);
	}
}
