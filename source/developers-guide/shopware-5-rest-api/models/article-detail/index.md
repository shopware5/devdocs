---
layout: default
title: Shopware 5 Rest API - Article Detail
github_link: developers-guide/shopware-5-rest-api/models/article-detail/index.md
indexed: true
---

## Introduction

This is the data of the article detail model.

* **Model:** Shopware\Models\Article\Detail
* **Table:** s_articles_details


## Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| number       		  | string                |                                                 |
| supplierNumber      | string                |                                                 |
| additionalText      | string                |                                                 |
| weight              | string                |                                                 |
| width               | string                |                                                 |
| len                 | string                |                                                 |
| height              | string                |                                                 |
| ean                 | string                |                                                 |
| purchaseUnit        | string                |                                                 |
| descriptionLong     | string                |                                                 |
| referenceUnit       | string                |                                                 |
| packUnit            | string                |                                                 |
| shippingTime        | string                |                                                 |
| prices              | object array          | **[Price](./price)** 							|
| configuratorOptions | object array          | **[ConfiguratorOption](./configurator-option)** |
| attribute           | object                | **[Attribute](./article-attribute)** 			|
| id                  | integer (primary key) |                                                 |
| articleId           | integer (foreign key) | **[Article](../api-resource-article)**          |
| unitId              | integer (foreign key) |                                                 |
| kind                | integer               |                                                 |
| inStock             | integer               |                                                 |
| position            | integer               |                                                 |
| minPurchase         | integer               |                                                 |
| purchaseSteps       | integer               |                                                 |
| maxPurchase         | integer               |                                                 |
| releaseDate         | date/time             |                                                 |
| active              | boolean               |                                                 |
| shippingFree        | boolean               |                                                 |

**[Back to overview](../)**