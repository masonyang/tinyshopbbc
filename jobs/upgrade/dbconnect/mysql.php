<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 18/4/17
 * Time: 下午12:15
 */
class upgrade_dbconnect_mysqlJob
{

    private static $conn = null;

    private static $tablePre = null;

    private static $dbo = null;

    private static function getConfig($db)
    {
        $mapper = Config::getInstance('mapper')->get($db);
        $dbinfo = $mapper['db'];

        return $dbinfo;
    }

    public static function getInstance($db,&$msg = '')
    {
        $dbinfo = self::getConfig($db);

        self::$tablePre = $dbinfo['tablePre'];

        self::$conn=mysql_connect($dbinfo['master']['host'],$dbinfo['master']['user'],$dbinfo['master']['password']);

        if(self::$conn===false)
        {
            $msg = '无法连接数据库，请检查数据库连接参数！';

            return false;
        }

        $charset = isset($dbinfo['master']['charset'])?$dbinfo['master']['charset']:'utf8';
        mysql_query("set names '$charset'");

        mysql_select_db($dbinfo['master']['name'],self::$conn);

        self::$dbo = new self();

        return self::$dbo;
    }

    /**
     * 执行SQL
     * 
     * @access public
     * @param mixed $sql
     * @return mixed
     */
    public function doSql($sql)
    {
        //更好的处理表前缀
        //$mainStr = ltrim(self::$dbinfo['tablePre'],'tiny_');
        $sql = preg_replace('/(\s+|,|`)(tiny_)+/i','$1'.self::$tablePre,$sql);
        $sql=trim($sql);

        $result=mysql_query($sql,self::$conn);

        //查询不成功时返回空数组
        $rows=array();
        //分析出读写操作
        if(preg_match("/^(select|show|describe)(.*)/i",$sql)==0)
        {
            
            if($result)
            {
                if(stripos($sql,'insert')!==false)
                {
                    return $this->lastId();
                }else if(stripos($sql,'update')!==false){
                    return  mysql_affected_rows();
                }
                return $result;
            }
            return false;
        }
        else
        {

            if(is_resource($result))
            {
                while($row = mysql_fetch_array($result,MYSQL_ASSOC)) $rows[]=$row;
                mysql_free_result($result);
                return $rows;
            }
            return array();
        }
    }

    /**
     * 最后一条ID
     * 
     * @access public
     * @return mixed
     */
    public function lastId()
    {
        return ($id = mysql_insert_id(self::$conn)) >= 0 ? $id : @mysql_result(mysql_query("SELECT last_insert_id()"), 0);
    }

    public function fieldExists($tablename, $fieldname) {
        $sql = "DESCRIBE " . $this->tablename($tablename) . " `{$fieldname}`";
        $isexists = $this->doSql($sql);
        return !empty($isexists) ? true : false;
    }

    public function indexExists($tablename, $indexname) {
        if (!empty($indexname)) {
            $indexs = $this->doSql("SHOW INDEX FROM " . $this->tablename($table));
            if (!empty($indexs) && is_array($indexs)) {
                foreach ($indexs as $row) {
                    if ($row['Key_name'] == $indexname) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function tableExists($table) {
        if(!empty($table)) {
            $data = $this->doSql("SHOW TABLES LIKE '".$this->tablename($table)."'");
            if(!empty($data)) {
                $data = array_values($data);
                $tablename = $this->tablename($table);
                if(in_array($tablename, $data)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function tablename($table)
    {
        return self::$tablePre . $table;
    }

    public function close()
    {
        mysql_close(self::$conn);
    }

}