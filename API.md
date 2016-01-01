We only support JSON at present.

### Status Code ###
  * 200 OK
  * 304 Not Modified
  * 400 Bad Request
  * 401 Not Authorized
  * 403 Forbidden
  * 404 Not Found
  * 500 Internal Server Error
  * 502 Bad Gateway
  * 503 Service Unavailable

**Tips**
When error occurs , a json string contains error related information was returned, like the following

```
{"error_code":"401","error":"Not Authorized","error_detail":"Login required"}
```

### Basic API ###
**Post Access API**

[api.php](#api.php.md) : Return the latest posts

[api.php?controller=post&action=create](#api.php?controller=post&action=create.md) : Create a post

**User API**

[api.php?controller=user&action=login](#api.php?controller=user&action=login.md) : Login with user account

api.php?controller=user&action=update : Get and/or update user account


### Search API ###

[api.php?controller=search](#api.php?controller=search.md) : Return records against the keyword you provided.



---

#### api.php ####
Supported Formats
> JSON
HTTP Request Method
> GET
Requires Authentication
> false
Request Parameters
|      | **Requires** | **Type and Range** | **Description** |
|:-----|:-------------|:-------------------|:----------------|
| **pid**| false        | int                | Get records according to page number , note start from 0 and ignored if Guestbook disabled pagination. |

Example
```
<?php
// create curl resource
$ch = curl_init();
// set url
curl_setopt($ch, CURLOPT_URL, "http://localhost/mapleleaf/api.php?pid=2");
//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// $output contains the output string
$output = curl_exec($ch);
var_dump($output);
// close curl resource to free up system resources
curl_close($ch);     
?>
```

Response

```
{"messages":
  [
    {
      "0":"1",
      "id":"1",
      "1":"127.0.0.1",
      "ip":"127.0.0.1",
      "2":null,
      "uid":null,
      "3":"rainyjune",
      "user":"rainyjune",
      "4":"Welcome to our site.",
      "post_content":"Welcome to our site.",
      "5":"1296881181",
      "time":"02-05 12:46",
      "6":null,
      "reply_content":"",
      "7":null,
      "reply_time":"01-01 08:00",
      "8":null,
      "b_username":null
    },
    {
      "0":"3",
      "id":"3",
      "1":"127.0.0.1",
      "ip":"127.0.0.1",
      "2":null,
      "uid":null,
      "3":"anonymous",
      "user":"anonymous",
      "4":"<strong>Strong<\/strong>",
      "post_content":"<strong>Strong<\/strong>",
      "5":"1296881296",
      "time":"02-05 12:48",
      "6":null,
      "reply_content":"",
      "7":null,
      "reply_time":"01-01 08:00",
      "8":null,
      "b_username":null
    }
],
"current_page":2,
"total":6,
"pagenum":3
}
```

#### api.php?controller=post&action=create ####

Supported Formats
> JSON
HTTP Request Method
> POST
Requires Authentication
> false
Request Parameters

|       | **Requires** | **Type and Range** | **Description** |
|:------|:-------------|:-------------------|:----------------|
| **user**| true         | string             | User name       |
| **content**| true         | string             | Post content,the length should less than 580  |
| **valid\_code**| false        |  string            | Required if Captcha feature is enabled |

Example
```
<?php
$post_data=array('user'=>'testuser','content'=>'test content');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/mapleleaf/api.php?controller=post&action=create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$data = curl_exec($ch);
var_dump($data);
curl_close($ch);
?>
```

Response

```
{"insert_id":15}
```

#### api.php?controller=user&action=login ####

Supported Formats
> JSON
HTTP Request Method
> GET
Requires Authentication
> true
Request Parameters

|       | **Requires** | **Type and Range** | **Description** |
|:------|:-------------|:-------------------|:----------------|
| **user**| true         | string             | User name       |
| **password**| true         | string             | User's password  |

Example
```
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/mapleleaf/api.php?controller=user&action=login&user=test1&password=test1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
var_dump($data);
curl_close($ch);
?>
```

Response

```
{"user":"test1","uid":"1","session_name":"PHPSESSID","session_value":"6d4blivuthkbvvk8ve06fkfhd2"}
```

#### api.php?controller=user&action=update ####
This part is complex and may be changed in future.

#### api.php?controller=search ####

Supported Formats
> JSON
HTTP Request Method
> GET
Requires Authentication
> false
Request Parameters

|       | **Requires** | **Type and Range** | **Description** |
|:------|:-------------|:-------------------|:----------------|
| **s** | true         | string             | key word        |

Example
```
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/mapleleaf/api.php?controller=search&s=content');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
var_dump($data);
curl_close($ch);
?>
```

Response

```
{"messages":[
{
  "0":"15",
  "id":"15",
  "1":"127.0.0.1",
  "ip":"127.0.0.1",
  "2":null,
  "uid":null,
  "3":"testuser",
  "user":"testuser",
  "4":"test content",
  "post_content":"test content",
  "5":"1296893710",
  "time":"02-05 16:15",
  "6":null,
  "reply_content":"",
  "7":null,
  "reply_time":"01-01 08:00",
  "8":null,
  "b_username":null
}
],
"nums":1
}
```