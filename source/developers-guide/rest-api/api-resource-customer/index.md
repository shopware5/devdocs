---
layout: default
title: REST API - Customer Resource
github_link: developers-guide/rest-api/api-resource-customer/index.md
indexed: false
---

## Introduction

In this part of the documentation you can learn more about the API's customer resource. With this resource, it is possible to retrieve, update and delete any customer of your shop. We will also have a look at the associated data structures.

## General Information

This resource supports the following operations:

|  Access URL                 | GET                   | GET (List)            | PUT                   | PUT (Batch)         | POST                 | DELETE                | DELETE (Batch)      |
|-----------------------------|-----------------------|-----------------------|-----------------------|---------------------|----------------------|-----------------------|---------------------|
| /api/customers              | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) |

If you want to access this resource, simply append your shop URL with

* **http://my-shop-url/api/customers**

## GET

#### Required Parameters

It is required to parametrize this API call. The following parameters are available:

| Identifier      | Parameter | DB column                    | Example call                              |
|-----------------|-----------|------------------------------|-------------------------------------------|
| Customer Id     | id        | s_user.id                    | /api/customers/2                          |
| Customer number | number    | s_user_billingaddress.number | /api/customers/20003?useNumberAsId=true   |

* **useNumberAsId=true** - This tells the API to query the customer's data by its number, instead of its actual identifier. Otherwise, the syntax is just **/api/customers/id**. It's not possible to provide both parameters at the same time.

### Return Value

| Model                                 | Table                 |
|------------------------------------|-----------------------|
| Shopware\Models\Customer\Customer  | s_user                |

| Field                 | Type                  | Original Object                                                               |
|-----------------------|-----------------------|-------------------------------------------------------------------------------|
| id                    | integer (primary key) |                                                                                 |
| paymentId             | integer (foreign key) | **[Payment](../models/#payment-data)**                                         |
| groupKey              | string (foreign key)  | **[CustomerGroup](../models/#customer-group)**                                    |
| shopId                | string (foreign key)  | **[Shop](../models/#shop)**                                                     |
| priceGroupId          | integer (foreign key) | **[PriceGroup](../models/#price-group)**                                          |
| encoderName           | string                |                                                                                 |
| hashPassword          | string                |                                                                                 |
| active                | boolean                |                                                                                |
| email                 | string                |                                                                                |
| firstLogin            | date/time                |                                                                                |
| lastLogin             | date/time                |                                                                                |
| accountMode           | integer                |                                                                                |
| confirmationKey       | string                |                                                                                |
| sessionId             | string                |                                                                                |
| newsletter            | boolean                |                                                                                |
| validation            | string                |                                                                                |
| affiliate             | boolean                |                                                                                |
| paymentPreset         | integer                |                                                                                |
| languageId            | integer (foreign key) |                                                                                |
| referer               | string                |                                                                                |
| internalComment       | string                |                                                                                |
| failedLogins          | integer                |                                                                                |
| lockedUntil           | date/time                |                                                                                |
| attribute             | object                | **[CustomerAttribute](../models/#customer-attribute)**                            |
| billing               | object                | **[Billing](../models/#billing)**                                                |
| paymentData           | array                    | **[PaymentData](../models/#payment-data)**                                        |
| shipping              | object                | **[Shipping](../models/#shipping)**                                            |
| debit                 | object                | **[Debit](../models/#debit)**                                                    |

## GET (List)

### Required Parameters
For this operation no parameters are required.
To get a list of all customers, simply query:

* **http://my-shop-url/api/customers/**

### Return Value
| Model                                 | Table                 |
|------------------------------------|-----------------------|
| Shopware\Models\Customer\Customer  | s_user                 |

This API call returns an array of elements, one for each customer. Each of these elements has the following structure:

| Field                 | Type                  | Original Object                                                               |
|-----------------------|-----------------------|-------------------------------------------------------------------------------|
| id                    | integer (primary key) |                                                                                 |
| paymentId                | integer (foreign key) | **[Payment](../models/#payment-instance)**                                     |
| groupKey                | string (foreign key)  | **[CustomerGroup](../models/#customer-group)**                                    |
| shopId                | string (foreign key)  | **[Shop](../models/#shop)**                                                     |
| priceGroupId            | integer (foreign key) | **[PriceGroup](../models/#price-group)**                                          |
| encoderName            | string                |                                                                                 |
| hashPassword            | string                |                                                                                 |
| active                | boolean                |                                                                                |
| email                    | string                |                                                                                |
| firstLogin            | date/time                |                                                                                |
| lastLogin                | date/time                |                                                                                |
| accountMode            | integer                |                                                                                |
| confirmationKey        | string                |                                                                                |
| sessionId                | string                |                                                                                |
| newsletter            | boolean                |                                                                                |
| validation            | string                |                                                                                |
| affiliate                | boolean                |                                                                                |
| paymentPreset            | integer                |                                                                                |
| languageId            | integer (foreign key) |                                                                                |
| referer                | string                |                                                                                |
| internalComment        | string                |                                                                                |
| failedLogins            | integer                |                                                                                |
| lockedUntil            | date/time                |                                                                                |

Appended to the above mentioned list, you will also find the following data:

| Field               | Type                  | Comment                                            |
|---------------------|-----------------------|-------------------------------------------------|
| total                  | integer                  | The total number of category resources          |
| success              | boolean                  | Indicates if the call was successful or not.    |

## POST (create) and PUT (update)

To `POST` or `PUT` content, use the same data as provided in the GET operation.

## DELETE

To delete a customer's data, simply call the specified resource with the `DELETE` operation, as the following example shows:

* **(DELETE) http://my-shop-url/api/customers/id**

Replace the `id` with the specific customer id.
