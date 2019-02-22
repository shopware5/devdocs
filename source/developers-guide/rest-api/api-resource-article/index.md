---
layout: default
title: REST API - Article Resource
github_link: developers-guide/rest-api/api-resource-article/index.md
indexed: false
---

## Introduction

In this part of the documentation you can learn more about the API's article resource. The name "article" is a legacy misnomer, it is used to describe products. With this resource, it's possible to retrieve, update and delete any product of your shop. We will also have a look at the associated data structures.


## General Information

This resource supports the following operations:

|  Access URL                 | GET                   | GET (List)            | PUT                   | PUT (Batch)            | POST                   | DELETE                | DELETE (Batch)        |
|-----------------------------|-----------------------|-----------------------|-----------------------|------------------------|------------------------|-----------------------|-----------------------|
| /api/articles               | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png)  | ![Yes](../img/yes.png)  | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/articles**

## Structure
In this part, we will have a look at the data provided by this resource and its structure. You will be guided through all seven different operations separately.

### GET

#### Required Parameters

This API call requires one of the following parameters to be defined:

| Identifier    | Parameter | DB column              | Example call                             |
|---------------|-----------|------------------------|------------------------------------------|
| Article Id    | id        | s_articles.id          | /api/articles/2                          |
| Detail Number | number    | s_articles.ordernumber | /api/articles/SW10003?useNumberAsId=true |

* **useNumberAsId=true** - This tells the API to query the product's data by its detail number, instead of its actual identifier. Otherwise, the syntax is just **/api/articles/id**. It's not possible to provide both parameters at the same time.

#### Optional Parameters
Optional parameters can be provided:
* language `id` or `shop` (from `s_core_shops`). If used, the returned info will be provided in the specified language (if available)
* `considerTaxInput`: By default, all returned prices are net values. If the boolean `considerTaxInput` is set to true, gross values will be returned instead.

| Identifier       | Parameter | DB column              | Example call                             |
|------------------|-----------|------------------------|------------------------------------------|
| language         | id        | s_core_shops           | /api/articles/2?language=3               |
| considerTaxInput | boolean   |                        | /api/articles/2?considerTaxInput=true    |

You can use one or more of these parameters together.

Here is an example of a parametrized URL:

* **http://my-shop-url/api/articles/2?considerTaxInput=true&language=3**
* **http://my-shop-url/api/articles/SW10003?useNumberAsId=true&considerTaxInput=true&language=3**

#### Return value

This call will return an array of the model **Shopware\Models\Article\Article** (`s_articles`).

The following table shows the fields, types and original objects of this array.

| Field               | Type                  | Original object                                    |
|---------------------|-----------------------|----------------------------------------------------|
| id                  | integer (primary key) |                                                    |
| mainDetailId        | integer (foreign key) | **[Detail](../models/#article-detail)**            |
| supplierId          | integer (foreign key) | **[Supplier](../models/#supplier)**                |
| taxId               | integer (foreign key) | **[Tax](../models/#tax)**                          |
| priceGroupId        | integer (foreign key) | **[PriceGroup](../models/#price-group)**           |
| filterGroupId       | integer (foreign key) | **[ConfiguratorSet](../models/#property-group)**   |
| configuratorSetId   | integer (foreign key) | **[ConfiguratorSet](../models/#configurator-set)** |
| name                | string                |                                                    |
| description         | string                |                                                    |
| descriptionLong     | string                |                                                    |
| added               | date/time             |                                                    |
| active              | boolean               |                                                    |
| pseudoSales         | integer               |                                                    |
| highlight           | boolean               |                                                    |
| keywords            | string                |                                                    |
| metaTitle           | string                |                                                    |
| changed             | date/time             |                                                    |
| priceGroupActive    | boolean               |                                                    |
| lastStock           | boolean               |                                                    |
| crossBundleLook     | boolean               |                                                    |
| notification        | boolean               |                                                    |
| template            | string                |                                                    |
| mode                | integer               |                                                    |
| availableFrom       | date/time             |                                                    |
| availableTo         | date/time             |                                                    |
| mainDetail          | object                | **[Detail](../models/#article-detail)**            |
| tax                 | object                | **[Tax](../models/#tax)**                          |
| propertyValue       | object                | **[PropertyValue](../models/#property-value)**     |
| supplier            | object                | **[Supplier](../models/#supplier)**                |
| propertyGroup       | object                | **[PropertyGroup](../models/#property-group)**     |
| customerGroups      | object array          | **[CustomerGroup](../models/#customer-group)**     |
| images              | object array          | **[Image](../models/#image)**                      |
| configuratorSet     | object                | **[ConfiguratorSet](../models/#configurator-set)** |
| links               | object array          | **[Link](../models/#link)**                        |
| downloads           | object array          | **[Download](../models/#download)**                |
| categories          | object array          | **[Category](../models/#category)**                |
| similar             | object array          | **[Similar](../models/#similar)**                  |
| related             | object array          | **[Related](../models/#related)**                  |
| details             | object array          | **[Detail](../models/#article-detail)**            |
| translations        | object array          | **[Translation](../models/#translation)**          |

### GET (List)

#### Optional Parameters

Optional parameters can be provided:
* language `id` or `locale` (from `s_core_locales`). If used, the returned info will be provided in the specified language (if available)
* `considerTaxInput`: By default, all returned prices are net values. If the boolean `considerTaxInput` is set to true, gross values will be returned instead.

| Identifier       | Parameter | DB column              | Example call                             |
|------------------|-----------|------------------------|------------------------------------------|
| language         | id        | s_core_locales         | /api/articles/language=de_DE             |
| considerTaxInput | boolean   |                        | /api/articles/considerTaxInput=true      |

You can use one or more of these parameters together.

Here is an example of a parametrized URL:

* **http://my-shop-url/api/articles/considerTaxInput=true&language=de_DE**

#### Return Value

| Field               | Type                  | Original object                                  |
|---------------------|-----------------------|--------------------------------------------------|
| id                  | integer (primary key) |                                                  |
| mainDetailId        | integer (foreign key) | **[Detail](../models/#article-detail)**           |
| supplierId          | integer (foreign key) | **[Supplier](../models/#supplier)**               |
| taxId               | integer (foreign key) | **[Tax](../models/#tax)**                         |
| priceGroupId        | integer (foreign key) | **[PriceGroup](../models/#price-group)**          |
| filterGroupId       | integer (foreign key) | **[ConfiguratorSet](../models/#property-group)**  |
| configuratorSetId   | integer (foreign key) | **[ConfiguratorSet](../models/#configurator-set)**|
| name                | string                |                                                  |
| description         | string                |                                                  |
| descriptionLong     | string                |                                                  |
| added               | date/time             |                                                  |
| active              | boolean               |                                                  |
| pseudoSales         | integer               |                                                  |
| highlight           | boolean               |                                                  |
| keywords            | string                |                                                  |
| metaTitle           | string                |                                                  |
| changed             | date/time             |                                                  |
| priceGroupActive    | boolean               |                                                  |
| lastStock           | boolean               |                                                  |
| crossBundleLook     | boolean               |                                                  |
| notification        | boolean               |                                                  |
| template            | string                |                                                  |
| mode                | integer               |                                                  |
| availableFrom       | date/time             |                                                  |
| availableTo         | date/time             |                                                  |

### POST (create)

| Field                 | Type                  | Notice                                                                        |  Original Object / Database table                  |
|-----------------------|-----------------------|-------------------------------------------------------------------------------|----------------------------------------------------|
| name (required)       | string                |                                                                               |                                                    |
| taxId (required)      | integer (foreign key) | Required if no tax object provided                                            |  `s_core_tax.id`                                   |
| tax (required)        | object                |                                                                               |  **[Tax](../models/#tax)**                          |
| mainDetail (required) | object                |                                                                                |  **[Detail](../models/#article-detail)**            |
| supplierId (required) | integer (foreign key) | Required if no supplier object provided                                       |  `s_articles_supplier.id`                          |
| supplier (required)   | object                | Will be created if it does not exist                                           |  **[Supplier](../models/#supplier)**                |
| priceGroupId          | integer (foreign key) |                                                                                 |  `s_core_pricegroups.id`                           |
| filterGroupId         | integer (foreign key) |                                                                                 |  `s_filter.id`                                     |
| description           | string                |                                                                               |                                                    |
| descriptionLong       | string                |                                                                               |                                                    |
| added                 | date/time             |                                                                               |                                                    |
| active                | boolean               |                                                                               |                                                    |
| pseudoSales           | integer               |                                                                               |                                                    |
| highlight             | boolean               |                                                                               |                                                    |
| keywords              | string                |                                                                               |                                                    |
| metaTitle             | string                |                                                                               |                                                    |
| changed               | date/time             |                                                                               |                                                    |
| priceGroupActive      | boolean               |                                                                               |                                                    |
| lastStock             | boolean               |                                                                               |                                                    |
| crossBundleLook       | boolean               |                                                                               |                                                    |
| notification          | boolean               |                                                                               |                                                    |
| template              | string                |                                                                               |                                                    |
| mode                  | integer               |                                                                               |                                                    |
| availableFrom         | date/time             |                                                                               |                                                    |
| availableTo           | date/time             |                                                                               |                                                    |
| propertyValues        | object array          | If provided it requires filterGroupId to be set                               | **[PropertyValue](../models/#property-value)**      |
| customerGroups        | object array          |                                                                               | **[CustomerGroup](../models/#customer-group)**      |
| images                | object array          |                                                                               | **[Image](../models/#image)**                       |
| configuratorSet       | object                |                                                                               | **[ConfiguratorSet](../models/#configurator-set)**  |
| downloads             | object array          |                                                                               | **[Download](../models/#download)**                 |
| categories            | object array          |                                                                               | **[Category](../models/#category)**                 |
| similar               | object array          |                                                                               | **[Similar](../models/#similar)**                   |
| related               | object array          |                                                                               | **[Related](../models/#related)**                   |
| variants              | object array          |                                                                               | **[Detail](../models/#article-detail)**             |

### PUT (update)

Products can be identified using the following:

| Identifier    | Parameter | DB column              | Example call                             |
|---------------|-----------|------------------------|------------------------------------------|
| Article Id    | id        | s_articles.id          | /api/articles/2                          |
| Detail Number | number    | s_articles.ordernumber | /api/articles/SW10003?useNumberAsId=true |

The data structure used is similar to the one used for creation (**POST** request)

## DELETE

The product(s) to delete can be defined using the following syntax:

| Identifier    | Parameter | DB column              | Example call                             |
|---------------|-----------|------------------------|------------------------------------------|
| Article Id    | id        | s_articles.id          | /api/articles/2                          |

## DELETE (Stack)

In order to delete more than one product at once, it's possible to provide an array of objects with ids or product numbers to the REST API.
Simply pass the array of objects to the following URL (example)

* **[DELETE] http://my-shop-url/api/articles/**

without providing an id as seen in the single `DELETE` request. 

### Example
* Deletes product with id 1 and product with number SW00002

```javascript
[
    {"id": 1},
    {"mainDetail":
        {
            "number": "SW00002"
        }
    }
]
```

## PUT (Stack)

Updating many products at once requires an array of product data being provided to the following URL using the `PUT` request (example):

* **[PUT] http://my-shop-url/api/articles/**

Simply provide the same data as described in the create statement.
