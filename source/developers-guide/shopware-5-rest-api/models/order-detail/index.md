---
layout: default
title: Shopware 5 Rest API - Order Detail
github_link: developers-guide/shopware-5-rest-api/models/order-detail/index.md
indexed: true
---

## Introduction

This is the data of the order-detail model.

* **Model:** Shopware\Models\Order\Detail
* **Table:** s_order_detail

## Structure

| Field               | Type                  | Original object                                 		|
|---------------------|-----------------------|---------------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 		|
| orderId       	  | string				  | **[Order](../api-resource-order)**              		|
| articleId			  | integer (foreign key) | **[Article](../api-resource-article)**   				|
| taxId				  | integer (foreign key) | **[Tax](./tax)**    									|
| taxRate			  |	double				  | 														|
| statusId			  | integer (foreign key) | **[Status](./order-status)**							|
| number			  | string (foreign key)  | **[Order](../api-resource-order)**						|
| articleNumber		  | string (foreign key)  | **[ArticleDetail](./article-detail)**					|
| price				  | double				  |															|
| quantity			  | integer 			  |															|
| articleName		  | string 			  	  |															|
| shipped			  | boolean 			  |															|
| shippedGroup		  | integer 			  |															|
| releaseDate		  | date/time 			  |															|
| mode				  | integer 			  |															|
| esdArticle		  | integer 			  |															|
| config			  | string	 			  |															|
| attribute			  | object	 			  |	**[OrderDetailAttribute](./order-detail-attribute)**	|

**[Back to overview](../)**