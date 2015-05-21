---
layout: default
title: Shopware 5 Rest API -  Billing
github_link: developers-guide/shopware-5-rest-api/models/billing/index.md
indexed: true
---

## Introduction

This is the data of the customer-billing model.

* **Model:** Shopware\Models\Customer\Billing
* **Table:** s_user_billingaddress

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| customerId       	  | integer (foreign key) |                                                 |
| countryId       	  | integer (foreign key) | **[Country](./country)**                        |
| stateId       	  | integer (foreign key) |                                                 |
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
| phone				  | string				  |													|
| fax				  | string				  |													|
| vatId				  | string				  |													|
| birthday			  | date/time			  |													|
| attribute			  | object				  |	**[BillingAttribute](./billing-attribute)**  	|



**[Back to overview](../)**