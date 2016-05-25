---
layout: default
title: REST API - Countries Resource
github_link: developers-guide/rest-api/api-resource-countries/index.md
shopware_version: 5.2
indexed: false
---

## Introduction

In this part of the documentation you can learn more about the API's countries resource. With this resource, it is possible to retrieve, update and delete any country of your shop. We will also have a look at the associated data structures.

## General Information

This resource supports the following operations:

|  Access URL                 | GET                   | GET (List)            | PUT                   | PUT (Batch)         | POST                 | DELETE                | DELETE (Batch)      |
|-----------------------------|-----------------------|-----------------------|-----------------------|---------------------|----------------------|-----------------------|---------------------|
| /api/countries              | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) |

If you want to access this resource, simply append your shop URL with

* **http://my-shop-url/api/countries**

## GET

#### Required Parameters

It is required to parametrize this API call. The following parameters are available:

| Identifier      | Parameter | DB column                    | Example call                              |
|-----------------|-----------|------------------------------|-------------------------------------------|
| Country Id     | id        | s_core_countries.id           | /api/countries/2                          

### Return Value

| Model                                 | Table                 |
|------------------------------------|-----------------------|
| Shopware\Models\Country\Country | s_core_countries         |

| Field                 | Type                  | Original Object                                                               |
|-----------------------|-----------------------|-------------------------------------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| name                  | string                |                                                 |
| iso                   | string                |                                                 |
| isoName               | string                |                                                 |
| position              | integer               |                                                 |
| description           | string                |                                                 |
| shippingFree          | boolean               |                                                 |
| taxFree               | boolean               |                                                 |
| taxFreeUstId          | boolean               |                                                 |
| taxFreeUstIdChecked   | boolean               |                                                 |
| active                | boolean               |                                                 |
| iso3                  | string                |                                                 |
| displayStateInRegistration | boolean          |                                                 |
| forceStateInRegistration | boolean            |                                                 |
| areaId                | integer (foreign key) | **[Area](../models/#area)**                     |
| states                | object array          | **[State](../models/#state)**                   |

## GET (List)

### Required Parameters
For this operation no parameters are required.
To get a list of all countries, simply query:

* **http://my-shop-url/api/countries/**

### Return Value
| Model                                 | Table                 |
|------------------------------------|-----------------------|
| Shopware\Models\Country\Country    | s_core_countries         |

This API call returns an array of elements, one for each country. Each of these elements has the following structure:

| Field                 | Type                  | Original Object                                                               |
|-----------------------|-----------------------|-------------------------------------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| name                  | string                |                                                 |
| iso                   | string                |                                                 |
| isoName               | string                |                                                 |
| position              | integer               |                                                 |
| description           | string                |                                                 |
| shippingFree          | boolean               |                                                 |
| taxFree               | boolean               |                                                 |
| taxFreeUstId          | boolean               |                                                 |
| taxFreeUstIdChecked   | boolean               |                                                 |
| active                | boolean               |                                                 |
| iso3                  | string                |                                                 |
| displayStateInRegistration | boolean          |                                                 |
| forceStateInRegistration | boolean            |                                                 |
| areaId                | integer (foreign key) | **[Area](../models/#area)**                     |
| states                | object array          | **[State](../models/#state)**                   |                                                                            |

Appended to the above mentioned list, you will also find the following data:

| Field               | Type                  | Comment                                            |
|---------------------|-----------------------|-------------------------------------------------|
| total               | integer               | The total number of country resources           |
| success             | boolean               | Indicates if the call was successful or not.    |

## POST (create) and PUT (update)

To `POST` or `PUT` content, use the same data as provided in the GET operation.

## DELETE

To delete a country, simply call the specified resource with the `DELETE` operation, as the following example shows:

* **(DELETE) http://my-shop-url/api/countries/id**

Replace the `id` with the specific country id.
