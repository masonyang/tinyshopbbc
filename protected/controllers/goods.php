<?php
/**
 * 商品管理
 *
 * @author masonyang
 * @package GoodsController
 */
class GoodsController extends Controller
{
	public $layout='admin';
	private $top = null;
	public $needRightActions = array('*'=>true);
	private $manager;
    private $other_tradeprice_rate = 0;

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
		$this->assign('manager',$this->safebox->get('manager'));

        $serverName = Tiny::getServerName();
        $this->assign("domain",$serverName['top']);

		$currentNode = $menu->currentNode();

        if(isset($currentNode['name']))$this->assign('admin_title',$currentNode['name']);

        $config = Config::getInstance()->get("other");

        $this->other_tradeprice_rate = $config['other_tradeprice_rate'] ? $config['other_tradeprice_rate'] : 0;
        $this->assign("other_tradeprice_rate",$this->other_tradeprice_rate);

        $this->assign('p',Req::get('p'));

        $serverName = Tiny::getServerName();
        $this->assign("local_domain",$serverName['top']);

	}
	public function noRight()
	{
		$this->redirect("/admin/noright");
	}

	//商品上下架
	public function set_online()
	{
		$id = Req::args("id");
		if(is_array($id)){
			$id = implode(',', $id);
		}
		$status = Filter::int(Req::args('status'));
		if($status!=0 && $status!=1) $status = 0;
		$model = new Model('goods');
		$model->data(array('is_online'=>$status))->where("id in($id)")->update();

        $params = array();
        $params['id'] = $id;// id is array
        $params['is_online'] = $status;
        $params['syncdata_type'] = 'goods';
        syncGoods::getInstance()->setParams($params,'update')->sync();

		$this->redirect("goods_list");
	}
	function goods_type_save(){
		$attr_id = Req::args('attr_id');
		$attr_name = Req::args('attr_name');
		$attr_type = Req::args('attr_type');
		$attr_value = Req::args('attr_value');
		$brand = Req::args('brand');
		//spec 处理部分开始
		$spec = Req::args('spec');
		$specs_array = array();
		if($spec){
			$spec_ids = $spec['id'];
			if(is_array($spec_ids)) $spec_ids = implode(',', $spec_ids);
			$model = new Model('goods_spec');
			$specs = $model->where('id in('.$spec_ids.')')->order("find_in_set(id,'".$spec_ids."')")->findAll();
			$spec_value = new Model('spec_value');
			foreach ($specs as $k=>$row) {
				$result = $spec_value->where('spec_id='.$row['id'])->findAll();
				$row['spec'] = $result;
				$row['show_type'] = $spec['show_type'][$k];
				$specs_array[] = $row;
			}

		}
		Req::args('spec',serialize($specs_array));
		//spec 处理结束

		$values = array();
		if(is_array($brand)) $brand = implode(',', $brand);
		Req::args('brand',$brand);
		$goods_type = new Model("goods_type");
		$id = Req::args('id');
		if($id == null){
            $action = 'add';
			$result = $goods_type->insert();
			$lastid = $result;
			Log::op($this->manager['id'],"添加商品类型","管理员[".$this->manager['name']."]:添加了商品类型 ".Req::args('name'));
		}else{
            $action = 'update';
			$result = $goods_type->where("id=".$id)->update();
			$lastid = $id;
			Log::op($this->manager['id'],"修改商品类型","管理员[".$this->manager['name']."]:修改了商品类型 ".Req::args('name'));
		}
		$goods_attr = new Model('goods_attr');
		$attr_value_model = new Model("attr_value");
		$attr_ids ='';
		if(is_array($attr_id)){
			foreach($attr_id as $v){
				if($v!=0) $attr_ids .=$v.',';
			}
			$attr_ids = rtrim($attr_ids,',');
			$goods_attr->where('type_id = '.$lastid.' and id not in('.$attr_ids.')')->delete();

            $mgadel = 'type_id = '.$lastid.' and id not in('.$attr_ids.')';

			foreach ($attr_id as $k=>$v) {
				if($v=='0'){
					$attr_last_id = $goods_attr->data(array('name'=>$attr_name[$k],'type_id'=>$lastid,'show_type'=>$attr_type[$k],'sort'=>$k))->insert();

                    $gainsert[] = array('name'=>$attr_name[$k],'type_id'=>$lastid,'show_type'=>$attr_type[$k],'sort'=>$k);

					$avdata = $this->update_attr_value($attr_value_model,$attr_last_id,$attr_value[$k]);

                    $mavdel = $avdata['del'];

                    $avupdate = $avdata['update'];

                    $avinsert = $avdata['add'];
				}
				else{
					$goods_attr->data(array('name'=>$attr_name[$k],'type_id'=>$lastid,'show_type'=>$attr_type[$k],'sort'=>$k))->where('id='.$attr_id[$k])->update();

                    $gaupdate[$attr_id[$k]] = array('name'=>$attr_name[$k],'type_id'=>$lastid,'show_type'=>$attr_type[$k],'sort'=>$k);

                    $avdata = $this->update_attr_value($attr_value_model,$attr_id[$k],$attr_value[$k]);

                    $mavdel = $avdata['del'];

                    $avupdate = $avdata['update'];

                    $avinsert = $avdata['add'];
				}
			}
			$goods_attrs = $goods_attr->where('type_id='.$lastid)->order("sort")->findAll();
			foreach ($goods_attrs  as $key => $row) {
				$row['values'] = $attr_value_model->where('attr_id = '.$row['id'])->order('sort')->findAll();
				$goods_attrs[$key] = $row;
			}
			$goods_type->data(array('attr'=>serialize($goods_attrs)))->where('id='.$lastid)->update();

            $gadel = '';
            $avdel = '';
		}else{

            $attrvals = $model->table('attr_value')->where("attr_id in (select id from tiny_goods_attr where type_id = ".$lastid.")")->findAll();

            $attr_value_ids = array();
            foreach($attrvals as $val){
                $attr_value_ids[] = $val['id'];
            }

            $avdel = 'id in ('.implode(',',$attr_value_ids).')';
            $gadel = 'type_id = '.$lastid;
            $mgadel = '';
            $gaupdate = array();
            $gainsert = array();

			$attr_value_model->where("attr_id  in (select id from tiny_goods_attr where type_id = ".$lastid.")")->delete();
			$goods_attr->where('type_id = '.$lastid)->delete();


		}

        $gtModel = new Model('goods_type',null,'master');
        $params = $gtModel->where('id='.$lastid)->find();
        $params['attr_value'] = array(
            'del'=>array($mavdel,$avdel),
            'update'=>$avupdate,
            'add'=>$avinsert,
        );
        $params['goods_attr'] = array(
            'del'=>array($mgadel,$gadel),
            'update'=>$gaupdate,
            'add'=>$gainsert,
        );
        $params['syncdata_type'] = 'goods_type';
        syncGoodsType::getInstance()->setParams($params,$action)->sync();

		$this->redirect('goods_type_list');
	}
	//更新属性值
	private function update_attr_value($attr_value_model,$attr_id,$attr_values){

		$attr_values = explode(',', $attr_values);
		$value_ids = '';
		foreach ($attr_values as $key => $value) {
			$value_array = explode(":=:", $value);
			if(count($value_array)>1){
				if($value_array[0]==0){
					$new_id = $attr_value_model->data(array('attr_id'=>$attr_id,'name'=>$value_array[1],'sort'=>$key))->insert();
					$value_ids .= $new_id.',';
                    $add[] = array('attr_id'=>$attr_id,'name'=>$value_array[1],'sort'=>$key);
				}
				else{
					$attr_value_model->data(array('attr_id'=>$attr_id,'name'=>$value_array[1],'sort'=>$key))->where('id='.$value_array[0])->update();
					$value_ids .= $value_array[0].',';
                    $update[$value_array[0]] = array('attr_id'=>$attr_id,'name'=>$value_array[1],'sort'=>$key);
				}
			}

		}
		$value_ids = rtrim($value_ids,',');
		if($value_ids=='')
			$attr_value_model->where('attr_id = '.$attr_id)->delete();
		else
			$attr_value_model->where('attr_id = '.$attr_id.' and id not in('.$value_ids.')')->delete();

        return array(
            'del'=>array(
                'value_ids'=>$value_ids,
                'use_value_ids_sql'=>'attr_id = '.$attr_id.' and id not in('.$value_ids.')',
                'use_no_value_ids_sql'=>'attr_id = '.$attr_id,
            ),
            'add'=>$add,
            'update'=>$update,
        );
	}
	function attr_values(){
		$this->layout='';
		$this->redirect();
	}
	function goods_type_del(){
		$id = Req::args('id');
		if($id){
			$model = new Model();
			if(is_array($id)){
				$ids = implode(',',$id);

                $attrvals = $model->table('attr_value')->where("attr_id in (select id from tiny_goods_attr where type_id in ({$ids}))")->findAll();

                $attr_value_ids = array();
                foreach($attrvals as $val){
                    $attr_value_ids[] = $val['id'];
                }

                $params = array();
                $params['id'] = $ids;
                $params['attr_value'] = array('id'=>implode(',',$attr_value_ids));
                $params['goods_attr'] = array('type_id'=>$ids);

				$goods_types = $model->table('goods_type')->where('id in('.$ids.')')->findAll();
				$model->table('goods_type')->where('id in('.$ids.')')->delete();
				$model->table('attr_value')->where("attr_id in (select id from tiny_goods_attr where type_id in ({$ids}))")->delete();
				$model->table('goods_attr')->where('type_id in('.$ids.')')->delete();
			}
			else{
                $attrvals = $model->table('attr_value')->where("attr_id in (select id from tiny_goods_attr where type_id ={$id})")->findAll();

                $attr_value_ids = array();
                foreach($attrvals as $val){
                    $attr_value_ids[] = $val['id'];
                }

                $params = array();
                $params['id'] = $id;
                $params['attr_value'] = array('id'=>implode(',',$attr_value_ids));
                $params['goods_attr'] = array('type_id'=>$id);

				$goods_types = $model->table('goods_type')->where('id in('.$id.')')->findAll();
				$model->table('goods_type')->where('id='.$id)->delete();
				$model->table('attr_value')->where("attr_id in (select id from tiny_goods_attr where type_id ={$id})")->delete();
				$model->table('goods_attr')->where('type_id ='.$id)->delete();

			}
            $params['syncdata_type'] = 'goods_type';
            syncGoodsType::getInstance()->setParams($params,'del')->sync();

			$str = '';
			foreach ($goods_types as $key => $value) {
				$str .= $value['name'].'、';
			}
			Log::op($this->manager['id'],"删除商品类型","管理员[".$this->manager['name']."]:删除了商品类型 ".$str);

			$this->redirect('goods_type_list');
		}else{
			$this->msg = array("warning","未选择项目，无法删除！");
			$this->redirect('goods_type_list',false);
		}
	}
	function goods_spec_show(){
		$this->layout = '';
		$this->redirect();
	}
	function goods_spec_save(){
		$id = Req::args('id');
		$value = Req::args('value');
		$img = Req::args('img');
		$value_id = Req::args('value_id');
		$name = Req::args("name");
		$values = array();
		$goods_spec = new Model("goods_spec");
		if($id){
            $action = 'update';
			$goods_spec->save();
			$lastid = $id;
			Log::op($this->manager['id'],"修改商品规格","管理员[".$this->manager['name']."]:修改了规格 ".$name);
		}else{
            $action = 'add';
			$lastid = $goods_spec->save();
			Log::op($this->manager['id'],"添加商品规格","管理员[".$this->manager['name']."]:添加了规格 ".$name);
		}
		$spec_value = new Model('spec_value');
		$value_ids ='';
		if(is_array($value_id)){
			foreach($value_id as $v){
				if($v!=0) $value_ids .=$v.',';
			}
			$value_ids = rtrim($value_ids,',');
			$spec_value->where('spec_id = '.$lastid.' and id not in('.$value_ids.')')->delete();
            $mdel = 'spec_id = '.$lastid.' and id not in('.$value_ids.')';
			foreach ($value_id as $k=>$v) {
				if($v=='0'){
					$spec_value->data(array('name'=>$value[$k],'spec_id'=>$lastid,'sort'=>$k,'img'=>is_array($img)?$img[$k]:''))->insert();
                    $add[] = array('name'=>$value[$k],'spec_id'=>$lastid,'sort'=>$k,'img'=>is_array($img)?$img[$k]:'');
				}
				else
                {
                    $spec_value->data(array('name'=>$value[$k],'spec_id'=>$lastid,'sort'=>$k,'img'=>is_array($img)?$img[$k]:''))->where('id='.$value_id[$k])->update();
                    $update[$value_id[$k]] = array('name'=>$value[$k],'spec_id'=>$lastid,'sort'=>$k,'img'=>is_array($img)?$img[$k]:'');
                }

			}
			$spec_values = $spec_value->where('spec_id = '.$lastid)->findAll();
			$goods_spec->data(array('value'=>serialize($spec_values)))->where('id='.$lastid)->update();
		}
		else{
			$spec_value->where('spec_id = '.$lastid)->delete();
            $del = 'spec_id = '.$lastid;
		}

        $specValueModel = new Model('goods_spec',null,'master');
        $params = $specValueModel->where('id='.$lastid)->find();
        $params['spec_value'] = array(
            'add'=>$add,
            'update'=>$update,
            'del'=>array($mdel,$del),
        );
        $params['syncdata_type'] = 'goods_spec';
        syncSpec::getInstance()->setParams($params,$action)->sync();

		$this->redirect('goods_spec_list');
	}
	function goods_spec_del(){
		$id = Req::args('id');
		if($id){
			$model = new Model();
			if(is_array($id)){
				$ids = implode(',',$id);
				$specs = $model->table('goods_spec')->where('id in('.$ids.')')->findAll();
				$model->table('goods_spec')->where('id in('.$ids.')')->delete();
				$model->table('spec_value')->where('spec_id in('.$ids.')')->delete();

			}
			else{
				$specs = $model->table('goods_spec')->where('id='.$id)->findAll();
				$model->table('goods_spec')->where('id='.$id)->delete();
				$model->table('spec_value')->where('spec_id ='.$id)->delete();
			}
			$str = '';
			foreach ($specs as $key => $value) {
				$str .= $value['name'].'、';
			}
			Log::op($this->manager['id'],"删除商品规格","管理员[".$this->manager['name']."]:删除了规格 ".$str);

            $params = array();
            $params['id'] = $id;
            $params['spec_value'] = $id;
            $params['syncdata_type'] = 'goods_spec';
            syncSpec::getInstance()->setParams($params,'del')->sync();

			$this->redirect('goods_spec_list');
		}else{
			$this->msg = array("warning","未选择项目，无法删除！");
			$this->redirect('goods_spec_list',false);
		}
	}

	//商品分类
	function goods_category_save(){
		$goods_category = new Model("goods_category",null,"master");
		$name = Req::args("name");
		$alias = Req::args("alias");
		$parent_id = Req::args("parent_id");
		$sort = intval(Req::args("sort"));
		$id = Req::args("id")==null?0:Req::args("id");
		$type_id = Req::args('type_id');
		$seo_title = Req::args('seo_title');
		$seo_keywords = Req::args("seo_keywords");
		$seo_description = Req::args('seo_description');
		$img = Filter::sql(Req::args("img"));
		$imgs = is_array(Req::args("imgs"))?Req::args("imgs"):array();

		$item = $goods_category->where("id != $id and ((name = '$name' and parent_id =$parent_id ) or alias = '$alias' )")->find();
		if($item){
			if($alias == $item['alias']) $this->msg = array("warning","别名要求唯一,方便url美化,操作失败！");
				else $this->msg = array("error","同一级别下已经在在相同分类！");
				unset($item['id']);
			$this->redirect("goods_category_edit",false,Req::args());
		}else{
			//最得父节点的信息
			$parent_node = $goods_category->where("id = $parent_id")->find();
			$parent_path = "";
			if($parent_node){
				$parent_path = $parent_node['path'];
			}
			$current_node = $goods_category->where("id = $id")->find();
			//更新节点
			if($current_node){
				$current_path = $current_node['path'];
				if(strpos($parent_path, $current_path)===false){

					if($parent_path!='')$new_path = $parent_path.$current_node['id'].",";
					else $new_path = ','.$current_node['id'].',';

					$goods_category->data(array('path'=>"replace(`path`,'$current_path','$new_path')"))->where("path like '$current_path%'")->update();
					$goods_category->data(array('parent_id'=>$parent_id,'id'=>$id,'sort'=>$sort,'name'=>$name,'alias'=>$alias,'type_id'=>$type_id,'seo_title'=>$seo_title,'seo_keywords'=>$seo_keywords,'seo_description'=>$seo_description,'img'=>$img,'imgs'=>serialize($imgs)))->update();
					Log::op($this->manager['id'],"更新商品分类","管理员[".$this->manager['name']."]:更新了商品分类 ".Req::args('name'));

                    $params = $goods_category->where('id='.$id)->find();
                    $params['syncdata_type'] = 'goods_category';
                    syncCategory::getInstance()->setParams($params,'update')->sync();

                    $this->redirect("goods_category_list");
				}else{
					$this->msg = array("warning","此节点不能放到自己的子节点上,操作失败！");
					$this->redirect("goods_category_edit",false,Req::args());
				}
			}
			else{
				//插件节点
				$lastid = $goods_category->insert();
				if($parent_path!='')$new_path = $parent_path."$lastid,";
				else $new_path = ",$lastid,";
				$goods_category->data(array('path'=>"$new_path",'id'=>$lastid,'sort'=>$sort,'type_id'=>$type_id,'seo_title'=>$seo_title,'seo_keywords'=>$seo_keywords,'seo_description'=>$seo_description,'img'=>$img,'imgs'=>serialize($imgs)))->update();

				Log::op($this->manager['id'],"添加商品分类","管理员[".$this->manager['name']."]:添加商品分类 ".Req::args('name'));

                $params = $goods_category->where('id='.$lastid)->find();
                $params['syncdata_type'] = 'goods_category';
                syncCategory::getInstance()->setParams($params,'add')->sync();

                $this->redirect("goods_category_list");
			}
			$cache = CacheFactory::getInstance();
        	$cache->delete("_GoodsCategory");
		}
	}
	//商品分类删除
	function goods_category_del(){
		$id = Req::args('id');
		$category = new Model("goods_category");
		$child = $category->where("parent_id = $id")->find();
		if($child){
			$this->msg = array("warning","由于存在子分类，此分类不能删除，操作失败！");
			$this->redirect("goods_category_list",false);
		}
		else{
			$goods = new Model("goods");
			$row = $goods->where('category_id = '.$id)->find();
			if($row){
				$this->msg = array("warning","此分类下还有商品，无法删除！");
			$this->redirect("goods_category_list",false);
			}else{
				$obj = $category->where("id=$id")->find();
				$category->where("id=$id")->delete();
				if($obj)Log::op($this->manager['id'],"删除商品分类","管理员[".$this->manager['name']."]:删除了商品分类 ".$obj['name']);
				$cache = CacheFactory::getInstance();
        		$cache->delete("_GoodsCategory");

                $params = array();
                $params['id'] = $id;
                $params['syncdata_type'] = 'goods_category';
                syncCategory::getInstance()->setParams($params,'del')->sync();

				$this->redirect("goods_category_list");
			}
		}
	}

	function goods_save(){
//        echo "<pre>";
//        print_r(Req::args());exit;
		$spec_items = Req::args('spec_items');
		$spec_item = Req::args('spec_item');
		$items = explode(",", $spec_items);
		$values_array = array();
		//货品中的一些变量
		$pro_no = Req::args("pro_no");
		$store_nums = Req::args("store_nums");
		$warning_line = Req::args("warning_line");
		$weight = Req::args("weight");
//goods_no] => 2323-2321
        // pro_no] => 2323-11
		$market_price = Req::args("market_price");
		$cost_price = Req::args("cost_price");

		$branchstore_goods_name = Req::args("branchstore_goods_name");//分店自定义商品名称
		$branchstore_sell_price = Req::args("branchstore_sell_price");//分店自定义销售价
		$trade_price = Req::args("trade_price");//批发价
		$sell_price = Req::args("sell_price");//销售价

		//values的笛卡尔积
		$values_dcr = array();
		$specs_new = array();
		if(is_array($spec_item)){
			foreach ($spec_item as $item) {
				$values = explode(",", $item);

				foreach ($values as $value) {
					$value_items = explode(":", $value);
					$values_array[$value_items[0]]=$value_items;
				}
			}
			$value_ids = implode(",",array_keys($values_array));
			$values_model = new Model('spec_value');
			$spec_model = new Model('goods_spec');
			$specs = $spec_model->where("id in ({$spec_items})")->findAll();
			$values = $values_model->where("id in ({$value_ids})")->order('sort')->findAll();
			$values_new = array();
			foreach ($values as $k => $row) {
				$current = $values_array[$row['id']];
				if($current[1]!=$current[2]) $row['name'] = $current[2];
				if($current[3]!='') $row['img'] = $current[3];
				$values_new[$row['spec_id']][$row['id']] = $row;
			}

			foreach ($specs as $key => $value) {
				$value['value'] = isset($values_new[$value['id']])?$values_new[$value['id']]:null;
				$specs_new[$value['id']] = $value;
			}

			foreach ($spec_item as $item) {
				$values = explode(",", $item);
				$key_code = ';';
				foreach ($values as $k => $value) {
					$value_items = explode(":", $value);
					$key = $items[$k];
					$tem[$key] = $specs_new[$key];
					$tem[$key]['value'] = $values_array[$value_items[0]];
					$key_code .= $key.':'.$values_array[$value_items[0]][0].';';
				}
				$values_dcr[$key_code] = $tem;
			}
		}

		//商品处理
		$goods = new Model('goods');

        $serverName = Tiny::getServerName();
        if($serverName['top'] == 'zd'){
            Req::args('specs',serialize($specs_new));
        }

		$imgs = is_array(Req::args("imgs"))?Req::args("imgs"):array();
        $attrs = array();
        $serverName = Tiny::getServerName();
        if($serverName['top'] == 'zd'){
            $attrs = is_array(Req::args("attr"))?Req::args("attr"):array();
            Req::args('attrs',serialize($attrs));
        }
		Req::args('imgs',serialize($imgs));
		Req::args('up_time',date("Y-m-d H:i:s"));

		$id = intval(Req::args("id"));
		$gdata = Req::args();

        $p = Req::args('p');

        $p = isset($p) ? $p : 1;

//        if(isset($branchstore_sell_price)){
//            $suggest_price = $sell_price * ($this->other_tradeprice_rate/100);
//            if($suggest_price <= $branchstore_sell_price){
//                $gdata['branchstore_sell_price'] = $branchstore_sell_price;
//            }else{
//                $this->msg = array("error","自定义销售价不能低于销售价的".$this->other_tradeprice_rate."%");
//
//                $this->redirect('goods/goods_edit/id/'.$id.'/p/'.$p,false);exit;
//            }
//        }


		$gdata['name'] = Filter::sql($gdata['name']);
		if(is_array($gdata['pro_no'])) $gdata['pro_no'] = $gdata['pro_no'][0];
		if($id==0){
            $action = 'add';
			$gdata['create_time'] = date("Y-m-d H:i:s");
			$goods_id = $goods->data($gdata)->save();
			Log::op($this->manager['id'],"添加商品","管理员[".$this->manager['name']."]:添加了商品 ".Req::args('name'));
		}else{
            $action = 'update';
			$goods_id = $id;
			$goods->data($gdata)->where("id=".$id)->update();
			Log::op($this->manager['id'],"修改商品","管理员[".$this->manager['name']."]:修改了商品 ".Req::args('name'));
		}
		//货品添加处理
        $g_store_nums = $g_warning_line = $g_weight = $g_sell_price = $g_market_price = $g_cost_price = 0;
        $g_branchstore_sell_price = array();
        $g_trade_price = array();
		$products = new Model("products");
		$k = 0;
		foreach ($values_dcr as $key => $value) {
			$result = $products->where("goods_id = ".$goods_id." and specs_key = '$key'")->find();

			$data = array('goods_id' =>$goods_id,'pro_no'=>$pro_no[$k],'store_nums'=>$store_nums[$k],'warning_line'=>$warning_line[$k],'weight'=>$weight[$k],'sell_price'=>$sell_price[$k],'market_price'=>$market_price[$k],'cost_price'=>$cost_price[$k],'trade_price'=>$trade_price[$k],'specs_key'=>$key,'spec'=>serialize($value));


            if(isset($branchstore_sell_price)){

                $suggest_price = $sell_price[$k] * ($this->other_tradeprice_rate/100);

                if(($branchstore_sell_price[$k] == '0') || ($branchstore_sell_price[$k] == '0.00')){
                    $branchstore_sell_price[$k] = 0;
                }else{
                    if($suggest_price <= $branchstore_sell_price[$k]){
                        $data['branchstore_sell_price'] = $branchstore_sell_price[$k];
                    }else{
                        $this->msg = array("error","货号:".$pro_no[$k].",自定义销售价不能低于销售价的".$this->other_tradeprice_rate."%");
                        $this->redirect('goods/goods_edit/id/'.$id.'/p/'.$p,false);exit;
                    }
                }

            }



            $g_store_nums += $data['store_nums'];
			if($g_warning_line==0) $g_warning_line = $data['warning_line'];
			else if($g_warning_line>$data['warning_line']) $g_warning_line = $data['warning_line'];
			if($g_weight==0) $g_weight = $data['weight'];
			else if($g_weight<$data['weight']) $g_weight = $data['weight'];
			if($g_sell_price==0) $g_sell_price = $data['sell_price'];
			else if($g_sell_price>$data['sell_price']) $g_sell_price = $data['sell_price'];
			if($g_market_price==0) $g_market_price = $data['market_price'];
			else if($g_market_price<$data['market_price']) $g_market_price = $data['market_price'];
			if($g_cost_price==0) $g_cost_price = $data['cost_price'];
			else if($g_cost_price<$data['cost_price']) $g_cost_price = $data['cost_price'];

            if(isset($data['branchstore_sell_price'])){
                $g_branchstore_sell_price[] = $data['branchstore_sell_price'];
            }


            $g_trade_price[] = $data['trade_price'];

            if($data['pro_no'] == ''){
                unset($data['pro_no']);
            }

            if($data['warning_line'] == ''){
                unset($data['warning_line']);
            }

            if($data['weight'] == ''){
                unset($data['weight']);
            }

            if($data['sell_price'] == ''){
                unset($data['sell_price']);
            }

            if($data['trade_price'] == ''){
                unset($data['trade_price']);
            }

            if($serverName['top'] != 'zd'){
                unset($data['store_nums']);
            }

			if(!$result){
				$last_p_id = $products->data($data)->insert();
                $data['id'] = $last_p_id;
                $padd[] = $data;
			}else{
				$products->data($data)->where("goods_id=".$goods_id." and specs_key='$key'")->update();
                $pupdate["goods_id=".$goods_id." and specs_key='$key'"] = $data;
			}
			$k++;
		}

        $g_trade_price = min($g_trade_price);
        $g_branchstore_sell_price = min($g_branchstore_sell_price);
		//如果没有规格
		if($k==0){
			$g_store_nums = $store_nums;
			$g_warning_line = $warning_line;
			$g_weight = $weight;
			$g_sell_price = $sell_price;
			$g_market_price = $market_price;

			$g_cost_price = $cost_price;

            $g_trade_price = $trade_price;
            $g_branchstore_sell_price = $branchstore_sell_price;

			$data = array('goods_id' =>$goods_id,'pro_no'=>$pro_no,'warning_line'=>$warning_line,'weight'=>$weight,'sell_price'=>$sell_price,'market_price'=>$market_price,'cost_price'=>$cost_price,'trade_price'=>$trade_price,'specs_key'=>'','spec'=>serialize(array()));

            if($serverName['top'] == 'zd'){
                $data['store_nums'] = $store_nums;
            }

            if($g_branchstore_sell_price){

                $suggest_price = $g_sell_price * ($this->other_tradeprice_rate/100);

                if(($g_branchstore_sell_price == '0') || ($g_branchstore_sell_price == '0.00')){

                    $g_branchstore_sell_price = 0;
                    
                }else{
                    if($suggest_price <= $g_branchstore_sell_price){
                        $data['branchstore_sell_price'] = $g_branchstore_sell_price;
                    }else{
                        $this->msg = array("error","自定义销售价不能低于销售价的".$this->other_tradeprice_rate."%");
                        $this->redirect('goods/goods_edit/id/'.$id.'/p/'.$p,false);exit;
                    }
                }

            }


            $result = $products->where("goods_id = ".$goods_id)->find();
			if(!$result){
				$last_p_id = $products->data($data)->insert();
                $data['id'] = $last_p_id;
                $padd[] = $data;
			}else{
				$products->data($data)->where("goods_id=".$goods_id)->update();
                $pupdate["goods_id=".$goods_id] = $data;
			}
		}
		//更新商品相关货品的部分信息

        $ggdata = array('warning_line'=>$g_warning_line,'weight'=>$g_weight,'sell_price'=>$g_sell_price,'trade_price'=>$g_trade_price,'market_price'=>$g_market_price,'cost_price'=>$g_cost_price,'branchstore_sell_price'=>$g_branchstore_sell_price);

        if($serverName['top'] == 'zd'){
            $ggdata['store_nums'] = $g_store_nums;
        }
        $goods->data($ggdata)->where("id=".$goods_id)->update();

		$keys = array_keys($values_dcr);
		$keys = implode("','", $keys);
		//清理多余的货品
		$products->where("goods_id=".$goods_id." and specs_key not in('$keys')")->delete();

        $pdel = "goods_id=".$goods_id." and specs_key not in('$keys')";

		//规格与属性表添加部分
		$spec_attr = new Model("spec_attr");
		//处理属性部分

		$value_str = '';
		if($attrs){
			foreach ($attrs as $key => $attr) {
				if(is_numeric($attr)) $value_str .= "($goods_id,$key,$attr),";
			}
		}
		foreach ($specs_new as $key => $spec) {
			if(isset($spec['value']))foreach($spec['value'] as $k => $v)$value_str .= "($goods_id,$key,$k),";
		}
		$value_str = rtrim($value_str,',');
		//更新商品键值对表
		$spec_attr->where("goods_id = ".$goods_id)->delete();

        $sadel = "goods_id = ".$goods_id;

		$spec_attr->query("insert into tiny_spec_attr values $value_str");
        $saadd = "insert into tiny_spec_attr values $value_str";

        $goodsModel = new Model('goods',null,'master');
        $params = $goodsModel->where('id='.$goods_id)->find();
        $params['products'] = array(
            'add'=>$padd,
            'update'=>$pupdate,
            'del'=>$pdel,
        );
        $params['spec_attr'] = array(
            'del'=>$sadel,
            'add'=>$saadd,
        );
        $params['syncdata_type'] = 'goods';
        syncGoods::getInstance()->setParams($params,$action)->sync();

        $p = Req::args('p');

        $p = isset($p) ? $p : 1;
        $this->redirect('goods/goods_list/p/'.$p);
	}
	function goods_del()
	{
		$id = Req::args("id");
		$model = new Model();
		$str = '';
		if(is_array($id)){
			$id = implode(',',$id);
			$model->table("spec_attr")->where("goods_id in($id)")->delete();
			$model->table("products")->where("goods_id in($id)")->delete();
			$goods = $model->table("goods")->where("id in ($id)")->findAll();
			$model->table("goods")->where("id in ($id)")->delete();
		}else if(is_numeric($id)){
			$model->table("spec_attr")->where("goods_id = $id")->delete();
			$model->table("products")->where("goods_id = $id")->delete();
			$goods = $model->table("goods")->where("id = $id ")->findAll();
			$model->table("goods")->where("id = $id ")->delete();
		}
		foreach ($goods as $gd) {
			$str .= $gd['name'].'、';
		}
		$str = trim($str,'、');
		Log::op($this->manager['id'],"删除商品","管理员[".$this->manager['name']."]:删除了商品 ".$str);

        $params = array();
        $params['id'] = $id;
        $params['products'] = $id;
        $params['spec_attr'] = $id;
        $params['syncdata_type'] = 'goods';
        syncGoods::getInstance()->setParams($params,'del')->sync();

		$msg = array('success',"成功删除商品 ".$str);
		$this->redirect("goods_list",false,array('msg'=> $msg));
	}

	function goods_list(){
//        $condition = Req::args("condition");
//        $condition_str =  Common::str2where($condition);

        $condition = Req::args("condition");
        $condition_input = Req::args("condition_input");

        if($condition && $condition_input){
            switch($condition){
                case 'name':
                    $where = 'name like "%'.trim($condition_input).'%"';
                    break;
                case 'goods_no':
                    $where = 'goods_no like "'.trim($condition_input).'%"';
                    break;
                case 'tag_ids':
                    $where = 'tag_ids like "'.trim($condition_input).'%"';
                    break;
            }
        }else{
            $where = "1=1";
        }

        $limit = Req::args('limit');
        $limit = $limit ? $limit : 20;

        $p = Req::args("p");

        if(isset($p) && (intval($p) > 0)){
            $this->assign("p",$p);
        }else{
            $this->assign("p",1);
        }
        $this->assign("condition",$condition);
        $this->assign("condition_input",$condition_input);

        $this->assign('limit',$limit);

		$this->assign("where",$where);
		$this->redirect();
	}

	function show_spec_select(){
		$this->layout = '';
		$this->redirect();
	}
	function photoshop(){
		$this->layout = '';
		$this->redirect();
	}

    function goods_select()
    {
        $goods = Req::args("goods");
        if(empty($goods)){
            $this->redirect("goods_list",false);exit;
        }

        $where = "1=1";
        if($goods && $goods!=''){
             $where = " p.pro_no like '{$goods}%'";
        }


        $productsModel = new Model('products as p');
        $products = $productsModel->fields('p.goods_id as goods_id')->join('left join goods as g on p.goods_id=g.id')->where($where)->findAll();

        $goodsids = array(0);
        if($products){
            foreach($products as $value){
                $goodsids[] = $value['goods_id'];
                $goodsids = array_unique($goodsids);
            }
        }else{
            $goodsids[] = $goods;
        }

        $where = 'id in ('.implode(',',$goodsids).')';
        $this->redirect("goods_list",false,array('searchwhere'=>$where,'search'=>$goods));
    }

    function save_goods_mobile()
    {

        //todo
        $id = intval(Req::post('goodsid'));
        $goodsname = trim(Req::post('goodsname'));
        $isonline = intval(Req::post('isonline'));

        if(empty($id)){
            echo json_encode(array('res'=>'success'));
            exit;
        }

        $data = array('is_online'=>$isonline);

        if(!empty($goodsname)){
            $data['name'] = $goodsname;
        }

        $model = new Model('goods');
        $model->data($data)->where("id = ".$id)->update();

        Log::op($this->manager['id'],"总店后台(手机端)修改商品","管理员[".$this->manager['name']."]:修改了商品 ".$goodsname);

        $params = array();
        $params['id'] = $id;
        $params['name'] = $goodsname;
        $params['is_online'] = $isonline;
        $params['syncdata_type'] = 'goods';
        syncGoods::getInstance()->setParams($params,'update')->sync();

        echo json_encode(array('res'=>'success'));
    }

    //商品导出
    public function goods_export()
    {
        $id = Req::args("id");
        $model = new Model('export_queue');

        if(is_array($id)){
            $ids = $id;
        }else if(is_numeric($id)){
            $ids = array($id);
        }else{
            $this->msg = array("warning","操作失败！");
            $this->redirect("goods_list",false);
            exit;
        }

        $data = array(
            'content'=>serialize($ids),
            'export_type'=>'goods',
            'export_name'=>'export-goods-'.time(),
            'create_time'=>time(),
            'status'=>'ready',
        );

        $model->data($data)->insert();

        $msg = array('success',"已成功加入导出队列 请去“系统设置”->“导出队列” 下载");
        $this->redirect("goods_list",false,array('msg'=> $msg));
    }

    //商品导入
    public function goods_import()
    {
        if(isset($_FILES["importfile"])){

           $ret = $this->goods_import_file();

           echo json_encode($ret);
           exit;

        }else{
            $this->layout = 'blank';

            $this->redirect();
        }

    }

    private function goods_import_file()
    {
        $output_dir = APP_ROOT.'data'.DIRECTORY_SEPARATOR.'import'.DIRECTORY_SEPARATOR.'goods'.DIRECTORY_SEPARATOR;
        if(isset($_FILES["importfile"]))
        {
            $ret = array();

//	This is for custom errors;
            /*	$custom_error= array();
                $custom_error['jquery-upload-file-error']="File already exists";
                echo json_encode($custom_error);
                die();
            */
//            $error =$_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData()
            if(!is_array($_FILES["importfile"]["name"])) //single file
            {

                $oldfilename = $_FILES["importfile"]["name"];

                $ext = pathinfo($oldfilename);

                $fileName = md5($oldfilename.time());

                move_uploaded_file($_FILES["importfile"]["tmp_name"],$output_dir.$fileName.'.'.$ext['extension']);

                $model = new Model('import_queue');

                $data = array(
                    'import_type'=>'goods',
                    'origin_name'=>$oldfilename,
                    'import_name'=>$fileName,
                    'create_time'=>time(),
                    'status'=>'ready',
                );

                $model->data($data)->insert();

                $ret[]= $oldfilename.'  上传成功';
            }
            //else  //Multiple files, file[]
            //{
//                $fileCount = count($_FILES["importfile"]["name"]);
//                for($i=0; $i < $fileCount; $i++)
//                {
//                    $fileName = $_FILES["importfile"]["name"][$i];
//                    move_uploaded_file($_FILES["importfile"]["tmp_name"][$i],$output_dir.$fileName);
//                    $ret[]= $fileName;
//                }

           // }
            return $ret;

        }
    }

}
