---
layout: default
title: Shopware 5 Rest API - Customer-Groups End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-index/index.md
indexed: true
---

## Introduction

In this part of the documentation you can learn more about the API's customer-groups-resource. With this resource, it is possible to 
receive, update and delete any customer-groups-data of your shop. Also we will have a look at the provided data.


## General Information
You may find the related resource under
**engine\Shopware\Controllers\Api\CustomerGroups.php**.

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/customerGroups         | ![Yes](./img/yes.png)    | ![Yes](./img/yes.png) | ![Yes](./img/yes.png) | ![No](./img/no.png)    | ![Yes](./img/no.png)   | ![Yes](./img/yes.png) | ![No](./img/no.png)   |

If you want to access this end-point, simply append your shop-URL with

* **http://my-shop-url/api/customerGroups**

## GET

### Required Parameters
Single customer-group details can be received via the customer-group id:

* **http://my-shop-url/api/customerGroups/id**

### Return Value

| Model					             | Table			     |
|------------------------------------|-----------------------|
| Shopware\Models\Customer\Group     | s_core_customergroups |

| Field                 | Type                  | Original Object                                                             |
|-----------------------|-----------------------|-----------------------------------------------------------------------------|
| id				    | integer (primary key) | 							                                                  |
| key    	            | string				|                     							                              |
| name                  | string                |       											                          |
| tax                   | boolean               |             									                              |
| taxInput              | boolean				| 																			  |
| mode                  | boolean               | 														                      |
| discount   		    | integer				| 														                      |
| minimumOrder 		    | integer				| 														                      |
| minimumOrderSurcharge | integer				| 														                      |
| discounts   		    | array 				| **[Surcharge](./models/group-surcharge)**		  							  |

## GET (List)

### Required Parameters
For this operation no parameters are required.
Simply call

* **http://my-shop-url/api/customerGroups/**

to get a list of all customer-groups.

### Return Value
| Model					             | Table			     |
|------------------------------------|-----------------------|
| Shopware\Models\Customer\Group     | s_core_customergroups |

| Field                 | Type                  | Original Object                                                             |
|-----------------------|-----------------------|-----------------------------------------------------------------------------|
| id				    | integer (primary key) | 							                                                  |
| key    	            | string				|                     							                              |
| name                  | string                |       											                          |
| tax                   | boolean               |             									                              |
| taxInput              | boolean				| 																			  |
| mode                  | boolean               | 														                      |
| discount   		    | integer				| 														                      |
| minimumOrder 		    | integer				| 														                      |
| minimumOrderSurcharge | integer				| 														                      |
| discounts   		    | array 				| **[Surcharge](./models/group-surcharge)**		  							  |


*Since this returns a list, the following fields will be appended to the array:*

| Field               | Type                  | Comment			                                |
|---------------------|-----------------------|-------------------------------------------------|
| total				  | integer				  | The total amount of category resources          |
| success		      | boolean				  | Indicates if the call was stressful or not.		|

## POST and PUT
POST and PUT operations support the following data to be provided (see GET, GET(List)):

| Model					             | Table			     |
|------------------------------------|-----------------------|
| Shopware\Models\Customer\Group     | s_core_customergroups |

| Field                 | Type                  | Original Object                                                             |
|-----------------------|-----------------------|-----------------------------------------------------------------------------|
| id				    | integer (primary key) | 							                                                  |
| key    	            | string				|                     							                              |
| name                  | string                |       											                          |
| tax                   | boolean               |             									                              |
| taxInput              | boolean				| 																			  |
| mode                  | boolean               | 														                      |
| discount   		    | integer				| 														                      |
| minimumOrder 		    | integer				| 														                      |
| minimumOrderSurcharge | integer				| 														                      |
| discounts   		    | array 				| **[Surcharge](./models/group-surcharge)**		  							  |

## DELETE
To delete a customer-group, simply call the specified endpoint with the 'DELETE' operation as the following example shows:

* **(DELETE) http://my-shop-url/api/customerGroups/id**

and don't forget to replace the 'id' with the specific customer-group id.

## Examples

TODO