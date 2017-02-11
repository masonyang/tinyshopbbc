#!/bin/sh
# 当前文件夹
bin_dir=$(cd "$(dirname "$0")";pwd)

#config_file=$bin_dir"/conf/liquibase.test.conf"

# 数据库用户名
username="root"
# 数据库密码
password=""
# 数据库连接url前缀，仅包含地址即可
jdbc_url_prefix="jdbc:mysql://localhost:3306/"
# 数据库连接url后缀
jdbc_url_postfix="?characterEncoding=UTF-8"

# 数据库连接url
jdbc_url=${jdbc_url_prefix}"test"${jdbc_url_postfix}

# changelog 文件
changeLog_file=$bin_dir"/db-changelog-example.xml"

# liquibase命令
#liquibase --driver=com.mysql.jdbc.Driver --classpath=$bin_dir/libs/mysql-connector-java-5.1.25-bin.jar --defaultsFile=$config_file  --logLevel=info --changeLogFile=$changeLog_file update
liquibase --driver=com.mysql.jdbc.Driver --classpath=$bin_dir/libs/mysql-connector-java-5.1.25-bin.jar --username=$username --password=$password --url=$jdbc_url --logLevel=info --changeLogFile=$changeLog_file update
