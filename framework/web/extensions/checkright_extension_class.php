<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * 后台权限验证扩展
 * 
 * @author Tiny
 * @class CheckRightExtension
 */
class CheckRightExtension implements Extension
{
	public function before($obj=null)
	{
		$id = $obj->getAction()->getId();
		if($id !='login' && $id !='logout' && isset($obj->needRightActions[$id]))
        {
            $obj->checkRight($id);
        }
	}
	public function after($obj=null)
	{
	}
}
