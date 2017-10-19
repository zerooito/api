# Guide API Products [V1]

**Fields**

| Field             | Type          |
| ----------------- | ------------- |
| name              | String        |
| sku               | String        |
| stock             | Integer       |
| price             | Decimal       |
| variations        | Array         |
| variations.name   | String        |
| variations.sku    | String        |
| variations.stock  | Integer       |
| variations.price  | Decimal       |

## Headers

 - Content-Type: application/json
 - Authorization: Bearer {token}

### /v1/products - [POST]

**Body**

```json
{
  "name": "Name of product",
  "sku": "SKUOFPRODUCT",
  "stock": 1,
  "price": 9.99,
  "variations": [
    {
      "name": "Name variation",
      "price": 9.99,
      "stock": 2,
      "sku": "SKUVARIATION"
    }
  ]
}
```

### /v1/products/{sku} - [PATCH]

**Body**

```json
{
  "name": "Name of product",
  "sku": "SKUOFPRODUCT",
  "stock": 1,
  "price": 9.99,
  "variations": [
    {
      "name": "Name variation",
      "price": 9.99,
      "stock": 2,
      "sku": "SKUVARIATION"
    }
  ]
}
```

**Response**

```json
{
  "name": "Name of product",
  "sku": "SKUOFPRODUCT",
  "stock": 1,
  "price": 9.99,
  "variations": [
    {
      "name": "Name variation",
      "price": 9.99,
      "stock": 2,
      "sku": "SKUVARIATION"
    }
  ]
}
```

### /v1/products/{sku} - [GET]

**Response**

```json
{
  "name": "Name of product",
  "sku": "SKUOFPRODUCT",
  "stock": 1,
  "price": 9.99,
  "variations": [
    {
      "name": "Name variation",
      "price": 9.99,
      "stock": 2,
      "sku": "SKUVARIATION"
    }
  ]
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
    "total": "1",
    "per_page": 15,
    "current_page": 1,
    "last_page": 0,
    "next_page_url": "v1/products?page=2",
    "prev_page_url": "v1/products?page=1",
    "from": 16,
    "to": 30,
    "data": [
        {
            "id": "1",
            "sku": "TESTE1",
            "name": "Produto teste",
            "price": "9.9000",
            "stock": "6"
        }
    ]
}
```
