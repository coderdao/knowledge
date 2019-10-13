## Docker小实例

### 创建文件
```text
vim index.html

<html>
<h1>docker is fun!</h1>
</html>
```

### 部署Nginx
```shell
// -p 8081:80  8081 映射到 80 | -d 守护进程
docker run -d -p 8080:80 nginx   // 验证 curl http://127.0.0.1:8080

docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                  NAMES
19d1b0186d1a        nginx               "nginx -g 'daemon of…"   12 seconds ago      Up 11 seconds       0.0.0.0:8081->80/tcp   nginxserver
```

### 复制文件到docker内
```shell
docker exec -it [CONTAINER ID] bash  // 用下面命令进入容器内部，查找nginx目录,
find . -name "nginx"                 // 将目前目录及其子目录下所有名称是 nginx 的文件列出来
exit                                 // 退出

docker cp index.html 19d1b0186d1a://usr/share/nginx/html
// 验证 curl http://127.0.0.1:8080
```

### 保存容器内改动
```shell
docker commit -m 'add index.html2/usr/share/nginx/html' 19d1b0186d1a nginx-fun
// 返回：sha256:ddccfc4cb7bec3e81ffa580826096adb94b77c7b4e859521af32ad5fa68f9d5d

docker stop 19d1b0186d1a
docker run -d -p 8080:80 nginx-fun   // 验证 curl http://127.0.0.1:8080
```

### 命令小结
| 命令 | 说明 |
|----- | ---- |
| docker pull | 获取image |
| docker build | 创建image |
| docker images | 列出image |
| docker run [CONTAINER NAME] | 运行container |
| docker container ls | 列出正在运行container |
| docker container ls -a | 列出所有container（终止状态的容器） |
| docker container stop [CONTAINER ID]| 终止container |
| docker container logs | 获取容器输出信息 |
| docker ps | 列出container|
| docker rm | 删除container|
| docker rmi | 删除image|
| docker exec -it [CONTAINER ID] bash | 进入container内部执行命令；不建议使用docker attach命令会在`exit`时终止容器|
| docker cp [SOURCE PATH] [CONTAINER ID]:/[TARGET PATH] | 复制文件到容器内部|
| docker commit -m '[MESSAGE]' [CONTAINER ID] [IMAGE NAME] | 保存改动为新 image|
