---
layout: README
title: 积分服务
date: 2017-05-04
modifiedOn: 2017-05-04
---
## 开发环境
1. 开启服务
```
php -S localhost:3000 -t public
```
2. 复制.env.example到.env并修改其中的数据库配置。

3. 数据初始化
```
php artisan migrate
```
4. 单元测试
```
phpunit
```

## docker部署

## 服务功能
### 增加积分
  - 请求 `post $host/add/`
  - 参数
    1. userId(int):用户编号
    2. integrals(int):积分数
  - 请求示例:`curl -H "Content-Type: application/json" --data '{"userId": "1","integrals":"2"}' $host/addintegral/`
  - 响应:
   1. 成功响应
     ```
     {
     "code": 200,
     "message": "ok"
     }
     ```
   2. 失败响应
     ```
     {
     "code": 404,
     "message": "error"
     }
     ```
### 消耗积分
  - 请求 `post $host/expend/`
  - 参数
    1. userId(int):用户编号
    2. integrals(int):积分数
  - 请求示例:`curl -H "Content-Type: application/json" --data '{"userId": "1","integrals":"2"}' $host/expend/`
  - 响应:
   1. 成功响应
     ```
     {
     "code": 200,
     "message": "ok"
     }
     ```
   2. 失败响应
     ```
     {
     "code": 404,
     "message": "error"
     }
     ```

###  获取一个用户的积分总数
  - 请求 `get $host/counts/users/{userId}/type/{type}`
  - 参数
    1. userId(int):用户编号
  - 请求示例:`get $host/counts/users/1/type/add`
  - 响应:
    ```
    {
      "code": 200,
      "result": [
        "1"
      ]
    }
    ```
###  获取一个用户的积分详情
  - 请求 `get $host/infos/users/{userId}/type/{type}`
  - 参数
    1. userId(int):用户编号
  - 请求示例:`get $host/infos/users/1/type/add`
  - 响应:
    ```
    {
      "code": 200,
      "result": [
        "1"
      ]
    }
    ```