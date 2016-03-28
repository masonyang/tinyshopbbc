<?php
class Config
{
	private static $system = array();
	private static $fileName = '';
	private static $change_flag = false;

	private static $obj = array();
	
	private function __construct(){}
	private function __clone(){}

	public static function getInstance($file = 'system')
	{
        if($file == 'config' || $file == 'mapper'){
            $serverName = Tiny::getServerName();

            $env = Tiny::getEnv($serverName['domain']);

            $file = $file.'_'.$env;
        }

		$md5file = md5($file);
		self::$fileName = APP_CODE_ROOT.'config/'.$file.'.php';
		self::$system = require(self::$fileName);
		if(!is_array(self::$system)) self::$system = array();
		self::$obj[$md5file] = new self();
		return self::$obj[$md5file];
	}
    
    public function get($key)
    {
        if(isset(self::$system[$key])) return self::$system[$key];
        else return null;
    }
    
    public function getAll()
    {
    	return self::$system;
    }

    public function set($key,$value)
    {
		if(!isset(self::$system[$key]) || self::$system[$key] != $value)
		{
			self::$change_flag = true;
			self::$system[$key] = $value;
		}
		
    }

	public function del($key)
	{
		if(isset(self::$system[$key])) unset(self::$system[$key]);
	}
    public function __destruct()
    {
		if(self::$change_flag)File::putContents(self::$fileName,'<?php return '.var_export(self::$system,true).';'); 
    }
}

