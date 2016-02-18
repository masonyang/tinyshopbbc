<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * widget类
 * 
 * @author Tiny
 * @class Widget
 */
class Widget
{
	private $controller;

	public function __construct($controller)
	{
		$this->controller=$controller;
	}
	public function __call($name,$args)
	{
		
	}
}
?>