---
layout: default
title: Shopware 5 Rest API - Generate Article Images Resource
github_link: developers-guide/shopware-5-rest-api/api-resource-generate-article-images/index.md
indexed: true
---

## Introduction

This resource allows you to regenerate article thumbnails.

## General Information

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Batch)      | POST             | DELETE          | DELETE (Batch)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/customerGroups         | ![No](./img/no.png)      | ![No](./img/no.png)   | ![Yes](./img/yes.png) | ![No](./img/no.png)    | ![No](./img/no.png)    | ![No](./img/no.png)   | ![No](./img/no.png)   |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/customerGroups**

## PUT

This operation allows you to regenerate the thumbnails of a specific article.

| Identifier     | Parameter   | Column                            | Example call                                         |
|----------------|-------------|-----------------------------------|------------------------------------------------------|
| Article Id	 | id		   | s_articles.id  				   | /api/generateArticleImages/2		                  | 
| Article Number | number	   | s_articles_details.customernumber | /api/generateArticleImages/20003?useNumberAsId=true  |

You can identify the product by either the ID or the detail number.

## Examples

TODO