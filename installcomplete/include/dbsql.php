<?php
class DbSql
{
	private $config = array('host'=>'localhost','user'=>'','password'=>'');

	public function checkConnect($config=array()){
		$config = array_merge($this->config,$config);
		$conn = @mysql_connect($config['host'],$config['user'],$config['password']);
		return $conn;
	}

	public function parseSql($filename){
		$lines=file($filename);
		$lines[0]=str_replace(chr(239).chr(187).chr(191),"",$lines[0]);//去除BOM头
		$flage=true;
		$sqls=array();
		$sql="";
		foreach($lines as $line)
		{
			$line=trim($line);
			$char=substr($line,0,1);
			if($char!='#' && strlen($line)>0)
			{
				$prefix=substr($line,0,2);
				switch($prefix)
				{
					case '/*':
					{
					$flage=(substr($line,-3)=='*/;'||substr($line,-2)=='*/')?true:false;
					break 1;
					}
					case '--': break 1;
					default : 
					{				
						if($flage)
						{
							$sql.=$line;
							if(substr($line,-1)==";")
							{
								$sqls[]=$sql;
								$sql="";
							}
						}
						if(!$flage)$flage=(substr($line,-3)=='*/;'||substr($line,-2)=='*/')?true:false;
					}
				}
			}
		}
		return $sqls;
	}

	//安装SQL函数
	function installSql($sqls, $per = '', $force=false)
	{
		$flag=true;
		if(is_array($sqls))
		{
			$total = count($sqls);
			$num = 0;
			foreach($sqls as $sql)
			{
				//$sql= preg_replace("/(create|drop|insert)([^`]+`)([a-zA-Z]*_)?(\w+)(`.*)/i","$1$2{$per}$4$5",$sql);
				if(preg_match_all("/(create|drop|insert)([^`]+`)(Tiny_)?(\w+)(`.*)/i",$sql,$out)){
					$sql = $out[1][0].$out[2][0].$per.$out[4][0].$out[5][0];
					$op = strtolower($out[1][0]);
					$str = '';

					if(mysql_query($sql)){
						if($op=='create')$str= "<b style='color:#009900'> 创建表 ".($per.$out[4][0])."…………成功</b>";
						//else if($op=='drop')echo "<div style='color:#009900'> 删除表 ".($per.$out[4][0])."…………成功</div>";
						else if($op=='insert')$str= "<b style='color:#009900'> 写入表 ".($per.$out[4][0])."…………成功</b>";
					}
					else 
					{
						if($op=='create')$str= "<b style='color:#990000'> 创建表 ".($per.$out[4][0])."…………失败</b>";
						//else if($op=='drop')echo "<div style='color:#990000'> 删除表 ".($per.$out[4][0])."…………失败</div>";
						else if($op=='insert')$str= "<b style='color:#990000'> 写入表 ".($per.$out[4][0])."…………失败</b>";
						$flag=false;
					}
					
					if($flag) $num++;
					if($str!=''){
						echo("<script>parent.document.getElementById('install_status_info').innerHTML=\"{$str}\";parent.document.getElementById('install_bar').style.width='".(($num/$total)*100)."%';</script>");
						ob_flush();
						flush();
					}
					if($flag==false){
						echo("<script>parent.document.getElementById('error_div').style.display='';parent.document.getElementById('error_info').innerHTML=\"数据库安装过程中发生以下错误:{$str}\";</script>");
						ob_flush();
						flush();
						break;
					}
				}
				
			}
			if($flag){
				echo("<script>parent.location='index.php?step=4';</script>");
				ob_flush();
				flush();
			}
		}
		return $flag;
	}
}