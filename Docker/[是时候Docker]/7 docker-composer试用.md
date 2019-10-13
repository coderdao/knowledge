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

> Dockerfile 可以让用户管理一个单独的应用容器；<br />
Compose 则允许用户在一个模板（YAML 格式）中定义一组相关联的应用容器（被称为一个 project，即项目）
<br />例如一个 Web 服务容器再加上后端的数据库服务容器等

### 文件目录
```text
ghost
  - ghost
    - Dockfile    
  - nginx
      - nginx.conf
      - Dockfile
  - data
  - docker-compose.yml
```

### docker-composer.yaml格式
```yaml
ghost-app:
    build:ghost
    depends_on:
        - db
    ports:
        - "2368:2368"
nginx:
     build:nginx
     ports:
         - "80:80"
     depends_on:
         - ghost-app
db:
    image: "mysql:5.7.15"
```

### 配置 ghost Dockerfile
```shell
FROM ghost
COPY ./config.js /var/lib/ghost/config.js
EXPOSE 2368
CMD ["npm", "start", "--production"]
```

### config.js
![](https://img3.mukewang.com/5bc7e523000125dc05000419.jpg)

### nginx Dockerfile
```shell
FROM nginx
COPY nginx.conf /etc/nginx/nginx.conf
EXPOSE 80
```

nginx.conf
```shell
worker processes 4;
events {worker connections 1024;}
http {
    server {
        listen 80;
        location / {
            proxy pass http://ghost-app:2368;
        }
    }
}
```

### ghost docker-compare.yaml
![](https://img2.mukewang.com/5bc7e7dd0001367f04590710.jpg)

此处数据库相关的配置要和config.js中的配置保持一致。【注：db.volumes的值应该为：$PWD/data:/var/lib/mysql】



ghost目录：

#将所有容器启动，并以daemon的方式后台运行
docke-compose up -d
启动容器后浏览器访问localhost

显示502 Bad Gateway错误

表示nginx已经启动，但是nginx和ghost-app之间的链接是不对的。

检查：<br />
1. docker-compose.yaml
2. nginx.conf


```shell
1.将已经启动的容器停掉
docker-compose stop
 
2.将已经停掉的容器删除掉
docker-compose rm
 
3.修改了nginx.conf之后需要重新构建镜像[第一次没有镜像，执行的时候回直接创建镜像，现在已经镜像了，所以需要重新构建]
docker-compose build 
 
4.使用镜像启动容器
docker-compose up -d
 
5.再次使用浏览器访问就成功了，表示使用docker-compose拉的三个容器已经启动，并且相互协作了
 
6.访问localhost/ghost/setup/one 进行配置
```
localhost/ghost/editor/ 写文章并发布，则可以在localhost看到发布的文章。

>更多内容下回分解
原创不易，如果我的文章对你有帮助，`请点赞鼓励`