docker基本入门以后，可以试试打包docker镜像与dockerfile了

docker镜像

docker hub仓库有2类仓库，用户仓库和顶层仓库，用户仓库由docker用户创建的，顶层仓库由docker内部的人来管理的。仓库里存放的是镜像文件，那么问题来了 ，怎么去创建镜像呢？

构建docker镜像的有2种方法：
1.使用docker commit命令。
2.使用docker build 命令和Dockerfile文件。在这里并不推荐使用docker commit命令，而应该使用更灵活，更强大的Dockerfile来构建镜像..

1.创建一个centos镜像

通过命令下载dockerhub上的官方镜像
`docker pull centos`拉取centos镜像后，创建一个基于centos镜像的容器

通过命令docker images查看服务器上已有的镜像 
通过命令
> docker run -dit -p 4000:8080 centos镜像名或id

创建一个基于centos镜像的容器在后台运行并将服务器的4000端口映射到容器中的8080端口

3.将jdk，tomcat的安装包和web项目上传至镜像中
docker cp /usr/local/jdk安装包 容器名:容器地址
docker cp /usr/local/tomcat安装包 容器名:容器地址
docker cp /usr/local/web项目 容器名:容器地址1

4.进入容器并操作

通过命令
docker attach 容器名或id
进入容器中

安装jdk和tomcat的步骤和在linux中安装步骤一致，你可以把容器当成一个linux虚拟机， 之后启动tomcat服务

5.验证

在浏览器上输入http://服务器ip:4000，如果出现tomcat页面则成功了

6.将容器打包成镜像
docker commit -a "runoob.com" -m "my apache" 容器名称或id 打包的镜像名称:标签
OPTIONS说明：
-a :提交的镜像作者；
-c :使用Dockerfile指令来创建镜像；
-m :提交时的说明文字；
-p :在commit时，将容器暂停。

7.上传至你的dockerhub

使用您的Docker ID登录 
如果您没有Docker帐户，请在cloud.docker.com注册一个 。记下你的用户名。 
登录到本地计算机上的Docker公共注册表。
```shell
docker login
```

标记镜像 
将本地映像与注册表上的存储库相关联的符号是 username/repository:tag。该标签是可选的，但推荐使用，因为这是注册管理机构为Docker镜像提供版本的机制。给存储库并为上下文标记有意义的名称，例如 get-started:part2。这将把图像放入get-started存储库并标记为part2。 
现在，把它们放在一起来标记镜像。运行docker tag image您的用户名，存储库和标签名称，以便镜像将上传到您想要的目的地。该命令的语法是：
```shell
docker tag image username/repository:tag
```

例如：
```shell
docker tag friendlyhello john/get-started:part2
```

运行docker镜像以查看新标记的镜像。（你也可以用docker image ls。） 
```shell
docker images
```

```shell
REPOSITORY TAG IMAGE ID CREATED SIZE 
 friendlyhello latest d9e555c53008 3 minutes ago 195MB 
 john/get-started part2 d9e555c53008 3 minutes ago 195MB 
 python 2.7-slim 1c7128a655f6 5 days ago 183MB 
```


发布镜像 
将您的标记镜像上传到存储库：
```shell
docker push username/repository:tag
```

8.下载镜像

一旦完成，这个上传的结果是公开的。如果你登录到Docker Hub，你将会看到那个新的镜像和它的pull命令。 
从远程存储库中提取并运行映像 
从现在起，您可以使用docker run此命令在任何机器上使用并运行您的应用程序：
```shell
docker run -p 4000:80 username/repository:tag
```
如果图像在机器上本地不可用，Docker将从存储库中取出。
