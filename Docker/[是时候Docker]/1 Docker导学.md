## 是时候Docker: 1 Docker导学
### Docker 是什么？
![](https://user-gold-cdn.xitu.io/2019/9/25/16d6403eba9d61e0?w=1846&h=1062&f=png&s=953293)
Docker 属于 Linux 容器的一种封装，提供简单易用的容器使用接口。它是目前最流行的 Linux 容器解决方案。

Docker 将应用程序与该程序的依赖，打包在一个文件里面。运行这个文件，就会生成一个虚拟容器。程序在这个虚拟容器里运行，就好像在真实的物理机上运行一样。有了 Docker，就不用担心环境问题。

总体来说，Docker 的接口相当简单，用户可以方便地创建和使用容器，把自己的应用放入容器。容器还可以进行版本管理、复制、分享、修改，就像管理普通的代码一样。

### Docker 的用途
- 简化配置
- 整合服务器
- 代码流水线管理
- 提高开发效率
- 隔离应用
- 调试能力
- 多租户
- 快速部署

### Docker 的安装
- [Mac](https://docs.docker.com/docker-for-mac/install/)
- [Window](https://docs.docker.com/docker-for-windows/install/)
- [CentOS](https://docs.docker.com/install/linux/docker-ce/centos/)

安装完成后执行下面命令，检查是否安装成功：
> docker version 或者 docker info

### 镜像(image)文件
Docker 把应用程序及其依赖，打包在 image 文件里面。
只有通过这个文件，才能生成 Docker 容器。image 文件可以看作是容器的模板。<br />
Docker 根据 image 文件生成容器的实例。同一个 image 文件，可以生成多个同时运行的容器实例。

image 是二进制文件。实际开发中，一个 image 文件往往通过继承另一个 image 文件，加上一些个性化设置而生成。举例来说，你可以在 Ubuntu 的 image 基础上，往里面加入 Apache 服务器，形成你的 image。
```shell
$ docker image ls                   // 列出本机的所有 image 文件
$ docker image rm [imageName]       // 删除 image 文件
``` 
image 文件是通用的，一台机器的 image 文件拷贝到另一台机器，照样可以使用。一般来说，为了节省时间，我们应该尽量使用别人制作好的 image 文件，而不是自己制作。即使要定制，也应该基于别人的 image 文件进行加工，而不是从零开始制作。

为了方便共享，image 文件制作完成后，可以上传到网上的仓库。Docker 的官方仓库 Docker Hub 是最重要、最常用的 image 仓库。此外，出售自己制作的 image 文件也是可以的。
 
### 实例：hello world
下面，我们通过最简单的 image 文件"hello world"，感受一下 Docker。
 
需要说明的是，国内连接 Docker 的官方仓库很慢，还会断线，需要将默认仓库改成国内的镜像网站，具体的修改方法在下一篇文章的第一节。有需要的朋友，可以先看一下。
 
首先，运行下面的命令，将 image 文件从仓库抓取到本地。
 > $ docker image pull library/hello-world

上面代码中，docker image pull是抓取 image 文件的命令。library/hello-world是 image 文件在仓库里面的位置，其中library是 image 文件所在的组，hello-world是 image 文件的名字。
 


抓取成功以后，就可以在本机看到这个 image 文件了。
 
 
> $ docker image ls

现在，运行这个 image 文件。
> $ docker container run hello-world

docker container run命令会从 image 文件，生成一个正在运行的容器实例。
 
注意，docker container run命令具有自动抓取 image 文件的功能。如果发现本地没有指定的 image 文件，就会从仓库自动抓取。因此，前面的docker image pull命令并不是必需的步骤。
 
如果运行成功，你会在屏幕上读到下面的输出。
 
> $ docker container run hello-world
 
Hello from Docker!
This message shows that your installation appears to be working correctly.
... ...
输出这段提示以后，hello world就会停止运行，容器自动终止。