---
layout: default
title: REST API - Address Resource
github_link: developers-guide/rest-api/api-resource-address/index.md
shopware_version: 5.2.0
indexed: false
---

## Introduction

In this part of the documentation you can learn more about the API's address resource. With this resource, it is possible to retrieve, update and delete any customer address of your shop. We will also have a look at the associated data structures.

## General Information

This resource supports the following operations:

|  Access URL                 | GET                   | GET (List)            | PUT                    | PUT (Batch)         | POST                 | DELETE                | DELETE (Batch)      |
|-----------------------------|-----------------------|-----------------------|------------------------|---------------------|----------------------|-----------------------|---------------------|
| /api/addresses              | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) |  ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/addresses**

## GET

### Required Parameters
Single address details can be retrieved via the address ID:

* **http://my-shop-url/api/address/id**

### Return Value
| Model                                 | Table            |
|------------------------------------|------------------|
| Shopware\Models\Customer\Address   | s_user_addresses |


| Field                    | Type                  | Original Object                                          |
|--------------------------|-----------------------|----------------------------------------------------------|
| id                       | integer (primary key) |                                                          |
| customer                 | integer (primary key) | **[Customer](../models/#customer)**                      |
| company                  | string                |                                                          |
| department               | string                |                                                          |
| salutation               | string                |                                                          |
| firstname                | string                |                                                          |
| lastname                 | string                |                                                          |
| street                   | string                |                                                          |
| zipcode                  | string                |                                                          |
| city                     | string                |                                                          |
| phone                    | string                |                                                          |
| vatId                    | string                |                                                          |
| additionalAddressLine1   | string                |                                                          |
| additionalAddressLine2   | string                |                                                          |
| country                  | int (foreign key)     | **[Country](../models/#country)**                        |
| state                    | int (foreign key)     |                                                          |
| attribute                | array                 |                                                          |

## GET (List)

### Required Parameters

For this operation, no parameters are required.
To get a list of all addresses, simply query:

* **http://my-shop-url/api/addresses**

### Return Value

| Model                                 | Table            |
|------------------------------------|------------------|
| Shopware\Models\Customer\Address   | s_user_addresses     |


This API call returns an array of elements, one for each address. Each of these elements has the same structure like a single element above.

Appended to the above mentioned list, you will also find the following data:

| Field               | Type                  | Comment                                           |
|---------------------|-----------------------|---------------------------------------------------|
| total               | integer               | The total number of address resources             |
| success             | boolean               | Indicates if the call was successful or not.      |


## POST (create) and PUT (update)
`POST` and `PUT` operations support the following data structure:

| Model                              | Table            |
|------------------------------------|------------------|
| Shopware\Models\Customer\Address   | s_user_addresses |

| Field                    | Type                  | Comment                                              |
|--------------------------|-----------------------|------------------------------------------------------|
| id                       | integer (primary key) | If null, a new entity will be created                |
| company                  | string                |                                                      |
| department               | string                |                                                      |
| salutation (required)    | string                |                                                      |
| firstname (required)     | string                |                                                      |
| lastname (required)      | string                |                                                      |
| street (required)        | string                |                                                      |
| zipcode (required)       | string                |                                                      |
| city (required)          | string                |                                                      |
| phone                    | string                |                                                      |
| vatId                    | string                |                                                      |
| additionalAddressLine1   | string                |                                                      |
| additionalAddressLine2   | string                |                                                      |
| country (required)       | int (foreign key)     | **[Country](../models/#country)**                    |
| state                    | int (foreign key)     |                                                      |
| attribute                | array                 |                                                      |


## DELETE
To delete an address, simply call the specified resource with the `DELETE` operation as the following example shows:

* **(DELETE) http://my-shop-url/api/addresses/id**

Replace the `id` with the specific address id.
