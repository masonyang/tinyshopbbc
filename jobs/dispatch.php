<?php
class dispatchJob extends syncdataJob
{
	private $syncDirect = null;

	protected function __before()
	{
		parent::__before();
		$this->syncDirect = $this->getOption('syncdirect');
	}

	protected function __run()
	{
		switch($this->syncDirect){
			case 'branchtohead':
				$this->getbhQueueList();
			break;
			case 'headtobranch':
				$this->gethbQueueList();
			break;
		}
	}

	private function getbhQueueList()
	{
		$distrInfo = branchToHeadJob::getInstance()->getDistributor();
		if(!$distrInfo){
			$this->outMsg = 'no data';
            return false;
		}	
		$this->outMsg = implode(' ',$distrInfo);
	}

	private function gethbQueueList()
	{

		$lists = headToBranchJob::getInstance()->getList();

		if(!$lists){
			$this->outMsg = 'no data';
            return false;
		}

        $disIds = array();

		foreach ($lists as $list) {
			$disIds[] = $list['site_url'];
		}

		$this->outMsg = implode(' ', $disIds);
	}

}