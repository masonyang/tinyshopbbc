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
        $condition = Req::args("condition");
        $condition_input = Req::args("condition_input");

        if($condition && $condition_input){
            switch($condition){
                case 'distributor_name':
                    $where = 'distributor_name like "'.trim($condition_input).'%"';
                    break;
                case 'site_url':
                    $where = 'site_url like "'.trim($condition_input).'%"';
                    break;
                case 'mobile':
                    $where = 'mobile like "'.trim($condition_input).'%"';
                    break;
            }
        }else{
            $where = "1=1";
        }

		$serverName = Tiny::getServerName();
		$domain = '.'.$serverName['domain'].'.'.$serverName['ext'];
		$this->assign("domain",$domain);

        $this->assign("where",$where);
        $this->assign("condition",$condition);
        $this->assign("condition_input",$condition_input);
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


        $distrObj = new Model('distributor');
        $distrInfos = $distrObj->where('disabled = 0')->findAll();

        $serverName = Tiny::getServerName();

        $env = Tiny::getEnv($serverName['domain']);

        $mapper = @require(APP_CODE_ROOT.'config/mapper_'.$env.'.php');

        $mappers = array_keys($mapper);
        unset($mapper);

        $siteurl = array('zd');
        foreach($distrInfos as $val){
            $siteurl[] = $val['site_url'];
        }

        $domainList = array_diff($mappers,$siteurl);

        unset($mappers);
        unset($distrInfos);

        if(!$id && empty($domainList)){
            $this->redirect("distributor_list",true,array('msg'=>array('error','没有可用的域名,暂时无法新建分销商！')));
        }

        $this->assign("domain_list",$domainList);

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
        $android_content = Req::post('android_content');
        $ios_content = Req::post('ios_content');
        $android_appversion = Req::post('android_appversion');
        $ios_appversion = Req::post('ios_appversion');
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
        $data['ios_content'] = $ios_content;
        $data['android_content'] = $android_content;
        $data['ios_appversion'] = $ios_appversion;
        $data['android_appversion'] = $android_appversion;
        $data['zip'] = $zip;
		$data['site_keyword'] = $site_keyword;
		$data['site_description'] = $site_description;
		$data['deposit'] = empty($deposit) ? 0 : $deposit;
		$data['disabled'] = $disabled;
		$data['catids'] = implode(',',$catids);

		$distributorModel = new Model("distributor");
		
		if($id){
			
			$distrModel = new Model('distributor',null,'master');
        	$distrInfo = $distrModel->fields('catids')->where('distributor_id='.$id)->find();

			$distributorModel->data($data)->where("distributor_id=$id")->update();

            $data['distributor_id'] = $id;
            $data['before_catids'] = $distrInfo['catids'];
            //同步分销商信息到分店
            syncDistributorInfo::getInstance()->setParams($data,'update')->sync();
//            //同步商品分类
//            syncBrand::getInstance()->setParams($data,'update')->sync();

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

			OpenShop::getInstance()->done();

            $this->redirect("distributor_list",true,array('msg'=>array('success',$distributor_name.'分销商新建成功！')));
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
            $distributor_password = CHash::md5($password,$validcode);

            $disInfo = $model->where("distributor_id=$id")->find();
			$flag = $model->where("distributor_id=$id")->data(array('distributor_password'=>$distributor_password,'validcode'=>$validcode))->update();

//            $data['distributor_id'] = $id;
//            $data['password'] = $distributor_password;
//            $data['validcode'] = $validcode;

            $mangeModel = new Model("manager",$disInfo['site_url']);

            $mangeModel->data(array('password'=>$distributor_password,'validcode'=>$validcode))->where("id=1")->update();

            //syncDistributorInfo::getInstance()->setParams($data,'update')->sync();
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

    public function deposit_list()
    {
        $condition = Req::args('condition');
        $condition_str = Common::str2where($condition);
        if($condition_str)$this->assign("where",$condition_str);
        else $this->assign("where","1=1");

        $this->assign("condition",$condition);
        $this->redirect();
    }

    public function deposit_log()
    {
        $this->layout = "blank";
        $id = Filter::int(Req::args('id'));
        $condition = Req::args('condition');
        $condition_str = Common::str2where($condition);
        if($condition_str)$this->assign("where",$condition_str);
        else $this->assign("where","1=1");

        $model = new Model("distributor");
        $distrInfo = $model->where("distributor_id=$id")->find();
        if($distrInfo['site_url']){
            $domain = $distrInfo['site_url'];
        }else{
            $domain = 'zd';
        }
        $this->assign("domain",$domain);
        $this->assign("id",$id);
        $this->redirect();
    }

    public function distributor_rechange()
    {
        $id = Filter::int(Req::args('id'));
        $deposit = Req::args('deposit');
        $info = array('status'=>'fail','msg'=>'充值失败');
        $model = new Model("distributor","zd","master");
        $distrInfo = $model->where("distributor_id=$id")->find();
        if($distrInfo){
            $distrInfo['deposit'] += $deposit;
            $model->data(array('deposit'=>$distrInfo['deposit']))->where("distributor_id=$id")->update();

            //同步预存款金额到分店
            $managerObj = new Model("manager",$distrInfo['site_url'],"master");
            $distrInfo = $managerObj->where("distributor_id=$id")->find();
            $distrInfo['deposit'] += $deposit;

            $managerObj->data(array('deposit'=>$distrInfo['deposit']))->where("id=".$distrInfo['id'])->update();

            $manager = $this->safebox->get('manager');
            $data['op_name'] = $manager['name'];
            $data['op_time'] = time();
            $data['op_id'] = $manager['id'];
            $data['money'] = $deposit;
            $data['action'] = 'add';
            $data['op_ip'] = Chips::getIP();
            $data['memo'] = '操作人【'.$manager['name'].'】充值 '.$deposit.'元, 充值后 预存款剩余金额:'.$distrInfo['deposit'].'元';
            Log::rechange($data,$distrInfo['site_url']);
            $info = array('status'=>'success','money'=>$distrInfo['deposit']);
        }

        echo JSON::encode($info);
    }

    public function rechange_login()
    {
        $rechangelogin = Req::post('rechangelogin');
        $info = array('status'=>'fail','msg'=>'密码错误');
        $manager = $this->safebox->get('manager');
        $managerObj = new Model('manager');
        $user = $managerObj->fields('distr_rechange_pwd,distr_rechange_validcode,roles')->where('id = '.$manager['id'])->find();

        if($user['roles'] == 'administrator'){
            $key = md5($user['distr_rechange_validcode']);
            $password = substr($key,0,16).$rechangelogin.substr($key,16,16);
            if($user['distr_rechange_pwd'] == md5($password))
            {
                $info = array('status'=>'success','msg'=>'');
            }
        }

        echo JSON::encode($info);
    }

    public function appinfo_list()
    {

       $this->redirect();

    }

    //提现审核列表
    public function distributor_txchecklist()
    {

        $where = '1=1';

        $serverName = Tiny::getServerName();

        $domain = '.'.$serverName['domain'].'.'.$serverName['ext'];

        $this->assign("domain",$domain);

        $this->assign("where",$where);

        $this->redirect();
    }

    //提现审核编辑页面
    public function distributor_txcheckedit()
    {
        $id = Filter::int(Req::args('id'));

        $applyModel = new Model('distributor_apply','zd','salve');

        $serverName = Tiny::getServerName();

        $applyData = $applyModel->where('id="'.$id.'"')->find();

        foreach($applyData as $k=>$v){
            $this->assign($k,$v);
        }

        $domain = '.'.$serverName['domain'].'.'.$serverName['ext'];

        $this->assign("domain",$domain);

        $this->redirect();
    }

    //提现审核保存
    public function distributor_txchecksave()
    {
        $args = Req::args();

        $status = $args['status'];
        $content = trim($args['content']);

        if(!$args){
            $this->msg=array("error","参数错误！");
            $this->redirect("distributor_txchecklist",false,Req::args());
            exit;
        }

        switch($status){
            case 'succ':

            break;
            case 'fail':
                if(empty($content)){
                    $this->msg=array("error","请填写原因！");
                    $this->redirect("distributor_txcheckedit",false,Req::args());
                    exit;
                }
            break;
            default:
                $this->msg=array("error","请选择审核状态！");
                $this->redirect("distributor_txcheckedit",false,Req::args());
                exit;
            break;
        }

        $data = array(
            'status'=>$args['status'],
            'content'=>$args['content'],
            'modify_time'=>time(),
        );

        $return = $this->upattach_file($ret);

        if($return){
            $data['attach_file'] = $ret['file'];
        }else{
            $this->msg=array("error",$ret['msg']);
            $this->redirect("distributor_txcheckedit",false,Req::args());
            exit;
        }

        $applyModel = new Model('distributor_apply','zd','master');

        $applyModel->data($data)->where('id='.$args['id'])->update();

        if($status == 'succ'){

            $applyData = $applyModel->where('id='.$args['id'])->find();

            $disModel = new Model('distributor','zd','master');

            $disData = $disModel->where('site_url = "'.$applyData['site_url'].'"')->find();

            $deposit = $disData['deposit'] - $applyData['apply_money'];

            $disModel->data(array('deposit'=>$deposit))->where('site_url = "'.$applyData['site_url'].'"')->update();

            $managerModel = new Model('manager',$applyData['site_url'],'master');

            $mangeData = $managerModel->where('site_url = "'.$applyData['site_url'].'"')->find();

            $managerModel->data(array('deposit'=>$deposit))->where('id = '.$mangeData['id'])->update();

        }

        $this->redirect('distributor_txchecklist');
    }

    //提现审核查看页面
    public function distributor_txcheckview()
    {
        $this->layout = "blank";
        $id = Filter::int(Req::args('id'));

        $serverName = Tiny::getServerName();

        $domain = '.'.$serverName['domain'].'.'.$serverName['ext'];

        $this->assign("domain",$domain);

        $this->assign("id",$id);
        $this->redirect();
    }

    //提现申请列表
    public function distributor_txapplylist()
    {

        $serverName = Tiny::getServerName();

        $where = 'site_url ="'.$serverName['top'].'"';

        $this->assign("where",$where);

        $this->redirect();
    }

    //提现申请编辑页面
    public function distributor_txapplyedit()
    {

        $managerModel = new Model('manager');

        $serverName = Tiny::getServerName();

        $deposit = $managerModel->fields('deposit')->where('site_url="'.$serverName['top'].'"')->find();

        $this->assign("deposit",$deposit['deposit']);

        $this->redirect();
    }

    //提现申请查看页面
    public function distributor_txapplyview()
    {
        $this->layout = "blank";
        $id = Filter::int(Req::args('id'));

        $this->assign("id",$id);
        $this->redirect();
    }

    //提现申请编辑页面
    public function distributor_txapplysave()
    {
        $args = Req::args();

        $money = $args['apply_money'];

        if(!Validator::number($args['apply_money'])){
            $this->msg=array("error","申请金额有误！");
            $this->redirect("distributor_txapplyedit",false,Req::args());
            exit;
        }

        if($args['apply_money'] < 0){
            $this->msg=array("error","申请金额不能小于0！");
            $this->redirect("distributor_txapplyedit",false,Req::args());
            exit;
        }

        $managerModel = new Model('manager');

        $serverName = Tiny::getServerName();

        $deposit = $managerModel->fields('deposit')->where('site_url="'.$serverName['top'].'"')->find();

        if($args['apply_money'] < $deposit['deposit']){
            $this->msg=array("error","最多可提现金额为".$deposit['deposit']."！");
            $this->redirect("distributor_txapplyedit",false,Req::args());
            exit;
        }

        $applyModel = new Model('distributor_apply','zd','master');

        $data = array(
            'site_url'=>$serverName['top'],
            'create_time'=>time(),
            'apply_money'=>$money,
        );

        $applyModel->data($data)->insert();

        $this->redirect('distributor_txapplylist');
    }

    public function upattach_file(&$ret = array())
    {
        $output_dir = APP_ROOT.'data'.DIRECTORY_SEPARATOR.'txapply'.DIRECTORY_SEPARATOR;

        $ret['file'] = '';

        if(isset($_FILES["attach_file"]))
        {

            if(!is_array($_FILES["attach_file"]["name"])) //single file
            {

                $oldfilename = $_FILES["attach_file"]["name"];

                $ext = pathinfo($oldfilename);

                $extsion = array('jpg');
                if(!in_array($ext['extension'],$extsion)){
                    $ret['msg'] = $ext['extension'].'不支持. 请上传类型为: '.implode(',',$extsion);
                    return false;
                }

                $fileName = md5($oldfilename.time());

                move_uploaded_file($_FILES["attach_file"]["tmp_name"],$output_dir.$fileName.'.'.$ext['extension']);

                $ret['file'] = $fileName.'.'.$ext['extension'];
            }

        }

        return true;
    }

}