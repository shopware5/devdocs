---
layout: default
title: REST API - Examples using the category resource
github_link: developers-guide/rest-api/examples/category/index.md
menu_title: The category resource
menu_order: 60
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article, you will find examples of the provided resource usage for different operations. For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[category API resource](/developers-guide/rest-api/api-resource-categories/)** if you haven't yet, to get more information about the category resource and the data it provides.

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.


## Example 1 - Creating a category
This example adds a sub-category to category 3

```
$createCategory = array(
    'parentId' => 3,
    'name'     => 'Test category'
);
$client->post('categories', $createCategory);

```

## Example 2 - Adding categories with attributes and additional fields

```

$categoryData = array(
    "name" => "Test category",
    "metaDescription" => "metaTest",
    "metaKeywords" => "keywordTest",
    "cmsHeadline" => "headlineTest",
    "cmsText" => "cmsTextTest",
    "active" => true,
    "noViewSelect" => true,
    "attribute" => array(
        1 => "Attribute1",
        2 => "Attribute2",
    )
);
$client->post('categories', $categoryData );

```
