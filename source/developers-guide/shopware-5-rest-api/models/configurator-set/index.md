---
layout: default
title: Shopware 5 Rest API - Configurator Set
github_link: developers-guide/shopware-5-rest-api/models/configurator-set/index.md
indexed: true
---

## Introduction

This is the data of the configurator set model.

* **Model:** Shopware\Models\Article\Configurator\Set
* **Table:** s_article_configurator_sets

## Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id            	    | integer (primary key  |                                                       |
| name                  | string                |                                                       |
| public                | boolean               |                                                       |
| type                  | integer               |                                                       |
| groups                | object array          | **[ConfiguratorGroup](./configurator-group)**         |

**[Back to overview](../)**