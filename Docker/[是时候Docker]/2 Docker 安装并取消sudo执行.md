## 是时候Docker: 2 安装并取消sudo执行docker
> 原文再续，就书接上一回。今天我们说说Docker安装的具体操作

### 目录
1. [Docker导学](https://juejin.im/post/5d8c169c6fb9a04e0855a141)
2. [安装并取消sudo执行docker](https://juejin.im/post/5d8d60c651882509453c1e83)
3. 划水中。。。

### Docker 的安装
- [Mac](https://docs.docker.com/docker-for-mac/install/)
- [Window](https://docs.docker.com/docker-for-windows/install/)
- [CentOS](https://docs.docker.com/install/linux/docker-ce/centos/)

这里以 CentOS 7举例:
### 启动Docker
> sudo systemctl start docker
 
验证安装是否成功
> docker version <br />
docker info

### 为了验证安装的 Docker社区版,执行hello-world镜像
> sudo docker run hello-world
它报`hello-world`镜像不存在，你就开大`docker image pull hello-world`

### 升级旧版Docker
> 要升级Docker Engine-Community，请下载更新的软件包文件并重复安装过程，使用`yum -y upgrade`而不是`yum -y install`并指向新文件。

### 卸载Docker
> sudo yum remove docker-ce

主机上的镜像，容器，自定义配置文件不会自动删除。要删除所有镜像，容器，自定义配置文件：
> sudo rm -rf /var/lib/docker

您必须手动删除所有已编辑的配置文件。

### 不使用`sudo`执行docker命令
```shell
sudo systemctl start docker     // 启动docker

sudo groupadd docker            // 添加 docker 组
sudo usermod -aG docker mayunbaba     // 往 docker 组添加 mayunbaba 用户

/**
 注销并重新登录，以便重新评估您的组成员身份。
 如果在Linux上，您还可以运行以下命令来激活对组的更改：
*/
newgrp docker  

// 验证您可以在没有sudo的情况下运行docker命令。
docker run info
```

### 拉取镜像
> docker image pull library/hello-world

由于 Docker 官方提供的 image 文件，都放在library组里面，所以它的是默认组，可以省略。
## 是时候Docker: 2 安装并取消sudo执行docker
> docker image pull hello-world
### 更多在线镜像
[在线镜像](https://hub.docker.com/)

### 删除镜像
> docker rmi hello-world
如果镜像曾被执行，你需要 docker rm [containID]  // 删除容器

### 查看镜像文件
> docker image

### 运行镜像
> docker container run hello-world

如果运行成功，你会在屏幕上读到下面的输出。
```text
Hello from Docker!
This message shows that your installation appears to be working correctly.
```
输出这段提示以后，hello world就会停止运行，容器自动终止。

对于那些不会自动终止的容器，必须使用`docker container kill`命令手动终止。
### 终止docker容器
> docker container kill [containID]

### 查看正在运行docker进程
> docker ps

### 更多内容下回分解
### 原创不易，如果你觉得我的文章对你有帮助，请点赞鼓励