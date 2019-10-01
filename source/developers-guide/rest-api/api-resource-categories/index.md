---
layout: default
title: REST API - Categories Resource
github_link: developers-guide/rest-api/api-resource-categories/index.md
indexed: false
---

## Introduction

In this part of the documentation you can learn more about the API's categories resource. With this resource, it is possible to retrieve, update and delete any category data of your shop. We will also have a look at the associated data structures.


## General Information

This resource supports the following operations:

|  Access URL                 | GET                   | GET (List)            | PUT                    | PUT (Batch)         | POST                 | DELETE                | DELETE (Batch)      |
|-----------------------------|-----------------------|-----------------------|------------------------|---------------------|----------------------|-----------------------|---------------------|
| /api/categories             | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) |  ![Yes](../img/yes.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/categories**

## GET

### Required Parameters
Single category details can be retrieved via the category ID:

* **http://my-shop-url/api/categories/id**

### Return Value
| Model                              | Table            |
|------------------------------------|------------------|
| Shopware\Models\Category\Category  | s_categories     |


| Field               | Type                  | Original Object                                                               |
|---------------------|-----------------------|-------------------------------------------------------------------------------|
| id                  | integer (primary key) |                                                                               |
| parentId            | integer (foreign key) | **[Category](../models/#category)**                                           |
| streamId            | integer               |                                                                               |
| name                | string                |                                                                               |
| position            | integer               |                                                                               |
| metaTitle           | string                |                                                                               |
| metaKeywords        | string                |                                                                               |
| metaDescription     | string                |                                                                               |
| cmsHeadline         | string                |                                                                               |
| cmsText             | string                |                                                                               |
| active              | boolean               |                                                                               |
| template            | string                |                                                                               |
| productBoxLayout    | string                |                                                                               |
| blog                | boolean               |                                                                               |
| path                | string                |                                                                               |
| showFilterGroups    | boolean               |                                                                               |
| external            | string                |                                                                               |
| hideFilter          | boolean               |                                                                               |
| hideTop             | boolean               |                                                                               |
| changed             | DateTime              |                                                                               |
| added               | DateTime              |                                                                               |
| mediaId             | integer (foreign key) |  **[Media](../models/#media)**                                                |
| attribute           | array                 |                                                                               |
| emotions            | array                 |     **[Media](../models/#media)**                                             |
| media               | Media                 |                                                                               |
| customerGroups      | array                 |                                                                               |
| childrenCount       | integer               |                                                                               |
| articleCount        | integer               |                                                                               |
| translations        | object array          | **[Translation](../models/#translation)**                                     |

## GET (List)

### Required Parameters

For this operation, no parameters are required.
To get a list of all categories, simply query:

* **http://my-shop-url/api/categories**

### Return Value

| Model                              | Table            |
|------------------------------------|------------------|
| Shopware\Models\Category\Category  | s_categories     |


This API call returns an array of elements, one for each category. Each of these elements has the following structure:


| Field               | Type                  | Original Object                                                               |
|---------------------|-----------------------|-------------------------------------------------------------------------------|
| id                  | integer (primary key) |                                                                               |
| active              | boolean               |                                                                               |
| name                | string                |                                                                               |
| position            | integer               |                                                                               |
| parentId            | integer (foreign key) | **[Category](../models/#category)**                                           |
| childrenCount       | integer               |                                                                               |
| articleCount        | integer               |                                                                               |

Appended to the above mentioned list, you will also find the following data:

| Field               | Type                  | Comment                                         |
|---------------------|-----------------------|-------------------------------------------------|
| total               | integer                  | The total number of category resources       |
| success             | boolean                  | Indicates if the call was successful or not. |


## POST (create) and PUT (update)
`POST` and `PUT` operations support the following data structure:

| Model                                 | Table         |
|------------------------------------|------------------|
| Shopware\Models\Category\Category  | s_categories     |

| Field               | Type                  | Comment                                              | Original Object / Database Column                                             |
|---------------------|-----------------------|------------------------------------------------------|-------------------------------------------------------------------------------|
| name (required)     | string                  |                                                      |                                                                                |
| id                   | integer (primary key) | If null, a new entity will be created                 | `s_category.id`                                                                  |
| parentId            | integer               | The field `parent` can be used with the same value as well | `s_category.id`                                                             |
| position            | integer               |                                                      |                                                                                  |
| metaTitle           | string                |                                                         |                                                                                  |
| metaKeywords        | string                |                                                         |                                                                                  |
| metaDescription      | string                  |                                                      |                                                                                  |
| cmsHeadline          | string                  |                                                      |                                                                                  |
| cmsText              | string                  |                                                      |                                                                                  |
| template             | string                  |                                                      |                                                                                  |
| path                | string                  |                                                      |                                                                                  |
| active               | boolean                  |                                                      |                                                                                  |
| blog                | boolean                  |                                                         |                                                                                  |
| showFilterGroup      | boolean                  | Only for SW < 5.2 |                                                                                  |
| external             | string                  |                                                      |                                                                                  |
| externalTarget             | string                  | `_blank` or `_parent` |                                                                                  |
| hideFilter           | boolean                  |                                                      |                                                                                  |
| facetIds           | string                  |                                                      |                                                                                  |
| hideSortings           | boolean                  |                                                      |                                                                                  |
| hideTop              | boolean                  |                                                      |                                                                                  |
| noViewSelect        | boolean                  | Only for SW < 5.2 |                                                                                  |
| productBoxLayout        | string                  | `extend`, `basic`, `minimal`, `image` or `list` |                                                                                  |
| changed             | date/time              |                                                      |                                                                                  |
| attribute           | array                  | Array with optional indexes from 1-6 and its values |                                                                                  |
| media                | object                  | Array with either `mediaId` or `link` property |                                                                                  |
| translations         | array                  | Array with either `shopId` `link` property and fields that should be translated | **[Translation](../models/#translation)**                                        |

### Example (POST)
* Creates a new sub category with parent category id 3 and multiple properties 
```javascript
{
	"name": "My Category",
	"parent": 3,
	"position": 1,
	"metaTitle": "My Category Meta Title",
	"metaKeywords": "my,category,meta,keywords",
	"metaDescription": "My Category Meta Description",
	"cmsHeadline": "The Category",
	"cmsText": "Discover the advantages of an api created category",
	"active": true,
	"blog": false,
	"external": "",
	"externalTarget": "",
	"hideFilter": false,
	"facetIds": "|2|3|",
	"hideSortings": false,
	"sortingIds": "|1|2|",
	"hideTop": true,
	"productBoxLayout": "minimal",
	"changed": "2018-01-01 18:00:00",
	"media": {
		"link": "https://my-image-url/path/to/image.jpg"
	}
}
```

## DELETE
To delete a category, simply call the specified resource with the `DELETE` operation as the following example shows:

* **(DELETE) http://my-shop-url/api/categories/id**

Replace the `id` with the specific category id.
