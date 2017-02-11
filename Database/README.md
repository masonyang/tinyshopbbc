## 数据库版本控制

使用[liquibase](http://www.liquibase.org/)进行数据库版本控制，数据库版本跟随服务端版本前进，每一个服务端版本都对应一个数据库版本。


## 安装liquibase

[参考文档](http://www.liquibase.org/download/index.html)

liquibase是基于java开发的，所以请确保你已经安装了java。


## 使用说明


* 本地执行
	* bin/deploy-db-local.sh
	
	> 该脚本会一次执行脚本中定义的位于changeLog文件夹下master中的changelog文件(xml结尾)。
    
    > 可用参数： -b [分支名称] (以逗号,分隔多个branch) 执行changeLog文件夹中某个子文件夹(对应分支名称)下的changelog文件。
    
    	bin/deploy-db-local.sh -b master,RB-0.67
    	* 该脚本会执行`changeLog/master`和`changeLog/RB-0.67`文件夹下的changeLog文件
    
* outer机器执行
 	* bin/deploy-db-outer.sh -b [分支名称] 
 	> 取消了默认运行命令。
 
 * product机器执行
 	* bin/deploy-db-product.sh -b [分支名称]
 	> 取消了默认运行命令。另外product的用户名和密码存储在~/.db_user和~/.db_password文件中
 	
## 开发人员使用说明

* 构建开发环境数据库
	* 复制`bin/create-dbs.sh.sample`为`bin/create-dbs.sh`,修改配置，运行脚本，创建对应的数据库
	* 下载基础库的sql语句，将这些sql文件放在changeLog/master/sql中。
	* 复制`bin/deploy-db-local.sh.sample`为`bin/deploy-db-local.sh`
	* 修改`bin/deploy-db-local.sh`中的数据库配置，示例如下:
	
			# *********config begins************
			
			# mysql server and port
			
			mysql_host="localhost"
			
			mysql_port=3306
			
			# 数据库用户名
			
			username="root"
			
			# 数据库密码
			
			password=""
			
			# 数据库名数组
			db_arr=("common" "count" "goods" "user_indexer" "userinfo1" "userinfo2")
			
			# 数据库前缀名
			db_prefix="yd_"
			
			# *********config ends************	
	
		
	* 默认在`changeLog/master`下已经存在对应数据库名的xml配置文件，如`common.xml` `count.xml`等，运行`deploy-db-local.sh`默认就会运行master下的所有数据库配置文件，这些配置文件中包含了数据库基础的结构和数据文件。
	
* 数据库回滚
	
	* 部分tag，如 “create table”, “rename column”, “add column”,可以自动生成rollback语句，其他的要通过rollback标签来显示声明，所以除了这3个标签我们强制规定必须在changeSet内添加rollback标签，如果没有rollback语句，那么就写一个`<rollback/>`即可，示例如下：
	
			<rollback>
		            drop table multiRollback1;
		            drop table multiRollback2;
		    </rollback>
		    <rollback>drop table multiRollback3</rollback>
		    
	* 执行`bin`目录下的rollback-db-local.sh即可，使用方法和deploy脚本类似，不过一般建议跟上`-d databasename`参数，这样既可以只回滚某一个库，然后指定`-c count`参数来指明回滚的changSet个数，比如下面的命令会回滚common表最近的3个changeSet，这里的最近可以从common库的DATABASECHANGELOG表的`orderexecuted`字段来由大到小指明。
	
			sh bin/rollback-db-local.sh -b RB-0.73 -d common -c 3
	
	
	
	
* 日常开发
	* 服务端按照版本进行开发，比如下一个分支是RB-0.67，那么在`changeLog`文件夹下建立对应的文件夹`RB-0.67`，然后将本版本中修改的sql语句写到对应的库配置文件中，比如如果修改了common库，那么就新建`common.xml`，并在其中写入变更的sql语句。	
	
	> #关于userinfo库的说明
	
	> 默认所有的userinfo库都会先去执行名为`userinfo.xml`的文件，然后再去执行对应自己名字的文件，比如`userinfo1.xml`。因此在`userinfo.xml`文件中配置在所有userinfo库中要执行的语句，然后在`userinfo1.xml`中执行其特定的sql语句。
		
	
	
	* 数据库配置文件xml说明，当数据库有变更发生时，只需要在对应的数据库配置xml文件中填写如下的语句即可
	
			<changeSet id="master-common-1" author="rockybean">
				<comment>[ your sql comment ]</comment>
		        <sql>
		            [ your sql statement ]
		        </sql>
		    </changeSet>
		    
		> 一个changeSet对应一次变更，具体可以查看liquibase的[文档](http://www.liquibase.org/documentation/changeset.html)，一般直接写sql即可。
		> 一个changeSet由id/author/filepath计算得到一个checksum获得。
		
		> * id为一个changeSet的id，由字母和数字组成，推荐以数字命名，可以一眼看出更改的顺序，每一个author自己一个顺序号。
		> * author为用户名
		> * comment为本次changeSet的更改描述，必填。
		> * sql为要执行的sql语句，可以是多条语句。 
		
	* 常用标签请参考[文档](http://www.liquibase.org/documentation/index.html)，常用示例如下：
				    <changeSet id="1" author="rockybean">
		        <createTable tableName="person" remarks="用户表">
		            <column name="id" type="int" autoIncrement="true" remarks="用户Id">
		                <constraints primaryKey="true" nullable="false"/>
		            </column>
		            <column name="firstname" type="varchar(50)" remarks="用户名"/>
		            <column name="lastname" type="varchar(50)" remarks="用户姓">
		                <constraints nullable="false"/>
		            </column>
		            <column name="state" type="char(2)" remarks="用户声明"/>
		        </createTable>
		    </changeSet>
		
		    <changeSet id="2" author="rockybean">
		        <addColumn tableName="person">
		            <column name="username" type="varchar(8)" remarks="用户全称"/>
		        </addColumn>
		    </changeSet>
		
		    <changeSet id="3" author="rockybean">
		        <renameColumn tableName="person" oldColumnName="username" newColumnName="myusername" columnDataType="varchar(8)" remarks="我的姓名"/>
		    </changeSet>
		
		    <changeSet id="4" author="rockybean">
		        <renameTable oldTableName="person" newTableName="person"/>
		    </changeSet>


		    
## 注意！！！

> 使用liquibase后，禁止直接操作数据库结构，所有的操作都先写到配置文件，然后通过运行文件来操作数据库，这样可以保证一致性！	    
	
# 资料

[Tutorial with oracle](http://www.liquibase.org/tutorial-using-oracle)		    
			    