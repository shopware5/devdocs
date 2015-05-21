---
layout: default
title: Shopware 5 Rest API - Cache End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-cache/index.md
indexed: true
---

## Introduction

In this part of the documentation you can learn more about the API's cache-resource. With this resource, it is possible to 
receive and delete any cache-data of your shop. Also we will have a look at the provided data.


## General Information
You may find the related resource under
**engine/Shopware/Controllers/Api/Cache.php**.

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/caches                 | ![Yes](./img/yes.png)    | ![Yes](./img/yes.png) | ![No](./img/no.png)   | ![No](./img/no.png)    | ![No](./img/no.png)    | ![Yes](./img/yes.png) | ![Yes](./img/yes.png) |

If you want to access this end-point, simply append your shop-URL with

* **http://my-shop-url/api/caches**

## GET

### Required Parameters
Single cache details can be received via the cache id:

* **http://my-shop-url/api/caches/id**

### Return Value

| Field               | Type                  | Comment			                                |
|---------------------|-----------------------|-------------------------------------------------|
| dir				  | string				  | The path to this cache folder                   |
| size		          | string				  | Including size unit                    			|
| files               | integer               | Amount of files within the cache directory      |
| freeSpace           | string                | Free space, including the size unit             |
| name                | string                | The name of the cache                           |
| backend             | string                | 												|
| id 				  | string				  | The identifier of this cache					|

## GET (List)

### Required Parameters
For this operation no parameters are required.
Simply call

* **http://my-shop-url/api/caches/**
to get a list of all caches.

### Return Value

| Field               | Type                  | Comment			                                |
|---------------------|-----------------------|-------------------------------------------------|
| dir				  | string				  | The path to this cache folder                   |
| size		          | string				  | Including size unit                    			|
| files               | integer               | Amount of files within the cache directory      |
| freeSpace           | string                | Free space, including the size unit             |
| name                | string                | The name of the cache                           |
| backend             | string                | 												|
| id 				  | string				  | The identifier of this cache					|

*Since this returns a list, the following fields will be added to the array:*

| Field               | Type                  | Comment			                                |
|---------------------|-----------------------|-------------------------------------------------|
| total				  | integer				  | The total amount of cache resources             |
| success		      | boolean				  | Indicates if the call was stressful or not.		|

## DELETE
To delete a cache, simply call the specified endpoint with the 'DELETE' operation as the following example shows:

* **(DELETE) http://my-shop-url/api/caches/id**

and don't forget to replace the 'id' with the specific cache id.

## DELETE (Stack)
To delete all caches, simply call

* **(DELETE) http://my-shop-url/api/caches** 

without providing a cache id.

## Examples

TODO