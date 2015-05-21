---
layout: default
title: Shopware 5 Rest API - Customer Group
github_link: developers-guide/shopware-5-rest-api/models/customer-group/index.md
indexed: true
---

## Introduction

This is the data of the customer-group model.

* **Model:** Shopware\Models\Customer\Group
* **Table:** s_core_customergroups


## Structure

| Field                 | Type                  | Original object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| id             	    | integer (primary key) |                                                 |
| key                   | string                |                                                 |
| name                  | string                |                                                 |
| tax                   | boolean               |                                                 |
| taxInput              | boolean               |                                                 |
| mode                  | boolean               |                                                 |
| discount              | double                |                                                 |
| minimumOrder          | double                |                                                 |
| minimumOrderSurcharge | double                |                                                 |
| basePrice             | double                |                                                 |
| percent               | double                |                                                 |

**[Back to overview](../)**