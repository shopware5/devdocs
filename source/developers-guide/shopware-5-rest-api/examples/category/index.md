---
layout: default
title: Shopware 5 Rest API - Examples using the category resource
github_link: developers-guide/shopware-5-rest-api/examples/category/index.md
indexed: true
---

## Introduction

In this article you can read more about using the category resource.
The following part will show you examples including provided data and data you need to provide if you want to use this resource.
Please read **[Category](../api-resource-category)** if you did not yet, to get more information about the category resource and the data it provides.
Also we are using the API-Client of the following document **[API-Client](../)**.


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