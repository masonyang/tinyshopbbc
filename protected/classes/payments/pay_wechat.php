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
	 * 获取打包数据
	 * @param array $payment
	 * @return array|bool
	 * @throws AppException
	 */
	public function packData($payment)
	{
		$server = Wechat::getInstance(
			array(
			'appId' => $payment['M_AppId'] ,
			'appSecret' => $payment['M_AppSecret'] ,
			'mchId' => $payment['M_PartnerId'] ,
			'key' => $payment['M_PartnerKey'] ,
			)
		)->setRedirectUrl(
			'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
		)->setNotifyUrl(
			$this->asyncCallbackUrl
		);
		
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
}
