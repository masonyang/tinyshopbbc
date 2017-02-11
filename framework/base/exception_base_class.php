<?php
/**
 * 异常基类
 * @author  maskwang
 * @since   2017/2/11 下午9:27
 */
class Exception_Base extends Exception
{
	/**
	 * Session Token出错
	 * @var	int
	 */
	const STATUS_SESSION_ERROR = 2;
	
	/**
	 * 数据库访问出错
	 * @var	int
	 */
	const STATUS_DB_ACCESS_ERROR = 7;
	
	/**
	 * 参数错误
	 * @var	int
	 */
	const STATUS_PARAMETER_ERROR = 10;
	
	/**
	 * 参数错误
	 * @var	int
	 */
	const STATUS_API_EXPIRED = 11;
	
	/**
	 * 错误的控制器名称
	 * @var	int
	 */
	const STATUS_CONTROLLER_NAME_ERROR = 50;
	
	/**
	 * 错误的动作器名称
	 * @var	int
	 */
	const STATUS_ACTIONER_NAME_ERROR = 51;
	
	/**
	 * 错误的方法名称
	 * @var	int
	 */
	const STATUS_METHOD_NAME_ERROR = 52;
	
	/**
	 * 方法不存在
	 * @var	int
	 */
	const STATUS_METHOD_NOT_EXIST = 52;
	
	/**
	 * 配置了错误的数据库引擎
	 * @var	int
	 */
	const STATUS_DB_ENGINE_CONFIG_ERROR = 53;
	
	/**
	 * API参数传入错误
	 * @var	int
	 */
	const STATUS_API_PARAMETER_ERROR = 100;
	
	/**
	 * 文件传入错误
	 * @var	int
	 */
	const STATUS_FILE_UPLOAD_ERROR = 101;
	
	/**
	 * 无法对Memcache加锁
	 * @var	int
	 */
	const STATUS_LOCK_MEMCACHE_ERROR = 200;
	
	/**
	 * 用户ID错误
	 * @var	int
	 */
	const STATUS_USER_ID_ERROR = 201;
	
	/**
	 * 索引缓存键错误
	 * @var	int
	 */
	const STATUS_CACHE_INDEX_KEY_ERROR = 202;
	
	/**
	 * 未知Case选项
	 * @var	int
	 */
	const STATUS_UNKNOWN_CASE_OPTION = 203;
	
	/**
	 * 不支持这个SQL选项
	 * @var	int
	 */
	const STATUS_NOT_SUPPORT_SQL_OPTION = 204;
	
	/**
	 * 不可能触碰到的代码
	 * @var	int
	 */
	const STATUS_COULD_NOT_REACH_CODE = 600;
	
	/**
	 * 邮箱已存在
	 * @var	int
	 */
	const STATUS_EMAIL_EXIST = 2000;
	
	/**
	 * 图片上传失败
	 * @var	int
	 */
	const STATUS_IMAGE_UPLOAD_FAILURE = 2004;
	
	/**
	 * 手机号码已存在
	 * @var	int
	 */
	const STATUS_MOBILE_EXIST = 2005;
	
	/**
	 * 用户名已存在
	 * @var	int
	 */
	const STATUS_USERNAME_EXIST = 2007;
	
	/**
	 * 管理员用户名已存在
	 * @var	int
	 */
	const STATUS_ADMIN_USERNAME_EXIST = 3001;
	
	/**
	 * 管理员密码错误
	 * @var	int
	 */
	const STATUS_ADMIN_PASSWORD_ERROR = 3002;
	
	/**
	 * 管理员用户名不存在
	 * @var	int
	 */
	const STATUS_ADMIN_USERNAME_NOT_EXIST = 3003;
	
	/**
	 * 管理员用户ID不存在
	 * @var	int
	 */
	const STATUS_ADMIN_USER_ID_NOT_EXIST = 3004;
	
	/**
	 * 需求管理员用户名称
	 * @var	int
	 */
	const STATUS_NEED_ADMIN_USER_NAME = 3005;
	
	/**
	 * 需求管理员用户密码
	 * @var	int
	 */
	const STATUS_NEED_ADMIN_USER_PASSWORD = 3006;
	
	/**
	 * 请求百度地址解析接口错误
	 * @var	int
	 */
	const STATUS_REQUEST_BAIDU_GEOCOORD_FAIL = 3007;
	
	/**
	 * 没有匹配的省份编号
	 * @var	int
	 */
	const STATUS_NO_MATCHED_PROVINCE_ID = 3008;
	
	/**
	 * 没有匹配的城市编号
	 * @var	int
	 */
	const STATUS_NO_MATCHED_CITY_ID = 3009;
	
	/**
	 * 没有匹配的区县编号
	 * @var	int
	 */
	const STATUS_NO_MATCHED_COUNTY_ID = 3010;
	
	/**
	 * 搭配印象不存在
	 * @var	int
	 */
	const STATUS_PFEED_NOT_EXIST = 5500;
	
	/**
	 * 自定义错误信息,用法：throw new AppException( Exception_Base::STATUS_CUSTOM_ERROR , '要显示的错误内容' );
	 * @var	int
	 */
	const STATUS_CUSTOM_ERROR = 10000;
	
	public function __construct( $errorCode = 1 )
	{
		$errorDesc = Config::getInstance('error')->getAll();
		$message = isset( $errorDesc[$errorCode] ) == null ? "ErrorCode: {$errorCode}" : $errorDesc[$errorCode]['message'];
		parent::__construct( $message , $errorCode );
	}
}
