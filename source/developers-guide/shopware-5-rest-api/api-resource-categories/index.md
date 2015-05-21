---
layout: default
title: Shopware 5 Rest API - Categories End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-categories/index.md
indexed: true
---

## Introduction

In this part of the documentation you can learn more about the API's categories-resource. With this resource, it is possible to 
receive, update and delete any category-data of your shop. Also we will have a look at the provided data.


## General Information
You may find the related resource under
**engine\Shopware\Controllers\Api\Categories.php**.

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/categories             | ![Yes](./img/yes.png)    | ![Yes](./img/yes.png) | ![Yes](./img/yes.png) | ![No](./img/no.png)    | ![Yes](./img/no.png)   | ![Yes](./img/yes.png) | ![No](./img/no.png)   |

If you want to access this end-point, simply append your shop-URL with

* **http://my-shop-url/api/categories**

## GET

### Required Parameters
Single category details can be received via the category id:

* **http://my-shop-url/api/categories/id**

### Return Value
| Model					             | Table			|
|------------------------------------|------------------|
| Shopware\Models\Category\Category  | s_categories     |


| Field               | Type                  | Original Object                                                               |
|---------------------|-----------------------|-------------------------------------------------------------------------------|
| id				  | integer (primary key) | 							                                                  |
| active	          | boolean				  |                     							                              |
| name                | string                |       											                              |
| position            | integer               |             									                              |
| parentId            | integer (foreign key) | **[Category](./models/category)** 											  |
| childrenCount       | integer               | 														                      |
| articleCount		  | integer				  | 														                      |

## GET (List)

### Required Parameters
For this operation no parameters are required.
Simply call

* **http://my-shop-url/api/caches/**
to get a list of all caches.

### Return Value

| Model					             | Table			|
|------------------------------------|------------------|
| Shopware\Models\Category\Category  | s_categories     |


| Field               | Type                  | Original Object                                                               |
|---------------------|-----------------------|-------------------------------------------------------------------------------|
| id				  | integer (primary key) | 							                                                  |
| active	          | boolean				  |                     							                              |
| name                | string                |       											                              |
| position            | integer               |             									                              |
| parentId            | integer (foreign key) | **[Category](./models/category)**											  |
| childrenCount       | integer               | 														                      |
| articleCount		  | integer				  | 														                      |

*Since this returns a list, the following fields will be appended to the array:*

| Field               | Type                  | Comment			                                |
|---------------------|-----------------------|-------------------------------------------------|
| total				  | integer				  | The total amount of category resources          |
| success		      | boolean				  | Indicates if the call was stressful or not.		|


## POST and PUT
POST and PUT operations support the following data to be provided:

| Model					             | Table			|
|------------------------------------|------------------|
| Shopware\Models\Category\Category  | s_categories     |

| Field               | Type                  | Comment                                              | Original Object / Database Column                                             |
|---------------------|-----------------------|------------------------------------------------------|-------------------------------------------------------------------------------|
| name (required)     | string				  |                                                      |       						                                                 |
| id     	          | integer (primary key) | If null, a new entity will be created    	         | `s_category.id`     							                                 |
| parent              | object                | Required if no parentId is provided                  | **[Category](./models/category)**											 |
| parentId            | integer               |                                                      | `s_category.id`            									                 |                
| position            | integer               |                                                      | 																			     |
| metaKeywords        | string                |												         | 														                         |
| metaDescription	  | string				  |                                                      | 														                         |
| cmsHeadline    	  | string				  |                                                      | 														                         |
| cmsText        	  | string				  |                                                      | 														                         |
| template       	  | string				  |                                                      | 														                         |
| path          	  | string				  |                                                      | 														                         |
| active         	  | boolean				  |                                                      | 														                         |
| blog          	  | boolean				  |                                                		 | 														                         |
| showFilterGroup	  | boolean				  |                                                      | 														                         |
| external       	  | string				  |                                                      | 														                         |
| hideFilter     	  | boolean				  |                                                      | 														                         |
| hideTop	          | boolean				  |                                                      | 														                         |
| noViewSelect  	  | boolean				  |                                                      | 														                         |
| changed       	  | date/time    		  |                                                      | 														                         |
| added         	  | date/time    		  |                                                      | 														                         |
| attribute     	  | array				  | Arrary with optional indexes from 1-6 and its values | 														                         |


## DELETE
To delete a cache, simply call the specified endpoint with the 'DELETE' operation as the following example shows:

* **(DELETE) http://my-shop-url/api/categories/id**

and don't forget to replace the 'id' with the specific category id.

## Examples

TODO