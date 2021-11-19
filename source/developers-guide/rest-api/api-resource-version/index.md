---
layout: default
title: REST API - Version resource
github_link: developers-guide/rest-api/api-resource-version/index.md
menu_title: Version resource
menu_order: 270
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this part of the documentation, you can learn more about the API's version resource.
With this resource, it is possible to retrieve the version of your Shopware installation.

## General Information

This resource supports the following operations:

| Access URL   | GET                    | GET (List)           | PUT                  | PUT (Batch)          | POST                 | DELETE               | DELETE (Batch)       |
|--------------|------------------------|----------------------|----------------------|----------------------|----------------------|----------------------|----------------------|
| /api/version | ![Yes](../img/yes.png) | ![No](../img/no.png) | ![No](../img/no.png) | ![No](../img/no.png) | ![No](../img/no.png) | ![No](../img/no.png) | ![No](../img/no.png) |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/version**

### GET

This resource supports only the `GET` operation, which retrieves a simple array,
containing details about your current Shopware installation's version:

| Field    | Type    | Description                                      |
|----------|---------|--------------------------------------------------|
| version  | string  | The actual shopware version (e.g 5.0)            |
| revision | string  | The release date by standard (e.g 201504010102)  |
| success  | boolean | A value indicating if the request was successful |
