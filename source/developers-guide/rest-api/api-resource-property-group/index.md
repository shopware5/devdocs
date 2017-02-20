---
layout: default
title: REST API - Property Groups Resource
github_link: developers-guide/rest-api/api-resource-property-group/index.md
indexed: false
---

## Introduction

In this part of the documentation, you can learn more about the API's property groups resource. With this resource, it's possible to retrieve and update any property group in your shop. We will also have a look at the associated data structures.

## General Information

This resource supports the following operations:

|  Access URL                 | GET                      | GET (List)            | PUT                   | PUT (Batch)         | POST                   | DELETE                | DELETE (Batch)      |
|-----------------------------|--------------------------|-----------------------|-----------------------|---------------------|------------------------|-----------------------|---------------------|
| /api/propertyGroups         | ![Yes](../img/yes.png)    | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png)  | ![Yes](../img/yes.png) | ![No](../img/no.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/propertyGroups**

## GET

You can retrieve a property group by using its id

* **http://my-shop-url/api/propertyGroups/id**

### Return Value

| Field               | Type                  | Original Object                                            |
|---------------------|-----------------------|---------------------------------------------------------|
| id                  | integer (primary key) |                                                            |
| name                  | string                  |                                                            |
| position              | integer                  |                                                            |
| comparable          | boolean                  |                                                            |
| sortMode              | integer                  |                                                            |
| options              | array                  | **[Option](../models/#property-group-option)**            |
| attribute              | array                  | **[Attribute](../models/#property-group-attribute)**        |

## GET (List)

To get more than one property group at once, simply remove the id parameter from the request URL.

* **http://my-shop-url/api/propertyGroups/**

### Return value

*Since this returns a list, the following fields will be added to the array:*

| Field               | Type                  | Comment                                            |
|---------------------|-----------------------|-------------------------------------------------|
| total                  | integer                  | The total number of cache resources             |
| success              | boolean                  | Indicates if the call was successful or not.    |

## POST (create) and PUT (update)

You can post or put data by querying the following URL:

* **(POST or PUT) http://my-shop-url/api/propertyGroups/id**

| Field               | Type                  | Original Object                                            |
|---------------------|-----------------------|---------------------------------------------------------|
| id                  | integer (primary key) |                                                            |
| name                  | string                  |                                                            |
| position              | integer                  |                                                            |
| comparable          | boolean                  |                                                            |
| sortMode              | integer                  |                                                            |

## DELETE
To delete a property group, simply query this URL with a `DELETE` request:

* **http://my-shop-url/api/propertyGroups/id**

Replace the `id` with the specific property group id.
