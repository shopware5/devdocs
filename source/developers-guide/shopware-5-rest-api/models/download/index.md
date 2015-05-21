---
layout: default
title: Shopware 5 Rest API - Download
github_link: developers-guide/shopware-5-rest-api/models/download/index.md
indexed: true
---

## Introduction

This is the data of the download model.

* **Model:** Shopware\Models\Article\Download
* **Table:** s_articles_downloads

## Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id            	    | integer (primary key) |                                                       |
| articleId             | integer (foreign key) | **[Article](../api-resources-article)**               |
| name                  | string                |                                                       |
| file                  | string                |                                                       |
| size                  | int                   |                                                       |

**[Back to overview](../)**