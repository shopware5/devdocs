---
layout: default
title: Shopware 5 Rest API - Billing Attribute
github_link: developers-guide/shopware-5-rest-api/models/billing-attribute/index.md
indexed: true
---

## Introduction

This is the data of the billing-attribute model.

* **Model:** Shopware\Models\Attribute\CustomerBilling
* **Table:** s_user_billingaddress_attributes

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| customerBillingId	  | integer (foreign key) |                                                 |
| text1				  | string				  |													|
| text2				  | string				  |													|
| text3				  | string				  |													|
| text4				  | string				  |													|
| text5				  | string				  |													|
| text6				  | string				  |													|

**[Back to overview](../)**