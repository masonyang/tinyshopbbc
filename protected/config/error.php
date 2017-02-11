<?php
/**
 * 出错提示定义
 * @return array(
 *  type=>int 前端报错方式（1: 前端不做任何处理； 2: 前端显示弹出框；3: 前端显示tips；4: 前端显示弹出框，并登出）
 *  error=>string 后端错误提示
 *  message=>string 前端显示信息
 * )
 */
return array
(
	//配置错误
	1 => array(
		'type' => 1,
		'error' => '系统配置 "{errorMessage}" 不存在',
		'message' => '',
	) ,
	
	//token验证相关的异常
	2 => array(
		'type' => 4,
		'error' => 'session会话错误',
		'message' => 'session会话错误，请重新登录',
	) ,
	
	3 => array(
		'type' => 4,
		'error' => 'session验证缺少 "{errorMessage}" 参数',
		'message' => 'session验证错误，请重新登录',
	) ,
	4 => array(
		'type' => 4,
		'error' => 'TOKEN错误，请重新登录',
		'message' => 'TOKEN错误，请重新登录',
	) ,
	5 => array(
		'type' => 4,
		'error' => '用户不存在',
		'message' => '用户不存在，请重新登录',
	) ,
	6 => array(
		'type' => 4,
		'error' => '系统配置错误',
		'message' => '系统错误,请联系管理员',
	) ,
	
	//接口调用参数相关的异常
	10 => array(
		'type' => 3,
		'error' => '参数 "{errorMessage}" 错误' ,
		'message' => '参数错误，请更新版本' ,
	) ,
	
	11 => array(
		'type' => 3,
		'error' => '亲爱的用户，你的版本太旧了，请更新最新的版本' ,
		'message' => '亲爱的用户，你的版本太旧了，请到 iChuanYi.com 更新最新的版本。' ,
	) ,
	
	12 => array(
		'type' => 3,
		'error' => '接口版本号异常' ,
		'message' => '接口版本号异常' ,
	) ,
	100 => array(
		'type' => 3,
		'error' => 'Api接口{errorMessage}参数错误',
		'message' => '接口参数错误，请联系管理员进行反馈',
	) ,
	101 => array(
		'type' => 3,
		'error' => '文件没有上传',
		'message' => '文件没有上传',
	) ,
	403 => array(
		'type' => 1,
		'error' => '禁止访问',
		'message' => '',
	) ,
	404 => array(
		'type' => 1,
		'error' => '资源 "{errorMessage}" 不存在',
		'message' => '',
	) ,
	//数据库操作相关错误
	1000 => array(
		'type' => 1,
		'error' => '数据库错误',
		'message' => '',
	) ,
	
	//注册相关的异常
	2000 => array(
		'type' => 2,
		'error' => '邮箱 "{errorMessage}" 已经被注册',
		'message' => '邮箱已经被注册，请使用其它邮箱注册',
	) ,
	2001 => array(
		'type' => 2,
		'error' => '邮箱 "{errorMessage}" 格式不正确',
		'message' => '邮箱格式不正确',
	) ,
	2002 => array(
		'type' => 2,
		'error' => '密码长度不足',
		'message' => '密码长度不足',
	) ,
	2003 => array(
		'type' => 2,
		'error' => '上传头像出错',
		'message' => '上传头像出错',
	) ,
	2004 => array(
		'type' => 2,
		'error' => '图片格式错误',
		'message' => '上传图片的格式错误',
	) ,
	2005 => array(
		'type' => 2,
		'error' => '手机号已经被注册',
		'message' => '手机号已经被注册',
	) ,
	2006 => array(
		'type' => 2,
		'error' => '手机号格式不正确',
		'message' => '手机号格式不正确',
	) ,
	2007 => array(
		'type' => 2,
		'error' => '昵称已经被注册',
		'message' => '昵称已经被注册',
	) ,
	2008 => array(
		'type' => 2,
		'error' => '微博账号信息获取失败',
		'message' => '微账号博信息获取失败',
	) ,
	2009 => array(
		'type' => 2,
		'error' => '用户ID错误',
		'message' => '用户ID错误',
	) ,
	2010 => array(
		'type' => 2,
		'error' => '用户被禁用',
		'message' => '用户被禁用',
	) ,
	//自定义错误提示，请拨10000号
	Exception_Base::STATUS_CUSTOM_ERROR => array(
		'type' => 1,
		'error' => '{errorMessage}',
		'message' => '{errorMessage}'
	)
);
