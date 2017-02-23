---
layout: default
title: REST API - Shops Resource
github_link: developers-guide/rest-api/api-resource-shops/index.md
indexed: false
---

## Introduction

In this part of the documentation you can learn more about the API's shops resource. With this resource, it is possible to retrieve, delete and update any shop in your system. We will also have a look at the associated data structures.

## General Information

This resource supports the following operations:

|  Access URL                 | GET                   | GET (List)            | PUT                   | PUT (Batch)         | POST                  | DELETE                | DELETE (Batch)      |
|-----------------------------|-----------------------|-----------------------|-----------------------|---------------------|-----------------------|-----------------------|---------------------|
| /api/shops                  | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/shops**

## GET

You can retrieve data of a shop by providing the specific id

* **http://my-shop-url/api/shops/id**


### Return Value

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| mainId                | integer (foreign key) | **[Shop](../models/#shop)**                      |
| categoryId          | integer (foreign key) | **[Category](../models/#category)**                |
| name                    | string                  |                                                 |
| title                  | string                  |                                                 |
| position              | integer                  |                                                 |
| host                  | string                  |                                                 |
| basePath              | string                  |                                                 |
| baseUrl              | string                  |                                                 |
| hosts                  | string                  |                                                 |
| secure              | boolean                  |                                                 |
| alwaysSecure          | boolean                  |                                                 |
| secureHost          | string                  |                                                 |
| secureBasePath      | string                  |                                                 |
| default              | boolean                  |                                                 |
| active              | boolean                  |                                                 |
| customerScope          | boolean                  |                                                 |
| currency              | object                  | **[Currency](../models/#currency)**                |

## GET (List)

To get more than one shop at once, simply remove the id parameter from the request URL.

* **http://my-shop-url/api/shops/**

### Return value

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| mainId                | integer (foreign key) | **[Shop](../models/#shop)**                      |
| categoryId          | integer (foreign key) | **[Category](../models/#category)**                |
| name                    | string                  |                                                 |
| title                  | string                  |                                                 |
| position              | integer                  |                                                 |
| host                  | string                  |                                                 |
| basePath              | string                  |                                                 |
| baseUrl              | string                  |                                                 |
| hosts                  | string                  |                                                 |
| secure              | boolean                  |                                                 |
| alwaysSecure          | boolean                  |                                                 |
| secureHost          | string                  |                                                 |
| secureBasePath      | string                  |                                                 |
| default              | boolean                  |                                                 |
| active              | boolean                  |                                                 |
| customerScope          | boolean                  |                                                 |

*Since this returns a list, the following fields will be added to the array:*

| Field               | Type                  | Comment                                            |
|---------------------|-----------------------|-------------------------------------------------|
| total                  | integer                  | The total number of shop resources              |
| success              | boolean                  | Indicates if the call was successful or not.    |

## POST (create) and PUT (update)
You can post or put data by sending the following data to this URL:

* **(POST or PUT) http://my-shop-url/api/shops/id**

| Field               | Type                  | Original Object                                            |
|---------------------|-----------------------|---------------------------------------------------------|
| name                  | string                  |                                                            |
| categoryId              | integer                  |                                                            |
| localeId          | integer                  |                                                            |
| currencyId              | integer                  |                                                            |
| customerGroupId              | integer                  |                                                            |

## DELETE
To delete a shop, simply call this URL with the DELETE request:

* **http://my-shop-url/api/shops/id**

Replace the `id` with the specific shop id.
