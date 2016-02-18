<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * 缓存工厂类
 * 
 * @author Tiny
 * @class CacheFactory
 */
class CacheFactory
{
	private static $obj;
	private static $cache;

	private function __construct(){}
	private function __clone(){}
    /**
     * 实例化
     * @access public
     * @param string $type
     * @return mixed
     */
	public static function getInstance($type = 'file')
	{
		if(!self::$obj instanceof self)
		{
			self::$obj = new self();
			self::$cache = self::getCache($type);
		}
		return self::$cache;
	}
    /**
     * 取得缓存类
     * 
     * @access private
     * @param mixed $type
     * @return mixed
     */
	private static function getCache($type)
	{
		$obj = null;

		if($type=='db'){
			$obj = new DbCache();
		}
		else{
			$obj = new FileCache();
		}
		
		return $obj;
	}
}
?>
