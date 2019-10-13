## Dockerfile创建镜像

### Dockerfile实例
```shell
FROM alpine:latest
MAINTAINER sbf
CMD echo 'hello docker'
```
### 说明
```text
FROM baseimage
MAINTAINER 共享是告诉其他人，是谁写的
CMD 运行命令
```

### 实际操作
```shell
touch Dockerfile
vim Dockerfile

docker build -t hello_docker .      // -t 构建image名  . 当前目录下所有内容都提交给docker产生image
docker images hello_docker          // 查看image 是否生成
docker run hello_docker             // 运行image, 输出内容 hello docker
```


### 复杂一点的实例
```shell
FROM ubuntu
MAINTAINER xbf
RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list
RUN apt-get update
RUN apt-get install -y nginx
COPY index.html /var/www/html
ENTRYPOINT ["/usr/sbin/nginx", "-g", "daemon off;"]
EXPOSE 80
```
```shell
docker build -t test_docker .
docker run -d -p 8080:80 test_docker
```

#### 镜像分层
Dockerfile的每一行都会产生一个分层且只读，比如
![](https://user-gold-cdn.xitu.io/2019/9/30/16d80f588f800ddc?w=960&h=316&f=png&s=110205)
当镜像运行时会产生`container layer`可读可写的新层来保证容器是可以修改。分层的好处在于，相同分层合并有利减低存储

### Dockerfile语法小结
| 语法  | 说明|
|------|-----|
| FROM | 基础镜像 |
| RUN | 容器内执行命令 |
| CMD | 程序入口，像main方法一样 |
| ENTRYPOINT | 如果指定，CMD会变为它的arguments |
| ADD | 可以添加远程文件，如ftp文件，比copy强大 |
| COPY | 拷贝文件 |
| WORKDIR | 工作目录 |
| MAINTAINER | 作者 |
| ENV | 设置容器内环境变量 |
| USER | 执行命令的用户，一般是非root |
| VOLUME | 挂在卷 |
| EXPOSE | 暴露端口 |
