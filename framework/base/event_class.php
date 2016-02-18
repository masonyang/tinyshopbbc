<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * 事件处理类
 * 
 * @author Tiny
 * @class Event
 */
class Event
{
	public $sender;

	public $handled = false;

	public function __construct($sender)
	{
		$this->sender = $sender;
	}
}
