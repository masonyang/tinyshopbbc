<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 16/3/16
 * Time: 下午12:35
 * 总店 数据同步到 分销商店铺
 */
class headToBranchJob
{
    private $headMasterModel = null;

    private static $instance = null;

	private $worker_msg = array();

	private $db = null;

	private $limit = 10;

    private $succ_id = array();

    private $syncMapper = array(
            'goods'=>'syncdata_goodsJob',
            'brand'=>'syncdata_brandJob',
            'category'=>'syncdata_categoryJob',
            'distrInfo'=>'syncdata_distributorInfoJob',
            'goods_type'=>'syncdata_goodstypeJob',
            'payment'=>'syncdata_paymentJob',
            'products'=>'syncdata_productsJob',
            'spec'=>'syncdata_goodsspecJob',
            'tag'=>'syncdata_tagJob'
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
        //实例化 总店分销商表的 model
        $this->headMasterModel = new Model('sync_queue','zd','master');
    }

    public function getList()
    {
    	$distrModel = new Model('distributor','zd','master');
    	return $distrModel->findAll();
    }

    protected function getQueueInfo()
    {
    	$this->queueModel = new Model('sync_queue','zd','master');

    	$this->queue_info = $this->queueModel->where("sync_direct='headtobranch' and action_type='normal' and status='ready' and domain='".$this->db."'")->limit($this->limit)->findAll();
    }

    protected function workerDispatch()
    {
		if(!$this->queue_info){
    		return false;
    	}

    	$msg = array();

    	foreach ($this->queue_info as $info) {
    		$synctype = $this->syncMapper[$info['sync_type']];
	        $result = $synctype::getInstance()->run($info);
	        if($result['res'] == 'success'){
	            //更新总店队列表 对应队列的状态
	            $this->succ_id[] = $info['id'];
	        }
	        $msg[] = $result;
    	}

		$this->worker_msg['headtobranch'] = $msg;
    }

    protected function dealMsg()
    {

        if($this->succ_id){
            $queueModel = new Model('sync_queue','zd','master');
            $queueModel->data(array('status'=>'success','modifytime'=>time()))->where('id in ('.implode(',',$this->succ_id).')')->update();
        }
        
    }

    public function dispatch($db,$limit)
    {

    	$this->db = $db;

        $this->limit = $limit;

        $this->getQueueInfo();

        $this->workerDispatch();

        $this->dealMsg();

        return $this->worker_msg;

    }

    public function deal($op_type,$params,$where,$table_name,$db)
    {
        switch ($op_type) {
            case 'add':
                $this->add($params,$table_name,$db);
                break;
            case 'update':
                $this->update($params,$where,$table_name,$db);
                break;
            case 'del':
                $this->del($where,$table_name,$db);
                break;
            default:
                # code...
                break;
        }
    }

    protected function update($data = array(),$where = array(),$table_name = '',$db)
    {
        if(empty($data) || empty($where) || $table_name == ''){
            return false;
        }

        $headMasterModel = new Model($table_name,$db,'master');
        return $headMasterModel->data($data)->where($where)->update();
    }

    protected function del($where = array(),$table_name = '',$db)
    {
        if(empty($where) || $table_name == ''){
            return false;
        }

        $headMasterModel = new Model($table_name,$db,'master');
        return $headMasterModel->where($where)->delete();
    }
   
    protected function add($data = array(),$table_name = '',$db)
    {
        if(empty($data) || $table_name == ''){
            return false;
        }

        $headMasterModel = new Model($table_name,$db,'master');

        if(is_array($data)){
            return $headMasterModel->data($data)->add();
        }else{
            return $headMasterModel->query($data);
        }

    }

}

#dispatcher.php
#    branchtohead / headtobranch 