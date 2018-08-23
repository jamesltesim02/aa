服务器1地址: 10.96.70.13
root/Iv66.net

服务器2地址: 10.96.70.16
root/admin123123

前端相关
frontend/aa123123
    pm2 start verdaccio

网站管理
website/aa123123

多媒体测试
mediasite/aa123123
	clipbucket: admin/admin

数据库
database/aa123123
	mysql: root/aa123123


查看是否安装了某软件
rpm -qa | grep "httpd"

搜索安装列表
yum search all php-mysql

查找包含子串的文件
find -type f -name '*.php'|xargs grep 'GroupRecord'

根据关键字杀掉进程
ps -ef|grep tomcat|grep -v grep|awk '{print $2}'|xargs kill -9

---MySQL------------------------------------------------------------------------------------------------
1. 配置mysql文件:
    > cd /etc/my.cnf 
    # 加入配置参数
    > character-set-server=utf8 
2. 启动mysql服务
    > service mysqld start
    #或者下面这个
    >/etc/init.d/mysqld start
3. 设置开机启动
    >chkconfig --add mysqld
    >chkconfig mysqld on
4. 查看开机启动设置是否成功
    >chkconfig --list | grep mysql* 
    # mysqld 0:关闭 1:关闭 2:启用 3:启用 4:启用 5:启用 6:关闭停止

3. 登录 mysql
>mysql -u root -p
#如果忘记密码解决,查看下面的问题

4. 远程访问 开放防火墙的端口号mysql
1.增加权限
# mysql库中的user表新增一条记录host为“%”，user为“root”

5. Linux MySQL 几个重要 的目录
数据库目录 /var/lib/mysql/
配置文件 /usr/share /mysql（mysql.server命令及配置文件）
相关命令 /usr/bin（mysqladmin mysqldump等命令）
启动脚本 /etc/rc.d/init.d/（启动脚本文件mysql的目录）

6.删除mysql数据库
#如果使用的是yum安装的mysql,需要删除的话,就是用如下命令:
> yum -y remove mysql*
# 然后将/var/lib/mysql文件夹下的所有文件都删除干净
# 然后在重新执行上面的安装步骤

7.授权用户从远程登录
注意:下面两个步骤都需要执行.步骤一,只是修改了用户的密码.但是并没有对用户进行授权;步骤二的作用就是授权,让用户可以指定权限(连接诶数据库,查询数据库...)
1. 改表法。可能是你的帐号不允许从远程登陆，只能在localhost。这个时候只要在localhost的那台电脑，登入mysql后，更改 "mysql" 数据库里的 "user" 表里的 "host" 项，从"localhost"改称"%"
    mysql -u root -pvmware;
    mysql>use mysql;
    mysql>update user set host = '%' where user = 'root';
    mysql>select host, user from user;
2. 授权法。例如，你想myuser使用mypassword从任何主机连接到mysql服务器的话。
    GRANT ALL PRIVILEGES ON *.* TO 'myuser'@'%' IDENTIFIED BY 'mypassword' WITH GRANT OPTION;
    如果你想允许用户myuser从ip为192.168.1.3的主机连接到mysql服务器，并使用mypassword作为密码  
    GRANT ALL PRIVILEGES ON *.* TO 'myuser'@'192.168.1.3' IDENTIFIED BY 'mypassword' WITH GRANT OPTION;

8.注意
注意:
    1.【下面这一句一定要执行，否则还是无法登陆】  
    mysql>flush privileges ;
    2.如果用户无法从本地登陆，这个时候就执行如下
    GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost.localdomain' IDENTIFIED BY '123456' WITH GRANT OPTION;
    # flush privileges ;

---Apache-----------------------------------------------------------------------------------------------
安装 
	yum install httpd
安装完成后，使用 systemctl 命令启动他，并设置为开机自动启动
	systemctl start httpd.service
	systemctl enable httpd.service
FirewallD 打开防火墙
	firewall-cmd --permanent --zone=public --add-service=http
	firewall-cmd --permanent --zone=public --add-service=https
	firewall-cmd --reload

配置文件
nano /etc/httpd/conf/httpd.conf 

---Nginx------------------------------------------------------------------------------------------------
安装
	rpm -Uvh http://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm
	yum install -y nginx
设置自动启动
	systemctl start nginx.service
	systemctl enable nginx.service
配置信息
	网站文件存放默认目录 /usr/share/nginx/html
	网站默认站点配置 /etc/nginx/conf.d/default.conf
	自定义Nginx站点配置文件存放目录 /etc/nginx/conf.d/
	Nginx全局配置 /etc/nginx/nginx.conf
	Nginx启动 nginx -c nginx.conf

---PHP--------------------------------------------------------------------------------------------------
1.安装epel-release
rpm -ivh https://dl.fedoraproject.org/pub/epel/7/x86_64/Packages/e/epel-release-7-11.noarch.rpm
2.安装PHP7的rpm源
rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
3.安装PHP7
yum install php70w

安装 php70w-fpm  php70w-mysqlnd

---防火墙------------------------------------------------------------------------------------------------
CentOS7使用firewalld打开关闭防火墙与端口
1、firewalld的基本使用
启动： systemctl start firewalld
关闭： systemctl stop firewalld
查看状态： systemctl status firewalld 
开机禁用  ： systemctl disable firewalld
开机启用  ： systemctl enable firewalld
 
 
2.systemctl是CentOS7的服务管理工具中主要的工具，它融合之前service和chkconfig的功能于一体。
启动一个服务：systemctl start firewalld.service
关闭一个服务：systemctl stop firewalld.service
重启一个服务：systemctl restart firewalld.service
显示一个服务的状态：systemctl status firewalld.service
在开机时启用一个服务：systemctl enable firewalld.service
在开机时禁用一个服务：systemctl disable firewalld.service
查看服务是否开机启动：systemctl is-enabled firewalld.service
查看已启动的服务列表：systemctl list-unit-files|grep enabled
查看启动失败的服务列表：systemctl --failed

3.配置firewalld-cmd

查看版本： firewall-cmd --version
查看帮助： firewall-cmd --help
显示状态： firewall-cmd --state
查看所有打开的端口： firewall-cmd --zone=public --list-ports
更新防火墙规则： firewall-cmd --reload
查看区域信息:  firewall-cmd --get-active-zones
查看指定接口所属区域： firewall-cmd --get-zone-of-interface=eth0
拒绝所有包：firewall-cmd --panic-on
取消拒绝状态： firewall-cmd --panic-off
查看是否拒绝： firewall-cmd --query-panic
 
那怎么开启一个端口呢
添加
	firewall-cmd --zone=public --add-port=80/tcp --permanent    （--permanent永久生效，没有此参数重启后失效）
重新载入
	firewall-cmd --reload
查看
	firewall-cmd --zone= public --query-port=80/tcp
删除
	firewall-cmd --zone= public --remove-port=80/tcp --permanent

---FTP Server-------------------------------------------------------------------------------------------
步骤一、环境

1、安装
	yum install vsftpd

2、关闭相应防火墙
	firewall-cmd --add-service=ftp --permanent
	systemctl restart firewalld

3、启动ftp服务器
	systemctl start vsftpd.service
	步骤二、配置ftp服务器（修改vsftpd.conf vim /etc/vsftpd/vsftpd.conf）

1、(1)禁用匿名访问,(2)设置只读权限
	anonymous_enable=NO
	write_enable=NO

2、指定用户登陆后目录
	local_root=/home/user00/data

3、设置不能范围上级目录，
	chroot_local_user=YES
注意：

!!!如果local_root是可写的就要设置这句
allow_writeable_chroot=YES

但是添加之后重启ftp可能会出错以下文档可解决这个问题
http://blog.163.com/xuyuntao521@yeah/blog/static/78424175201551610111875/
https://stackoverflow.com/questions/31726754/login-error-vsftpd-on-centos7-azure-box
完成配置之后重启ftp服务器

systemctl restart vsftpd.service

---TinyProxy--------------------------------------------------------------------------------------------
杀掉所有进程
ps -ef|grep tinyproxy|grep -v grep|awk '{print $2}'|xargs kill -9
启动代理服务器
/usr/sbin/tinyproxy -c /etc/tinyproxy/tinyproxy.conf
配置文件
/etc/tinyproxy/tinyproxy.conf

---Docker-----------------------------------------------------------------------------------------------
安装:
	yum install docker-io
启动:
	systemctl start docker

常用命令:
	运行一个指定的容器: docker run image_name[:tag]
		如果本地没有镜像,则会自动下载该镜像
		image_name 表示镜像名称
		tag 为镜像标签,如 ubuntu:15.10,若不指定tag则默认使用latest
		-t 在新容器中指定一个伪终端
		-i 允许对容器进行标准输入交互
		-d 让容器在后台运行
		-P 将容器内部使用的网络端口映射到主机上
		    (随机绑定:通常是32768 32769等)
		-p 指定端口绑定
		    如: -p 5000:5000
		--name 指定启动容器的名称
	从仓库获取指定镜像: docker pull image_name[:tag]
		image_name 表示镜像名称
		tag 为镜像标签,如 ubuntu:15.10,若不指定tag则默认使用latest
	从仓库查找指定镜像: docker search image_name
		image_name 表示镜像名称,如 docker search httpd
		检索结果
		NAME 镜像仓库源的名称
		DESCRIPTION 镜像的描述
		OFFICIAL 是否docker官方发布
	退出内部伪终端模式: Ctrl + d
	查看在运行容器列表: docker ps
	查看容器控制台输出: docker logs id/name
	查看容器绑定的端口: docker port id/name
	查看容器运行的进程: docker top id/name
	查看容器的底层信息: docker inspect id/name
		会返回一个 JSON 文件记录着 Docker 容器的配置和状态信息
	查看所有镜像列表:   docker images
	停止指定容器:       docker stop id/name
	启动已停止的容器:   docker start id/name
	删除指定镜像:       docker rm id/name [-f]
		容器必须是停止状态
		-f 强制删除
		--no-prune 不移除该镜像的过程镜像，默认移除
	构建docker镜像:    docker build image_folder
		image_folder 镜像文件目录
    进入运行中的容器:   docker exec [-i -t -d] id/name  command
    	command 执行命令,如: /bin/bash
    	-i 允许与容器进行标准输出交互
    	-t 分配一个伪终端
    	-d 在后台运行

运行示例:
docker run ubuntu:15.10 /bin/echo "Hello world"
	各个参数解析：
	docker: Docker 的二进制执行文件。
	run:与前面的 docker 组合来运行一个容器。
	ubuntu:15.10指定要运行的镜像，Docker首先从本地主机上查找镜像是否存在，如果不存在，Docker 就会从镜像仓库 Docker Hub 下载公共镜像。
	/bin/echo "Hello world": 在启动的容器里执行的命令

运行交互式的容器:
docker run -i -t ubuntu:15.10 /bin/bash
	各个参数解析：
	-t:在新容器内指定一个伪终端或终端。
	-i:允许你对容器内的标准输入 (STDIN) 进行交互。

启动容器（后台模式）:
docker run -d ubuntu:15.10 /bin/sh -c "while true; do echo hello world; sleep 1; done"
	参数说明:
    -d:让容器在后台运行。

启动容器绑定端口
docker run  -d -p 4873:4873 --name verdaccio nb/pm2-verdaccio

查看在运行的容器列表:
docker ps
	CONTAINER ID:容器ID
	NAMES:自动分配的容器名称
	IMAGE: 镜像信息
	COMMAND: 在运行命令
	STATUS: 运行状态

查看容器的标准输出:
	docker logs 容器id 或 容器名称

停止容器:
	docker stop 容器id 或 容器名称

打开docker监控面板
	docker stats

构建docker镜像
docker build ./pm2-verdaccio/
Dockerfile示例:
	FROM node:10.8.0-alpine

	ENV NODE_VERSION 10.87.0

	RUN apk update \
	        && apk upgrade \
	        && apk add --no-cache bash \
	        bash-doc \
	        bash-completion \
	        && rm -rf /var/cache/apk/* \
	        && /bin/bash

	RUN yarn add pm2 verdaccio \
	        && ln -s /node_modules/pm2/bin/pm2 /usr/bin/pm2 \
	        && ln -s /node_modules/pm2/bin/pm2-runtime /usr/bin/pm2-runtime

	ENV NODE_ENV production

	EXPOSE 4873

	CMD ["pm2-runtime", "/node_modules/verdaccio/bin/verdaccio", "--", "--listen", "http://0.0.0.0:4873"]

打开正在运行容器的命令行
docker exec -i -t verdaccio /bin/bash
	-i 允许与容器进行标准输出交互
	-t 分配一个伪终端





appkey:
n19jmcy5n87r9

secret:
1t6Bz5qPYACB
