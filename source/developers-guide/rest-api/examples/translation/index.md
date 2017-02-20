---
layout: default
title: REST API - Examples using the translation resource
github_link: developers-guide/rest-api/examples/translation/index.md
menu_title: The translation resource
menu_order: 50
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article you can read more about using the translation resource.
The following part will show you examples including provided data and data you need to provide if you want to use this resource.
Please read **[Translation](/developers-guide/rest-api/api-resource-translation/)** if you did not yet, to get more information about the translation resource and the data it provides.
Also we are using the API client of the following document **[API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.

## Example 1 - Creating a new translation

This example shows how to create a new article translation

```php
<?php
$api->post('translations', [
        'key' => 200,            #  s_articles.id
        'type' => 'article',
        'shopId' => 2,           # s_core_shops.id
        'data' => [
                'name' => 'Dummy translation',
        ...
    ]
]);

```

## Example 2 - Updating a translation

```php
<?php
$client->put('translations/200', [ #  s_articles.id
        'type' => 'article',
        'shopId' => 2,             # s_core_shops.id
        'data' => [
                'name' => 'Dummy translation',
        ...
    ]
]);

```

## Example 3 - Creating a property group translation

```php
<?php
$api->post('translations', [
        'key' => 6,             # s_filter.id
        'type' => 'propertygroup',
        'shopId' => 2,          # s_core_shops.id
        'data' => [
            'groupName' => 'Dummy translation',
        ]
]);
```

## Example 4 - Updating a property group translation

```php
<?php
$api->post('translations/6', [  # s_filter.id
        'type' => 'propertygroup',
        'shopId' => 2,          # s_core_shops.id
        'data' => [
            'groupName' => 'Dummy translation Edited',
        ]
]);
```

## Example 5 - Creating a property option translation

```php
<?php
$api->post('translations', [
        'key' => 1,          # s_filter_options.id
        'type' => 'propertyoption',
        'shopId' => 2,       # s_core_shops.id
        'data' => [
            'optionName' => 'Dummy translation',
        ]
]);
```

## Example 6 - Updating a property option translation

```php
<?php
$api->post('translations/1', [  # s_filter_options.id
        'type' => 'propertyoption',
        'shopId' => 2,          # s_core_shops.id
        'data' => [
            'optionName' => 'Dummy translation Edited',
        ]
]);
```

## Example 7 - Creating a property value translation

```php
<?php
$api->post('translations', [
        'key' => 166,          # s_filter_values.id
        'type' => 'propertyvalue',
        'shopId' => 2,         # s_core_shops.id
        'data' => [
            'optionValue' => 'Dummy translation',
        ]
]);
```

## Example 8 - Updating a property value translation

```php
<?php
$api->post('translations/166', [ # s_filter_values.id
        'type' => 'propertyvalue',
        'shopId' => 2,           # s_core_shops.id
        'data' => [
            'optionValue' => 'Dummy translation Edited',
        ]
]);
```
