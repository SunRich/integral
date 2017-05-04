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
```
composer run-script copy-env
```
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
     "result": "2"
     }
     ```
   2. 失败响应
     ```
     {
     "code": 404,
     "message": "error message"
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
     "result": "2"
     }
     ```
   2. 失败响应
     ```
     {
     "code": 404,
     "message": "error message"
     }
     ```

###  获取一个用户的积分总数
  - 请求 `get $host/counts/users/{userId}/type/{type}/start/{startTime}/end/{endTime}`
  - 参数
    1. userId(int):用户编号
    2. type(string):类型:add=>增加，expend=>消耗，left=>当前剩余
    3. start(string):开始时间
    4. end(string):结束时间
  - 请求示例:`get $host/counts/users/1/type/add/start/2017-05-01/end/2017-05-04`
  - 响应:
    ```
    {
      "code": 200,
      "result": 2
    }
    ```
###  获取一个用户的积分详情
  - 请求 `get $host/infos/users/{userId}/type/{type}/start/{startTime}/end/{endTime}`
  - 参数
       1. userId(int):用户编号
       2. type(string):类型:add=>增加，expend=>消耗，left=>当前剩余
       3. start(string):开始时间
       4. end(string):结束时间
  - 请求示例:`get $host/infos/users/1/type/add/start/2017-05-01/end/2017-05-04`
  - 响应:
    ```
    {
      "code": 200,
      "result": {
        "total": 1,
        "per_page": 10,
        "current_page": 1,
        "last_page": 1,
        "next_page_url": null,
        "prev_page_url": null,
        "from": 1,
        "to": 1,
        "data": [
          {
            "id": 1,
            "user_id": 1,
            "integral": 2,
            "content": "签到增加积分",
            "time": "2017-05-04 14:59:15"
          }
        ]
      }
    }
    ```
###  获取积分排行
  - 请求 `get $host/ranks/type/{type}/start/{startTime}/end/{endTime}`
  - 参数
       1. type(string):类型:add=>增加，expend=>消耗，left=>当前剩余
       2. start(string):开始时间
       3. end(string):结束时间
  - 请求示例:`get $host/ranks/type/add/start/2017-05-01/end/2017-05-04`
  - 响应:
    ```
    {
      "code": 200,
      "result": {
        "total": 1,
        "per_page": 10,
        "current_page": 1,
        "last_page": 1,
        "next_page_url": null,
        "prev_page_url": null,
        "from": 1,
        "to": 1,
        "data": [
          {
            "integral_sum": "2",
            "user_id": 1
          }
        ]
      }
    }
    ```
