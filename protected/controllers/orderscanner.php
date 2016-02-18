<?php
/**
 * 订单扫描管理
 *
 * @author masonyang
 * @package OrderscannerController
 */
class OrderscannerController extends Controller
{
    public $layout='admin';
    private $manager = null;
    public $needRightActions = array('*'=>true);
    private $scannersettingModel = null;

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
        $this->manager = $this->safebox->get('manager');
        $this->assign('manager',$this->manager);

        $this->scannersettingModel = new Model('scannersetting');

        $currentNode = $menu->currentNode();
        if(isset($currentNode['name']))$this->assign('admin_title',$currentNode['name']);
    }

    public function noRight(){
        $this->layout = '';
        $this->redirect("admin/noright");
    }

    public function scannersetting_list()
    {
        $condition = Req::args('condition');
        $condition_str = Common::str2where($condition);
        if($condition_str)$this->assign("where",$condition_str);
        else $this->assign("where","1=1");
        $this->assign("condition",$condition);
        $scannermenu = ScannerSetting::getScanNo();
        $this->assign('scannermenu',$scannermenu);
        $this->redirect();
    }

    public function scannersetting_del()
    {
        $scanner_ids = Req::args("id");
        if(empty($scanner_ids)) return false;

        if(is_array($scanner_ids)){
            $where = 'scanner_id in ('.implode(',',$scanner_ids).')';
        }else{
            $where = 'scanner_id = '.intval($scanner_ids);
        }

        $res = $this->scannersettingModel->where($where)->delete();

        if($res){
            $msg = array('success',"删除成功");
            $this->redirect("scannersetting_list",false,array('msg'=> $msg));
        }else{
            $this->msg = array("error","删除失败！");
            $this->redirect("scannersetting_list",false);
        }

    }

    public function scannersetting_add()
    {
        $id = Req::args("id");
        $memo = '新增';
        $scanners = array();
        $this->assign('action','scannersetting_toadd');
        if($id!==null){
            $memo = '编辑';
            $scanners = $this->scannersettingModel->where('scanner_id = '.intval($id))->find();
            $this->assign('action','scannersetting_tosave');
        }

        $scannermenu = ScannerSetting::getScanNo();
        $this->assign('scannermenu',$scannermenu);
        $this->assign('scanners',$scanners);
        $this->assign('memo',$memo);
        $this->redirect('scannersetting_add');
    }

    public function scannersetting_toadd()
    {
        $scanner_id = Req::post("id");
        $post = array();
        $post['scanner_name'] = Req::post("scanner_name");
        $post['scanner_number'] = Req::post("scanner_number");
        $post['scan_no'] = Req::post("scan_no");
        $post['status'] = Req::post("status");
        if(empty($scanner_id)){
            $checkinfo = $this->checkscannerInfo(array('scanner_name'=>$post['scanner_name'],'scanner_number'=>$post['scanner_number']));
            if($checkinfo){
                $this->msg = array("error","员工姓名或员工工号已被使用！");
                $this->redirect("scannersetting_add",false);exit;
            }

            if($this->toSave($post)){
                $msg = array('success',"保存成功");
                $this->redirect("scannersetting_list",false,array('msg'=> $msg));
            }else{
                $this->msg = array("error","保存失败！");
                $this->redirect("scannersetting_add",false);exit;
            }

        }else{
            $this->msg = array("error","参数有误！");
            $this->redirect("scannersetting_add",false);exit;
        }

    }

    private function toSave($params = array())
    {
        $id = $params['scanner_id'];
        unset($params['scanner_id']);

        $scanner_type = ScannerSetting::getScannerType($params['scan_no']);

        $aData = array(
            'scanner_name'=>$params['scanner_name'],
            'scanner_number'=>$params['scanner_number'],
            'scan_no'=>$params['scan_no'],
            'modifytime'=>time(),
            'scanner_type'=>$scanner_type,
            'status'=>$params['status'],
        );


        if(empty($id)){
            $rs = $this->scannersettingModel->data($aData)->add();
            if($rs){
                return true;
            }else{
                return false;
            }
        }else{
            unset($aData['scanner_name']);
            $rs = $this->scannersettingModel->data($aData)->where('scanner_id='.$id)->update();
            if($rs){
                return true;
            }else{
                return false;
            }
        }
    }

    private function checkscannerInfo($params = array(),$scanner_id)
    {

        $where = array();

        if(isset($params['scanner_name']) && $params['scanner_name']){
            $where[] = 'scanner_name = "'.$params['scanner_name'].'"';
        }
        if(isset($params['scanner_number']) && $params['scanner_number']){
            $where[] = 'scanner_number = "'.$params['scanner_number'].'"';
        }

        if(empty($scanner_id)){
            $sqlwhere = implode(' OR ',$where);
        }else{
            $sqlwhere = 'scanner_id != '.$scanner_id.' AND ('.implode(' OR ',$where).')';
        }

        $rows = $this->scannersettingModel->fields("count(scanner_id) as _count")->where($sqlwhere)->find();

        if($rows['_count'] > 0){
            return true;
        }else{
            return false;
        }
    }

    public function scannersetting_tosave()
    {
        $post = array();
        $post['scanner_id'] = Req::post("id");
        $post['scanner_name'] = Req::post("scanner_name");
        $post['scanner_number'] = Req::post("scanner_number");
        $post['scan_no'] = Req::post("scan_no");
        $post['status'] = Req::post("status");
        if(!empty($post['scanner_id'])){

            $checkinfo = $this->checkscannerInfo(array('scanner_name'=>$post['scanner_name'],'scanner_number'=>$post['scanner_number']),$post['scanner_id']);
            if($checkinfo){
                $this->msg = array("error","员工姓名或员工工号已被使用！");
                $this->redirect('scannersetting_add',false);exit;
            }
            if($this->toSave($post)){
                $msg = array('success',"保存成功");
                $this->redirect("scannersetting_list",false,array('msg'=> $msg));
            }else{
                $this->msg = array("error","保存失败！");
                $this->redirect('scannersetting_add',false);exit;
            }
        }else{
            $this->msg = array("error","参数有误！");
            $this->redirect('scannersetting_add',false);exit;
        }
    }

    public function scannerorder()
    {
        $this->redirect("scannerorder");
    }

    public function docheckauth(){

        $post = Req::post();
        $result = OrderScanner::getInstance()->docheckauth($post);

        echo json_encode($result);exit;
    }


    public function checkscannorders(){

        $post = Req::post();

        if(is_array($post['order_id'])){
            $result = array('res'=>'true','msg'=>'');
            $error = '';
            foreach($post['order_id'] as $orderid){
                $errorInfo = OrderScanner::getInstance()->scannorderInfo($post['scanner_type'],trim($orderid));
                if($errorInfo['res'] == 'false'){
                    $error .= $orderid.':'.$errorInfo['msg'].'.';
                }
            }

            if(!empty($error)){
                $result['res'] = 'false';
                $result['msg'] = $error;
            }
        }else{
            $result = OrderScanner::getInstance()->scannorderInfo($post['scanner_type'],trim($post['order_id']));
        }

        echo json_encode($result);exit;
    }

    public function doscannorders(){

        $post = Req::post();

        if(is_array($post['order_id'])){

            foreach($post['order_id'] as $orderid){
                $post = array(
                    'scanner_id'=>$post['scanner_id'],
                    'order_id'=>trim($orderid),
                    'scanner_time'=>time(),
                );
                $result = OrderScanner::getInstance()->doscannorders($post);

            }
        }else{
            $post = array(
                'scanner_id'=>$post['scanner_id'],
                'order_id'=>trim($post['order_id']),
                'scanner_time'=>time(),
            );
            $result = OrderScanner::getInstance()->doscannorders($post);
        }

        echo json_encode($result);exit;
    }

}