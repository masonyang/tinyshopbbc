<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 17/4/17
 * Time: 下午9:43
 */
class upgrade_baseJob
{

    public static function checkParams($version = null)
    {
        if($version == null){
            return false;
        }

        $upgradefilepath = APP_ROOT.'jobs/upgrade/file/';

        if(!file_exists($upgradefilepath.$version.'.php')){
            return false;
        }

        return true;
    }

    public static function upgrade($version = null,$db = null)
    {
        if(($version == null) || ($db == null)){
            return false;
        }

        $upgradefilepath = APP_ROOT.'jobs/upgrade/file/';

        list($upgradeschema,$upgradedata) = require($upgradefilepath.$version.'.php');

        if($db == 'zd'){
            $store = 'head';
        }else{
            $store = 'branch';
        }
        //todo 执行
        self::runSchema($db,$store,$upgradeschema);

        self::runData($db,$store,$upgradedata);

        return true;
    }


    //执行表结构操作
    protected static function runSchema($db,$store,$upgradeschema)
    {

        echo $store."--".$db." -- upgradeschema \r\n";

        $obj = upgrade_dbconnect_mysqlJob::getInstance($db,$msg);

        if(!$obj){
            return $msg;
        }

        if(isset($upgradeschema[$store]['create']) && !empty($upgradeschema[$store]['create'])){

            foreach($upgradeschema[$store]['create'] as $table =>$schema){
                $exist = $obj->tableExists($table);
                if(!$exist){
                    $obj->doSql($schema);
                }
            }
        }

        if(isset($upgradeschema[$store]['modify']) && !empty($upgradeschema[$store]['modify'])){

            foreach($upgradeschema[$store]['modify'] as $table =>$data){
                foreach($data as $fieldname=>$sql){
                    $exist = $obj->fieldExists($table,$fieldname);
                    if(!$exist){
                        $obj->doSql($sql);
                    }
                }
            }
        }

        $obj->close();
    }

    //执行表数据操作
    protected static function runData($db,$store,$upgradedata)
    {

        echo $store."--".$db." -- updatedata \r\n";

        $obj = upgrade_dbconnect_mysqlJob::getInstance($db,$msg);

        if(!$obj){
            return $msg;
        }

        if(isset($upgradedata[$store]['insert']) && !empty($upgradedata[$store]['insert'])){
            foreach($upgradedata[$store]['insert'] as $table =>$data){

                $res = $obj->doSql('select * from '.$obj->tablename($table).' where '.$data['where']);

                if(!$res){
                    $obj->doSql($data['sql']);
                }

            }
        }

        if(isset($upgradedata[$store]['update']) && !empty($upgradedata[$store]['update'])){
            foreach($upgradedata[$store]['update'] as $table =>$data){
                
                $res = $obj->doSql('select * from '.$obj->tablename($table).' where '.$data['where']);

                if(!$res){
                    $obj->doSql($data['sql']);
                }

            }
        }

        if(isset($upgradedata[$store]['delete']) && !empty($upgradedata[$store]['delete'])){
            foreach($upgradedata[$store]['delete'] as $table =>$data){
                
                $res = $obj->doSql('select * from '.$obj->tablename($table).' where '.$data['where']);

                if(!$res){
                    $obj->doSql($data['sql']);
                }

            }
        }

        $obj->close();
    }

}
