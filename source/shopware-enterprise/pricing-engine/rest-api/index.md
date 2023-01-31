---
layout: default
title: REST API
github_link: pricing-engine/rest-api/index.md
indexed: true
tags: [pricing engine, rest api]
menu_title: REST API
menu_order: 3
group: Shopware Enterprise
subgroup: Pricing Engine
---

<div class="alert alert-info">
You can also find a fully comprehensive and up-to-date Swagger documentation <a target="_blank" href="https://demo2.enterprise.shopware.com/custom/plugins/SwagEnterprisePricingEngine/swagger.json">here</a>. 
</div>

<div class="toc-list"></div>

## API Structure

In order to maintain your custom pricing lists, the Pricing Engine offers a range of RESTful endpoints. In the following examples is http://10.222.222.30/api our local endpoint for the shopware api. You can find examples written with guzzle in the official <a href="https://gitlab.com/shopware/shopware/enterprise/swagenterprisepricingengine" target="_blank">git repo</a>. If you don't have access yet, please create a ticket via your shopware account. You will receive an invitation soon. 

### Price lists

`/api/PriceList`

* `GET` Get all available price lists

    - `limit`, `offset`
    
```http request
GET http://10.222.222.30/api/PriceList
Authorization: Digest demo demo
Content-Type: application/json

{
    "filters": [
        {
            "field-name": "id",
            "value": 6,
            "type": "eq"
        }
    ]
}
```

* `POST` Create a price list

    - `priceList`

```http request
POST http://10.222.222.30/api/PriceList
Authorization: Digest demo demo
Content-Type: application/json

{
    "name": "Create new PriceList",
    "priority": 0
}
```

* `UPDATE` Update a price list

```http request
PUT http://10.222.222.30/api/PriceList/11
Authorization: Digest demo demo
Content-Type: application/json

{
    "id": 11,
    "name": "Update PriceList",
    "priority": 0
}
```

* `DELETE` Delete a price list

```http request
DELETE http://10.222.222.30/api/PriceList/11
Authorization: Digest demo demo
Content-Type: application/json
```

### Single price list

`/api/PriceList/{id}`

* `GET` Get one price list

    - `id`
    
```http request
GET http://10.222.222.30/api/PriceList/11
Authorization: Digest demo demo
Content-Type: application/json
```

* `PUT` Update one price list

    - `id`, `priceList`
    
```http request
PUT http://10.222.222.30/api/PriceList/11
Authorization: Digest demo demo
Content-Type: application/json

{
    "name": "Update PriceList",
    "priority": 0
}
```

* `DELETE` Delete one price list

  - `id`
  
```http request
DELETE http://10.222.222.30/api/PriceList/11
Authorization: Digest demo demo
Content-Type: application/json
```

### Conditions

`/api/PriceListCondition`

* `GET` Returns all configurable conditions

```http request
GET http://10.222.222.30/api/PriceListCondition
Authorization: Digest demo demo
Content-Type: application/json
```

* `POST` Create one price list condition

    - `priceListCondition`    
    
```http request
POST http://10.222.222.30/api/PriceListCondition
Authorization: Digest demo demo
Content-Type: application/json

{
    "priceListId": 1,
    "type": "SwagEnterprisePricingEngine\\Source\\PriceListCondition\\Conditions\\CurrencyCondition",
    "value": "EUR"

}
```

* `UPDATE` Create one price list condition

    - `priceListCondition`    
    
```http request
PUT http://10.222.222.30/api/PriceListCondition/16
Authorization: Digest demo demo
Content-Type: application/json

{
    "priceListId": 1,
    "type": "SwagEnterprisePricingEngine\\Source\\PriceListCondition\\Conditions\\CurrencyCondition",
    "value": "EUR"

}
```

* `DELETE` Delete

### Single condition

`/api/PriceListCondition/{id}`

* `GET` Get one price list condition

    - `id`

```http request
GET http://10.222.222.30/api/PriceListCondition/1337
Authorization: Digest demo demo
Content-Type: application/json
```


### Prices in all lists

`/api/PriceListPrices`

* `GET` Get all available price list prices

    - `limit`, `offset`
    
```http request
GET http://10.222.222.30/api/PriceListPrices?limit=10
Authorization: Digest demo demo
Content-Type: application/json

{
    "filters": [
        {
            "field-name": "id",
            "value": 6,
            "type": "eq"
        }
    ]
}
```

* `POST` Create one or more price list prices

    - `priceListPrices`
    
```http request
POST http://10.222.222.30/api/PriceListPrices
Authorization: Digest demo demo
Content-Type: application/json

{
    "prices": [
        {
            "priceListId": 1,
            "orderNumber": "SW10010",
            "price": 3.78151260504201,
            "pseudoPrice": 5.8823529411764595,
            "from": 1,
            "to": 1,
            "validFrom": null,
            "validTo": null,
            "calculation": null,
            "gross": false
        }
    ]
}
```

* `PUT` Update one or more price list prices

    - `priceListPrices`

```http request
PUT http://10.222.222.30/api/PriceListPrices
Authorization: Digest demo demo
Content-Type: application/json

{
    "prices": [
        {
            "id": 1867,
            "priceListId": 1,
            "orderNumber": "SW10010",
            "price": 3.78151260504201,
            "pseudoPrice": 5.8823529411764595,
            "from": 1,
            "to": 1,
            "validFrom": null,
            "validTo": null,
            "calculation": null,
            "gross": true
        }
    ]
}
```

* `DELETE` Remove an amount of price list prices

    - `priceListPrices`
    
```http request
DELETE http://10.222.222.30/api/PriceListPrices
Authorization: Digest demo demo
Content-Type: application/json

{
    "prices": [
        {
            "id": 1867
        }
    ]
}
```

### Prices in a single list

`/api/PriceListPrices/{id}`

* `GET` get one price list prices
    
    - `id`
    
```http request
GET http://10.222.222.30/api/PriceListPrices/1867
Authorization: Digest demo demo
Content-Type: application/json
```
