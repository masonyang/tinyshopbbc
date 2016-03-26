#! /bin/bash

# headtobranch shell
# /var/www/html/qqc/protected/config/online/dispatch-headtobranch.sh /usr/bin/php

phpExec=$1
selfpath=$(cd "$(dirname "$0")"; pwd)
LAUNCHER="$selfpath/../../../jobs/job.php"

#队列一次处理10条
LIMIT=10

function checkProcess()
{
	if (ps aux|grep -v grep|grep "$1" )
    then
        echo "active"
    else
        #echo "miss"
        #echo $1
        $phpExec $LAUNCHER $1 --limit=$LIMIT
    fi
}

function execScript()
{
	BRANCHS=`$phpExec $LAUNCHER dispatchJob --syncdirect=headtobranch --limit=$LIMIT`

	if [ "${BRANCHS}" != "no data" ]; then
	    for branch in $BRANCHS
		do
			checkProcess "workerJob --syncdirect=headtobranch --db=$branch"
		done
	fi
}

execScript