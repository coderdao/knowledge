### 创建前端项目

- npm install -g vue-cli  全局安装 vue-cli
- 创建项目
    - vue create <project-name>  选择手动配置
    - vue ui
- npm run build 建立生成环境代码
- npm run serve  /  npm run dev 启动webpack
  - vue serve demo.vue 单页应用启动

### 修改仓库源
创建或修改 /etc/docker/daemon.json 文件，修改为如下形式

# vi /etc/docker/daemon.json
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