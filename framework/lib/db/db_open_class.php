<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 13/2/16
 * Time: 下午6:41
 */
class DbOpenSql
{
    private $config = array('host'=>'localhost','user'=>'','password'=>'');

    private static $instance = null;

    private $conn = null;

    public static function getInstance()
    {
        if(null === self::$instance){
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function checkConnect($config=array()){
        $config = array_merge($this->config,$config);
        $conn = @mysql_connect($config['host'],$config['user'],$config['password']);
        return $conn;
    }

    public function createDb($db_name)
    {
        mysql_query("CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8;");
        mysql_query("set names 'utf8'");
        mysql_select_db($db_name);
    }

    public function close()
    {
        mysqli_close($this->conn);
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
    function installSql($sqls, $per = '')
    {
        if(is_array($sqls))
        {
            foreach($sqls as $sql)
            {
                //$sql= preg_replace("/(create|drop|insert)([^`]+`)([a-zA-Z]*_)?(\w+)(`.*)/i","$1$2{$per}$4$5",$sql);
                if(preg_match_all("/(create|drop|insert)([^`]+`)(Tiny_)?(\w+)(`.*)/i",$sql,$out)){
                    $sql = $out[1][0].$out[2][0].$per.$out[4][0].$out[5][0];
                    //$op = strtolower($out[1][0]);
                    mysql_query($sql);
                }

            }

            ob_flush();
            flush();

        }
        return true;
    }
}