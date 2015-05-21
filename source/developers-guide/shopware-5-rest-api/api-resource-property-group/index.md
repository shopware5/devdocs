---
layout: default
title: Shopware 5 Rest API - Orders End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-property-group/index.md
indexed: true
---

## Introduction

In this part of the documentation you can learn more about the API's PropertyGroups resource. With this resource, it is possible to 
receive, and update any order in your shop. Also we will have a look at the provided data.

## General Information
You may find the related resource under
**engine\Shopware\Controllers\Api\PropertyGroups.php**.

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/propertyGroups         | ![Yes](./img/yes.png)    | ![Yes](./img/yes.png) | ![Yes](./img/yes.png) | ![No](./img/no.png)    | ![Yes](./img/yes.png)  | ![Yes](./img/yes.png) | ![No](./img/no.png)   |

If you want to access this end-point, simply append your shop-URL with

* **http://my-shop-url/api/propertyGroups**

## GET

You can receive a property group by providing the specific id

* **http://my-shop-url/api/propertyGroups/id**

Simply replace the 'id' with the specific identifier

### Return Value

| Field               | Type                  | Original Object			                                |
|---------------------|-----------------------|---------------------------------------------------------|
| id				  | integer (primary key) |															|
| name				  | string				  |															|
| position			  | integer				  |															|
| comparable		  | boolean				  |															|
| sortMode			  | integer				  |															|
| options			  | array				  | **[Option](./models/property-group-option)**			|
| attribute			  | array				  | **[Attribute](./models/property-group.attribute)**		|

## GET (List)

To get more than one property group at once, simply remove the id parameter from the request URL.

* **http://my-shop-url/api/propertyGroups/**

### Return value

*Since this returns a list, the following fields will be added to the array:*

| Field               | Type                  | Comment			                                |
|---------------------|-----------------------|-------------------------------------------------|
| total				  | integer				  | The total amount of cache resources             |
| success		      | boolean				  | Indicates if the call was stressful or not.		|

## POST and PUT
You can post or put data by sending the following data to this URL:

* **(POST or PUT) http://my-shop-url/api/propertyGroups/id**

| Field               | Type                  | Original Object			                                |
|---------------------|-----------------------|---------------------------------------------------------|
| id				  | integer (primary key) |															|
| name				  | string				  |															|
| position			  | integer				  |															|
| comparable		  | boolean				  |															|
| sortMode			  | integer				  |															|

## DELETE
To delete a property group, simply call this URL with the DELETE request:

* **http://my-shop-url/api/propertyGroups/id**

Simply replace 'id' with the specific identifier.

## Examples

TODO