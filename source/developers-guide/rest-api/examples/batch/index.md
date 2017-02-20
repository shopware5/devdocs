---
layout: default
title: REST API - Examples using the batch mode
github_link: developers-guide/rest-api/examples/batch/index.md
menu_title: The batch mode
menu_order: 100
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article, you will find examples of the provided resource usage for different operations. For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[category API resource](/developers-guide/rest-api/api-resource-categories/)** if you haven't yet, to get more information about the category resource and the data it provides.

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.

## Batch mode

The batch mode allows to create and / or update multiple elements in one request. 
Notice the list of resources which supports the batch mode.  

The results of the different tasks (create / update) is stacked and returns one result.
In addition the batch mode supports the detach of elements in on request.

The following resources supports the batch mode.
* Article
* Variant
* Translation

To use the batch mode, send a PUT request without an id in the URL.

```php
$restClient->put(
    'articles/', 
    array(
        array('id' => 1, 'name' => '...'),
        array('id' => 1, 'name' => '...'),
        array('name' => '...'),
        array('name' => '...')
    )
);

$restClient->delete(
    'articles/',
    array(
        array('id' => 2),
        array('id' => 4),
        array('id' => 6)
    )
);
```
