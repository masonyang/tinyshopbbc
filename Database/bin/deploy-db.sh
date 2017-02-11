#!/bin/sh
# 本地执行数据库版本控制的脚本

if [ ! "$mysql_host" ];then
	echo "YOU SHOULD NOT RUN THIS SHELL DIRECTLY!"
	exit 0
fi

# 脚本所在文件夹
bin_dir=$(cd "$(dirname "$0")";pwd)

# 主文件夹
cur_dir=$(dirname $bin_dir)

# liquibase执行文件
if [ ! $liquibase_bin ];then
	liquibase_bin=$cur_dir/liquibase/liquibase
fi



# 数据库连接url前缀，仅包含地址即可
jdbc_url_prefix="jdbc:mysql://"${mysql_host}:${mysql_port}"/"

# 数据库连接url后缀
jdbc_url_postfix="?characterEncoding=UTF-8"

# changeLog文件夹路径
changeLog_dir=$cur_dir"/changeLog"

# 要执行的分支名
branch_name=("master")

# 输出帮助信息
function usage()
{
	echo "Usage:"
	echo "-b [branch name] -d [db name] -p [db prefix]"
	echo "ex: -b master,RB-0.67,RB-0.68 -d common,userinfo1 -p yd_"
}

if [ "${silent}" == "" ] ; then
	silent=0
	# 获取传入的branch name
	while getopts "b:d:p:h:s" arg #选项后面的冒号表示该选项需要参数
	do
		case $arg in
			b)  # 设定要执行的分支
				tmpBranch=$OPTARG
				branch_name=(${tmpBranch//,/ })
				;;
			d)  # 设定要执行的数据库名
				tmpDB=$OPTARG
				db_arr=(${tmpDB//,/ })
				;;
			p) # 数据库前缀
				db_prefix=$OPTARG
				;;
			h)  # 输出帮助信息
				usage
				exit 0
				;;
		esac
	done
else
	tmpBranch=${version}
	branch_name=(${tmpBranch//,/ })
fi

if [ "${silent}" == "0" ] ;then
	# ask whether continue or not
	echo "continue to execute [ ${db_arr[@]} ] with prefix ( $db_prefix ) on [ ${branch_name[@]} ] sql changeLog?y/n"
	read cmd
	case ${cmd} in
		y|yes)
		;;
		*)
		usage
		echo "bye~"
		exit 0
		;;
	esac
fi

# java options parameter
export JAVA_OPTS=" -DdbPrefix="$db_prefix" "

# 遍历branch
for i in "${branch_name[@]}"; do
	tmpDir=${changeLog_dir}/$i

	if [[ ${no_master} && "$i"x = "master"x ]];then
		echo "YOU SHOULD NOT EXECUTE MASTER CHANGE LOG HERE!"
		exit 0
	fi


	if [ -d $tmpDir ];then
		# 遍历该文件夹下的所有文件
        for tmpdb in ${db_arr[@]}
        do
            dbname=$db_prefix$tmpdb
			# 数据库连接url
			jdbc_url=${jdbc_url_prefix}${dbname}${jdbc_url_postfix}

            # 获取该库的changeLog文件
			changeLog_file=$tmpDir/${tmpdb}.xml

			# userInfo库特殊处理
			case $tmpdb in
                userinfo*)
                    # userinfo库，首先使用通用的userinfo配置
					changeLog_file=$tmpDir/userinfo.xml
                ;;
            esac

			if [ -f $changeLog_file ];then
				echo "$liquibase_bin --driver=com.mysql.jdbc.Driver --classpath=$cur_dir/libs/mysql-connector-java-5.1.25-bin.jar --username=$username --password=$password --url=$jdbc_url --logLevel=info --changeLogFile=$changeLog_file --contexts=$contexts update"
				echo "starts ................................................................."
				$liquibase_bin --driver=com.mysql.jdbc.Driver --classpath=$cur_dir/libs/mysql-connector-java-5.1.25-bin.jar --username=$username --password=$password --url=$jdbc_url --logLevel=info --changeLogFile=$changeLog_file --contexts=$contexts update
				echo "ends ................................................................."
			else
				echo "************************"
				echo "$changeLog_file does not exist!"
				echo "************************"
			fi

			# 如果默认的对应userinfo表存在，则执行
			case $tmpdb in
                userinfo*)
					changeLog_file=$tmpDir/${tmpdb}.xml
                    # 判断对应的数据库changeLog如果存在，则运行
                    if [ -f $changeLog_file ];then
						echo "$liquibase_bin --driver=com.mysql.jdbc.Driver --classpath=$cur_dir/libs/mysql-connector-java-5.1.25-bin.jar --username=$username --password=$password --url=$jdbc_url --logLevel=info --changeLogFile=$changeLog_file --contexts=$contexts update"
						echo "starts ................................................................."
						$liquibase_bin --driver=com.mysql.jdbc.Driver --classpath=$cur_dir/libs/mysql-connector-java-5.1.25-bin.jar --username=$username --password=$password --url=$jdbc_url --logLevel=info --changeLogFile=$changeLog_file --contexts=$contexts update
						echo "ends ................................................................."
                    fi
                ;;
            esac

        done
	else
		echo "************************"
		echo "$tmpDir does not exist!"
		echo "************************"
	fi
done






