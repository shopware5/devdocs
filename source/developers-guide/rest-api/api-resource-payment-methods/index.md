---
layout: default
title: REST API - Payment method resource
github_link: developers-guide/rest-api/api-resource-payment-methods/index.md
shopware_version: 5.5.3
menu_title: Payment method resource
menu_order: 190
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this part of the documentation, you can learn more about the API's payment methods resource.
With this resource, it's possible to retrieve and update any payment method in your shop.
We will also have a look at the associated data structures.

## General Information

This resource supports the following operations:

| Access URL          | GET                    | GET (List)             | PUT                    | PUT (Batch)          | POST                   | DELETE                 | DELETE (Batch)       |
|---------------------|------------------------|------------------------|------------------------|----------------------|------------------------|------------------------|----------------------|
| /api/paymentMethods | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/paymentMethods**

## GET

You can retrieve a payment method by using its id

* **http://my-shop-url/api/paymentMethods/id**

### Return Value

| Field                 | Type                  | Original Object                   |
|-----------------------|-----------------------|-----------------------------------|
| id                    | integer (primary key) |                                   |
| name                  | string                |                                   |
| description           | string                |                                   |
| template              | string                |                                   |
| class                 | string                |                                   |
| hide                  | boolean               |                                   |
| additionalDescription | string                |                                   |
| debitPercent          | float                 |                                   |
| surcharge             | float                 |                                   |
| surchargeString       | string                |                                   |
| position              | integer               |                                   |
| active                | boolean               |                                   |
| esdActive             | boolean               |                                   |
| mobileInactive        | boolean               |                                   |
| embedIFrame           | string                |                                   |
| hideProspect          | integer               |                                   |
| action                | string                |                                   |
| pluginId              | integer               |                                   |
| source                | string                |                                   |
| countries             | array                 | **[Country](../models/#country)** |
| shops                 | array                 |                                   |
| attribute             | array                 |                                   |


## GET (List)

To get more than one payment method at once, simply remove the id parameter from the request URL.

* **http://my-shop-url/api/paymentMethods/**

### Return value

*Since this returns a list, the following fields will be added to the array:*

| Field   | Type    | Comment                                      |
|---------|---------|----------------------------------------------|
| total   | integer | The total number of cache resources          |
| success | boolean | Indicates if the call was successful or not. |

## POST (create) and PUT (update)

You can post or put data by querying the following URL:

* **(POST or PUT) http://my-shop-url/api/paymentMethods/id**

| Field                 | Type                  | Original Object                   |
|-----------------------|-----------------------|-----------------------------------|
| id                    | integer (primary key) |                                   |
| name                  | string                |                                   |
| description           | string                |                                   |
| template              | string                |                                   |
| class                 | string                |                                   |
| hide                  | boolean               |                                   |
| additionalDescription | string                |                                   |
| debitPercent          | float                 |                                   |
| surcharge             | float                 |                                   |
| surchargeString       | string                |                                   |
| position              | integer               |                                   |
| active                | boolean               |                                   |
| esdActive             | boolean               |                                   |
| mobileInactive        | boolean               |                                   |
| embedIFrame           | string                |                                   |
| hideProspect          | integer               |                                   |
| action                | string                |                                   |
| pluginId              | integer               |                                   |
| source                | string                |                                   |
| countries             | array                 | **[Country](../models/#country)** |
| shops                 | array                 |                                   |
| attribute             | array                 |                                   |

## DELETE
To delete a payment method, simply query this URL with a `DELETE` request:

* **http://my-shop-url/api/paymentMethods/id**

Replace the `id` with the specific payment method id.
