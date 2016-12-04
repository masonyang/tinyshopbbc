<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 4/12/16
 * Time: 上午11:39
 * 会员相关统计
 */
class MembercountController extends Controller
{
    public $layout='admin';
    private $top = null;
    public $needRightActions = array('*'=>true);
    public function init()
    {
        $menu = new Menu();
        $this->assign('mainMenu',$menu->getMenu());
        $menu_index = $menu->current_menu();
        $this->assign('menu_index',$menu_index);
        $this->assign('subMenu',$menu->getSubMenu($menu_index['menu']));
        $this->assign('menu',$menu);
        $nav_act = Req::get('act')==null?$this->defaultAction:Req::get('act');
        $nav_act = preg_replace("/(_edit)$/", "_list", $nav_act);
        $this->assign('nav_link','/'.Req::get('con').'/'.$nav_act);
        $this->assign('node_index',$menu->currentNode());
        $this->safebox = Safebox::getInstance();
        $this->assign('manager',$this->safebox->get('manager'));

        $currentNode = $menu->currentNode();
        if(isset($currentNode['name']))$this->assign('admin_title',$currentNode['name']);
    }

    public function noRight(){
        $this->redirect("admin/noright");
    }

    #会员统计
    public function member_count()
    {
        $this->redirect();
    }

}