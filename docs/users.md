# API Users

About endpoint users API Winners

## Create User

### /v1/users - [POST]

***Request***
```
{
    "name": "Reginaldo Junior",
    "email": "email@teste.com",
    "phone_area": "11",
    "phone": "12345678",
    "password": "123456",
    "confirm_password": "123456"
}
```

***Response***
```
{
    "id": "1",
    "name": "Reginaldo Junior",
    "email": "email@teste.com",
    "phone": "11 12345678",
}
```


## Get User

## Headers

 - Content-Type: application/json
 - Authorization: Bearer {token}

### /v1/users - [GET]

***Response***
```
{
    "name": "Reginaldo Junior",
    "email": "email@teste.com",
    "phone": "11 12345678",
    "access_token": "11 12345678",
}
```