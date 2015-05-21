---
layout: default
title: Shopware 5 Rest API - Translations End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-translation/index.md
indexed: true
---

## Introduction

In this part of the documentation you can learn more about the API's translation resource. With this resource, it is possible to 
receive, delete and update any translation in your shops. Also we will have a look at the provided data.

## General Information
You may find the related resource under
**engine\Shopware\Controllers\Api\Shops.php**.

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/translations	          | ![No](./img/no.png)     | ![Yes](./img/yes.png) | ![Yes](./img/yes.png) | ![Yes](./img/yes.png)  | ![Yes](./img/yes.png)  | ![Yes](./img/yes.png) | ![Yes](./img/yes.png) |

If you want to access this end-point, simply append your shop-URL with

* **http://my-shop-url/api/translations**

## GET (List)

You can receive data of translations by providing the specific id

* **http://my-shop-url/api/translations/id**

Simply replace the 'id' with the specific identifier

### Return Value

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| type 	         	  | string				  |                                                 |
| data		      	  | array				  | 		                                        |
| key				  | integer 			  | 												|
| localeId	      	  | integer (foreign key) | **[Locale](./models/locale)**                    |
| locale			  | object				  | **[Locale](./models/locale)**					|

*Since this returns a list, the following fields will be added to the array:*

| Field               | Type                  | Comment			                                |
|---------------------|-----------------------|-------------------------------------------------|
| total				  | integer				  | The total amount of cache resources             |
| success		      | boolean				  | Indicates if the call was stressful or not.		|

## POST
To post a translation, you need to identify it by the following parameters

### Required Parameters

| Identifier			| Parameter			| Database Column			| Example Call                                          |
|-----------------------|-------------------|---------------------------|-------------------------------------------------------|
| Translation Id		| id				| `s_core_translations.id`  | /api/translations/2									|
| Element number		| -					| -							| /api/translations/20003?useNumberAsId=true			|


### Data

You can use this data to add a new translation to the shop

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| locale	      	  | string				  | 		                                        |
| language			  | string  			  | 												|
| territory	      	  | string				  |                                     			|
| locale			  | object				  | 												|
| type 	         	  | string				  |                                                 |
| data		      	  | array				  | 		                                        |
| key				  | integer 			  | 												|
| localeId	      	  | integer (foreign key) | **[Locale](./models/locale)**                    |

You can post or put data by sending the following data to this URL:

* **(POST or PUT) http://my-shop-url/api/translations/id**

| Field               | Type                  | Original Object			                                |
|---------------------|-----------------------|---------------------------------------------------------|
| id				  | integer (primary key) |															|
| name				  | string				  |															|
| position			  | integer				  |															|
| comparable		  | boolean				  |															|
| sortMode			  | integer				  |															|

## DELETE
To delete a shop, simply call this URL with the DELETE request:

* **http://my-shop-url/api/tanslations/id**

Simply replace 'id' with the specific identifier.

## Examples

TODO