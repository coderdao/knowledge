本文将介绍如何使用docker镜像 快速部署 php+vue 项目.文末提供[源码地址](https://github.com/ruidao/demo/tree/master/Docker/php_vue)

## 本地创建Docker映射目录
```text
—— vue_demo         # Demo项目
—— php_vue
—— docker-compose.yaml
—— nginx
———— apps           # 项目代码
———— conf           # nginx配置文件
—————— nginx.conf
———— log            # nginx
———— vhost          # 虚拟机配置目录
—————— default.conf
```

## 创建`docker-compose.yaml`
```yaml
version : "3" #docker-compose的版本
services: #容器的集合
      mysql: #项目名称
              image: mysql:5.7 #镜像名称，如果是通过dockerfile创建的可以使用build属性
              container_name: mysql  #容器名称，如果没有这个属性的话，docker-compose会随机分配一个名字给容器
              privileged: true    #允许操作的表示，如果不加的话会出现类似，permission deny的错误
              ports:
                      - 3307:3306  #开放宿主机和容器的映射端口
              environment:
                      MYSQL_ROOT_PASSWORD: root  #mysql镜像中的环境变量
      php:
              image: php:7.2-fpm
              container_name: php
              privileged: true
              ports:
                    - 9001:9000
              links:
                     - mysql  #容器之间进行关联
              volumes:
                     - ./nginx/apps:/usr/share/nginx/html  #挂载卷，需要注意的是，php在以模块的形式加载到nginx的时候，需要他们两个的目录结构一致，否则nginx无法加载php,但是 html等静态问价可以正常访问。
      nginx:
               image:  nginx
               container_name: nginx
               privileged: true
               links:
                       - php
               ports:
                       - 8088:80
               volumes:
                       - ./nginx/vhost:/www/nginx/vhost
                       - ./nginx/conf/nginx.conf:/etc/nginx/nginx.conf
                       - ./nginx/log:/www/nginx/log
                       - ./nginx/apps:/usr/share/nginx/html

      phpmyadmin:
              image: phpmyadmin/phpmyadmin
              container_name: phpmyadmin
              privileged: true
              links:
                      - mysql
              ports:
                      - 7001:80
              environment:
                      MYSQL_ROOT_PASSWORD: root
                      PMA_HOST: mysql

      redis:
              image: redis:4.0.14
              container_name: redis
              privileged: true
              ports:
                      - 6379:6379
      mongo:
              image: mongo
              restart: always
              ports:
                      - 27017:27017
```
建立容器,执行命令 `docker-compose up -d`

### 创建前端项目
使用`vue create vue_demo`或`vue ui`创建项目`vue_demo`
![](https://user-gold-cdn.xitu.io/2019/10/13/16dc417680e843b7?w=963&h=272&f=png&s=38193)


打包项目`cd vue_demo && npm run build`，复制 `dist`文件夹到 `php_vue/nginx/apps`下
![](https://user-gold-cdn.xitu.io/2019/10/13/16dc435c93545869?w=1309&h=420&f=png&s=58575)

> 测试  `curl localhost:9099`  返回html代码

### 创建php文件
```php
<?php
echo 'hello Docker';
```
> 测试  `curl localhost:8088`  返回 hello Docker

## 过程中的疑难杂症
docker 基础命令

### image拉取速度过慢
解决方法 - 修改仓库源<br />
创建、修改 `/etc/docker/daemon.json` 文件，修改为如下形式


```shell
vi /etc/docker/daemon.json          # 编辑daemon.json 

{
    "registry-mirrors": ["http://hub-mirror.c.163.com"]
}

systemctl restart docker            # 重启docker服务
```

国内加速地址有：

| Docker中国区官方镜像 | https://registry.docker-cn.com |
| 网易 | http://hub-mirror.c.163.com |
| ustc  | https://docker.mirrors.ustc.edu.cn |
| 中国科技大学  | https://docker.mirrors.ustc.edu.cn |
| 阿里云  | https://cr.console.aliyun.com |

> docker-compose 拉取镜像，成功之后就不会重复拉取。<br />
所以导出现某个镜像速度慢、卡死。 `Ctrl + c`终止换源，重启docker再执行命令 `docker-compose up -d`



### docker 创建 iptables链 报错

> ... iptables --wait -t nat -I DOCKER -i br-d38d92300109 -j RETURN:iptables: No chain/target/match by that name.

docker 服务启动的时候，docker服务会向iptables注册一个链，以便让docker服务管理的containner所暴露的端口之间进行通信
通过命令 `iptables -L` 可以查看iptables链

在开发环境中，如果你删除了iptables中的docker链，或者iptables的规则被丢失了（例如重启firewalld），docker就会报iptables error例如：failed programming external connectivity … iptables: No chain/target/match by that name
要解决这个问题，只要 `重启docker服务`，之后，正确的iptables规则就会被创建出来

## 项目源码
> https://github.com/ruidao/demo/tree/master/Docker/php_vue

## 不足 & 后续跟进
- 上述部署还是不够快，正确打开方式应该是创建自己centos项目镜像
- 项目代码迭代还可以更自动一点



| a | a |
| - | - |

| 操作 | 代码 |

| 登录仓库 | docker login docker-registry.xxxx (用户名不带mail) |
| 退出仓库 | docker logout|
|拉取镜像	| docker pull docker-registry.xxx/docker/nginx:1.13.6|
|推送镜像	| dockerpush|
|删除镜像	| dockerrmi IMAGE ID|
|查看正在运行的容器	| dockerps|
|停止一个容器	| dockerstop CONTAINER ID|
|查看所有容器	| dockerps -a|
|删除一个容器	| dockerrm CONTAINER ID|
|删除所有容器	| dockerrm $(docker ps -a -q)|
|容器挂载	| docker-v /opt/www/html:/var/www/html 宿主机绝对路径(一定要是绝对路径，不然无法挂载):docker容器|
|build容器	| dockerbuild -t fgf/nginx:beta_v1 . (一定要有.，并且在Dockfile所在文件夹操作，也可以做其他设置，简单点就行)|
|run	| dockerrun -d -p 80:80 IMAGE ID(-d 后台运行 -p 宿主机端口:docker端口)|
|进入一个正在运行的docker	| dockerexec -it CONTAINER ID /bin/bash|
|进入一个镜像	| dockerrun -it IMAGE ID /bin/bash|
|从容器中退出 | Ctrl + d 或者exit回车|
|镜像重命名 | docker tag IMAGE ID 新名字|

指导文章
[docker-compose安装lnmp环境](https://www.cnblogs.com/callmelx/p/11099562.html)
[使用Jenkins、Docker、GitLab 部署SpringBoot项目](https://juejin.im/post/5d9025e7f265da5bba416b54)