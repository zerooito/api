# API Auth

About auth using API winners

### /auth/login - [POST]

***Request***
```
{
    "email": "email@teste.com",
    "password": "123456"
}
```

***Response***
```
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLmNpYXduLmNvbS5ici9hdXRoL2xvZ2luIiwiaWF0IjoxNTA4Mzc1MzY5LCJleHAiOjE1MDgzNzg5NjksIm5iZiI6MTUwODM3NTM2OSwianRpIjoidWZseEFpN01LdTVLZlU2RyIsInN1YiI6MX0.l69__8rq9ifGkCnkOsUwuSXmG0_Z824kTGTfI_4Qui4",
    "token_type": "bearer"
}
```

***Response 401***
```
{
    "user_not_found"
}
```

***Response 500***
```
{
    "token_expired"
}
```
or
```
{
    "token_absent"
}
```
