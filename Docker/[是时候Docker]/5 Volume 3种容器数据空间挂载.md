## 5 Volume 3种容器数据空间挂载

### 目录
- [是时候Docker: 1 Docker导学](https://juejin.im/post/5d8c169c6fb9a04e0855a141)
- [是时候Docker: 2 安装并取消sudo执行docker](https://juejin.im/post/5d8d60c651882509453c1e83)
- [是时候Docker: 3 Docker小实例](https://juejin.im/post/5d91d21c518825094d62676b)
- [是时候Docker: 3.1 Docker命令速查表]()
- [是时候Docker: 4 Dockerfile创建镜像](https://juejin.im/post/5d91d7fe5188250970132e2b)
- [是时候Docker: 5 Volume 独立于容器外的持久化存储]()
- 待续。。。

### 简介
之前我们知道，我们再容器中的改动默认是不会被保存的，需要`docker commit`提交更新image<br />
而volume提供方便持久化的存储，并且volume可用于容器与容器间的数据共享

### Docker默认映射数据卷
```shell
docker run -d --name nginx -v /usr/share/nginx/html nginx
docker inspect nginx            // 检查，列表容器的所有信息
```
这里我们关心得是`Mounts`部分
```json
{"Mounts": [
     {
         "Type": "volume",
         "Name": "e9484a423cea196a0646fd3570caa3c029724c80b5861479a2429e1854be1225",
         "Source": "/var/lib/docker/volumes/e9484a423cea196a0646fd3570caa3c029724c80b5861479a2429e1854be1225/_data",
         "Destination": "/usr/share/nginx/html",
         "Driver": "local",
         "Mode": "",
         "RW": true,
         "Propagation": ""
     }
]}
```
表示将机器路径`/var/lib/docker/volumes/e9484a423cea196a0646fd3570caa3c029724c80b5861479a2429e1854be1225/_data`挂载到容器`/usr/share/nginx/html`<br />
进机器看看：
```shell
sudo ls /var/lib/docker/volumes/e9484a423cea196a0646fd3570caa3c029724c80b5861479a2429e1854be1225/_data
```
![](https://user-gold-cdn.xitu.io/2019/9/30/16d820653bdf7f8c?w=1241&h=89&f=jpeg&s=28651)

进入容器内看看:
```shell
docker exec -it nginx bash
ls /usr/share/nginx/html
```
![](https://user-gold-cdn.xitu.io/2019/9/30/16d82087bef587f1?w=771&h=72&f=png&s=15831)
上下对应

### 指定本机路径映射数据卷
```shell
docker run -p 8080:80 -d -v $PWD/html:/usr/share/nginx/html nginx   // 当前目录下html文件夹指向 容器文件夹/var/www/html

cd $PWD/html
```
新建文件 index.html
```shell
vim index.html
this is new path: /Users/XXX/Dev/workplace/docker/test/dockerfile2/html

// 测试 curl  http://localhost:8080/
```

### 创建 数据容器 挂靠在 其他容器
```shell
docker create -v $PWD/data:/var/mydata --name data_container ubuntu 
docker run -it --volumes-from data_container ubuntu bash                // --volumes-from 数据区来源于； ubuntu 基础镜像
```

进入容器命令行输入`mount`，你会找到`/var/mydata`
```shell
overlay on / type overlay (rw,relatime,lowerdir=/var/lib/docker/overlay2/l/HV7J3XHTSUR2VIX5FAICK3GTNP:/var/lib/docker/overlay2/l/FNZVNT3SVEJA6OA6AUF4HS7BAF:/var/lib/docker/overlay2/l/HLYOPOXL7XLBVZ75AEBUXBDSI6:/var/lib/docker/overlay2/l/VLLH4C5NZ7VWCHKU2FE5WQIF43:/var/lib/docker/overlay2/l/IZU6B7HVYHQIVVS5HF4QNFZSWN,upperdir=/var/lib/docker/overlay2/7e5a362489866581c4c8bc1979782cda8f85d06f48855cdc0faf0cda1c9498de/diff,workdir=/var/lib/docker/overlay2/7e5a362489866581c4c8bc1979782cda8f85d06f48855cdc0faf0cda1c9498de/work)
proc on /proc type proc (rw,nosuid,nodev,noexec,relatime)
tmpfs on /dev type tmpfs (rw,nosuid,size=65536k,mode=755)
devpts on /dev/pts type devpts (rw,nosuid,noexec,relatime,gid=5,mode=620,ptmxmode=666)
sysfs on /sys type sysfs (ro,nosuid,nodev,noexec,relatime)
tmpfs on /sys/fs/cgroup type tmpfs (ro,nosuid,nodev,noexec,relatime,mode=755)
cpuset on /sys/fs/cgroup/cpuset type cgroup (ro,nosuid,nodev,noexec,relatime,cpuset)
cpu on /sys/fs/cgroup/cpu type cgroup (ro,nosuid,nodev,noexec,relatime,cpu)
cpuacct on /sys/fs/cgroup/cpuacct type cgroup (ro,nosuid,nodev,noexec,relatime,cpuacct)
blkio on /sys/fs/cgroup/blkio type cgroup (ro,nosuid,nodev,noexec,relatime,blkio)
memory on /sys/fs/cgroup/memory type cgroup (ro,nosuid,nodev,noexec,relatime,memory)
devices on /sys/fs/cgroup/devices type cgroup (ro,nosuid,nodev,noexec,relatime,devices)
freezer on /sys/fs/cgroup/freezer type cgroup (ro,nosuid,nodev,noexec,relatime,freezer)
net_cls on /sys/fs/cgroup/net_cls type cgroup (ro,nosuid,nodev,noexec,relatime,net_cls)
perf_event on /sys/fs/cgroup/perf_event type cgroup (ro,nosuid,nodev,noexec,relatime,perf_event)
net_prio on /sys/fs/cgroup/net_prio type cgroup (ro,nosuid,nodev,noexec,relatime,net_prio)
hugetlb on /sys/fs/cgroup/hugetlb type cgroup (ro,nosuid,nodev,noexec,relatime,hugetlb)
pids on /sys/fs/cgroup/pids type cgroup (ro,nosuid,nodev,noexec,relatime,pids)
cgroup on /sys/fs/cgroup/systemd type cgroup (ro,nosuid,nodev,noexec,relatime,name=systemd)
mqueue on /dev/mqueue type mqueue (rw,nosuid,nodev,noexec,relatime)
shm on /dev/shm type tmpfs (rw,nosuid,nodev,noexec,relatime,size=65536k)
osxfs on /var/mydata type fuse.osxfs (rw,nosuid,nodev,relatime,user_id=0,group_id=0,allow_other,max_read=1048576)
/dev/sda1 on /etc/resolv.conf type ext4 (rw,relatime,data=ordered)
/dev/sda1 on /etc/hostname type ext4 (rw,relatime,data=ordered)
/dev/sda1 on /etc/hosts type ext4 (rw,relatime,data=ordered)
devpts on /dev/console type devpts (rw,nosuid,noexec,relatime,gid=5,mode=620,ptmxmode=666)
proc on /proc/bus type proc (ro,relatime)
proc on /proc/fs type proc (ro,relatime)
proc on /proc/irq type proc (ro,relatime)
proc on /proc/sys type proc (ro,relatime)
proc on /proc/sysrq-trigger type proc (ro,relatime)
tmpfs on /proc/acpi type tmpfs (ro,relatime)
tmpfs on /proc/kcore type tmpfs (rw,nosuid,size=65536k,mode=755)
tmpfs on /proc/keys type tmpfs (rw,nosuid,size=65536k,mode=755)
tmpfs on /proc/timer_list type tmpfs (rw,nosuid,size=65536k,mode=755)
tmpfs on /proc/sched_debug type tmpfs (rw,nosuid,size=65536k,mode=755)
tmpfs on /sys/firmware type tmpfs (ro,relatime)
```

验证一下：
```shell
touch index.html
```
`exit`退出，如`$PWD/data`下出现`index.html`证明挂载成功<br />
以上方式，可以实现多容器数据同步 


### 更多内容下回分解
### 原创不易，如果你觉得我的文章对你有帮助，请点赞鼓励