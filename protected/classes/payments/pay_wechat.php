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
			array('field' => 'app_id', 'caption' => '公众平台AppId', 'type' => 'string') ,
			array('field' => 'app_key', 'caption' => '公众平台AppKey', 'type' => 'string') ,
		);
	}
	
	/**
	 * 打包数据
	 * @param array $payment
	 * @return array
	 */
	public function packData($payment)
	{
		$wxPartnerId = $payment['M_PartnerId'];
		$wxPartnerKey = $payment['M_PartnerKey']; // TODO
		
		// 微信原样返回的参数
		$attachParam = array(
			// 是否微信代付
			'isAgent' => 0 ,
		);
		
		// 生成package
		$package = array(
			'attach' => json_encode( $attachParam , JSON_UNESCAPED_UNICODE ) ,
			'bank_type' => 'WX' ,
			'body' => $payment['R_Name']."(订单号:".$payment['M_OrderNO'].")" ,
			'fee_type' => 1 ,
			'input_charset' => 'UTF-8' ,
			'notify_url' => $this->asyncCallbackUrl ,
			'out_trade_no' => $payment['M_OrderNO'] ,
			'partner' => $wxPartnerId ,
			'spbill_create_ip' => $this->_getRequestIp() ,
			'total_fee' => number_format($payment['M_Amount'], 2, '.', '') ,
			'time_start' => date( 'YmdHis' , $_SERVER[ 'REQUEST_TIME' ] ),
		);
		
		// 获取签名
		$prepackageStr = $this->_getLinkString( $package , true ,true );
		
		$reqData = array(
			'appid' => 123 , // 使用公众平台appId TODO
			'appkey' => 123 , // TODO
			'timestamp' => (string)$_SERVER[ 'REQUEST_TIME' ] ,
			'noncestr' => $this->_getNonceStr() ,
		);
		
		// 生成package内容
		$reqData[ 'package' ] = $prepackageStr . '&sign=' . $this->_createMd5Sign( $package , $wxPartnerKey ) ;
		// 生成参数签名
		$reqData[ 'paysign'] = $this->_createAppSign( $reqData );
		$reqData[ 'signtype' ] = 'sha1';
		return $reqData;
		
	}
	
	/**
	 * 获取请求的ip地址
	 * @return string
	 */
	private function _getRequestIp()
	{
		$reqIp = '';
		if( !empty( $_SERVER[ "HTTP_CLIENT_IP" ] ) )
		{
			$reqIp = $_SERVER[ "HTTP_CLIENT_IP" ];
		}
		elseif( !empty( $_SERVER[ "REMOTE_ADDR" ] ) )
		{
			$reqIp = $_SERVER[ "REMOTE_ADDR" ];
		}
		return $reqIp;
	}
	
	/**
	 * 将参数数组拼接为http get需要的形式
	 * @param $params array 参数数组
	 * @param $withUrlEncode bool 是否使用urlencode
	 * @param $sort bool 是否排序
	 * @return string
	 */
	private function _getLinkString( $params , $withUrlEncode = false , $sort = false )
	{
		$arr = array();
		if( $sort )
		{
			ksort( $params );
		}
		foreach( $params as $key => $value )
		{
			if( "{$value}" == "" )
			{
				continue;
			}
			$arr[] = $key . '=' . ( $withUrlEncode ? urlencode( $value ) : $value );
		}
		
		$rtn = implode( '&' , $arr );
		//如果存在转义字符，那么去掉转义
		if( get_magic_quotes_gpc() )
		{
			$rtn = stripslashes( $rtn );
		}
		
		return $rtn;
	}
	
	/**
	 * 获取一个32位内的随机字符串，用于防重发
	 */
	private function _getNonceStr()
	{
		return md5( str_pad( rand( 1 , 9999 ) , 4 , '0' , STR_PAD_LEFT ) );
	}
	
	/**
	 * 生成md5签名
	 * @param $params
	 * @param $wxPartnerKey
	 * @return string
	 */
	private function _createMd5Sign( $params , $wxPartnerKey )
	{
		unset( $params[ 'sign' ] );
		unset( $params[ 'sign_method' ] );
		return strtoupper( md5( $this->_getLinkString( $params , false , true ) . '&key=' . $wxPartnerKey ) );
	}
	
	/**
	 * 生成签名
	 * @param $reqData
	 * @return string
	 */
	private function _createAppSign( $reqData )
	{
		unset( $reqData[ 'sign_method' ] );
		return sha1( $this->_getLinkString( $reqData , false , true ) );
	}
}
