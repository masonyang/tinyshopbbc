<?php
#申请开通分销商网店流程
class OpenShop
{
	private static $instance = null;

    private $opendb = null;

    private $dbname = null;

    private $config = array();

    private $categorys = '';
	
	private $goodsType = array();//商品类型

	private $goodsBrand = array();//品牌

	private $goodsSpec = array();//商品规格

	private $typeids = array();

	private $brandids = array();

	private $goodsids = array();
	
	private $specs = array();//商品规格id存储
	
	private $specvalues = array();//规格值

	private $tagids = array();//标签

	private $distrInfo = array(
		'distributor_id'=>'distributor_id',//和总店注册的分销商关联的id  
		'catids'=>'catids',//授权分类
		'distributor_name'=>'name',//分销商名称
		'distributor_password'=>'password',//分销商密码
		'validcode'=>'validcode',//密码的校验码
		'register_time'=>'register_time',//注册时间
		'email'=>'email',//邮箱
		'province'=>'province',//省份
		'city'=>'city',//城市
		'county'=>'county',//县
		'addr'=>'addr',//地址
		'phone'=>'phone',//电话
		'site_name'=>'site_name',//站点名称
		'site_logo'=>'site_logo',//站点logo
		'site_icp'=>'site_icp',//站点备案
		'site_url'=>'site_url',//站点网址
		'site_ios_url'=>'site_ios_url',//ios下载地址
		'site_android_url'=>'site_android_url',//android下载地址
		'zip'=>'zip',//邮编
		'site_keyword'=>'site_keyword',//关键字
		'site_description'=>'site_description',//描述信息
		'deposit'=>'deposit',//预存款
		'roles'=>'roles',//administrator
	);
	
	private $distrMainInfo = array();

	private $result = array(
		'res'=>false,
		'msg'=>'未知错误!',
	);

	public static function getInstance()
	{
		if(null === self::$instance){
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected function __construct()
	{
		
	}
	
	//设置参数
	public function setParams($params = array())
	{
		$this->dbname = $params['site_url'];
        $this->categorys = $params['catids'];
        $this->config = array(
//            'host'=>'localhost:3306',
//            'user'=>'root',
//            'password'=>'Zhangweilong',
            'host'=>'114.55.11.208:3306',
            'user'=>'qqc',
            'password'=>'qqc-2016-mysql',
        );
		$this->distrMainInfo = $params;
	}

    //更新mapper配置文件
    public function updateMapperConfig()
    {

    }

	//分配db
	public function createDb()
	{
        $this->opendb = DbOpenSql::getInstance();
        $this->opendb->checkConnect($this->config);
        $this->opendb->createDb($this->dbname);
	}

	//创建数据表
	public function createTables()
	{
        $filename = APP_ROOT.'data/branchstore.sql';
        $sqls = $this->opendb->parseSql($filename);
        $this->opendb->installSql($sqls,'tiny_');
	}
	
	//同步分类所对应的商品、规格、类型、品牌
	public function syncGoods()
	{
        echo "<span style='text-align:center;font-size:24px;color:#FF0000;'>为了数据同步无误,请不要关闭该窗口</span>";
        echo "正在同步商品分类...<br>";
		$this->_syncCategory();//同步分类
        echo "商品分类同步完毕...<br>";
        echo "正在同步商品...<br>";
        $this->_syncGoods();//根据授权商品分类 去获取 对应的商品
        echo "商品同步完毕...<br>";
        echo "正在同步产品...<br>";
        $this->_syncProducts();//同步货品
        echo "产品同步完毕...<br>";
        echo "正在同步商品规格...<br>";
        $this->_syncGoodsSpec();//商品规格
        echo "商品规格同步完毕...<br>";
        echo "正在同步商品类型...<br>";
        $this->_syncGoodsType();//对应商品类型
        echo "商品类型同步完毕...<br>";
        echo "正在同步品牌...<br>";
        $this->_syncGoodsBrand();//品牌 信息
        echo "品牌同步完毕...<br>";
        echo "正在同步商品标签...<br>";
        $this->_syncTags();//商品标签
        echo "商品标签同步完毕...<br>";
        echo "正在同步支付方式...<br>";
        $this->_syncPayment();//支付方式
        echo "支付方式同步完毕...<br>";
        echo "<span style='text-align:center;font-size:24px;color:#008000;'>所有数据已完成同步!</span>";
	}

	//同步分类
	protected function _syncCategory()
	{
		$categoryModel = new Model('goods_category');
        $categorys = $categoryModel->where('id in ('.$this->categorys.')')->findAll();

        $syncCategoryModel = new Model('goods_category',$this->dbname,'master');
		if($categorys){
			foreach($categorys as $category){
				$syncCategoryModel->data($category)->add();
			}
		}
	}

	//同步商品数据
	protected function _syncGoods()
	{
		$goodsModel = new Model('goods');
		$goods = $goodsModel->where('category_id in ('.$this->categorys.')')->findAll();

		$syncGoodsModel = new Model('goods',$this->dbname,'master');
		if($goods){
			$this->specs = array();
			foreach($goods as $good){
				$this->typeids[$good['type_id']] = $good['type_id'];
				$this->brandids[$good['brand_id']] = $good['brand_id'];
				$this->goodsids[$good['id']] = $good['id'];
				$this->tagids[$good['tag_ids']] = $good['tag_ids'];
				$specs = unserialize($good['specs']);
				foreach($specs as $k=>$item){
					$this->specs[$k] = $k;
					$specvalues = implode(',',array_keys($item['value']));
					$md5 = md5($specvalues);
					$this->specvalues[$md5] = $specvalues;
				}

				$syncGoodsModel->data($good)->add();
			}
		}

		//tiny_goods
		//tiny_goods_attr
		//tiny_goods_category
		//tiny_goods_type
		//tiny_brand
		//tiny_spec_attr
		//tiny_products
		//tiny_goods_spec
		//tiny_spec_value
		//tiny_attr_value
//        truncate table `tiny_goods`;
//        truncate table `tiny_goods_attr`;
//        truncate table `tiny_goods_category`;
//        truncate table `tiny_goods_type`;
//        truncate table `tiny_brand`;
//        truncate table `tiny_spec_attr`;
//        truncate table `tiny_products`;
//        truncate table `tiny_goods_spec`;
//        truncate table `tiny_spec_value`;
//        truncate table `tiny_attr_value`;
//        truncate table `tiny_tags`;
	}
	
	//同步货品
	protected function _syncProducts()
	{
		$productsModel = new Model('products');
		$products = $productsModel->where('goods_id in ('.implode(',',$this->goodsids).')')->findAll();

		$syncProductModel = new Model('products',$this->dbname,'master');
		
		if($products){
			foreach($products as $product){
				$syncProductModel->data($product)->add();
			}
		}
	}

	//同步商品规格
	protected function _syncGoodsSpec()
	{
		
		$goodsSpecAttrModel = new Model('spec_attr');
		$goodsSpecAttrs = $goodsSpecAttrModel->where('goods_id in ('.implode(',',$this->goodsids).')')->findAll();

		$syncGoodsSpecAttrModel = new Model('spec_attr',$this->dbname,'master');
		if($goodsSpecAttrs){
			foreach($goodsSpecAttrs as $goodsSpecAttr){
				$syncGoodsSpecAttrModel->data($goodsSpecAttr)->add();
			}
		}
		
		$goodsSpecModel = new Model('goods_spec');
		$goodsSpecs = $goodsSpecModel->where('id in ('.implode(',',$this->specs).')')->findAll();

		$syncGoodsSpecModel = new Model('goods_spec',$this->dbname,'master');
		if($goodsSpecs){
			foreach($goodsSpecs as $goodsSpec){
				$syncGoodsSpecModel->data($goodsSpec)->add();
			}
		}

		$SpecValueModel = new Model('spec_value');
		$specValues = $SpecValueModel->where('id in ('.implode(',',$this->specvalues).')')->findAll();

		$syncSpecValueModel = new Model('spec_value',$this->dbname,'master');
		if($specValues){
			foreach($specValues as $specValue){
				$syncSpecValueModel->data($specValue)->add();
			}
		}

	}

	//同步商品类型
	protected function _syncGoodsType()
	{
		$goodsAttrModel = new Model('goods_attr');
		$goodsAttrs = $goodsAttrModel->where('type_id in ('.implode(',',$this->typeids).')')->findAll();

		$syncGoodsAttrModel = new Model('goods_attr',$this->dbname,'master');
		if($goodsAttrs){
			foreach($goodsAttrs as $goodsAttr){
				$this->attrvalues[$goodsAttr['id']] = $goodsAttr['id'];
				$syncGoodsAttrModel->data($goodsAttr)->add();
			}
		}

		$attrValueModel = new Model('attr_value');
		$attrValues = $attrValueModel->where('attr_id in ('.implode(',',$this->attrvalues).')')->findAll();

		$syncattrValueModel = new Model('attr_value',$this->dbname,'master');
		if($attrValues){
			foreach($attrValues as $attrValue){
				$syncattrValueModel->data($attrValue)->add();
			}
		}


		$goodsTypeModel = new Model('goods_type');
		$goodsTypes = $goodsTypeModel->where('id in ('.implode(',',$this->typeids).')')->findAll();

		$syncGoodsTypeModel = new Model('goods_type',$this->dbname,'master');
		if($goodsTypes){
			foreach($goodsTypes as $goodsType){
				$syncGoodsTypeModel->data($goodsType)->add();
			}
		}
		

	}

	//同步品牌
	protected function _syncGoodsBrand()
	{
		$brandModel = new Model('brand');
		$brands = $brandModel->where('id in ('.implode(',',$this->brandids).')')->findAll();

		$syncBrandModel = new Model('brand',$this->dbname,'master');
		if($brands){
			foreach($brands as $brand){
				$syncBrandModel->data($brand)->add();
			}
		}

	}

	//同步商品标签
	protected function _syncTags()
	{
		$tagsModel = new Model('tags');
		$tags = $tagsModel->where('id in ('.implode(',',$this->tagids).')')->findAll();

		$syncTagModel = new Model('tags',$this->dbname,'master');
		if($tags){
			foreach($tags as $tag){
				$syncTagModel->data($tag)->add();
			}
		}
	}

    //同步支付方式
    protected function _syncPayment()
    {
        $paymentModel = new Model('payment');
        $payments = $paymentModel->findAll();

        $syncPaymentModel = new Model('payment',$this->dbname,'master');
        if($payments){
            foreach($payments as $payment){
                $syncPaymentModel->data($payment)->add();
            }
        }

        $payPluginModel = new Model('pay_plugin');
        $payPlugins = $payPluginModel->findAll();

        $syncPayPluginModel = new Model('pay_plugin',$this->dbname,'master');
        if($payPlugins){
            foreach($payPlugins as $payPlugin){
                $syncPayPluginModel->data($payPlugin)->add();
            }
        }
    }

	//同步分销商基本信息
	public function syncDistributorInfo()
	{
		$distrMainInfo = array();
		$this->distrMainInfo['roles'] = 'administrator';
		foreach($this->distrInfo as $k=>$v){
			$distrMainInfo[$v] = $this->distrMainInfo[$k];
		}
		$managerModel = new Model('manager',$this->dbname,'master');
		$managerModel->data($distrMainInfo)->add();
	}

	public function done()
	{
        $this->opendb->close();

        $this->result['res'] = true;

		return $this->result;
	}
}