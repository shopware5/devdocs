---
layout: default
title: Shopware 5 Rest API - Shops End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-shops/index.md
indexed: true
---

## Introduction

In this part of the documentation you can learn more about the API's shops resource. With this resource, it is possible to 
receive, delete and update any shop in your system. Also we will have a look at the provided data.

## General Information
You may find the related resource under
**engine\Shopware\Controllers\Api\Shops.php**.

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/shops		          | ![Yes](./img/yes.png)    | ![Yes](./img/yes.png) | ![Yes](./img/yes.png) | ![No](./img/no.png)    | ![Yes](./img/yes.png)  | ![Yes](./img/yes.png) | ![No](./img/no.png)   |

If you want to access this end-point, simply append your shop-URL with

* **http://my-shop-url/api/shops**

## GET

You can receive data of a shop by providing the specific id

* **http://my-shop-url/api/shops/id**

Simply replace the 'id' with the specific identifier

### Return Value

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| mainId	      	  | integer (foreign key) | **[Shop](./models/shop)**                       |
| categoryId		  | integer (foreign key) | **[Category](./models/category)**				|
| name		      	  | string				  | 		                                        |
| title				  | string				  | 												|
| position			  | integer				  | 												|
| host				  | string				  | 												|
| basePath			  | string				  | 												|
| baseUrl			  | string				  | 												|
| hosts				  | string				  | 												|
| secure			  | boolean				  | 												|
| alwaysSecure		  | boolean				  | 												|
| secureHost		  | string				  | 												|
| secureBasePath	  | string				  | 												|
| default			  | boolean				  | 												|
| active			  | boolean				  | 												|
| customerScope		  | boolean				  | 												|
| currency			  | object				  | **[Currency](./models/currency)**				|

## GET (List)

To get more than one property group at once, simply remove the id parameter from the request URL.

* **http://my-shop-url/api/propertyGroups/**

### Return value

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| mainId	      	  | integer (foreign key) | **[Shop](./models/shop)**                       |
| categoryId		  | integer (foreign key) | **[Category](./models/category)**				|
| name		      	  | string				  | 		                                        |
| title				  | string				  | 												|
| position			  | integer				  | 												|
| host				  | string				  | 												|
| basePath			  | string				  | 												|
| baseUrl			  | string				  | 												|
| hosts				  | string				  | 												|
| secure			  | boolean				  | 												|
| alwaysSecure		  | boolean				  | 												|
| secureHost		  | string				  | 												|
| secureBasePath	  | string				  | 												|
| default			  | boolean				  | 												|
| active			  | boolean				  | 												|
| customerScope		  | boolean				  | 												|

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
To delete a shop, simply call this URL with the DELETE request:

* **http://my-shop-url/api/shops/id**

Simply replace 'id' with the specific identifier.

## Examples

TODO