`是时候Docker` 系列完结之作。本文将介绍如何使用docker镜像 php+vue 项目，用于快速部署


## 本地创建Docker映射目录
```text
—— vue_demo/         # Demo项目
—— php_vue/
—— docker-compose.yaml
—— mysql5/
—— nginx/
———— apps/           # 项目代码
—————— dist/           # npm run build 生成的 dist代码
—————— php/           # php代码
———— conf/           # nginx配置文件
—————— nginx.conf
———— log/            # nginx
———— vhost/          # 虚拟机配置目录
—————— php_demo.conf
—————— vue_demo.conf
```

## 创建`docker-compose.yaml`
```yaml
version : "3" # docker-compose的版本
services: # 容器的集合
      mysql: # 项目名称
              image: mysql:5.7        # 镜像名称，如果是通过dockerfile创建的可以使用build属性
              container_name: mysql   # 容器名称，如果没有这个属性的话，docker-compose会随机分配一个名字给容器
              privileged: true        # 允许操作的表示，如果不加的话会出现类似，permission deny的错误
              ports:
                      - 3307:3306     # 开放宿主机和容器的映射端口
              environment:
                      MYSQL_ROOT_PASSWORD: root   # mysql镜像中的环境变量：此处表示root用户密码
              volumes:
                    - ./mysql5:/var/lib/mysql     # 开放宿主机和容器的挂载卷映射
      php:
              image: php:7.2-fpm
              container_name: php
              privileged: true
              ports:
                    - 9001:9000
              links:
                     - mysql                      #容器之间进行关联

              volumes:
                     - ./nginx/apps:/usr/share/nginx/html  #挂载卷，需要注意的是，php在以模块的形式加载到nginx的时候，需要他们两个的目录结构一致，否则nginx无法加载php,但是 html等静态问价可以正常访问。
      nginx:
               image:  nginx
               container_name: nginx
               privileged: true
               links:
                       - php
               ports:
                       - 8088:8088
                       - 9099:9099
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


## 创建自己的镜像


### 修改仓库源
创建或修改 /etc/docker/daemon.json 文件，修改为如下形式

## 修改源 
vi /etc/docker/daemon.json
```json
{
    "registry-mirrors": ["http://hub-mirror.c.163.com"]
}
```
systemctl restart docker.service

国内加速地址有：
Docker中国区官方镜像
https://registry.docker-cn.com

网易
http://hub-mirror.c.163.com

ustc 
https://docker.mirrors.ustc.edu.cn

中国科技大学
https://docker.mirrors.ustc.edu.cn

阿里云容器  服务
https://cr.console.aliyun.com/
首页点击“创建我的容器镜像”  得到一个专属的镜像加速地址，类似于“https://1234abcd.mirror.aliyuncs.com”



### 奇怪的错误

... iptables --wait -t nat -I DOCKER -i br-d38d92300109 -j RETURN:iptables: No chain/target/match by that name.

docker 服务启动的时候，docker服务会向iptables注册一个链，以便让docker服务管理的containner所暴露的端口之间进行通信

通过命令iptables -L可以查看iptables 链

 

在开发环境中，如果你删除了iptables中的docker链，或者iptables的规则被丢失了（例如重启firewalld），docker就会报iptables error例如：failed programming external connectivity … iptables: No chain/target/match by that name

要解决这个问题，只要重启docker服务，之后，正确的iptables规则就会被创建出来

遗留学习：
1. iptables 链是什么，链怎么创建
2. 为什么重启docker就可以创建docker的链


调整下 www域名就好
6.访问localhost:8088端口，并在web目录下新增test.php，通过localhost:8088/test 访问

指导文章
[docker-compose安装lnmp环境](https://www.cnblogs.com/callmelx/p/11099562.html)
[使用Jenkins、Docker、GitLab 部署SpringBoot项目](https://juejin.im/post/5d9025e7f265da5bba416b54)