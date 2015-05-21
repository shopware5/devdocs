---
layout: default
title: Shopware 5 Rest API - Customer Attribute
github_link: developers-guide/shopware-5-rest-api/models/customer-attribute/index.md
indexed: true
---

## Introduction

This is the data of the customer-attribute model.

* **Model:** Shopware\Models\Attribute\Customer
* **Table:** s_user_attributes

## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id 	         	  | integer (primary key) |                                                 |
| customerId       	  | integer (foreign key) | **[Customer](../api-resource-customer)**        |

**[Back to overview](../)**