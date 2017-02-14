<?php
/**
 * 微信类
 * @author  maskwang
 * @since   2017/2/14 下午12:38
 */
use Gaoming13\WechatPhpSdk\Api as apiLib;

class Wechat
{
	/**
	 * 单例
	 * @var
	 */
	private static $singleton;
	
	/**
	 * @var Gaoming13\WechatPhpSdk\Api
	 */
	private $_api;
	
	/**
	 * 用户授权回调地址
	 * @var
	 */
	private $_redirectUrl;
	
	/**
	 * 异步通知地址
	 * @var
	 */
	private $_notifyUrl;
	
	/**
	 * 获取实例
	 * @param $config
	 *          array(
	 *              'appId' => string ,
	 *              'appSecret' => string ,
	 *              'mchId' => string ,
	 *              'key' => string ,
	 *          )
	 * @return Wechat
	 */
	public static function getInstance($config)
	{
		if(self::$singleton === null)
		{
			self::$singleton = new self($config);
		}
		return self::$singleton;
	}
	
	/**
	 * 构造函数
	 * Wechat constructor.
	 * @param $config
	 */
	protected function __construct($config)
	{
		$this->_api = new apiLib(array(
			'appId' => $config['appId'] ,
			'appSecret' => $config['appSecret'] ,
			'mchId' => $config['mchId'] , // 微信支付商户号
			'key' => $config['key'] , // 微信商户API秘钥
			'get_access_token' => function() {
				return $_SESSION['wxAccessToken'];
			} ,
			'save_access_token' => function($token) {
				$_SESSION['wxAccessToken'] = $token;
			}
		));
	}
	
	/**
	 * 设置异步通知地址
	 * @param $notifyUrl
	 * @return $this
	 */
	public function setNotifyUrl($notifyUrl)
	{
		$this->_notifyUrl = $notifyUrl;
		return $this;
	}
	
	/**
	 * 设置用户授权回调地址
	 * @param $redirectUrl
	 * @return $this
	 */
	public function setRedirectUrl($redirectUrl)
	{
		$this->_redirectUrl = $redirectUrl;
		return $this;
	}
	
	/**
	 * 获取openId
	 * @param $userId
	 * @return mixed
	 */
	public function getOpenId($userId)
	{
		$model = new Model('tiny_user');
		$user = $model->where("id='".$userId."'")->find();
		
		if($user && $user['wxOpenId'])
		{
			return $user['wxOpenId'];
		}
		
		list($err, $userInfo) = $this->_api->get_userinfo_by_authorize('snsapi_base');
		if($userInfo == null)
		{
			$url = $this->_api->get_authorize_url('snsapi_base', $this->_redirectUrl );
			header('Location:' . $url);
		}
		
		//更新信息到用户表
		$model->table('tiny_user')
			->data(
				array(
					'wxOpenId' => $userInfo['access_token'] ,
					'wxAccessToken' => $userInfo['openid'] ,
				)
			)
			->where("id=".$userId)
			->update();
		
		return $userInfo['openid'];
	}
	
	/**
	 * 创建微信预订单
	 * @param $orderInfo
	 * @return array|bool
	 */
	public function createUnifiedOrder($orderInfo)
	{
		$wxOrder = $this->_api->wxPayUnifiedOrder(array(
			'trade_type' => 'JSAPI' ,
			'out_trade_no' => $orderInfo['orderId'] ,
			'body' => $orderInfo['body'] ,
			'total_fee' => $orderInfo['totalFee'] , //单位是分
			'notify_url' => $this->_notifyUrl ,
			'openid' => $orderInfo['openId'] ,
		));
		
		// 判断预订单是否生成成功
		if ($wxOrder['return_code'] == 'SUCCESS' && $wxOrder['result_code'] == 'SUCCESS') {
			return $wxOrder;
		} else {
			return false;
		}
	}
	
	/**
	 * 获取JSAPI支付需要的参数
	 * @param $prepayId
	 * @return string
	 */
	public function getWxPayJsApiParameters($prepayId)
	{
		return $this->_api->getWxPayJsApiParameters($prepayId);
	}
	
	/**
	 * 处理业务逻辑
	 */
	public function doAsyncPayNotify()
	{
		list($res, $notifyData, $replyData) = $this->_api->progressWxPayNotify();
		
		// TODO 处理业务逻辑
		
		apiLib::replyWxPayNotify($replyData);
		exit();
	}
}
