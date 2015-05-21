---
layout: default
title: Shopware 5 Rest API - Order Status Model
github_link: developers-guide/shopware-5-rest-api/models/order-status/index.md
indexed: true
---

## Introduction

This is the data of the order-status model.

* **Model:** Shopware\Models\Order\Status
* **Table:** s_core_states

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| description      	  | string				  | 		                                        |
| position			  | integer				  | 												|
| group		      	  | string				  | 		                                        |
| sendMail			  | boolean				  | 												|

**[Back to overview](../)**