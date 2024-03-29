---
layout: default
title: REST API - Generate product images resource
github_link: developers-guide/rest-api/api-resource-generate-article-images/index.md
menu_title: Generate product images resource
menu_order: 130
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

This resource allows you to regenerate product thumbnails.

## General Information

This resource supports the following operations:

| Access URL                 | GET                  | GET (List)           | PUT                    | PUT (Batch)          | POST                 | DELETE               | DELETE (Batch)       |
|----------------------------|----------------------|----------------------|------------------------|----------------------|----------------------|----------------------|----------------------|
| /api/generateArticleImages | ![No](../img/no.png) | ![No](../img/no.png) | ![Yes](../img/yes.png) | ![No](../img/no.png) | ![No](../img/no.png) | ![No](../img/no.png) | ![No](../img/no.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/generateArticleImages**

## PUT (update)

This operation allows you to regenerate the thumbnails of a specific article.

| Identifier     | Parameter    | Column                         | Example call                                        |
|----------------|--------------|--------------------------------|-----------------------------------------------------|
| Article Id     | id           | s_articles.id                  | /api/generateArticleImages/2                        |
| Article Number | number       | s_articles_details.ordernumber | /api/generateArticleImages/20003?useNumberAsId=true |

You can identify the product by either the ID or the detail number.
