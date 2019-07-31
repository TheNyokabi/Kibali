## API

To use API you have to have an active Eramba user account allowed to access API through ACL.

You will need to login on each api request using Basic Authorization Header, ie "authorization: Basic ...code...".

All API requests requires SSL connection. SSL is not required while in debug mode.

By default all API responses will be in JSON format. API request holding custom data should have data formatted as `application/x-www-form-urlencoded` or JSON.

## Examples

### List index items

#### HTTP

```
GET /api/security_incidents.json HTTP/1.1
Host: eramba.localhost
Authorization: Basic ...insert_hash...
Cache-Control: no-cache
```

#### cUrl

```
curl -X GET -H "Content-Type: application/json" -H "Accept: application/json" -H "Authorization: Basic ...insert_code..." -H "Cache-Control: no-cache" "http://eramba.localhost/api/security_incidents.json"
```


### Add a new item

#### HTTP

```
POST /api/security_incidents.json HTTP/1.1
Host: eramba.localhost
Content-Type: application/x-www-form-urlencoded
Authorization: Basic ...insert_code...
Cache-Control: no-cache

title=moj+titulok+api&open_date=2016-07-12&closure_date=2016-07-12&user_id=1&security_service_id%5B%5D=5&security_service_id%5B%5D=6
```

#### cUrl

```
curl -X POST -H "Content-Type: application/x-www-form-urlencoded" -H "Authorization: Basic ...insert_code..." -H "Cache-Control: no-cache" -H -d 'title=moj titulok api&open_date=2016-07-12&closure_date=2016-07-12&user_id=1&security_service_id[]=5&security_service_id[]=6' "http://eramba.localhost/api/security_incidents.json"
```

### Edit an item (id 1)

#### HTTP 

```
PUT /api/security_incidents/1.json HTTP/1.1

... the rest of the request looks exactly like adding an item ...
```

### View item (id 1)

#### HTTP

```
GET /api/security_incidents/1.json HTTP/1.1
Host: eramba.localhost
Authorization: Basic ...insert_hash...
Cache-Control: no-cache
```
