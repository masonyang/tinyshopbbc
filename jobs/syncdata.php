<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 28/2/16
 * Time: 下午2:39
 */

class syncdataJob
{

	protected $options = array();

	protected $outMsg = array();

	public function run()
	{
		$this->__init();//初始化

		$this->__before();

		$this->__run();

		$this->__after();

	}

	protected function __init()
	{
		//接收外部参数
		$this->initOption();
		//加载扩展类
		$this->loadExClass();
	}

	protected function __before()
	{
		//加载model 并初始化
	}

	protected function __run()
	{
		//执行主程序
	}

	protected function __after()
	{
		//处理额外信息
	}

	public function outMsg()
	{
		$logfile = $this->getOption('logfile');
		if(isset($logfile)){
			if(is_array($this->outMsg)){
				$data = var_export($this->outMsg,1);
			}else{
				$data = $this->outMsg;
			}
			file_put_contents($logfile, $data);
		}else{
            if(is_array($this->outMsg)){
                print_r($this->outMsg);
            }else{
                echo $this->outMsg;
            }
		}
		
	}

	protected function initOption()
	{
		global $argv;

        array_shift($argv); // $argv[0] Script Name
        array_shift($argv); // $argv[1] Job Name

        foreach ($argv as $arg) {
            if (strpos($arg, '--') !== 0) {
                continue;
            }

            $pair = str_replace('--', '', $arg);

            if (strpos($pair, '=') !== false) {
                list($key, $value) = explode('=', $pair);
            } else {
                $key = $pair;
                $value = false;
            }

            $key = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $key));

            $this->options[$key] = $value;
        }

        if ($this->issetOption('help')) {
            echo $this->usage(), PHP_EOL;
            die;
        }
	}

	protected function loadExClass()
	{
		if(isset($config['classes']))Tiny::setClasses($config['classes']);
			else Tiny::setClasses('classes.*');
	}

	protected function issetOption($name)
	{
		return isset($this->options[$name]);
	}

    protected function getOptions()
    {
        return $this->options;
    }

    protected function getOption($name, $default = null)
    {
        if (isset($this->options[$name])) {
            $value = $this->options[$name];
        } else {
            $value = $default;
        }

        return $value;
    }

	protected function usage()
	{
		$job  = __CLASS__;
		$path = dirname(__FILE__);
		$usage = <<< USAGE
Usage: php job.php $path $job [--foo=bar]

Parameters:

    --foo       Example of command options.
    --help      Show this help message.

USAGE;

        return $usage;
	}

} 