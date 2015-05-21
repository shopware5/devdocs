---
layout: default
title: Shopware 5 Rest API - Version End-Point
github_link: developers-guide/shopware-5-rest-api/api-resource-version/index.md
indexed: true
---

## Introduction

In this part of the documentation you can learn more about the API's version-resource. With this resource, it is possible to 
receive the version of your shop. Also we will have a look at the provided data.

## General Information

This resource supports the following operations:

|  Access URL                 | GET                | GET (List)      | PUT             | PUT (Stack)      | POST             | DELETE          | DELETE (Stack)  |
|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| /api/version                | ![Yes](./img/yes.png)    | ![No](./img/no.png)   | ![No](./img/no.png)   | ![No](./img/no.png)    | ![No](./img/no.png)    | ![No](./img/no.png)   | ![No](./img/no.png)   |

If you want to access this end-point, simply append your shop-URL with

* **http://my-shop-url/api/version**

## Structure
In this part, we will have a look at the data, provided by this end-point and its structure. You will be guided through all seven different operations separately.

| Field				| Type						|
|-------------------|---------------------------|
| version			| string					|
| revision			| string					|
| success			| boolean					|

