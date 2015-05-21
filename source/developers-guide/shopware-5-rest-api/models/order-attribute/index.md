---
layout: default
title: Shopware 5 Rest API - Order Attribute Model
github_link: developers-guide/shopware-5-rest-api/models/order-attribute/index.md
indexed: true
---

## Introduction

This is the data of the order-attribute model.

* **Model:** Shopware\Models\Attribute\OrderDetail
* **Table:** s_order_attributes

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| orderId	      	  | integer (foreign key) | 		                                        |
| attribute1		  | string				  | 												|
| attribute2		  | string				  | 												|
| attribute3		  | string				  | 												|
| attribute4		  | string				  | 												|
| attribute5		  | string				  | 												|
| attribute6		  | string				  | 												|

**[Back to overview](../)**