#!/bin/sh
# 本地执行数据库版本控制的脚本

if [ ! -f ~/.db_user ];then
	echo "YOU SHOULD CONFIG YOUR DATABASE USERNAME IN ~/.db_user FILE"
	exit 0
fi

if [ ! -f ~/.db_password ];then
	echo "YOU SHOULD CONFIG YOUR DATABASE PASSWORD IN ~/.db_password FILE"
	exit 0
fi

# *********config begins************

# mysql server and port

mysql_host="dbmaster"

mysql_port=3306

# 数据库用户名

username=`cat ~/.db_user`

# 数据库密码

password=`cat ~/.db_password`

# 数据库名数组
db_arr=("tinyshop" "a")

# 数据库前缀名
db_prefix=""

# 运行环境上下文
contexts="product"

# 不执行master分支
no_master="1"

# *********config ends************



# 当前文件夹
bin_dir=$(cd "$(dirname "$0")";pwd)

cur_dir=$(dirname $bin_dir)

source $bin_dir/deploy-db.sh

