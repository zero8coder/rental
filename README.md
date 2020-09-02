# 租房系统
## 安装步骤
### 设置站点
homestead.yaml
```
ip: "192.168.10.10"
memory: 2048
cpus: 1
provider: virtualbox

authorize: ~/.ssh/id_rsa.pub

keys:
    - ~/.ssh/id_rsa
    - ~/.ssh/id_rsa.pub

folders:
    - map: ~/Code
      to: /home/vagrant/Code

sites:
    - map: rental.test
      to: /home/vagrant/Code/rental/public

databases:
    - rental

```
### 安装 PHP 依赖
```
composer install
```
### 安装 Nodejs 依赖
```
yarn config set registry https://registry.npm.taobao.org

SASS_BINARY_SITE=http://npm.taobao.org/mirrors/node-sass yarn
```
### 配置 .env 文件
```
cp .env.example .env
```
.env
```
APP_NAME="Rental"
.
.
.
APP_URL=http://rental.test
.
.
.
DB_DATABASE=rental
DB_USERNAME=homestead
DB_PASSWORD=secret
.
.
.
QUEUE_CONNECTION=redis
.
.
.
```

### 生成 APP_KEY
```
php artisan key:generate
```
### 创建软链
```
php artisan storage:link
```

### 迁移数据
```
php artisan migrate
```
### 安装后台表
```
php artisan admin:install
```

