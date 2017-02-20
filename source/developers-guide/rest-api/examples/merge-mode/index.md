---
layout: default
title: REST API - Examples using the merge mode
github_link: developers-guide/rest-api/examples/merge-mode/index.md
menu_title: The merge mode
menu_order: 80
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

<div class="toc-list"></div>

## Introduction

In this article, you will find examples of the provided resource usage for different operations. For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[category API resource](/developers-guide/rest-api/api-resource-categories/)** if you haven't yet, to get more information about the category resource and the data it provides.

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.


It is possible to change the default behavior of the API to process associated data.

## API merge Mode (Data merge)

<b>Example situation:</b>
* There exists a product with two images in the database. <code>Artikel-ID: 1</code> and <code>Bild-ID: 2, 3</code>
* The first example overwrites the images with two new images.
* The second example adds two new images.

### Example request 1: (overwrite)
```php
// PUT /api/articles/1
array(
    '__options_images' => array('replace' => true),
    'images' => array(
        112 => array(
            'mediaId' => 112,
            'main' => 1,
        ),
        113 => array(
            'mediaId' => 113,
            'main' => '2',
        )
    ),
);
```

### Example request 2: (Merge)

```php
// PUT /api/articles/1
array(
    '__options_images' => array('replace' => false),
    'images' => array(
        114 => array(
            'mediaId' => 114,
            'main' => 2,
        ),
        115 => array(
            'mediaId' => 115,
            'main' => '2',
        )
    ),
);
```

## The following collection implements the "merge mode"

```php
$articleData = array(
    // default: replace
    '__options_categories' => array('replace' => true),
    'categories' => array(
        array('id' => 13)
    ),
    // default: replace
    '__options_related' => array('replace' => true),
    'related' => array(
        array('id' => 13)
    ),
    // default: replace
    '__options_similar' => array('replace' => true),
    'similar' => array(
        array('id' => 13)
    ),
    // default: replace
    '__options_downloads' => array('replace' => true),
    'downloads' => array(
        array('id' => 13)
    ),
    // default: replace
    '__options_customerGroups' => array('replace' => true),
    'customerGroups' => array(
        array('id' => 13)
    ),
    // default: merge
    '__options_images' => array('replace' => false),
    'images' => array(
        array('id' => 13)
    ),
    // default: replace
    '__options_variants' => array('replace' => true),
    'variants' => array(
        array(
            'id' => 13
        )
    ),
    'mainDetail' => array(
        // default: replace
        '__options_prices' => array('replace' => true),
        'prices' => array(
             
        )
    )
);
```
