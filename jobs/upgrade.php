<?php
/**
 * Created by PhpStorm.
 * User: yangminsheng
 * Date: 17/4/17
 * Time: 下午9:55
 *
 * 版本升级脚本 用于批量执行 表结构、数据等
 * 
 * /usr/local/Cellar/php54/5.4.45_3/bin/php job.php upgradeJob --database=zd --version=20170417
 * 
 */
class upgradeJob extends syncdataJob
{

    private $version = null;

    private $database = null;

    private $distrInfo = array();

    protected function __before()
    {
        parent::__before();

        $this->database = $this->getOption('database');

        $this->version = $this->getOption('version');

        $this->distrInfo = $this->getDistributor();

    }

    protected function __run()
    {

        $ispass = upgrade_baseJob::checkParams($this->version);

        if($ispass){

            if(!in_array($this->database,array('zd','withoutzd'))){ //指定 具体数据库 执行更新

                echo "count:".count($this->distrInfo)."\r\n";
                
                foreach($this->distrInfo as $db){

                    if($db == $this->database){
                        upgrade_baseJob::upgrade($this->version,$db);
                        break;
                    }

                }

            }else{

                if($this->database == 'zd'){ //针对 总店数据库 执行更新

                    upgrade_baseJob::upgrade($this->version,'zd');

                }elseif($this->database == 'withoutzd'){// 遍历执行所有分店数据库 执行更新

                    foreach($this->distrInfo as $db){

                        upgrade_baseJob::upgrade($this->version,$db);

                    }

                }

            }

        }
    }

    private function getDistributor()
    {
        $headMasterModel = new Model('distributor','zd','master');
        $disInfos = $headMasterModel->where('disabled=0')->findAll();

        if(!$disInfos){
            return false;
        }

        $distrInfo = array();

        foreach ($disInfos as $disinfo) {
            $distrInfo[] = $disinfo['site_url'];
        }

        return $distrInfo;
    }

}