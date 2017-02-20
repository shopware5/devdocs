---
layout: default
title: REST API - Examples using filter
github_link: developers-guide/rest-api/examples/media/index.md
menu_title: Filter
menu_order: 110
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article, you will find examples of the provided resource usage for different operations. For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[category API resource](/developers-guide/rest-api/api-resource-categories/)** if you haven't yet, to get more information about the category resource and the data it provides.

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.


## Filter by language

In this article you can read more about the filter function. The filter functionality is for limiting the result.
  
```php
$translationResource->getList(0, 10, array(
    array('property' => 'translation.shopId', 'value' => 2)
));
```

### Filter by language and article ID

```php
$translationResource->getList(0, 1, array(
    array('property' => 'translation.shopId', 'value' => 2),
    array('property' => 'translation.key', 'value' => 200),
    array('property' => 'translation.type', 'value' => 'article')
));
```

<b>Example output:</b>

```php
array('total' => 246, 'data' => array(
    array(
        'type' => 'article',
        'data' =>
            array(
                'name' => 'Dummy Translation',
                'description' => 'Dummy Translation',
                'descriptionLong' => 'Dummy Translation',
                'additionalText' => 'Dummy Translation',
                'keywords' => 'Dummy Translation',
                'packUnit' => 'Dummy Translation',
            ),
        'key' => 5,
        'shopId' => '2'
    ),
    array(
        'type' => 'article',
        'data' =>
            array(
                'name' => 'Dummy Translation',
                'description' => 'Dummy Translation',
                'descriptionLong' => 'Dummy Translation',
                'additionalText' => 'Dummy Translation',
                'keywords' => 'Dummy Translation',
                'packUnit' => 'Dummy Translation',
            ),
        'key' => 4,
        'shopId' => '2'
    )
));
```
