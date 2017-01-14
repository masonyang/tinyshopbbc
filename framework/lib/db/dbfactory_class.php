<?php
/**
 * Tiny - A PHP Framework For Web Artisans
 * @author Tiny <tinylofty@gmail.com>
 * @copyright Copyright(c) 2010-2014 http://www.tinyrise.com All rights reserved
 * @version 1.0
 */

/**
 * DB工厂类，实现多数据库的支持
 *
 * @author Tiny
 * @class DBFactory
 */
class DBFactory
{
	private static $dbinfo=array();
    /**
     * 得到实例
     *
     * @access public
     * @return mixed
     */
    public static function getInstance($domain = null,$issalve = 'salve')
    {
		$dbinfo = self::getDbInfo($domain,$issalve);
        $db = DBMysql::getInstance($dbinfo,$issalve);
        $db->selectDb($dbinfo[$issalve]['name']);
        return $db;
//        switch($dbinfo['type']){
//            default:{
//                if(version_compare(PHP_VERSION, '5.4.0') >= 0){
//                    $db = DBMysqli::getInstance($dbinfo,$issalve);
//                    $db->selectDb($dbinfo[$issalve]['name']);
//                    return $db;
//                }else{
//                    $db = DBMysql::getInstance($dbinfo,$issalve);
//                    $db->selectDb($dbinfo[$issalve]['name']);
//                    return $db;
//                }
//            }
//        }
    }
    /**
     * 取得数据库信息
     *
     * @access public
     * @return mixed
     */
	public static function getDbInfo($domain = null,$issalve = 'salve'){
		if(null === $domain){
            $headStore = Config::getInstance('config')->get('headStore');
			$domain = $headStore;
			self::$dbinfo[md5($domain.'-'.$issalve)] = Tiny::app()->db;
		}else{
			$mapper = Config::getInstance('mapper')->get($domain);
			self::$dbinfo[md5($domain.'-'.$issalve)] = $mapper['db'];
		}

		return self::$dbinfo[md5($domain.'-'.$issalve)];
	}
}
