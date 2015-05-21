---
layout: default
title: Shopware 5 Rest API - Customer End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-customer/index.md
indexed: true
---

## Introduction

In this part of the documentation you can learn more about the API's customer resource. With this resource, it is possible to 
receive, update and delete any customer of your shop. Also we will have a look at the provided data.


## General Information
You may find the related resource under
**'engine\Shopware\Controllers\Api\Customers.php**.

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/customers              | ![Yes](./img/yes.png)    | ![Yes](./img/yes.png) | ![Yes](./img/yes.png) | ![No](./img/no.png)    | ![Yes](./img/no.png)   | ![Yes](./img/yes.png) | ![No](./img/no.png)   |

If you want to access this end-point, simply append your shop-URL with

* **http://my-shop-url/api/customers**

## GET

#### Required Parameters

It is required to to parameterize this API-Call. The following parameters are available:

| Identifier      | Parameter | DB column                    | Example call                              |
|-----------------|-----------|------------------------------|-------------------------------------------|
| Customer Id     | id        | s_user.id                    | /api/customers/2                          |
| Customer number | number    | s_user_billingaddress.number | /api/customers/20003?useNumberAsId=true   |

* **useNumberAsId=true** - This tells the API to query the customers's data by its number, not by its actual identifier. Otherwise the syntax is just **/api/customers/id**. It is not possible to provide both parameter at the same time.

### Return Value

| Model					             | Table			     |
|------------------------------------|-----------------------|
| Shopware\Models\Customer\Customer  | s_user                |

| Field                 | Type                  | Original Object                                                               |
|-----------------------|-----------------------|-------------------------------------------------------------------------------|
| id				    | integer (primary key) | 							                                                    |
| paymentId			    | integer (foreign key) | **[Payment](./models/payment-data)**                                          |
| groupKey			    | string (foreign key)  | **[CustomerGroup](./models/customer-group)**			                        |
| shopId				| string (foreign key)  | **[Shop](./models/shop)**			 										    |
| priceGroupId			| integer (foreign key) | **[PriceGroup](./models/price-group)**      								    |
| encoderName			| string				| 																		        |
| hashPassword			| string				| 																				|
| active				| boolean				|																				|
| email					| string				|																			    |
| firstLogin			| date/time				|																				|
| lastLogin				| date/time				|																				|
| accountMode			| integer				|																				|
| confirmationKey		| string				|																				|
| sessionId				| string				|																				|
| newsletter			| boolean				|																				|
| validation			| string				|																				|
| affiliate				| boolean				|																				|
| paymentPreset			| integer				|																				|
| languageId			| integer (foreign key) |																				|
| referer				| string				|																				|
| internalComment		| string				|																				|
| failedLogins			| integer				|																				|
| lockedUntil			| date/time				|																				|
| attribute				| object				| **[CustomerAttribute](./models/customer-attribute)**							|
| billing				| object				| **[Billing](./models/billing)**												|
| paymentData			| array					| **[PaymentData](./models/payment-data)**										|
| shipping				| object				| **[Shipping](./models/shipping)**												|
| debit					| object				| **[Debit](./models/debit)**													|

## GET (List)

### Required Parameters
For this operation no parameters are required.
Simply call

* **http://my-shop-url/api/customers/**

to get a list of all customers.

### Return Value
| Model					             | Table			     |
|------------------------------------|-----------------------|
| Shopware\Models\Customer\Customer  | s_user				 |

| Field                 | Type                  | Original Object                                                               |
|-----------------------|-----------------------|-------------------------------------------------------------------------------|
| id				    | integer (primary key) | 							                                                    |
| paymentId			    | integer (foreign key) | **[Payment](./models/payment-instance)**                                      |
| groupKey			    | string (foreign key)  | **[CustomerGroup](./models/customer-group)**			                        |
| shopId				| string (foreign key)  | **[Shop](./models/shop-shit-resource)** 										|
| priceGroupId			| integer (foreign key) | **[PriceGroup](./models/price-group)**      								    |
| encoderName			| string				| 																		        |
| hashPassword			| string				| 																				|
| active				| boolean				|																				|
| email					| string				|																			    |
| firstLogin			| date/time				|																				|
| lastLogin				| date/time				|																				|
| accountMode			| integer				|																				|
| confirmationKey		| string				|																				|
| sessionId				| string				|																				|
| newsletter			| boolean				|																				|
| validation			| string				|																				|
| affiliate				| boolean				|																				|
| paymentPreset			| integer				|																				|
| languageId			| integer (foreign key) |																				|
| referer				| string				|																				|
| internalComment		| string				|																				|
| failedLogins			| integer				|																				|
| lockedUntil			| date/time				|																				|

*Since this returns a list, the following fields will be appended to the array:*

| Field               | Type                  | Comment			                                |
|---------------------|-----------------------|-------------------------------------------------|
| total				  | integer				  | The total amount of category resources          |
| success		      | boolean				  | Indicates if the call was stressful or not.		|

## POST and PUT

To post or put content, use the same data as provided in the GET operation.

## DELETE
To delete a customer, simply call the specified endpoint with the 'DELETE' operation as the following example shows:

* **(DELETE) http://my-shop-url/api/customers/id**

and don't forget to replace the 'id' with the specific customer id.

## Examples

TODO