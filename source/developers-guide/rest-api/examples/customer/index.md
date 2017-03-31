---
layout: default
title: REST API - Examples using the customer resource
github_link: developers-guide/rest-api/examples/customer/index.md
menu_title: The customer resource
menu_order: 30
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

<div class="toc-list"></div>

## Introduction

In this article, you will find examples of the customer resource usage for different operations. For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[customer API resource](/developers-guide/rest-api/api-resource-customer)** if you haven't yet, to get more information about the customer resource and the data it provides.

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.

## Example 1 - Get all customers

In this example, you can see how it's possible to get a list of all customers in a shop and how to limit the result to a fixed number.

```
$client->get('customers');
$client->get('customers?limit=20');

```

### Result

```
{
    "data":[
        {
            "id":1,
            "paymentId":5,
            "groupKey":"EK",
            "shopId":1,
            "priceGroupId":null,
            "encoderName":"md5",
            "hashPassword":"a256a310bc1e5db755fd392c524028a8",
            "active":true,
            "email":"test@example.com",
            "salutation": "mr",
            "firstname": "Max",
            "lastname": "Mustermann",
            "firstLogin":"2011-11-23T00:00:00+0100",
            "lastLogin":"2012-01-04T14:12:05+0100",
            "accountMode":0,
            "confirmationKey":"",
            "sessionId":"uiorqd755gaar8dn89ukp178c7",
            "newsletter":0,
            "validation":"",
            "affiliate":0,
            "paymentPreset":0,
            "languageId":"1",
            "referer":"",
            "internalComment":"",
            "failedLogins":0,
            "lockedUntil":null
        },
        {
            "id":2,
            "paymentId":4,
            "groupKey":"H",
            "shopId":1,
            "priceGroupId":null,
            "encoderName":"md5",
            "hashPassword":"352db51c3ff06159d380d3d9935ec814",
            "active":true,
            "email":"mustermann@b2b.com",
            "salutation": "mr",
            "firstname": "Max",
            "lastname": "Mustermann",
            "firstLogin":"2012-08-30T00:00:00+0200",
            "lastLogin":"2012-08-30T11:43:17+0200",
            "accountMode":0,
            "confirmationKey":"",
            "sessionId":"66e9b10064a19b1fcf6eb9310c0753866c764836",
            "newsletter":0,
            "validation":"0",
            "affiliate":0,
            "paymentPreset":4,
            "languageId":"1",
            "referer":"",
            "internalComment":"",
            "failedLogins":0,
            "lockedUntil":null
        }
    ],
    "total":2,
    "success":true
}

```

## Example 2 - Get a specific customer

This example shows you how to get a customer by its id

```
$client->get('customers/1');
```

### Result
```
{
    "data":{
        "id":1,
        "paymentId":5,
        "groupKey":"EK",
        "shopId":1,
        "priceGroupId":null,
        "encoderName":"md5",
        "hashPassword":"a256a310bc1e5db755fd392c524028a8",
        "active":true,
        "email":"test@example.com",
        "salutation": "mr",
        "firstname": "Max",
        "lastname": "Mustermann",
        "firstLogin":"2011-11-23T00:00:00+0100",
        "lastLogin":"2012-01-04T14:12:05+0100",
        "accountMode":0,
        "confirmationKey":"",
        "sessionId":"uiorqd755gaar8dn89ukp178c7",
        "newsletter":0,
        "validation":"",
        "affiliate":0,
        "paymentPreset":0,
        "languageId":"1",
        "referer":"",
        "internalComment":"",
        "failedLogins":0,
        "lockedUntil":null,
        "attribute":null,
        "billing":{
            "id":1,
            "customerId":1,
            "country":2,
            "state":3,
            "company":"Muster GmbH",
            "department":"",
            "salutation":"mr",
            "number":"20001",
            "firstname":"Max",
            "lastname":"Mustermann",
            "street":"Musterstr. 55",
            "zipCode":"55555",
            "city":"Musterhausen",
            "phone":"05555 \/ 555555",
            "fax":"",
            "vatId":"",
            "birthday":null,
            "attribute":null
        },
        "paymentData":[

        ],
        "shipping":{
            "id":2,
            "customerId":1,
            "company":"shopware AG",
            "department":"",
            "salutation":"mr",
            "firstname":"Max",
            "lastname":"Mustermann",
            "street":"Mustermannstra\u00dfe 55",
            "zipCode":"48624",
            "city":"Sch\u00f6ppingen",
            "state":null,
            "country":2,
            "attribute":null
        },
        "debit":{
            "id":2,
            "customerId":1,
            "account":"1234566",
            "bankCode":"6654321",
            "bankName":"Bank",
            "accountHolder":"Owner"
        }
    },
    "success":true
}
```

## Example 3 - Create customer

This shows you how to create a minimalistic customer:

```
$client->post('customers',  array(
    'email' => 'meier@mail.de',
    'firstname' => 'Max',
    'lastname' => 'Meier',
    'salutation' => 'mr',
    'billing' => array(
        'firstname' => 'Max',
        'lastname' => 'Meier',
        'salutation' => 'mr',
        'street' => 'Musterstrasse 55',
        'city' => 'Sch\u00f6ppingen',
        'zipcode' => '48624',
        'country' => 2
    )
));
```

### Result

If the customer was created successfully this will be returned:

```
Array
(
    [id] => 15
    [location] => http://www.ihredomain.de/api/customers/15
)
```

## Example 4 - Update specific customer

It's possible to update a customer by passing its identifier and the new fields.

```
$client->put('customers/1', array(
    'email' => 'new@mail.de'
));

```

## Example 5 - Delete a specific customer

```
$client->delete('customers/1');

```
