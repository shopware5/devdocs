---
layout: default
title: Shopware 5 Rest API - Dispatch Model
github_link: developers-guide/shopware-5-rest-api/models/dispatch/index.md
indexed: true
---

## Introduction

This is the data of the dispatch model.

* **Model:** Shopware\Models\Dispatch\Dispatch
* **Table:** s_premium_dispatch

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| name		      	  | string				  | 		                                        |
| type				  | integer				  | 												|
| description      	  | string				  | 		                                        |
| comment	      	  | string				  | 		                                        |
| active	      	  | boolean				  | 		                                        |
| position      	  | integer				  | 		                                        |
| calculation      	  | integer				  | 		                                        |
| surchargeCalculation| integer				  | 		                                        |
| taxCalculation   	  | integer				  | 		                                        |
| shippingFree     	  | decimal				  | 		                                        |
| multiShopId      	  | integer (foreign key) | **[Shop](./shop)**                              |
| customerGroupId  	  | integer (foreign key) | **[CustomerGroup](./customer-group)**           |
| bindShippingFree 	  | integer				  | 		                                        |
| bindTimeFrom     	  | integer				  | 		                                        |
| bindTimeTo      	  | integer				  | 		                                        |
| bindInStock      	  | integer				  | 		                                        |
| bindWeekdayFrom  	  | integer				  | 		                                        |
| bindPriceTo      	  | integer				  | 		                                        |
| bindSql	      	  | string				  | 		                                        |
| statusLink      	  | string				  | 		                                        |
| calculationSql   	  | string				  | 		                                        |

**[Back to overview](../)**