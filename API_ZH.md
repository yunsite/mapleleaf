目前只返回 JSON 格式的数据。

### 状态码 ###
  * 200 一切正常
  * 304 未改变
  * 400 坏的请求
  * 401 未认证
  * 403 禁止访问
  * 404 未找到
  * 500 服务器内部错误
  * 502 坏网关
  * 503 服务不可用

**提示**
当出现错误时，一个包含错误信息的 json 字符串被返回，如下

```
{"error_code":"401","error":"Not Authorized","error_detail":"Login required"}
```

### 基本 API ###
**留言访问 API**

[api.php](#api.php.md) : 返回最新的留言

[api.php?controller=post&action=create](#api.php?controller=post&action=create.md) : 发表一条留言

**用户 API**

[api.php?controller=user&action=login](#api.php?controller=user&action=login.md) : 用户登录

api.php?controller=user&action=update : 得到或者更新用户的信息


### 搜索 API ###

[api.php?controller=search](#api.php?controller=search.md) : 返回你搜索的记录



---

#### api.php ####
HTTP 请求的类型
> GET
是否需要认证
> 无须
请求的参数
|      | **是否必需** | **类型和范围** | **描述** |
|:-----|:---------|:----------|:-------|
| **pid**| 否        | 整数               | 得到指定页的留言，注意仅在留言板开启分页时有意义，从 0 开始。 |

例子
```
http://localhost/mapleleaf/api.php?pid=2
```


响应

```
{"messages":
  [
    {
      "id":"1",
      "ip":"127.0.0.1",
      "uid":null,
      "user":"rainyjune",
      "post_content":"Welcome to our site.",
      "time":"02-05 12:46",
      "reply_content":"",
      "reply_time":"01-01 08:00",
      "b_username":null
    },
    {
      "id":"3",
      "ip":"127.0.0.1",
      "uid":null,
      "user":"anonymous",
      "post_content":"<strong>Strong<\/strong>",
      "time":"02-05 12:48",
      "reply_content":"",
      "reply_time":"01-01 08:00",
      "b_username":null
    }
],
"current_page":2,
"total":6,
"pagenum":3
}
```

```
字段说明
messages:获得的数据
  id:留言ID
  ip:留言者的IP地址
  uid:留言者的 ID
  user:匿名用户的用户名
  post_content:留言的内容
  time:留言发表的日期
  reply_content:管理员回复的内容
  reply_time:管理员回复的时间
  b_username:留言者的用户名
current_page:当前的页码数，注意从0开始，2意味着是第三页
total:留言的数目
pagenum:总页码数
```

#### api.php?controller=post&action=create ####

HTTP 请求的类型
> POST
是否需要认证
> 无需
请求的参数

|       | **是否必需** | **类型和范围** | **描述** |
|:------|:---------|:----------|:-------|
| **user**| 是        | 字符串             | 用户名     |
| **content**| 是        | 字符串             | 留言的内容，应当少于 580 字  |
| **valid\_code**| 否        |  字符串      | 若留言板开启了验证码功能，则需要提供此参数 |

例子
```
http://localhost/mapleleaf/api.php?controller=post&action=create
Post包体格式：
user=testuser&content=xxx&valid_code=xxx
```

响应

```
{"insert_id":15}
```

```
字段说明
insert_id:新发表的留言的id
```

#### api.php?controller=user&action=login ####

HTTP 请求的类型
> GET
是否需要认证
> 是
请求的参数

|       | **是否必需** | **类型和范围** | **描述** |
|:------|:---------|:----------|:-------|
| **user**| 是        | 字符串             | 用户名     |
| **password**| 是        | 字符串             | 密码     |

例子
```
http://localhost/mapleleaf/api.php?controller=user&action=login&user=test1&password=test1
```

响应

```
{"user":"test1","uid":"1","session_name":"PHPSESSID","session_value":"6d4blivuthkbvvk8ve06fkfhd2"}
```

字段说明
```
user:用户名
uid:用户的id
session_name:用于发送给服务器的 session 的名字
session_value:用于发送给服务器的 session 的值
```

#### api.php?controller=user&action=update ####
此部分复杂，以后可能会更改.

#### api.php?controller=search ####

HTTP 请求类型
> GET
是否需要认证
> 否
请求的参数

|       | **是否必需** | **类型和范围** | **描述** |
|:------|:---------|:----------|:-------|
| **s** | 是        | 字符串             | 要搜索的关键字     |

例子
```
http://localhost/mapleleaf/api.php?controller=search&s=keyword
```

响应

```
{"messages":[
 {
  "id":"15",
  "ip":"127.0.0.1",
  "uid":null,
  "user":"testuser",
  "post_content":"test content",
  "time":"02-05 16:15",
  "reply_content":"",
  "reply_time":"01-01 08:00",
  "b_username":null
 }
],
"nums":1
}
```

字段说明
```
messages:搜索到的相关留言
  id:留言ID
  ip:留言者的IP地址
  uid:留言者的 ID
  user:匿名用户的用户名
  post_content:留言的内容
  time:留言发表的日期
  reply_content:管理员回复的内容
  reply_time:管理员回复的时间
  b_username:留言者的用户名
nums:搜索到的相关留言的数量
```