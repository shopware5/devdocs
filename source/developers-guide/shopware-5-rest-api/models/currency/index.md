---
layout: default
title: Shopware 5 Rest API - Currency Model
github_link: developers-guide/shopware-5-rest-api/models/currency/index.md
indexed: true
---

## Introduction

This is the data of the currency model.

* **Model:** Shopware\Models\Shop\Currency
* **Table:** s_core_currencies

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| currency	      	  | string				  | 		                                        |
| name				  | string				  | 												|
| default			  | boolean				  | 												|
| factor			  | double				  | 												|
| symbol			  | string				  | 												|
| symbolPosition	  | integer				  | 												|
| position			  | integer				  | 												|

**[Back to overview](../)**