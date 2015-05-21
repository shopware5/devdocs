---
layout: default
title: Shopware 5 Rest API - Debit
github_link: developers-guide/shopware-5-rest-api/models/debit/index.md
indexed: true
---

## Introduction

This is the data of the customer-billing model.

* **Model:** Shopware\Models\Customer\Debit
* **Table:** s_user_debit

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| customerId		  | integer (foreign key) |                                                 |
| account			  | string				  |													|
| bankCode			  | string				  |													|
| bankName			  | string				  |													|
| accountHolder		  | string				  |													|

**[Back to overview](../)**