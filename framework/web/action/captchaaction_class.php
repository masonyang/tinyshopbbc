<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * 验证码Action
 * 
 * @author Tiny
 * @class CaptchaAction
 */
class CaptchaAction extends BaseAction
{
	//Action运行入口
	public function run()
	{
		$captcha = new Captcha();
		$captcha->renderImage();
	}
}
?>