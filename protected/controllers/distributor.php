<?php
/**
 * 分销商管理
 *
 * @author masonyang
 * @package DistributorController
 */
class DistributorController extends Controller
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

	public function noRight()
	{
		$this->redirect("admin/noright");
	}

	public function distributor_list()
	{
		$condition = Req::args('condition');
		$condition_str = Common::str2where($condition);
		if($condition_str)$this->assign("where",$condition_str);
		else $this->assign("where","1=1");

		$serverName = Tiny::getServerName();
		$domain = '.'.$serverName['domain'].'.'.$serverName['ext'];
		$this->assign("domain",$domain);

		$this->assign("condition",$condition);
		$this->redirect();
	}

	public function distributor_edit()
	{
        $id = Filter::int(Req::args('id'));
		$distributor = Req::args();
		if($id){
			$model = new Model("distributor");
			$distributor = $model->where("distributor_id=".$id)->find();
		}
		$serverName = Tiny::getServerName();
		$domain = '.'.$serverName['domain'].'.'.$serverName['ext'];
		$this->assign("domain",$domain);
		$this->redirect('distributor_edit',false,$distributor);
	}

	public function distributor_save()
	{
		$return = true;
        $id = Filter::int(Req::post('distributor_id'));
		$catids = Req::post('catids');
		$distributor_name = Req::post('distributor_name');
		$distributor_password = Req::post('distributor_password');
		$email = Req::post('email');
		$province = Req::post('province');
		$city = Req::post('city');
		$county = Req::post('county');
		$addr = Req::post('addr');
		$phone = Req::post('phone');
        $mobile = Req::post('mobile');
		$site_name = Req::post('site_name');
		$site_logo = Req::post('site_logo');
		$site_icp = Req::post('site_icp');
		$site_url = Req::post('site_url');
		$site_ios_url = Req::post('site_ios_url');
		$site_android_url = Req::post('site_android_url');
		$zip = Req::post('zip');
		$site_keyword = Req::post('site_keyword');
		$site_description = Req::post('site_description');
		$deposit = Req::post('deposit');
		$disabled = Req::post('disabled');
		
		$data = array();

		$data['email'] = $email;
		$data['province'] = $province;
		$data['city'] = $city;
		$data['county'] = $county;
		$data['addr'] = $addr;
		$data['phone'] = $phone;
        $data['mobile'] = $mobile;
		$data['site_name'] = $site_name;
		$data['site_logo'] = $site_logo;
		$data['site_icp'] = $site_icp;
		$data['site_url'] = $site_url;
		$data['site_ios_url'] = $site_ios_url;
		$data['site_android_url'] = $site_android_url;
        $data['zip'] = $zip;
		$data['site_keyword'] = $site_keyword;
		$data['site_description'] = $site_description;
		$data['deposit'] = empty($deposit) ? 0 : $deposit;
		$data['disabled'] = $disabled;
		$data['catids'] = implode(',',$catids);


//        $data['distributor_name'] = '测试分销商';
//        $data['validcode'] = 'h10Y!2P8';
//        $data['distributor_password'] = 'b74893a3b2d0e1f078e8212aa2e7fcb1';
//        $data['register_time'] = time();
//        $data['distributor_id'] = 1;
//
//        OpenShop::getInstance()->setParams($data);
        //OpenShop::getInstance()->updateMapperConfig();
        //OpenShop::getInstance()->createDb(); //ok
        //OpenShop::getInstance()->createTables(); //ok
//        OpenShop::getInstance()->syncDistributorInfo();//ok
//        OpenShop::getInstance()->syncGoods();
//        $msg = OpenShop::getInstance()->done();
//        exit;
		$distributorModel = new Model("distributor");
		
		if($id){
			$distributorModel->data($data)->where("distributor_id=$id")->update();

			//为分销商 更新授权商品分类、授权商品、分销商信息

		}else{
			$data['distributor_name'] = $distributor_name;
			$validcode = CHash::random(8);
			$data['validcode'] = $validcode;
			$data['distributor_password'] = CHash::md5($distributor_password,$validcode);
			$data['register_time'] = time();
			$data['distributor_id'] = $distributorModel->data($data)->add();

			//为新建的分销商 创建开通database table 分配域名, 写入授权商品分类、授权商品、分销商信息
			OpenShop::getInstance()->setParams($data);
			OpenShop::getInstance()->updateMapperConfig();
			OpenShop::getInstance()->createDb(); //ok
			OpenShop::getInstance()->createTables(); //ok
			OpenShop::getInstance()->syncDistributorInfo();//ok
			OpenShop::getInstance()->syncGoods();
			$msg = OpenShop::getInstance()->done();
				if(false == $msg['res']){
					$this->msg = array("error",$msg['msg']);
				}
		}
		$this->redirect("distributor_list",$return);
	}

	public function distributor_password()
	{
        $id = Filter::int(Req::post('id'));
		$password = Req::post("password");
		$repassword = Req::post("repassword");
		$info = array('status'=>'fail');
		if($id && $password && $password == $repassword){
			$model = new Model("distributor");
			$validcode = CHash::random(8);
			$flag = $model->where("distributor_id=$id")->data(array('distributor_password'=>CHash::md5($password,$validcode),'validcode'=>$validcode))->update();
			if($flag)$info = array('status'=>'success');
		}
		echo JSON::encode($info);
	}

	public function distributor_view()
	{
		$this->layout = "blank";
        $id = Filter::int(Req::args('id'));
		$serverName = Tiny::getServerName();
		$domain = '.'.$serverName['domain'].'.'.$serverName['ext'];
		$this->assign("domain",$domain);
		$this->assign("id",$id);
		$this->redirect();
	}

}