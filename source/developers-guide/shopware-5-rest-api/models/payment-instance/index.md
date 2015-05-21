---
layout: default
title: Shopware 5 Rest API - Payment Instance Model
github_link: developers-guide/shopware-5-rest-api/models/payment-instance/index.md
indexed: true
---

## Introduction

This is the data of the payment-instance model.

* **Model:** Shopware\Models\Payment\PaymentInstance
* **Table:** s_core_payment_instance

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| firstName      	  | string				  | 		                                        |
| lastName			  | string				  | 												|
| address	      	  | string				  | 		                                        |
| zipCode			  | string				  | 												|
| city				  | string				  | 												|
| bankName			  | string				  | 												|
| bankCode			  | string				  | 												|
| accountNumber		  | string				  | 												|
| accountHolder		  | string				  | 												|
| bic				  | string				  | 												|
| iban				  | string				  | 												|
| amount			  | string				  | 												|
| createdAt			  | date/time			  | 												|

**[Back to overview](../)**