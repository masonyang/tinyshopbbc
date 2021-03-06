#! /bin/bash

# 保证同一时间只有一个Dispatcher在运行
TEMP=$(ps aux | grep "dispatch-headtobranch.sh" | grep -v "grep" | wc -l)
if [ $TEMP -gt 2 ]; then
    echo "dispatch-headtobranch already running."
    exit 1
fi

#队列一次处理10条
LIMIT=10

# 分配器 总店数据同步到分店脚本
PHP="/usr/local/Cellar/php54/5.4.45_3/bin/php"

LAUNCHER="/Users/yangminsheng/masonInHome/tinyshop/jobs/job.php"

DISPATCHHB="/Users/yangminsheng/masonInHome/tinyshop/data/log/dispatch-headtobranch.log"

WORKINFO="/Users/yangminsheng/masonInHome/tinyshop/data/log/headtobranch/dispatch-headtobranch-disId.log"

$PHP $LAUNCHER dispatchJob --syncdirect=headtobranch --logfile=$DISPATCHHB --limit=$LIMIT

BRANCHS=`cat ${DISPATCHHB}`

if [ "${BRANCHS}" != "no data" ]; then
	for branch in $BRANCHS
	do
		$PHP $LAUNCHER workerJob --syncdirect=headtobranch --db=$branch --limit=$LIMIT
	done
fi

rm $DISPATCHHB