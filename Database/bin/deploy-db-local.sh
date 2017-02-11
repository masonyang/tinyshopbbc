#!/bin/sh
# 本地执行数据库版本控制的脚本

# *********config begins************

# mysql server and port

mysql_host="localhost"

mysql_port=3306

# 数据库用户名

username="root"

# 数据库密码

password="123456"

# 数据库名数组
db_arr=("tinyshop" "a")

# 数据库前缀名
db_prefix=""

# 运行环境上下文
contexts="local"

# 不执行master分支
no_master="1"

# *********config ends************

# 当前文件夹
bin_dir=$(cd "$(dirname "$0")";pwd)

cur_dir=$(dirname $bin_dir)

source $bin_dir/deploy-db.sh
