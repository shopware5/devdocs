---
layout: default
title: Shopware 5 Rest API - Order Document Model
github_link: developers-guide/shopware-5-rest-api/models/document/index.md
indexed: true
---

## Introduction

This is the data of the order-detail model.

* **Model:** Shopware\Models\Order\Document\Document
* **Table:** s_order_documents

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| date           	  | date/time			  | 		                                        |
| typeId			  | integer (foreign key) | **[DocumentType](./document-type)**				|
| customerId		  | integer (foreign key) | **[Customer](./customer)**						|
| orderId			  |	integer (foreign key) | **[Order](./order)**							|
| amount			  | double				  | 												|
| documentId		  | integer (foreign key) | 												|
| hash				  | string 				  |													|
| type				  | object				  |	**[DocumentType](./document-type)**				|
| attribute			  | object	 			  |	**[DocumentAttribute](./document-attribute)**	|

**[Back to overview](../)**