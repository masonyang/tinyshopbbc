<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */
/**
 * 视图Action
 * 
 * @author Tiny
 * @class ViewAction
 */
class ViewAction extends BaseAction
{
	private $viewParam = 'view';

	private $defaultView = 'index';

	private $view;

	private $basePath = 'pages';

	private $layout;

	private $viewPath=null;
    /**
     * 设置视图路径
	 * 
	 * @access public
	 * @param mixed $viewName
	 */
	public function setViewPath($viewName)
	{
		$this->viewPath = $viewName;
	}
    /**
     * 取得视图路径
     * 
     * @access public
     * @return String
     */
	public function getViewPath()
	{
		if($this->viewPath === null)
		{
			if(!is_null(Req::args($this->viewParam))) $this->resolveView(Req::args($this->viewParam));
			else $this->viewPath = strtolower($this->getController()->id).DIRECTORY_SEPARATOR.strtr($this->id,'.','/');
		}
		return $this->viewPath;
	}
    /**
     * 解析视图
     * 
     * @access public
     * @param mixed $viewPath
     * @return mixed
     */
	public function resolveView($viewPath)
	{
		if(preg_match('/^\w[\w\.\-]*$/',$viewPath))
		{
			$view = strtr($viewPath,'.','/');
			if(!empty($this->basePath)) $view = $this->basePath.'/'.$view;
			if($this->getController()->resolveViewFile($view) !== false)
			{
				$this->view = $view;
				return;
			}
		}
	}
    /**
     * 错误视图action运行入口 
     * 
     * @access public
     * @return mixed
     */
	public function run()
	{
		//$this->resolveView($this->getViewPath());
		$controller = $this->getController();
		$controller->render($this->getId(),$this->getData());
	}
}
