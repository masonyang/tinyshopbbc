<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * 扩展集
 * 
 * @author Tiny
 * @class ExtensionCollection
 */
class ExtensionCollection
{
	private $extensions = array();
    /**
     * 添加扩展
     * 
     * @access public
     * @param Extension $extension
     * @return mixed
     */
	public function addExtension(Extension $extension)
	{
		$this->extensions[] = $extension;
	}
    /**
     * 调用每一个扩展的brfore方法
     * 
     * @access public
     * @param mixed $obj
     * @return void
     */
	public function before($obj=null)
	{
		foreach($this->extensions as $extension)
		{
			$extension->before($obj);
		}
	}
    /**
     * 调用每一个扩展的after方法
	 * 
	 * @access public
	 * @param mixed $obj
	 * @return void
	 */
	public function after($obj=null)
	{
		$extensions = array_reverse($this->extensions);
		foreach($extensions as $extension)
		{
			$extension->after($obj);
		}
	}
}
