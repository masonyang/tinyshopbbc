<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * 系统的最基类
 * 
 * @author Tiny
 * @class Object
 */
class Object
{
	protected $properties;
    /**
     * getter方法
     * 
     * @access public
     * @param mixed $name
     * @return mixed
     */
	public function __get($name)
	{
		$getter = 'get'.$name;
		if(method_exists($this,$getter)) return $this->$getter();
		if(isset($this->properties[$name])) return $this->properties[$name];
		else null;
	}
    /**
     * steter方法
     * 
     * @access public
     * @param mixed $name
     * @param mixed $value
     * @return mixed
     */
	public function __set($name,$value)
	{
		$setter = 'set'.$name;
		if(method_exists($this,$setter)) return $this->$setter($value);
		$this->properties[$name] = $value;
	}
    /**
     * isset判断
     * 
     * @access public
     * @param mixed $name
     * @return mixed
     */
	public function __isset($name)
	{
		$getter = 'get'.$name;
		if(method_exists($this,$getter)) return $this->$getter()!==null;
	}
    /**
     * 销毁
     * 
     * @access public
     * @param mixed $name
     * @return mixed
     */
	public function __unset($name)
	{
		$setter = 'set'.$name;
		if(method_exists($this,$setter)) $this->$setter(null);
	}
	/**
     * 调用方法
	 * 
	 * @access public
	 * @param mixed $name
	 * @param mixed $args
	 * @return mixed
	 */
	public function __call($name,$args=null)
	{
		if(method_exists($this,$name))throw new Exception(get_class($this)." method {$name}  is private or protected method",E_USER_ERROR);
		else throw new Exception(get_class($this)." not exists {$name} method",E_USER_ERROR);
	}
    /**
     * 取得属性
     * 
     * @access public
     * @return mixed
     */
	public function getPropertys()
	{
		return $this->properties;
	}
}