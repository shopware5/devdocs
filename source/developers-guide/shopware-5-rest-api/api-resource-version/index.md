---
layout: default
title: Shopware 5 Rest API - Version Resource
github_link: developers-guide/shopware-5-rest-api/api-resource-version/index.md
indexed: true
---

## Introduction

In this part of the documentation, you can learn more about the API's version resource. With this resource, it is possible to retrieve the version of your Shopware installation.

## General Information

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Batch)      | POST             | DELETE          | DELETE (Batch)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/version                | ![Yes](./img/yes.png)    | ![No](./img/no.png)   | ![No](./img/no.png)   | ![No](./img/no.png)    | ![No](./img/no.png)    | ![No](./img/no.png)   | ![No](./img/no.png)   |

If you want to access this resource, simply query the following URL:

* **http://my-shop-url/api/version**

### GET

This resource supports only the `GET` operation, which retrieves a simple array, containing details about your current Shopware installation's version:

| Field				| Type						|
|-------------------|---------------------------|
| version			| string					|
| revision			| string					|
| success			| boolean					|

