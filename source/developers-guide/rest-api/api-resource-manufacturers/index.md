---
layout: default
title: REST API - Manufacturers Resource
github_link: developers-guide/rest-api/api-resource-manufacturers/index.md
shopware_version: 5.1.3
indexed: false
---

## Introduction

In this part of the documentation you can learn more about the API's manufacturers resource. With this resource, it is possible to retrieve, update and delete any manufacturer data of your shop. We will also have a look at the associated data structures.


## General Information

This resource supports the following operations:

|  Access URL                 | GET                   | GET (List)            | PUT                    | PUT (Batch)         | POST                 | DELETE                | DELETE (Batch)      |
|-----------------------------|-----------------------|-----------------------|------------------------|---------------------|----------------------|-----------------------|---------------------|
| /api/manufacturers             | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) |  ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/manufacturers**

## GET

### Required Parameters
Single manufacturer details can be retrieved via the manufacturer ID:

* **http://my-shop-url/api/manufacturers/id**

### Return Value
| Model                                 | Table            |
|------------------------------------|------------------|
| Shopware\Models\Article\Supplier  | s_articles_supplier     |


| Field               | Type                  | Original Object                                                               |
|---------------------|-----------------------|-------------------------------------------------------------------------------|
| id                  | integer (primary key) |                                                                               |
| name                | string                |                                                                                 |
| image              | string (foreign key, path)                  | **[Media](../models/#media)**                          |
| link              | string                  |                                                                               |
| description              | string                  |                                                                               |
| metaTitle                | string                |                                                                                 |
| metaKeywords                | string                |                                                                                 |
| metaDescription                | string                |                                                                                 |
| attribute       | array               |                                                                               |

## GET (List)

### Required Parameters

For this operation, no parameters are required.
To get a list of all manufacturers, simply query:

* **http://my-shop-url/api/manufacturers**

### Return Value

| Model                                 | Table            |
|------------------------------------|------------------|
| Shopware\Models\Article\Supplier  | s_articles_supplier     |


This API call returns an array of elements, one for each manufacturer. Each of these elements has the following structure:


| Field               | Type                  | Original Object                                                               |
|---------------------|-----------------------|-------------------------------------------------------------------------------|
| id                  | integer (primary key) |                                                                               |
| name                | string                |                                                                                 |
| image               | string                |                                                                                 |
| link                | string                |                                                                                 |
| description         | string                |                                                                                 |
| metaTitle                | string                |                                                                                 |
| metaKeywords                | string                |                                                                                 |
| metaDescription                | string                |                                                                                 |

Appended to the above mentioned list, you will also find the following data:

| Field               | Type                  | Comment                                            |
|---------------------|-----------------------|-------------------------------------------------|
| total                  | integer                  | The total number of manufacturer resources          |
| success              | boolean                  | Indicates if the call was successful or not.    |


## POST (create) and PUT (update)
`POST` and `PUT` operations support the following data structure:

| Model                                 | Table            |
|------------------------------------|------------------|
| Shopware\Models\Article\Supplier  | s_articles_supplier     |

| Field               | Type                  | Comment                                              | Original Object / Database Column                                             |
|---------------------|-----------------------|------------------------------------------------------|-------------------------------------------------------------------------------|
| name (required)     | string                  |                                                      |                                                                                |
| id                   | integer (primary key) | If null, a new entity will be created                 | `s_articles_supplier.id`                                                                  |
| image                | array                  | Array with either `mediaId` or `link` property |                                                                                  |
| link          | string                  |                                                      |                                                                                  |
| description              | string                  |                                                      |                                                                                  |
| metaTitle                | string                |                                                                                 | |
| metaKeywords                | string                |                                                                                 | |
| metaDescription                | string                |                                                                                 | |
| changed             | date/time              |                                                      |                                                                                  |
| attribute           | array                  | Array with optional indexes from 1-6 and its values |                                                                                  |


## DELETE
To delete a cache, simply call the specified resource with the `DELETE` operation as the following example shows:

* **(DELETE) http://my-shop-url/api/manufacturers/id**

Replace the `id` with the specific manufacturer id.
