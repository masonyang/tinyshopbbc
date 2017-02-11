<?php

/**
 * 应用级异常
 * 错误码会尝试在Config/ErrorConfig.php中查找对应错误信息
 */
class AppException extends Exception
{
	public function __construct( $errorCode , $errorMessage = '' , $errorInfo = array() )
	{
		$errorConfig = Config::getInstance('error')->getAll();
		$error['error'] = isset( $errorConfig[ $errorCode ]['error'] ) ? str_replace( '{errorMessage}' , $errorMessage , $errorConfig[ $errorCode ]['error'] ) : $errorMessage;
		$error['message'] = isset( $errorConfig[ $errorCode ]['message'] ) ? str_replace( '{errorMessage}', $errorMessage , $errorConfig[ $errorCode ]['message'] ) : $errorMessage;
		if( !empty( $errorInfo ) )
		{
			foreach( $errorInfo as $k => $v )
			{
				$error[$k] = $v;
			}
		}
		$errorMessage = json_encode( $this->_format( $error ) );
		parent::__construct( $errorMessage , $errorCode );
	}
	
	/**
	 * 格式化接口输出
	 * @param $error
	 * @return array
	 */
	private function _format( $error )
	{
		return array(
			'status' => 'fail' ,
			'msg' => $error['error'] ,
			'data' => array() ,
		);
	}
}
