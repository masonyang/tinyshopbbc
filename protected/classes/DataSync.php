<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 27/2/16
 * Time: 下午3:37
 */
//数据同步类
/*
 * 访问方式:
 * $syncObj = syncDistributorInfo::getInstance();
 * $syncObj->setParams(array(),'add');
 * $syncObj->sync();
 *
 *
 *
 *
 */
class DataSync
{

    private static $instance = null;

    protected $syncDirect = '';//同步方向

    protected $vailRule = array();//入参验证规则设置

    protected $params = array();

    protected $domain = '';

    protected $syncMapper = array(
        'payment'=>'syncPayment',
        'brand'=>'syncBrand',
        'tags'=>'syncTag',
    );

    protected $action_type = 'normal';//动作类型 normal:普通类型,openshop:新开店铺

    protected $syncType = '';

    protected $syncAction = '';//同步操作类型 增add 删del 改update

    public static function getInstance()
    {
        if(null === self::$instance){
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function __construct()
    {
        $this->syncDirect();
    }

    //设置接手参数
    public function setParams($params = array(),$action = 'add',$action_type = 'normal')
    {
        $this->syncAction = $action;

        $this->action_type = $action_type;

        $this->params = $params;
        return $this;
    }


    public function sync()
    {
        $data = array();
        //写入同步队列
        $tablename = 'sync_queue';
        $data['sync_type'] = $this->syncType;//goods,category,brand...
        $data['status'] = 'ready'; //ready,syncing,success,fail
        $data['action'] = $this->syncAction;//操作类型 增删改查
        $data['sync_direct'] = $this->syncDirect;
        $data['distributor_id'] = ($this->params['distributor_id']) ? $this->params['distributor_id'] :0;
        $data['level'] = 0;//0:普通 1:中 2:高
        $data['action_type'] = $this->action_type;
        $data['content'] = serialize($this->params);
        $model = new Model($tablename,$this->domain,'master');
        $model->data($data)->add();
    }

    protected function syncDirect()
    {
        $serverName = Tiny::getServerName();
        $mapper = Config::getInstance('mapper')->get($serverName['top']);

        $this->domain = $serverName['top'];
        $this->syncDirect = ($mapper['menu'] == 'headstore') ? 'headtobranch' : 'branchtohead';
    }

    //验证传入的参数
    protected function vaildParams()
    {
        if($this->vailRule){
            foreach($this->vailRule as $k=>$v){
                if(empty($this->params[$k])){
                    unset($this->params[$k]);
                }
            }
        }

    }

    //数据同步service机制
    public function service($type = '',$action = '',$data = array())
    {
        $className = $this->syncMapper[$type] ? $this->syncMapper[$type] : false;

        if($className){

            $syncObj = $className::getInstance();

            $syncObj->setParams($data,$action)->sync();

            return true;
        }

        return false;
    }
} 