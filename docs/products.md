# Guide API Products [V1]

## /v1/products - [POST]

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

**Fields**

| First Header  | Second Header |
| ------------- | ------------- |
| Content Cell  | Content Cell  |
| Content Cell  | Content Cell  |
