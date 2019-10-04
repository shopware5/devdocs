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

## Example 1 - Get all customers

In this example, you can see how it's possible to get a list of all customers in a shop and how to limit the result to a fixed number.

{% include 'api_badge.twig' with {'route': '/api/customers', 'method': 'GET'} %}

{% include 'api_badge.twig' with {'route': '/api/customers?limit=20', 'method': 'GET'} %}

### Result

```json
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
            "doubleOptinConfirmDate":"2011-11-23T12:00:00+0100",
            "doubleOptinEmailSentDate":"2011-11-23T00:00:00+0100",
            "doubleOptinRegister":true,
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
            "doubleOptinConfirmDate":null,
            "doubleOptinEmailSentDate":null,
            "doubleOptinRegister":false,
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

## Example 2 - Get a specific customer account

This example shows you how to get a customer account by its id

{% include 'api_badge.twig' with {'route': '/api/customers/1', 'method': 'GET'} %}

### Result
```json
{
    "data":{
        "number": "20001",
        "id":1,
        "paymentId":5,
        "groupKey":"EK",
        "shopId":1,
        "priceGroupId":null,
        "encoderName":"md5",
        "hashPassword":"a256a310bc1e5db755fd392c524028a8",
        "active":true,
        "doubleOptinConfirmDate":null,
        "doubleOptinEmailSentDate":null,
        "doubleOptinRegister":false,
        "email":"test@example.com",
        "salutation": "mr",
        "title": "",
        "firstname": "Max",
        "lastname": "Mustermann",
        "firstLogin":"2011-11-23T00:00:00+0100",
        "lastLogin":"2012-01-04T14:12:05+0100",
        "birthday": null,
        "accountMode":0,
        "confirmationKey":"",
        "sessionId":"uiorqd755gaar8dn89ukp178c7",
        "newsletter":0,
        "validation":"0",
        "affiliate":0,
        "paymentPreset":0,
        "languageId":"1",
        "referer":"",
        "internalComment":"",
        "failedLogins":0,
        "lockedUntil":null,
        "attribute":{
            "id": 1,
            "customerId": 1,
        },
        "defaultBillingAddress": {
            "id": 1,
            "company": "Muster GmbH",
            "department": "",
            "salutation": "mr",
            "firstname": "Max",
            "title": null,
            "lastname": "Mustermann",
            "street": "Musterstr. 55",
            "zipcode": "55555",
            "city": "Musterhausen",
            "phone": "05555 \/ 555555",
            "vatId": "",
            "additionalAddressLine1": null,
            "additionalAddressLine2": null,
            "countryId": 2,
            "stateId": 3,
            "attribute": {
                "id": 1,
                "customerAddressId": 1,
                "text1": null,
                "text2": null,
                "text3": null,
                "text4": null,
                "text5": null,
                "text6": null
            },
            "country": {
                "id": 2,
                "name": "Deutschland",
                "iso": "DE",
                "isoName": "GERMANY",
                "position": 1,
                "description": "",
                "taxFree": 0,
                "taxFreeUstId": 0,
                "taxFreeUstIdChecked": 0,
                "active": true,
                "iso3": "DEU",
                "displayStateInRegistration": false,
                "forceStateInRegistration": false,
                "areaId": 1
            },
            "state": null
        },
        "paymentData":[
        ],
        "defaultShippingAddress": {
            "id": 2,
            "company": "shopware AG",
            "department": "",
            "salutation": "mr",
            "firstname": "Max",
            "title": null,
            "lastname": "Mustermann",
            "street": "Mustermannstra\u00dfe 55",
            "zipcode": "48624",
            "city": "Sch\u00f6ppingen",
            "phone": null,
            "vatId": "",
            "additionalAddressLine1": null,
            "additionalAddressLine2": null,
            "countryId": 2,
            "stateId": null,
            "attribute": {
                "id": 2,
                "customerAddressId": 1,
                "text1": null,
                "text2": null,
                "text3": null,
                "text4": null,
                "text5": null,
                "text6": null
            },
            "country": {
                "id": 2,
                "name": "Deutschland",
                "iso": "DE",
                "isoName": "GERMANY",
                "position": 1,
                "description": "",
                "taxFree": 0,
                "taxFreeUstId": 0,
                "taxFreeUstIdChecked": 0,
                "active": true,
                "iso3": "DEU",
                "displayStateInRegistration": false,
                "forceStateInRegistration": false,
                "areaId": 1
            },
            "state": null
        }
    },
    "success":true
}
```

## Example 3 - Create a customer account

This shows you how to create a minimalistic customer account:

{% include 'api_badge.twig' with {'route': '/api/customers', 'method': 'POST', 'body': true} %}
```json
{
    "email": "meier@mail.de",
    "firstname": "Max",
    "lastname": "Meier",
    "salutation": "mr",
    "billing": {
        "firstname": "Max",
        "lastname": "Meier",
        "salutation": "mr",
        "street": "Musterstrasse 55",
        "city": "Sch\\u00f6ppingen",
        "zipcode": "48624",
        "country": 2
    }
}
```

### Result

```json
{
  "id": 15,
  "location": "https://shop.example.com/api/customers/15"
}
```

## Example 4 - Create customer account with Double-Opt-In confirmation

This examples shows how to add a minimalistic customer using `'doubleOptinRegister' => true` to register him during
Double-Opt-In and `'sendOptinMail' => true` to send him the required E-Mail with a confirmation link to complete his registration:

{% include 'api_badge.twig' with {'route': '/api/customers', 'method': 'POST', 'body': true} %}
```json
{
    "email": "meier@mail.de",
    "firstname": "Max",
    "lastname": "Meier",
    "salutation": "mr",
    "doubleOptinRegister": true,
    "sendOptinMail": true,
    "billing": {
        "firstname": "Max",
        "lastname": "Meier",
        "salutation": "mr",
        "street": "Musterstrasse 55",
        "city": "Sch\\u00f6ppingen",
        "zipcode": "48624",
        "country": 2
    }
}
```

### Result

```json
{
  "id": 16,
  "location": "https://shop.example.com/api/customers/16"
}
```

## Example 5 - Update a specific customer account

{% include 'api_badge.twig' with {'route': '/api/customers/1', 'method': 'PUT', 'body': true} %}
```json
{
  "email": "updated@example.com"
}
```

## Example 6 - Delete a specific customer account

{% include 'api_badge.twig' with {'route': '/api/customers/12', 'method': 'DELETE'} %}
