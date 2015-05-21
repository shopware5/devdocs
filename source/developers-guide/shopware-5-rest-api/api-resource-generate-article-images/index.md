---
layout: default
title: Shopware 5 Rest API - Generate Article Images End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-generate-article-images/index.md
indexed: true
---

## Introduction

This resource allows you to re-generate specific article thumbnails. This resource supports only one operation: 'PUT'.


## General Information
You may find the related resource under
**engine\Shopware\Controllers\Api\GenerateArticleImages.php**.

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/customerGroups         | ![No](./img/no.png)      | ![No](./img/no.png)   | ![Yes](./img/yes.png) | ![No](./img/no.png)    | ![No](./img/no.png)    | ![No](./img/no.png)   | ![No](./img/no.png)   |

If you want to access this end-point, simply append your shop-URL with

* **http://my-shop-url/api/customerGroups**

## PUT

This operation allows you to re-generate the thumbnails of a specific article.

| Identifier     | Parameter   | Column                            | Example call                                         |
|----------------|-------------|-----------------------------------|------------------------------------------------------|
| Article Id	 | id		   | s_articles.id  				   | /api/generateArticleImages/2		                  | 
| Article Number | number	   | s_articles_details.customernumber | /api/generateArticleImages/20003?useNumberAsId=true  |

You can either use the id as identifier or the detail number of the article.

## Examples

TODO