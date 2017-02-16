---
layout: default
title: REST API - Translations Resource
github_link: developers-guide/rest-api/api-resource-translation/index.md
indexed: false
---

## Introduction

In this part of the documentation you can learn more about the API's translation resource. With this resource, it is possible to retrieve, delete and update any translation in your shops. We will also have a look at the associated data structures.

## General Information

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)            | PUT                   | PUT (Batch)            | POST                   | DELETE                | DELETE (Batch)        |
|-----------------------------|--------------------|-----------------------|-----------------------|------------------------|------------------------|-----------------------|-----------------------|
| /api/translations              | ![No](../img/no.png)| ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png)  | ![Yes](../img/yes.png)  | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/translations**

## GET (List)

You can retrieve data of translations by providing the specific id

* **http://my-shop-url/api/translations/id**

### Return Value

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| type                    | string                  |                                                 |
| data                    | array                  |                                                 |
| key                  | integer               |                                                 |
| shopId              | integer (foreign key) | **[Shop](../models/#shop)**                     |
| shop                  | object                  | **[Shop](../models/#shop)**                     |

*Since this returns a list, the following fields will be added to the array:*

| Field               | Type                  | Comment                                            |
|---------------------|-----------------------|-------------------------------------------------|
| total                  | integer                  | The total number of translations                |
| success              | boolean                  | Indicates if the call was successful or not.    |

## POST (create)

To post a translation, you need to identify it by the following parameters

### Required Parameters

| Identifier            | Parameter            | Database Column            | Example Call                                          |
|-----------------------|-------------------|---------------------------|-------------------------------------------------------|
| Translation Id        | id                | `s_core_translations.id`  | /api/translations/2                                    |
| Element number        | -                    | -                            | /api/translations/20003?useNumberAsId=true            |


### Data

You can use this data to add a new translation to the shop

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| locale                | string                  |                                                 |
| language              | string                |                                                 |
| territory                | string                  |                                                 |
| locale              | object                  |                                                 |
| type                    | string                  |                                                 |
| data                    | array                  |                                                 |
| key                  | integer               |                                                 |
| shopId                | integer (foreign key) | **[Shop](../models/#shop)**                     |

You can post or put data by sending the following data to this URL:

* **(POST or PUT) http://my-shop-url/api/translations/id**

| Field               | Type                  | Original Object                                            |
|---------------------|-----------------------|---------------------------------------------------------|
| id                  | integer (primary key) |                                                            |
| name                  | string                  |                                                            |
| position              | integer                  |                                                            |
| comparable          | boolean                  |                                                            |
| sortMode              | integer                  |                                                            |

## DELETE
To delete a shop, simply call this URL with the `DELETE` request:

* **http://my-shop-url/api/translations/id**

Replace the `id` with the specific translation id.

## DELETE (Stack)

In order to delete more than one translation at once, it's possible to provide an array of ids to the REST API.
Simply pass the array of translation ids to the following URL (example)

* **[DELETE] http://my-shop-url/translations/**

without providing an id as seen in the single `DELETE` request. As data provide the array of ids you wish to delete.

## PUT (update) (Stack)

Updating many articles at once requires an array of translation data being provided to the following URL using the `PUT` request (example):

* **[PUT] http://my-shop-url/translations/**

Simply provide the same data as described in the `GET` request.
