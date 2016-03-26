<?php
class billJob
{
	private static $instance = null;

	protected $params = array();

	protected $return_msg = array(
			  'res'=>'fail',
			  'data'=>array(),
			  'reason'=>'',
	);

	public static function getInstance()
	{
		if(null === self::$instance){
			self::$instance = new static();
		}

		return self::$instance;
	}

    public function __construct()
    {

    }

	public function run($params = array())
	{

        $params['content'] = unserialize(base64_decode($params['content']));

        $this->params = $params;

		return $this->_main();
	}
	
	private function _main()
	{
		switch($this->params['sync_direct']){
			case 'headtobranch':
			    return $this->hbDeal();
			break;
			case 'branchtohead':
				return $this->bhDeal();
			break;
		}
	}
}