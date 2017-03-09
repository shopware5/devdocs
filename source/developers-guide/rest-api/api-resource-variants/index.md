---
layout: default
title: REST API - Variants Resource
github_link: developers-guide/rest-api/api-resource-variants/index.md
indexed: false
---

## Introduction

In this part of the documentation, you can learn more about the API's variants resource. With this resource, it's possible to retrieve, delete and update any variant in your shops. We will also have a look at the associated data structures.

## General Information

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)            | PUT                   | PUT (Batch)           | POST                  | DELETE                | DELETE (Batch)        |
|-----------------------------|--------------------|-----------------------|-----------------------|-----------------------|-----------------------|-----------------------|-----------------------|
| /api/variants                  | ![No](../img/no.png)| ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) |

If you want to access this resource, simply query the following URL

* **http://my-shop-url/api/variants**

## GET

You can retrieve the variants data by providing the specific id

* **http://my-shop-url/api/variants/id**

### Required Parameters

| Identifier        | Parameter            | Database Column                      | Example call                                                            |
|-------------------|-------------------|-------------------------------------|-------------------------------------------------------------------------|
| Detail id            | id                | `s_articles_details.id`              | /api/variants/2                                                            |
| Detail number        | number            | `s_articlies_details.ordernumber`      | /api/variants/SW10003?useNumberAsId=true                                |

Option parameters can be provided:
* `considerTaxInput`: By default, all returned prices are net values. If the boolean `considerTaxInput` is set to true, gross values will be returned instead.

### Return Value

| Model                                | Table                        |
|-----------------------------------|---------------------------|
| Shopware\Models\Article\Detail    | `s_articles_details`        |

| Field               | Type                  | Original object                                         |
|---------------------|-----------------------|---------------------------------------------------------|
| id                    | integer (primary key) |                                                         |
| articleId                | integer (foreign key) | **[Article](../api-resource-article)**                   |
| unitId              | integer (foreign key) |                                                         |
| number                | string                  |                                                         |
| supplierNumber      | string                  |                                                         |
| kind                  | integer                  |                                                            |
| additionalText      | string                  |                                                            |
| active              | boolean                  |                                                            |
| inStock              | integer                  |                                                            |
| stockMin              | integer                  |                                                            |
| weight              |    string                  |                                                            |
| len                  | string                  |                                                            |
| height              | string                  |                                                            |
| ean                  | string                  |                                                         |
| position              | integer                  |                                                            |
| minPurchase          | integer                  |                                                            |
| purchaseSteps          |    integer                  |                                                            |
| maxPurchase          | integer                  |                                                            |
| purchaseUnit          | string                  |                                                            |
| shippingFree          | boolean                  |                                                            |
| releaseDate          | date/time              |                                                            |
| shippingTime          | string                  |                                                            |
| prices              | array                  | **[Price](../models/#price)**                            |
| attribute              | object                  | **[Attribute](../models/#article-attribute)**            |
| configuratorOptions | array                  | **[ConfiguratorOptions](../models/#configurator-option)**|

## POST (create)
To post a variant, you need to provide the data as shown below:


### Data

You can use this data to add a new variant to the shop
| Model                                | Table                        |
|-----------------------------------|---------------------------|
| Shopware\Models\Article\Detail    | `s_articles_details`        |

| Field               | Type                  | Original object                                         |
|---------------------|-----------------------|---------------------------------------------------------|
| id                    | integer (primary key) |                                                         |
| articleId                | integer (foreign key) | **[Article](../api-resource-article)**                   |
| unitId              | integer (foreign key) |                                                         |
| number                | string                  |                                                         |
| supplierNumber      | string                  |                                                         |
| kind                  | integer                  |                                                            |
| additionalText      | string                  |                                                            |
| active              | boolean                  |                                                            |
| inStock              | integer                  |                                                            |
| stockMin              | integer                  |                                                            |
| weight              |    string                  |                                                            |
| len                  | string                  |                                                            |
| height              | string                  |                                                            |
| ean                  | string                  |                                                         |
| position              | integer                  |                                                            |
| minPurchase          | integer                  |                                                            |
| purchaseSteps          |    integer                  |                                                            |
| maxPurchase          | integer                  |                                                            |
| purchaseUnit          | string                  |                                                            |
| shippingFree          | boolean                  |                                                            |
| releaseDate          | date/time              |                                                            |
| shippingTime          | string                  |                                                            |
| prices              | array                  | **[Price](../models/#price)**                            |
| attribute              | object                  | **[Attribute](../models/#article-attribute)**            |
| configuratorOptions | array                  | **[ConfiguratorOptions](../models/#configurator-option)**|

You can post or put data by sending the following data to this URL:

* **(POST or PUT) http://my-shop-url/api/variants/id**

## PUT (update)

To put data to a variant, simply provide one of the following parameters to identify it:

| Identifier        | Parameter        | Database Column                    | Example Call                                        |
|-------------------|---------------|-----------------------------------|---------------------------------------------------|
| Detail Id            | id            | `s_articles_details.id`            | /api/variants/2                                    |
| Detail number        | number        | `s_articles_details.ordernumber`    | /api/variants/SW10003?useNumberAsId=true            |

**The data is the same as shown in the POST operation.**

You can use this data to update a variant.
| Model                                | Table                        |
|-----------------------------------|---------------------------|
| Shopware\Models\Article\Detail    | `s_articles_details`        |

| Field               | Type                  | Original object                                         |
|---------------------|-----------------------|---------------------------------------------------------|
| id                    | integer (primary key) |                                                         |
| articleId                | integer (foreign key) | **[Article](../api-resource-article)**                   |
| unitId              | integer (foreign key) |                                                         |
| number                | string                  |                                                         |
| supplierNumber      | string                  |                                                         |
| kind                  | integer                  |                                                            |
| additionalText      | string                  |                                                            |
| active              | boolean                  |                                                            |
| inStock              | integer                  |                                                            |
| stockMin              | integer                  |                                                            |
| weight              |    string                  |                                                            |
| len                  | string                  |                                                            |
| height              | string                  |                                                            |
| ean                  | string                  |                                                         |
| position              | integer                  |                                                            |
| minPurchase          | integer                  |                                                            |
| purchaseSteps          |    integer                  |                                                            |
| maxPurchase          | integer                  |                                                            |
| purchaseUnit          | string                  |                                                            |
| shippingFree          | boolean                  |                                                            |
| releaseDate          | date/time              |                                                            |
| shippingTime          | string                  |                                                            |
| prices              | array                  | **[Price](../models/#price)**                            |
| attribute              | object                  | **[Attribute](../models/#article-attribute)**            |
| configuratorOptions | array                  | **[ConfiguratorOptions](../models/#configurator-option)**|

## DELETE
To delete a variant, simply provide one of the following parameters to identify it:

| Identifier        | Parameter        | Database Column                    | Example Call                                        |
|-------------------|---------------|-----------------------------------|---------------------------------------------------|
| Detail Id            | id            | `s_articles_details.id`            | /api/variants/2                                    |
| Detail number        | number        | `s_articles_details.ordernumber`    | /api/variants/SW10003?useNumberAsId=true            |

## DELETE (Stack)

In order to delete more than one variant at once, it's possible to provide an array of ids to the REST API.
Simply pass the array of article ids to the following URL (example)

* **[DELETE] http://my-shop-url/variants/**

without providing an id as seen in the single `DELETE` request. As data provide the array of ids you wish to delete.

## PUT (update) (Stack)

Updating many articles at once requires an array of variant data being provided to the following URL using the `PUT` request (example):

* **[PUT] http://my-shop-url/variants/**

Simply provide the same data as described in the create statement.
