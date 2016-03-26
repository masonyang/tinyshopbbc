

head -> branch

sync_queue  

branch -> head
1.修改分销商信息 同步到总店 distributor表

branchToHeadJob.php

private func init():
	select * from tiny_sync_queue where sync_direct = 'branchtohead' and status='ready' order by id asc;

	$this->headMasterModel = new Model('distributor','zd','master');

protected func update():
	$this->headMasterModel->data($data)->where($where)->update();

DROP TABLE IF EXISTS `tiny_sync_queue`;
CREATE TABLE `tiny_sync_queue` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `content` longtext NOT NULL COMMENT '扫描人',
  `distributor_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '分销商id',
  `inserttime` int(11) DEFAULT NULL COMMENT '插入时间',
  `modifytime` int(11) DEFAULT NULL COMMENT '执行时间',
  `level` int(1) NOT NULL DEFAULT 0 COMMENT '优先级 0:普通 1:中 2:高',
  `sync_direct` enum('headtobranch', 'branchtohead') DEFAULT 'headtobranch',
  `action` enum('add', 'update','del') DEFAULT 'add',
  `status` enum('ready', 'syncing','success','fail') DEFAULT 'ready',
  `action_type` enum('normal', 'openshop') DEFAULT 'normal',
  `sync_type` enum('goods', 'brand','category','distrInfo','goods_type','payment','products','spec','tag') DEFAULT 'goods',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据同步队列表'; 