---
layout: default
title: Shopware 5 Rest API - Configurator Option
github_link: developers-guide/shopware-5-rest-api/models/configurator-option/index.md
indexed: true
---

## Introduction

This is the data of the configurator option model.

* **Model:** Shopware\Models\Article\Configurator\Option
* **Table:** s_article_configurator_options

## Structure

| Field                 | Type                  | Original object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| id             	    | integer (primary key) |                                                 |
| groupId               | integer (foreign key) | **[ConfiguratorGroup](./configurator-group)**   |
| name                  | string                |                                                 |
| position              | integer               |                                                 |

**[Back to overview](../)**