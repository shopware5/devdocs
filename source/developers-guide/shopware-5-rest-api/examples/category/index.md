---
layout: default
title: Shopware 5 Rest API - Examples using the category resource
github_link: developers-guide/shopware-5-rest-api/examples/category/index.md
indexed: true
---

## Introduction

In this article, you will find examples of the provided resource usage for different operations. For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[category API resource](../api-resource-category)** if you haven't yet, to get more information about the category resource and the data it provides.

These examples assume you are using the provided **[demo API client](../)**.


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

**[Back](../)**