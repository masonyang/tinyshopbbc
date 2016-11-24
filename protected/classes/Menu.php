<?php
//系统菜单类
class Menu
{
    private $nodes;
    private $subMenu;
    private $menu;

    private $_menu;
    private $_subMenu;
    private $link_key;

    public function __construct()
    {
		$serverConfig = Tiny::getServerConfig();

		$menuManage = Config::getInstance('menuManage')->get($serverConfig['menu']);

		$nodes = $menuManage['nodes'];
		//分组菜单
		$subMenu = $menuManage['submenu'];
		//主菜单
        $menu = $menuManage['menu'];

        $safebox = Safebox::getInstance();
        $manager = $safebox->get('manager');
        if(isset($manager['roles']) && $manager['roles'] != 'administrator'){
            $roles = new Roles($manager['roles']);
            $result = $roles->getRoles();
            if(isset($result['rights']))
                $rights = $result['rights'];
            else
                $rights = '';
            if(is_array($nodes)){
                $subMenuKey = array();
                foreach ($nodes as $key => $value) {
                    $_key = trim(strtr($key,'/','@'),'@');
                    if(stripos($rights, $_key)===false) unset($nodes[$key]);
                    else{
                        if(!isset($subMenuKey[$value['parent']]))$subMenuKey[$value['parent']] = $key;
                        else{
                            if(stristr($key,'_list'))$subMenuKey[$value['parent']] = $key;
                        }
                    }
                }
                $menuKey = array();
                foreach ($subMenu as $key => $value) {
                    if(isset($subMenuKey[$key])){
                        $menuKey[$value['parent']] = $key;
                    }else unset($subMenu[$key]);
                }
                foreach ($menu as $key => $value) {
                    if(!isset($menuKey[$key]))unset($menu[$key]);
                    else{
                        $menu[$key]['link'] = $subMenuKey[$menuKey[$key]];
                    }
                }
            }
        }
        //var_dump($subMenuKey,$menuKey,$menu);exit;
        if(is_array($nodes))$this->nodes = $nodes;
        else $this->nodes = array();
        if(is_array($subMenu))$this->subMenu = $subMenu;
        else $this->subMenu = array();
        if(is_array($menu))$this->menu = $menu;
        else $this->menu = array();

        foreach($this->nodes as $key => $nodes){
            $this->_subMenu[$nodes['parent']][] = array('link'=>$key,'name'=>$nodes['name'],'display'=>isset($nodes['name'])?$nodes['name']:true);
        }

        foreach($this->subMenu as $key => $subMenu){
            $this->_menu[$subMenu['parent']][] = array('link'=>$key,'name'=>$subMenu['name']);
        }
        $this->link_key = '/'.(Req::get('con')==null?strtolower(Tiny::app()->defaultController):Req::get('con')).'/'.(Req::get('act')==null?Tiny::app()->getController()->defaultAction:Req::get('act'));

    }
    public function current_menu($key=null)
    {
        $key = $this->link_key;
        if(isset($this->nodes[$key]))
        {
            $subMenu = $this->nodes[$key]['parent'];
            $menu = $this->subMenu[$subMenu]['parent'];
            return array('menu'=>$menu,'subMenu'=>$subMenu);
        }
        return null;
    }
    public function getMenu()
    {
        return isset($this->menu)?$this->menu:array();
    }
    public function getSubMenu($key)
    {
        return isset($this->_menu[$key])?$this->_menu[$key]:array();
    }
    public function getNodes($key)
    {
        return isset($this->_subMenu[$key])?$this->_subMenu[$key]:array();
    }
    public function currentNode()
    {

        $key = $this->link_key;
        return isset($this->nodes[$key])?$this->nodes[$key]:array();
    }
    public function getLink()
    {
        return $this->link_key;
    }
}
