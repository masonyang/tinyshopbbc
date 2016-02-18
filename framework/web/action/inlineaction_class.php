<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * 内联action
 * 
 * @author Tiny
 * @class InlineAction
 */
class InlineAction extends BaseAction
{
	//Action运行入口
	public function run()
	{
		$controller=$this->getController();
		$methodName=$this->getId();
		$controller->$methodName();
	}
}
?>