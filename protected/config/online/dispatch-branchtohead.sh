#!/bin/bash

# branchtohead shell
# /var/www/html/qqc/protected/config/online/dispatch-branchtohead.sh /usr/bin/php

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
	BRANCHS=`$phpExec $LAUNCHER dispatchJob --syncdirect=branchtohead`

	if [ "${BRANCHS}" != "no data" ]; then
	    for branch in $BRANCHS
		do
			checkProcess "workerJob --syncdirect=branchtohead --db=$branch"
		done
	fi
}

execScript