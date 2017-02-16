---
layout: default
title: REST API - Customer Groups Resource
github_link: developers-guide/rest-api/api-resource-index/index.md
indexed: false
---

## Introduction

In this part of the documentation you can learn more about the API's customer groups resource. With this resource, it is possible to retrieve, update and delete any customer group data of your shop. We will also have a look at the associated data structures.


## General Information

This resource supports the following operations:

|  Access URL                 | GET                   | GET (List)            | PUT                   | PUT (Batch)         | POST                 | DELETE                | DELETE (Batch)      |
|-----------------------------|-----------------------|-----------------------|-----------------------|---------------------|----------------------|-----------------------|---------------------|
| /api/customerGroups         | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/customerGroups**

## GET

### Required Parameters
Single customer group details can be retrieved via the customer group id:

* **http://my-shop-url/api/customerGroups/id**

### Return Value

| Model                                 | Table                 |
|------------------------------------|-----------------------|
| Shopware\Models\Customer\Group     | s_core_customergroups |

| Field                 | Type                  | Original Object                                                             |
|-----------------------|-----------------------|-----------------------------------------------------------------------------|
| id                    | integer (primary key) |                                                                             |
| key                   | string                |                                                                             |
| name                  | string                |                                                                             |
| tax                   | boolean               |                                                                             |
| taxInput              | boolean               |                                                                             |
| mode                  | boolean               |                                                                             |
| discount              | integer               |                                                                             |
| minimumOrder          | integer               |                                                                             |
| minimumOrderSurcharge | integer               |                                                                             |
| discounts             | array                 | **[Surcharge](../models/#group-surcharge)**                                 |

## GET (List)

### Required Parameters

For this operation, no parameters are required.
To get a list of all customer groups, simply query:

* **http://my-shop-url/api/customerGroups/**

### Return Value
| Model                                 | Table                 |
|------------------------------------|-----------------------|
| Shopware\Models\Customer\Group     | s_core_customergroups |

This API call returns an array of elements, one for each customer group. Each of these elements has the following structure:

| Field                 | Type                  | Original Object                                                             |
|-----------------------|-----------------------|-----------------------------------------------------------------------------|
| id                    | integer (primary key) |                                                                             |
| key                   | string                |                                                                             |
| name                  | string                |                                                                             |
| tax                   | boolean               |                                                                             |
| taxInput              | boolean               |                                                                             |
| mode                  | boolean               |                                                                             |
| discount              | integer               |                                                                             |
| minimumOrder          | integer               |                                                                             |
| minimumOrderSurcharge | integer               |                                                                             |
| discounts             | array                 | **[Surcharge](../models/#group-surcharge)**                                 |

Appended to the above mentioned list, you will also find the following data:

| Field               | Type                  | Comment                                         |
|---------------------|-----------------------|-------------------------------------------------|
| total               | integer               | The total number of customer group resources    |
| success             | boolean               | Indicates if the call was successful or not.    |

## POST and PUT
`POST` and `PUT` operations support the following data structure:

| Model                                 | Table                 |
|------------------------------------|-----------------------|
| Shopware\Models\Customer\Group     | s_core_customergroups |

| Field                 | Type                  | Original Object                                                             |
|-----------------------|-----------------------|-----------------------------------------------------------------------------|
| id                    | integer (primary key) |                                                                               |
| key                   | string                |                                                                               |
| name                  | string                |                                                                             |
| tax                   | boolean               |                                                                               |
| taxInput              | boolean                |                                                                               |
| mode                  | boolean               |                                                                               |
| discount              | integer                |                                                                               |
| minimumOrder          | integer                |                                                                               |
| minimumOrderSurcharge | integer                |                                                                               |
| discounts             | array                 | **[Surcharge](../models/#group-surcharge)**                                    |

## DELETE
To delete a customer group, simply call the specified resource with the `DELETE` operation, as the following example shows:

* **(DELETE) http://my-shop-url/api/customerGroups/id**

Replace the `id` with the specific customer group id.
