<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 28/2/16
 * Time: 下午2:36
 * 分销商信息同步
 */
class syncdata_distributorInfoJob extends billJob
{

	//分店信息同步到总店
	protected function bhDeal()
	{
		$op_type = $this->params['action'];//操作类型 增删改查
		
		$params = $this->params['content'];//更新内容

        if($params['syncdata_type'] != 'distributor'){

            $this->return_msg['reason'] = '同步数据类型不对';

            return $this->return_msg;
        }

		$where = 'distributor_id='.$params['distributor_id'];
		
		unset($params['distributor_id']);

		branchToHeadJob::getInstance()->deal($op_type,$params,$where,'distributor');

        $this->return_msg['res'] = 'success';

        return $this->return_msg;
	}

	//总店信息同步到分店
    protected function hbDeal()
	{
        $op_type = $this->params['action'];

        $params = $this->params['content'];

        if($params['syncdata_type'] != 'distributor'){

            $this->return_msg['reason'] = '同步数据类型不对';

            return $this->return_msg;
        }

        $where = 'distributor_id='.$params['distributor_id'];

        $db = $this->params['domain'];

        unset($params['distributor_id']);

        $params['is_lock'] = $params['disabled'];

        headToBranchJob::getInstance()->deal($op_type,$params,$where,'manager',$db);

        $this->return_msg['res'] = 'success';

        return $this->return_msg;

	}

} 