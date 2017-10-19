# Guide API Orders [V1]

**Fields**

| Field                        | Type          |
| ---------------------------- | ------------- |
| value                        | Decimal       |
| cust                         | Decimal       |
| products                     | Array         |
| products.sku                 | String        |
| products.quantity            | Integer       |
| products.unit_value          | Decimal       |
| products.total               | Decimal       |
| client.firstname             | String        |
| client.lastname              | String        |
| client.document              | String        |
| client.payer_info.name       | String        |
| client.payer_info.neighboohood | String        |
| client.payer_info.street     | String        |
| client.payer_info.zipcode    | String        |
| client.payer_info.number     | String        |
| client.payer_info.state      | String        |
| client.payer_receiver.name   | String        |
| client.payer_receiver.neighboohood | String        |
| client.payer_receiver.street | String        |
| client.payer_receiver.zipcode| String        |
| client.payer_receiver.number | String        |
| client.payer_receiver.state  | String        |

## Headers

 - Content-Type: application/json
 - Authorization: Bearer {token}

### /v1/orders - [POST]

**Body**

```json
{
   "value":9.9,
   "cust": 0,
   "products":[
      {
         "sku":"TESTE1",
         "quantity":1,
         "unit_value":9.9,
         "total":9.9
      }
   ],
   "client":{
      "firstname":"Reginaldo",
      "lastname":"Junior",
      "document":"123.444.342-24",
      "email":"reginaldo@junior.com",
      "payer_info":{
         "name":"Reginaldo Junior",
         "street":"Avenida do Contorno",
         "zipcode":"07252015",
         "number":"19",
         "neighborhood":"Nova Cidade",
         "city":"Guarulhos",
         "state":"SP"
      },
      "receiver_info":{
         "name":"Reginaldo Junior",
         "street":"Avenida Franscisco Matarrazo",
         "zipcode":"05010000",
         "number":"175",
         "neighborhood":"Perdizes",
         "city":"S\u00e3o Paulo",
         "state":"SP"
      }
   }
}
```

**Response**

```json
{
    "value": "9.9000",
    "client_id": "6",
    "cust": "0",
    "id": 5,
    "payer_info": [
        {
            "zipcode": "07252015",
            "street": "Avenida do Contorno",
            "number": "19",
            "neighborhood": "Nova Cidade",
            "city": "Guarulhos",
            "state": "SP"
        }
    ],
    "receiver_info": [
        {
            "zipcode": "05010000",
            "street": "Avenida Franscisco Matarrazo",
            "number": "175",
            "neighborhood": "Perdizes",
            "city": "São Paulo",
            "state": "SP"
        }
    ],
    "products": [
      {
         "sku":"TESTE1",
         "quantity":1,
         "unit_value":9.9,
         "total":9.9
      }
    ]
}
```

### /v1/orders/{id} - [PATCH]

**Body**

```json
{
  "shipments": {
    "track_url": "http://correios.com/BR9341203948",
    "track_code": "BR9341203948",
    "nfe_key": "23480293849",
    "nfe_serie": "1",
    "nfe_number": "239041892834",
    "company": "Correios",
    "service": "Sedex"
  }
}
```

**Response**

```json
{
    "track_url": "http://correios.com/BR9341203948",
    "track_code": "BR9341203948",
    "nfe_key": "23480293849",
    "nfe_serie": "1",
    "nfe_number": "239041892834",
    "company": "Correios",
    "service": "Sedex",
    "created_at": "2017-10-19",
    "updated_at": "2017-10-19",
    "order_id": "5"
}
```

### /v1/products/{id} - [GET]

**Response**

```json
{
    "value": "9.90",
    "cust": "0.00",
    "products": [
        {
            "sku": "TESTE1",
            "quantity": "1",
            "unit_value": "9.90",
            "total": "9.90"
        }
    ],
    "client": {
        "firstname": "Reginaldo",
        "lastname": "Junior",
        "email": "reginaldo@junior.com",
        "payer_info": {
            "zipcode": "07252015",
            "street": "Avenida do Contorno",
            "number": "19",
            "neighborhood": "Nova Cidade",
            "city": "Guarulhos",
            "state": "SP"
        },
        "receiver_info": {
            "zipcode": "05010000",
            "street": "Avenida Franscisco Matarrazo",
            "number": "175",
            "neighborhood": "Perdizes",
            "city": "São Paulo",
            "state": "SP"
        }
    }
}
```

### /v1/products - [GET]

#### Filters

  - sort
  - page
  - per_page (limit 100)

**Response**

```json
{
    "total": "5",
    "per_page": 15,
    "current_page": 1,
    "last_page": 0,
    "next_page_url": "v1/orders?page=2",
    "prev_page_url": "v1/orders?page=1",
    "from": 16,
    "to": 30,
    "data": [
        {
            "order_id": "5",
            "value": "9.9000",
            "cust": "0",
            "date_order": "2017-10-19",
            "is_budget": "0",
            "name": "Reginaldo Junior",
            "document": null,
            "birtday": null,
            "client_id": "6"
        }
    ]
}
```
