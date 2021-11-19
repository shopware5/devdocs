---
layout: default
title: REST API - Examples using the category resource
github_link: developers-guide/rest-api/examples/category/index.md
menu_title: Category examples
menu_order: 80
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article, you will find examples of the provided resource usage for different operations.
For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[category API resource](/developers-guide/rest-api/api-resource-categories/)** if you haven't yet,
to get more information about the category resource and the data it provides.

## Example 1 - Creating a category
This example adds a sub-category to category 3

{% include 'api_badge.twig' with {'route': '/api/categories', 'method': 'POST', 'body': true} %}
```json
{
  "name": "Test-category",
  "parentId": 3
}
```

## Example 2 - Adding categories with attributes and additional fields

{% include 'api_badge.twig' with {'route': '/api/categories', 'method': 'POST', 'body': true} %}
```json
{
  "name": "Test-category",
  "parentId": 3,
  "metaDescription": "metaTest",
  "metaKeywords": "keywordTest",
  "cmsHeadline": "headlineTest",
  "cmsText": "cmsTextTest",
  "active": true,
  "noViewSelect": true,
  "attribute": {
    "1": "Attribute1",
    "2": "Attribute2"
  }
}
```

## Example 3 - Create a category with translation

{% include 'api_badge.twig' with {'route': '/api/categories', 'method': 'POST', 'body': true} %}
```json
{
  "name": "Test-category",
  "parentId": 3,
  "attribute": {
    "1": "Attr1"
  },
  "translations": {
    "2": {
      "shopId": 2,
      "description": "Test category, english translation",
      "__attribute_attribute1": "Attr1 English"
    }
  }
}
```
