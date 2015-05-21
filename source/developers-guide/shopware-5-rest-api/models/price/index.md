---
layout: default
title: Shopware 5 Rest API - Price
github_link: developers-guide/shopware-5-rest-api/models/price/index.md
indexed: true
---

## Introduction

This is the data of the price model.

* **Model:** Shopware\Models\Article\Price
* **Table:** s_articles_prices


## Structure

| Field               | Type                  | Original object                                       |
|---------------------|-----------------------|-------------------------------------------------------|
| customerGroupKey	  | string (foreign key)  | **[CustomerGroup](./customer-group)** |
| customerGroup       | object                | **[CustomerGroup](./customer-group)** |
| articleDetailsId    | integer (foreign key) | **[Detail](./article-detail)** |
| articleId           | integer (foreign key) | **[Article](../api-resources-article)**               |
| id                  | integer (primary key) |                                                       |
| from                | integer/string        |                                                       |
| to                  | string                |                                                       |
| price               | double                |                                                       |
| pseudoPrice         | double                |                                                       |
| basePrice           | double                |                                                       |
| percent             | double                |                                                       |

**[Back to overview](../)**