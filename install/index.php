<?php
error_reporting(0);
header("Content-type: text/html; charset=UTF-8");
define("APP_ROOT", dirname(dirname(__file__)).DIRECTORY_SEPARATOR);

if(file_exists('install.lock'))
{
	exit("安装锁定，已经安装过了，如果您确定要重新安装，请到服务器上删除
./install/install.lock");
}
function get($k) {
	//$t = strtoupper($t);
	isset($_POST[$k]) ? $var = &$_POST : $var = &$_GET;
	return isset($var[$k]) ? $var[$k] : null;
}
function random($len=6,$type='mix')
{
    $len = intval($len);
	if($len >90) $len = 90;
	$str = '';
    switch ($type) {
        case 'int':
            $templet = '012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789';
            break;
        case 'lowchar':
            $templet = 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijkl';
            break;
        case 'upchar':
            $templet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKL';
            break;
        case 'char':
            $templet = 'abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyzamwz0379bhklqdklg482156smyew';
            break;
        default:
            $templet = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ~!@#$%^&*()_+-=[]{}:";<>,.?|';
            break;
    }
    $start = mt_rand(1, (90-$len));
    $string = str_shuffle($templet);
    return substr($string,$start,$len);
}
$action = isset($_GET['action'])?$_GET['action']:'install';

if($action=='install') include("include/install.php");
else if($action=="checkConnect"){
	include("include/dbsql.php");
	$dbsql = new DbSql();
	$db['host'] = get('db_address');
	$db['user'] = get('db_user');
	$db['password'] = get('db_pwd');
	$flag = $dbsql->checkConnect($db);
	if($flag) echo("success");
	else echo("fail");
}
elseif ($action == 'installSql') {
	include("include/dbsql.php");
	$dbsql = new DbSql();
	$db['host'] = get('db_address');
	$db['user'] = get('db_user');
	$db['password'] = get('db_pwd');
	$db_name = get('db_name');
	$db_pre = get('db_pre');

	$admin_user = get("admin_user");
	$admin_pwd = get("admin_pwd");
	$admin_repwd = get("admin_repwd");


	if($db['host']!=null && $db_name!=null  && $db['user']!=null && $admin_user!=null && $admin_pwd!=null && $admin_pwd==$admin_repwd){
		//安装Sql
		$flag = $dbsql->checkConnect($db);
		if($flag){

			//apache_setenv('no-gzip', 1);
			@ini_set('zlib.output_compression', 0);
			@ini_set('implicit_flush', 1);
			@ini_set('output_buffering',0);
			for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
			ob_implicit_flush(1);
			echo("<script>parent.document.getElementById('install_status').style.display='';</script>\r\n");
			ob_flush();
			flush();
			usleep(1000000);

			$sql_file = dirname(__file__)."/data/install.sql";
			$sqls = $dbsql->parseSql($sql_file);
			mysql_query("CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8;");
			mysql_query("set names 'utf8'");
			mysql_select_db($db_name);

			$validcode = rundStr();
			$key = md5($validcode);
			$password = md5(substr($key,0,16).$admin_pwd.substr($key,16,16));

			$insert_admin = "insert  into `manager`(`name`,`roles`,`password`,`validcode`,`is_lock`) values ('{$admin_user}','administrator','{$password}','{$validcode}',0)";
			$insert_payment="INSERT INTO `tiny_payment` (`id`,`plugin_id`,`pay_name`,`config`,`client_type`,`description`,`note`,`pay_fee`,`fee_type`,`sort`,`status`) values (1,'1','预存款支付','a:2:{s:10:\"partner_id\";s:32:\"".random(32,'char')."\";s:11:\"partner_key\";s:32:\"".random(32,'char')."\";}',2,'预存款是客户在您网站上的虚拟资金帐户。','',0.00,1,1,0)";
			$sqls[]=$insert_admin;
			$sqls[]=$insert_payment;
			if($dbsql->installSql($sqls,$db_pre)){
				//配制文件
				$config_ini_file = dirname(__file__)."/../protected/config/config.ini.php";
				$config_file = dirname(__file__)."/../protected/config/config.php";
				$config = include("$config_ini_file");
				$config['db'] = array('type'=>'mysql', 'tablePre'=>$db_pre, 'host'=>$db['host'], 'user'=>$db['user'], 'password'=>$db['password'], 'name'=>$db_name);
				$str = var_export($config,true);
				file_put_contents($config_file, '<?php return '.$str.';?>');
				//重写首页
				$index_file = APP_ROOT.'./index.php';
				$content = '<?php
//应用目录，为了程序的更好应用与开发。
define("APP_ROOT",dirname(__file__).DIRECTORY_SEPARATOR);
//引入框架文件

include("framework/tiny.php");

//加载配制文件
$configPath = "protected/config/config.php";
$config = is_file($configPath)?include($configPath):null;
//运行应用程序

Tiny::createWebApp($config)->run();
?>';
				file_put_contents($index_file, $content);
			}
		}else{
			echo("<script>parent.document.getElementById('error_div').style.display='';parent.document.getElementById('error_info').innerHTML=\"数据库连接失败，请核实地址、账户与密码信息！\";</script>");
		}
	}
	else{
		echo("<script>parent.document.getElementById('error_div').style.display='';parent.document.getElementById('error_info').innerHTML=\"填写错误，请认真正确填写每一项信息，才能继续安装！\";</script>");
	}
}
function rundStr($length=8)
{
	$chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q','r','s','t','u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O', 'P','Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!','@','#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '[', ']', '{', '}', '<', '>', '~', '`', '+', '=', ',', '.', ';', ':', '/', '?', '|');
	$keys = array_rand($chars, $length);
	$password = '';
	for($i = 0; $i < $length; $i++)
	{
		$password .= $chars[$keys[$i]];
	}
	return $password;
}
