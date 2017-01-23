#!/bin/bash
# Program
#    use cp to backup mysql data everyday!
# History
#    2013-05-02 guo     first
# Path
#    ....
BakDir=/www/weilong/qqc/mysqlbk/daily
BinDir=/var/lib/mysql
LogFile=/www/weilong/qqc/mysqlbk/bak.log
BinFile=/var/lib/mysql/mysql-bin.index
/usr/bin/mysqladmin -uqqc -pqqc flush-logs
#这个是用于产生新的mysql-bin.00000*文件
Counter=`/usr/bin/wc -l $BinFile |awk '{print $1}'`
NextNum=0
#这个for循环用于比对$Counter,$NextNum这两个值来确定文件是不是存在或最新的。
for file in  `/bin/cat $BinFile`
do
        base=`basename $file`
        #basename用于截取mysql-bin.00000*文件名，去掉./mysql-bin.000005前面的./
        NextNum=`expr $NextNum + 1`
        if [ $NextNum -eq $Counter ]
        then
                echo $base skip!  >> $LogFile
        else
                dest=$BakDir/$base
                if(test -e $dest)
                #test -e用于检测目标文件是否存在，存在就写exist!到$LogFile去。
                then
                        echo  $base exist! >> $LogFile
                else
                        cp $BinDir/$base $BakDir
                        echo $base copying >> $LogFile
                fi
        fi
done
echo `date +"%Y年%m月%d日 %H:%M:%S"` $Next Bakup succ! >> $LogFile