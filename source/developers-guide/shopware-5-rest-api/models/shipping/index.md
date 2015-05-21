---
layout: default
title: Shopware 5 Rest API - Shipping
github_link: developers-guide/shopware-5-rest-api/models/shipping/index.md
indexed: true
---

## Introduction

This is the data of the shipping model.

* **Model:** Shopware\Models\Customer\Shipping
* **Table:** s_user_shippingaddress

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| customerId       	  | integer (foreign key) | **[Customer](../api-resource-customer)**        |
| countryId       	  | integer (foreign key) | **[Country](./country)**                        |
| stateId       	  | integer (foreign key) | 		                                        |
| company			  | string				  |													|
| department		  | string				  |													|
| salutation		  | string				  |													|
| number			  | string				  |													|
| firstName			  | string				  |													|
| lastName			  | string				  |													|
| street			  | string				  |													|
| streetNumber		  | string				  |													|
| zipCode			  | string				  |													|
| city				  | string				  |													|
| attribute			  | object				  |	**[SippingAttribute](./shipping-attribute)**	|

**[Back to overview](../)**