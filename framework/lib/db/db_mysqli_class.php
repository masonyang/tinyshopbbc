<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */

/**
 * 用单列模式对mysql数据库的操作类
 *
 * @author Tiny
 * @class DBMysql
 */
class DBMysqli
{
    private static $dbo = array();
    private static $conn = array();
    private static $dbinfo = array();
    private static $md5key = null;
    private static $tablePre = null;

    private function __construct(){}
    private function __clone(){}
    /**
     * 构造
     * @access public
     * @return mixed
     */
    public function __destruct()
    {
        mysqli_close(self::$conn[self::$md5key]);
    }
    /**
     * 取得实例
     *
     * @access public
     * @param mixed $dbinfo
     * @return mixed
     */
    public static function getInstance($dbinfo,$issalve = 'salve')
    {
        self::$tablePre = $dbinfo['tablePre'];
        self::md5key($dbinfo,$issalve);
        self::$dbinfo[self::$md5key] = $dbinfo[$issalve];

        if(null === self::$dbo[self::$md5key]){
            self::$conn[self::$md5key]=mysqli_connect($dbinfo[$issalve]['host'],$dbinfo[$issalve]['user'],$dbinfo[$issalve]['password']);

            if(self::$conn[self::$md5key]===false){
                trigger_error('<span style="color:red;border:red 1px solid;padding:0.5em;">无法连接数据库，请检查数据库连接参数！</span>', E_USER_ERROR);
                exit;
            }
            $charset = isset($dbinfo[$issalve]['charset'])?$dbinfo[$issalve]['charset']:'utf8';
            mysqli_query(self::$conn[self::$md5key],"set names '$charset'");

            self::$dbo[self::$md5key]=new self();
        }
        return self::$dbo[self::$md5key];
    }

    public function selectDb($dbname = null)
    {
        mysqli_select_db(self::$conn[self::$md5key],$dbname);
        return self::$dbo[self::$md5key];
    }

    protected static function md5key($dbinfo = array(),$issalve = 'salve')
    {
        if($issalve == 'salve'){
            $salve = 'salve';
        }else{
            $salve = 'master';
        }
        self::$md5key = md5($dbinfo['domain'].'-'.$salve);
    }

    /**
     * 检测是否存在
     *
     * @access public
     * @param mixed $name
     * @return mixed
     */
    public function exist($name)
    {
        $table = $this->doSql("SHOW TABLES LIKE '".$name."'");
        if($table) return mysqli_num_rows($table)==1?true:false;
        else return false;
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
        if(DEBUG){
            $doSqlBeforTime = microtime(true);
            $result=mysqli_query(self::$conn[self::$md5key],$sql);
            $useTime = microtime(true) - $doSqlBeforTime;
            Tiny::setSqlLog($sql,$useTime);
        }else {
            $result=mysqli_query(self::$conn[self::$md5key],$sql);
        }
        //查询不成功时返回空数组
        $rows=array();
        //分析出读写操作
        if(preg_match("/^(select|show)(.*)/i",$sql)==0){

            if($result){
                if(stripos($sql,'insert')!==false){
                    return $this->lastId();
                }else if(stripos($sql,'update')!==false){
                    return  mysqli_affected_rows(self::$conn[self::$md5key]);
                }
                return $result;
            }
            return false;
        }else{
            if($result){
                while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) $rows[]=$row;
                mysqli_free_result($result);
                return $rows;
            }else if(DEBUG){

                throw new Exception("SQLError:{$sql} ,".mysqli_error(self::$conn[self::$md5key])."", E_USER_ERROR);
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
        return ($id = mysqli_insert_id(self::$conn[self::$md5key])) >= 0 ? $id : @mysqli_result(mysqli_query("SELECT last_insert_id()"), 0);
    }
    /**
     * 取得表信息
     *
     * @access public
     * @param mixed $table
     * @return mixed
     */
    public function getTableInfo($table)
    {
        $table = explode(' ', $table);
        $table = $table[0];
        $sql="show fields from `{$table}`";
        $result = $this->doSql($sql);
        if(!$result) return false;
        $pri = '';
        $fields = array();
        foreach ($result as $row) {
            if($row['Key']=='PRI')  $pri= $row['Field'];
            $fields[$row['Field']] = $row['Type'];
        }
        return array('primary_key'=>$pri,'fields'=>$fields);
    }
}
