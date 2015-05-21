---
layout: default
title: Shopware 5 Rest API - Link
github_link: developers-guide/shopware-5-rest-api/models/link/index.md
indexed: true
---

## Introduction

This is the data of the link model.

* **Model:** Shopware\Models\Article\Link
* **Table:** s_articles_information

## Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id            	    | integer (primary key) |                                                       |
| articleId             | integer (foreign key) | **[Article](../api-resources-article)**               |
| name                  | string                |                                                       |
| link                  | string                |                                                       |
| target                | string                |                                                       |

**[Back to overview](../)**