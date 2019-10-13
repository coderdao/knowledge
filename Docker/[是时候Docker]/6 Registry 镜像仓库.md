## 是时候Docker: 6 Registry 镜像仓库

> [原文]再续，
就书接上一回。今天我们说说使用Dockerfile创建镜像容器

### 目录
- [是时候Docker: 1 Docker导学](https://juejin.im/post/5d8c169c6fb9a04e0855a141)
- [是时候Docker: 2 安装并取消sudo执行docker](https://juejin.im/post/5d8d60c651882509453c1e83)
- [是时候Docker: 3 Docker小实例](https://juejin.im/post/5d91d21c518825094d62676b)
- [是时候Docker: 3.1 Docker19 命令速查表](https://juejin.im/post/5d9409b95188252af43b7632)
- [是时候Docker: 4 Dockerfile创建镜像](https://juejin.im/post/5d91d7fe5188250970132e2b)
- [是时候Docker: 5 Volume 3种容器数据空间挂载](https://juejin.im/post/5d9409f951882509165fe909)

实际上Registry本身并不是仓库的意思；window有个Registry注册表，它是注册的意思。<br />
Registry实际上是saas服务,提供注册&存储镜像，方便大家来共享镜像。回头看我们第一节的Docker架构图
![](https://user-gold-cdn.xitu.io/2019/9/26/16d6dc329bcbe46f?w=682&h=371&f=png&s=97515)

Registry中的`CentOS`就是其中一个镜像。用户通过`docker pull`命令给`daemon`把`CentOS`镜像加载到本地来使用

### 术语
| English | 中文 |
| ------- | ---- |
| host | 宿主机 |
| image | 镜像 |
| container | 容器 |
| registry | 仓库 |
| daemon | 守护程序 |
| client | 客户端 |

### Registry 交互
```shell
docker search hello-world       // 查找镜像 hello-world
docker pull hello-world         // 获取镜像 hello-world
docker push myname/hello-world  // 推送个人镜像 hello-world
```

## Docker仓库
| 国内仓库 | 地址 |
| ------- | ---- |
| 阿里云 | https://dev.aliyun.com/search.html |
| 网易云 | https://c.163.com/hub#/m/home/ |
| DaoCloud | https://hub.daocloud.io/ |

| 国外仓库 | 地址 |
| ------- | ---- |
| Docker Hub | https://hub.docker.com/ |
| Quay | https://quay.io/ |

[Docker 国内仓库和镜像设置](https://www.cnblogs.com/wushuaishuai/p/9984228.html)

### 实践操作一下
```shell
docker search whalesay
docker pull docker/whalesay

// 试用打印些东西
docker run docker/whalesay cowsay Docker好玩

// 复制仓库
docker tag docker/whalesay myname/whalesay

// 登录并共享到dock hub
docker login
docker push myname/whalesay   

// 完成后登录docker hub 个人主页就能看到`myname/whalesay`镜像
```


>更多内容下回分解
原创不易，如果我的文章对你有帮助，`请点赞鼓励`