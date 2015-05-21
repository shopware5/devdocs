---
layout: default
title: Shopware 5 Rest API - Image
github_link: developers-guide/shopware-5-rest-api/models/image/index.md
indexed: true
---

## Introduction

This is the data of the image model.

* **Model:** Shopware\Models\Article\Image
* **Table:** s_articles_img

## Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id            	    | integer (primary key  |                                                       |
| articleId             | integer (foreign key) | **[Article](../api-resources-article)**               |
| articleDetailId       | integer (foreign key) | **[Detail](./article-detail)** 						|
| description           | string                |                                                       |
| path                  | string                |                                                       |
| main                  | integer               |                                                       |
| position              | integer               |                                                       |
| width                 | integer               |                                                       |
| height                | integer               |                                                       |
| relations             | string                |                                                       |
| extension             | string                |                                                       |
| parentId              | integer               | 			                                            |
| mediaId               | integer               | **[Media](../api-resource-media)**                    |

**[Back to overview](../)**