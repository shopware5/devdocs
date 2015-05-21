---
layout: default
title: Shopware 5 Rest API - Shop Model
github_link: developers-guide/shopware-5-rest-api/models/shop/index.md
indexed: true
---

## Introduction

This is the data of the shop model.

* **Model:** Shopware\Models\Shop\Shop
* **Table:** s_core_shops

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| mainId	      	  | integer (foreign key) | 		                                        |
| categoryId		  | integer (foreign key) | **[Category](./category)**						|
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
| locale			  | object				  | **[Locale](./locale)**							|

**The locale is only available for languageSubShops.**

**[Back to overview](../)**