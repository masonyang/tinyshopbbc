<?php
/**
 * 请求的输入数据
 * @author  maskwang
 * @since   2017/2/11 下午8:55
 */
class InputData
{
	/**
	 * 输入的数据
	 * @var	array
	 * <pre>
	 * 	array(
	 * 		{$key: string}: mixed
	 * 	)
	 * </pre>
	 */
	public $data = array();
	
	/**
	 * 没有设置错误码
	 * @var	int
	 */
	const NON_SET_ERROR_CODE = 0;
	
	/**
	 * 变量类型：int
	 */
	const VAR_TYPE_INT = 1;
	
	/**
	 * 变量类型：string
	 */
	const VAR_TYPE_STRING = 2;
	
	/**
	 * 变量类型：图片url数组,上传url图片
	 */
	const VAR_TYPE_IMAGE = 3;
	
	/**
	 * 变量类型：arrayImage
	 */
	const VAR_TYPE_ARRAY_IMAGE = 4;
	
	/**
	 * 变量类型：数组
	 */
	const VAR_TYPE_ARRAY = 5;
	
	/**
	 * 变量类型：浮点型
	 */
	const VAR_TYPE_FLOAT = 6;
	
	/**
	 * 变量类型：日期型
	 */
	const VAR_TYPE_DATE_TIME = 7;
	
	/**
	 * 实例化
	 */
	public function __construct($params = array())
	{
        if($params){
            $this->data = $params;
        }else{
            $this->data = Req::args();
        }

	}
	
	/**
	 * 获取值为布尔的输入参数
	 * @param	string	$key	字段名
	 * @param	boolean	$defaultValue	默认值，默认false
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @return	int
	 */
	public function getValueAsBoolean( $key , $defaultValue = false , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
	{
		if( !isset( $this->data[$key] ) )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		if( $this->data[$key] === 'false' )
		{
			return false;
		}
		
		return $this->data[$key] ? true : false;
	}
	
	/**
	 * 获取值为正整数的输入参数
	 * @param	string	$key	字段名
	 * @param	int	$defaultValue	默认值
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @return	int
	 */
	public function getValueAsPositiveInteger( $key , $defaultValue = 0 , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
	{
		if( !isset( $this->data[$key] ) || ( $value = (integer)$this->data[$key] ) <= 0 )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		return $value;
	}
	
	/**
	 * 获取值为整数的输入参数
	 * @param	string	$key	字段名
	 * @param	int	$defaultValue	默认值
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @return	int
	 */
	public function getValueAsInteger( $key , $defaultValue = 0 , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
	{
		if( !isset( $this->data[$key] ) || $this->data[$key] === '' )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		return (integer)$this->data[$key];
	}
	
	/**
	 * 获取值为正浮点数的输入参数
	 * @param	string	$key	字段名
	 * @param	float	$defaultValue	默认值
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @return	float
	 */
	public function getValueAsPositiveFloat( $key , $defaultValue = 0.0 , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
	{
		if( !isset( $this->data[$key] ) || ( $value = (float)$this->data[$key] ) <= 0 )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		return $value;
	}
	
	/**
	 * 获取值为正浮点数的输入参数
	 * @param	string	$key	字段名
	 * @param	float	$defaultValue	默认值
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @return	float
	 */
	public function getValueAsFloat( $key , $defaultValue = 0.0 , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
	{
		if( !isset( $this->data[$key] ) )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		return (float)$this->data[$key];
	}
	
	/**
	 * 获取值为前后没有空格的字符串的输入参数
	 * @param    string $key 字段名
	 * @param    string $defaultValue 默认值
	 * @param    boolean $isThrowException 是否抛出异常
	 * @param    int $errorCode 错误码
	 * @param bool $filterHtml 是否过滤html标签
	 * @param string $allowedTags 不过滤的html标签
	 * @throws AppException
	 * @return int
	 */
	public function getValueAsTrimString( $key , $defaultValue = '' , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE , $filterHtml = true , $allowedTags = '' )
	{
		if( !isset( $this->data[$key] ) || strlen( $value = $this->_filterHTMLOrNot( trim( $this->data[$key] ) , $filterHtml , $allowedTags ) ) <= 0 )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		return $value;
	}
	
	/**
	 * 获取值为字符串的输入参数
	 * @param string $key 字段名
	 * @param string $defaultValue 默认值
	 * @param boolean $isThrowException 是否抛出异常
	 * @param int $errorCode 错误码
	 * @param bool $filterHtml 是否过滤html标签
	 * @throws AppException
	 * @return int
	 */
	public function getValueAsNotTrimString( $key , $defaultValue = '' , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE , $filterHtml = true )
	{
		if( !isset( $this->data[$key] ) || strlen( $value = $this->_filterHTMLOrNot( $this->data[$key] , $filterHtml ) ) <= 0 )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		return $value;
	}
	
	/**
	 * 获取值为时间戳的输入参数
	 * @param	string	$key	字段名
	 * @param	int	$defaultValue	默认值
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @return	int
	 */
	public function getValueAsTimeStamp( $key , $defaultValue = 0 , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
	{
		if( !isset( $this->data[$key] ) || ( $time = strtotime( $this->getValueAsTrimString( $key , '' , $isThrowException , $errorCode ) ) ) <= 0 )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		return $time;
	}
	
	/**
	 * 获取一个由正整型组成的数组
	 * @param	string	$key	字段名
	 * @param	int[]	$defaultValue	默认值
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @return	int[]
	 */
	public function getValuesAsPositiveIntegerArray( $key , $defaultValue = array() , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
	{
		if( !isset( $this->data[$key] ) || !is_array( $values = $this->data[$key] ) )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		foreach( $values as $key => $value )
		{
			if( preg_match( '|(?<number>[\-\d]+)|' , $value , $match ) <= 0 )
			{
				unset( $values[$key] );
			}
			$values[$key] = $match['number'];
			if( $values[$key] == (integer)$match['number'] )
			{
				$values[$key] = (integer)$match['number'];
			}
		}
		
		if( count( $values ) <= 0 )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		return $values;
	}
	
	/**
	 * 获取一个数组
	 * @param	string	$key	字段名
	 * @param	array	$defaultValue	默认值
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @return	array
	 */
	public function getValuesAsArray( $key , $defaultValue = array() , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
	{
		if( !isset( $this->data[$key] ) || !is_array( $values = $this->data[$key] ) )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		return $values;
	}
	
	/**
	 * 获取一个由前后没有空格的字符串组成的数组
	 * @param	string	$key	字段名
	 * @param	string[]	$defaultValue	默认值
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @param	bool	$filterHtml	是否过滤html标签
	 * @return	string[]
	 */
	public function getValuesAsTrimStringArray( $key , $defaultValue = array() , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE , $filterHtml = true )
	{
		if( !isset( $this->data[$key] ) || !is_array( $values = $this->data[$key] ) )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		foreach( $values as $key => $value )
		{
			if( strlen( $values[$key] = $this->_filterHTMLOrNot( trim( $value ) , $filterHtml ) ) <= 0 )
			{
				unset( $values[$key] );
			}
		}
		
		if( count( $values ) <= 0 )
		{
			$this->_tryThrowError( $key , $isThrowException , $errorCode );
			
			return $defaultValue;
		}
		
		return $values;
	}
	
	/**
	 * @param	string	$key	字段名
	 * @param	array	$formats	array(
	 * 									key: array(
	 * 										type: int
	 * 										isThrowException: boolean
	 * 										errorCode: int
	 * 										defaultValue: mixed
	 * 										notExistValue: mixed
	 * 									)
	 * 								)
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @param	boolean	$keepKey	保留数组的key
	 * @return	array
	 */
	public function getValuesAsFormatArray( $key , $formats , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE , $keepKey = false )
	{
		$array = $this->getValuesAsArray( $key , array() , $isThrowException , $errorCode );
		if( empty( $array ) )
		{
			return array();
		}
		$returnDatas = array();
		//填上该有的参数
		$formats = $this->_formatsDefault( $formats );
		
		foreach( $array as $key => $data )
		{
			$returnDatas[$key] = $this->_getFormatData( $data , $formats );
		}
		return $keepKey ? $returnDatas : array_values( $returnDatas );
	}
	
	/**
	 * @param string $key
	 * @param array  $formats
	 * array(
	 *   type:int
	 *   isThrowException:boolean
	 *   errorCode:int
	 *   defaultValue:int|string
	 * )
	 * @param bool   $isThrowException
	 * @param int    $errorCode
	 * @return array
	 */
	public function getValueAsFormatArray( $key , $formats , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
	{
		$array = $this->getValuesAsArray( $key , array() , $isThrowException , $errorCode );
		if( empty( $array ) )
		{
			return array();
		}
		//填上该有的参数
		$formats = $this->_formatsDefault( $formats );
		
		return $this->_getFormatData( $array , $formats );
	}
	
	/**
	 * 是否存在字段
	 * @param	string	$key	键
	 * @return	boolean
	 */
	public function isHad( $key )
	{
		return isset( $this->data[$key] );
	}
	
	/**
	 * 获取字段原始值
	 * @param	string	$key	键
	 * @return	boolean
	 */
	public function getValueAsRaw( $key )
	{
		return $this->data[$key];
	}
	
	/**
	 * 创建一个对象
	 * @param	array	$data	数据
	 * @return	InputData
	 */
	public static function createObject( $data )
	{
		$InputDataData = new self();
		$InputDataData->data = $data;
		return $InputDataData;
	}
	
	/**
	 * 尝试报错
	 * @param	string	$key	字段名
	 * @param	boolean	$isThrowException	是否抛出异常
	 * @param	int	$errorCode	错误码
	 * @throws	AppException
	 */
	private function _tryThrowError( $key , $isThrowException = false , $errorCode = self::NON_SET_ERROR_CODE )
	{
		if( $isThrowException && $errorCode > 0 )
		{
			throw new AppException( $errorCode , '['. $key .']' );
		}
	}
	
	/**
	 * 把默认值填上
	 * @param $formats
	 * @return array
	 * array(
	 *  key:array(
	 *      type:int
	 *      isThrowException:boolean
	 *      errorCode:int
	 *      defaultValue:int
	 *  )
	 * )
	 */
	private function _formatsDefault( $formats )
	{
		foreach( $formats as $key => $info )
		{
			$formats[$key]['isThrowException'] = isset( $info['isThrowException'] ) ?  $info['isThrowException'] : false;
			$formats[$key]['errorCode'] = isset( $info['errorCode'] ) ?  $info['errorCode'] : self::NON_SET_ERROR_CODE;
			switch( $info['type'] )
			{
				case self::VAR_TYPE_INT:
				case self::VAR_TYPE_FLOAT:
				case self::VAR_TYPE_DATE_TIME:
					$formats[$key]['defaultValue'] = isset( $info['defaultValue'] ) ?  $info['defaultValue'] : 0;
					break;
				case self::VAR_TYPE_IMAGE:
					$formats[$key]['defaultValue'] = isset( $info['defaultValue'] ) ?  $info['defaultValue'] : array(
						'path' => '',
						'width' => 0,
						'height' => 0
					);
					break;
				case self::VAR_TYPE_ARRAY:
					$formats[$key]['defaultValue'] = isset( $info['defaultValue'] ) ?  $info['defaultValue'] : array();
					break;
				case self::VAR_TYPE_STRING:
				default:
					$formats[$key]['defaultValue'] = isset( $info['defaultValue'] ) ?  $info['defaultValue'] : '';
					$formats[$key]['type'] = self::VAR_TYPE_STRING;
					$formats[$key]['filterHtml'] = isset( $info['filterHtml'] ) ? $info['filterHtml'] : true;
					break;
			}
		}
		return $formats;
	}
	
	/**
	 * @param $data
	 * @param $formats
	 * @return array
	 */
	private function _getFormatData( $data , $formats )
	{
		$returnData = array();
		foreach( $formats as $subKey => $format )
		{
			if( !isset( $data[$subKey] ) )
			{
				$this->_tryThrowError( $subKey , $format['isThrowException'] , $format['errorCode'] );
				$returnData[$subKey] = isset( $format['notExistValue'] ) ? $format['notExistValue'] : $format['defaultValue'];
				continue;
			}
			switch( $format['type'] )
			{
				case self::VAR_TYPE_INT:
					if( ( $value = (integer)$data[$subKey] ) <= 0 )
					{
						$this->_tryThrowError( $subKey , $format['isThrowException'] , $format['errorCode'] );
						$returnData[$subKey] = $format['defaultValue'];
						continue;
					}
					break;
				
				case self::VAR_TYPE_FLOAT:
					if( ( $value = (float)$data[$subKey] ) <= 0 )
					{
						$this->_tryThrowError( $subKey , $format['isThrowException'] , $format['errorCode'] );
						$returnData[$subKey] = $format['defaultValue'];
						continue;
					}
					break;
				
				case self::VAR_TYPE_IMAGE:
					if( !is_array( $value = $data[$subKey] ) || empty( $data[$subKey]['path'] ) )
					{
						$this->_tryThrowError( $subKey , $format['isThrowException'] , $format['errorCode'] );
						$returnData[$subKey] = $format['defaultValue'];
						continue;
					}
					break;
				
				case self::VAR_TYPE_ARRAY:
					if( !is_array( $value = $data[$subKey] ) )
					{
						$this->_tryThrowError( $subKey , $format['isThrowException'] , $format['errorCode'] );
						$returnData[$subKey] = $format['defaultValue'];
						continue;
					}
					break;
				
				case self::VAR_TYPE_DATE_TIME:
					if( ( $value = strtotime( $data[$subKey] ) ) <= 0 )
					{
						$this->_tryThrowError( $subKey , $format['isThrowException'] , $format['errorCode'] );
						$returnData[$subKey] = $format['defaultValue'];
						continue;
					}
					break;
				
				case self::VAR_TYPE_STRING:
				default:
					if( strlen( $value = $this->_filterHTMLOrNot( trim( $data[$subKey] ) , $format['filterHtml'] ) ) <= 0 )
					{
						$this->_tryThrowError( $subKey , $format['isThrowException'] , $format['errorCode'] );
						$returnData[$subKey] = $format['defaultValue'];
						continue;
					}
					break;
			}
			$returnData[$subKey] = $value;
		}
		return $returnData;
	}
	
	/**
	 * 过滤或不过滤HTML代码
	 * @param    string $string 字符串
	 * @param    bool $isFilter 是否过滤
	 * @param string $allowedTags
	 * @return string
	 */
	private function _filterHTMLOrNot( $string , $isFilter = true , $allowedTags = '' )
	{
		if( $isFilter )
		{
			return strlen( $allowedTags ) > 0 ? strip_tags( $string , $allowedTags ) : strip_tags( $string );
		}
		
		return $string;
	}
}
