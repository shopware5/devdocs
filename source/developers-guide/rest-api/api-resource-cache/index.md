---
layout: default
title: REST API - Cache Resource
github_link: developers-guide/rest-api/api-resource-cache/index.md
indexed: false
---

## Introduction

In this part of the documentation you can learn more about the API's cache resource. With this resource, it is possible to get information about your current cache status, as well as clear its content. We will also have a look at the associated data structures.


## General Information

This resource supports the following operations:

|  Access URL                 | GET                  | GET (List)            | PUT                   | PUT (Batch)            | POST                   | DELETE                | DELETE (Batch)        |
|-----------------------------|----------------------|-----------------------|-----------------------|------------------------|------------------------|-----------------------|-----------------------|
| /api/caches                 | ![Yes](../img/yes.png)| ![Yes](../img/yes.png) | ![No](../img/no.png)   | ![No](../img/no.png)    | ![No](../img/no.png)    | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/caches**

## GET

### Required Parameters

Single cache details can be retrieved by using its id:

* **http://my-shop-url/api/caches/id**

### Return Value

| Field               | Type                  | Comment                                         |
|---------------------|-----------------------|-------------------------------------------------|
| dir                 | string                | The path to this cache directory                |
| size                | string                | Including size unit                             |
| files               | integer               | Amount of files within the cache directory      |
| freeSpace           | string                | Free space, including the size unit             |
| name                | string                | The name of the cache                           |
| backend             | string                |                                                 |
| id                  | string                | The identifier of this cache                    |

## GET (List)

### Required Parameters
For this operation, no parameters are required.
To get a list of all caches, simply query:

* **http://my-shop-url/api/caches/**

### Return Value

This API call returns an array of elements, one for each cache type. Each of these elements has the following structure:

| Field               | Type                  | Comment                                                     |
|---------------------|-----------------------|-------------------------------------------------------------|
| dir                 | string                | The path to this cache directory                            |
| size                | string                | Spaced used by this cache's content, including size unit    |
| files               | integer               | Number of files within the cache directory                  |
| freeSpace           | string                | Free space, including the size unit                         |
| name                | string                | The name of the cache                                       |
| backend             | string                |                                                             |
| id                  | string                | The identifier of this cache                                |

Appended to the above mentioned list, you will also find the following data:

| Field               | Type                  | Comment                                      |
|---------------------|-----------------------|----------------------------------------------|
| total               | integer               | The number of cache resources                |
| success             | boolean               | Indicates if the call was successful or not. |

## DELETE
To delete a cache's content, simply call the specified resource with the `DELETE` operation, as the following example shows:

* **(DELETE) http://my-shop-url/api/caches/id**

Replace the `id` with the specific cache id.

## DELETE (Batch)
To delete all caches, simply call

* **(DELETE) http://my-shop-url/api/caches**

without providing a cache id.
