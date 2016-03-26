<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 16/3/16
 * Time: 下午12:35
 * 分销商店铺 数据同步到 总店
 */
class branchToHeadJob
{
    private $headMasterModel = null;

    private static $instance = null;

    private $db = null;

    private $limit = 10;

    private $table_name = 'sync_queue';

    private $master = 'master';

    private $queue_info = array();

    private $worker_msg = array();

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

        $this->db = 'zd';
    }

    public function getDistributor()
    {
        $headMasterModel = new Model('distributor','zd','master');
        $disInfos = $headMasterModel->where('disabled=0')->findAll();

        if(!$disInfos){
            return false;
        }

        $distrInfo = array();

        foreach ($disInfos as $disinfo) {
            $distrInfo[] = $disinfo['site_url'];
        }

        return $distrInfo;
    }

    public function getQueueInfo()
    {
        $this->queueModel = new Model($this->table_name,$this->db,$this->master);
        $this->queue_info = $this->queueModel->where("sync_direct='branchtohead' and action_type='normal' and status='ready'")->limit($this->limit)->findAll();
    }

    public function updateQueue($id,$status='fail')
    {
        if(!$id){
            return false;
        }
        
        $queueModel = new Model($this->table_name,$this->db,$this->master);
        $queueModel->data(array('status'=>$status))->where('id='.$id)->update();
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

    protected function workerDispatch()
    {
        if(!$this->queue_info){
            return false;
        }

        $msg = array();

        foreach($this->queue_info as $info){
            $synctype = $this->syncMapper[$info['sync_type']];
            $result = $synctype::getInstance()->run($info);
            if($result['res'] == 'success'){
                $this->succ_id[] = $info['id'];
            }

            $msg[] = $result;
        }
        
        $this->worker_msg['branchtohead'] = $msg;
    }

    protected function dealMsg()
    {
        if($this->succ_id){
            $queueModel = new Model($this->table_name,$this->db,$this->master);
            $queueModel->data(array('status'=>'success','modifytime'=>time()))->where('id in ('.implode(',',$this->succ_id).')')->update();
        }
        
    }

    public function deal($op_type,$params,$where,$table_name)
    {
        switch ($op_type) {
            case 'add':
                $this->add($params,$table_name);
                break;
            case 'update':
                $this->update($params,$where,$table_name);
                break;
            case 'del':
                $this->del($where,$table_name);
                break;
            default:
                # code...
                break;
        }
    }

    protected function update($data = array(),$where = array(),$table_name = '')
    {
        if(empty($data) || empty($where) || $table_name == ''){
            return false;
        }
        $headMasterModel = new Model($table_name,'zd','master');
        return $headMasterModel->data($data)->where($where)->update();
    }

    protected function del($where = array(),$table_name = '')
    {
        if(empty($where) || $table_name == ''){
            return false;
        }

        $headMasterModel = new Model($table_name,'zd','master');
        return $headMasterModel->where($where)->delete();
    }
   
    protected function add($data = array(),$table_name)
    {
        if(empty($data) || $table_name == ''){
            return false;
        }

        $headMasterModel = new Model($table_name,'zd','master');
        return $headMasterModel->data($data)->add();
    }

}

#dispatcher.php
#    branchtohead / headtobranch 