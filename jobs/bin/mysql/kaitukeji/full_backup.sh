#!/bin/bash
# Program
#    use mysqldump to Fully backup mysql data per week!
# History
#    2013-04-27 guo     first
# Path
#    ....
BakDir=/www/weilong/qqc/mysqlbk
LogFile=/www/weilong/qqc/mysqlbk/bak.log
Date=`date +%Y%m%d`
Begin=`date +"%Y年%m月%d日 %H:%M:%S"`
cd $BakDir
DumpFile=$Date.sql
GZDumpFile=$Date.sql.tgz
/usr/bin/mysqldump -uqqc -pqqc --quick --all-databases --flush-logs --delete-master-logs --single-transaction > $DumpFile
/bin/tar czvf $GZDumpFile $DumpFile
/bin/rm $DumpFile
Last=`date +"%Y年%m月%d日 %H:%M:%S"`
echo 开始:$Begin 结束:$Last $GZDumpFile succ >> $LogFile
cd $BakDir/daily
rm -f *