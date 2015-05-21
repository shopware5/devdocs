---
layout: default
title: Shopware 5 Rest API - Shipping Attribute
github_link: developers-guide/shopware-5-rest-api/models/shipping-attribute/index.md
indexed: true
---

## Introduction

This is the data of the shipping-attribute model.

* **Model:** Shopware\Models\Attribute\CustomerShipping
* **Table:** s_user_shippingaddress_attributes

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| customerShippingId  | integer (foreign key) |                                                 |
| text1				  | string				  |													|
| text2				  | string				  |													|
| text3				  | string				  |													|
| text4				  | string				  |													|
| text5				  | string				  |													|
| text6				  | string				  |													|

**[Back to overview](../)**