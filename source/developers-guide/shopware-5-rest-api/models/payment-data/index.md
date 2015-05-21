---
layout: default
title: Shopware 5 Rest API - Payment Data
github_link: developers-guide/shopware-5-rest-api/models/payment-data/index.md
indexed: true
---

## Introduction

This is the data of the payment-data model.

* **Model:** Shopware\Models\Customer\PaymentData
* **Table:** s_core_payment_data

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| paymentMeanId		  | integer (foreign key) |                                                 |
| useBillingData	  | string				  |													|
| bankName			  | string				  |													|
| bic				  | string				  |													|
| iban				  | string				  |													|
| accountNumber		  | string				  |													|
| bankCode			  | string				  |													|
| accountHolder		  | string				  |													|
| createdAt			  | date/time			  |													|

**[Back to overview](../)**