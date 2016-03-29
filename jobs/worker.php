<?php
class workerJob extends syncdataJob
{
	private $syncDirect = null;

	private $db = null;

	private $limit = 10;

	protected function __before()
	{
		parent::__before();

		$this->syncDirect = $this->getOption('syncdirect');
		
		$this->limit = $this->getOption('limit');

		$this->db = $this->getOption('db');

	}

	protected function __run()
	{
		switch($this->syncDirect){
			case 'branchtohead':
				$this->outMsg = branchToHeadJob::getInstance()->dispatch($this->db,$this->limit);
			break;
			case 'headtobranch':
				$this->outMsg = headToBranchJob::getInstance()->dispatch($this->db,$this->limit);
			break;
		}
		
	}

}