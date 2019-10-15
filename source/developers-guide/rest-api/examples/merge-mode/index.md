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

It is possible to change the default behavior of the API to process associated data.

## API merge Mode (Data merge)

**Example situation:**
* There exists a product with two images in the database. `product-id: 1` and `image-id: [2, 3]`
* The first example overwrites the images with two new images.
* The second example adds two new images.

### Example request 1: (overwrite)

{% include 'api_badge.twig' with {'route': '/api/articles/1', 'method': 'PUT', 'body': true} %}
```json
{
    "__options_images": {
        "replace": true
    },
    "images": {
        "112": {
            "mediaId": 112,
            "main": 1
        },
        "113": {
            "mediaId": 113,
            "main": "2"
        }
    }
}
```

### Example request 2: (Merge)

{% include 'api_badge.twig' with {'route': '/api/articles/1', 'method': 'PUT', 'body': true} %}
```json
{
    "__options_images": {
        "replace": false
    },
    "images": {
        "114": {
            "mediaId": 114,
            "main": 2
        },
        "115": {
            "mediaId": 115,
            "main": "2"
        }
    }
}
```

## Entities implementing the "merge mode"

The following list contains all entities implementing the merge mode.
The replace value given here represents the default value which is used
when no value for `replace` is given in the request body.

A default value of `replace: true` means, that the existing entity should
be overwritten, `replace: false` means, that the existing entities should
be merged with the new ones provided in the API-request.

```json
{
    "__options_categories": {
        "replace": true
    },
    "categories": [
        {
            "id": 13
        }
    ],
    "__options_related": {
        "replace": true
    },
    "related": [
        {
            "id": 13
        }
    ],
    "__options_similar": {
        "replace": true
    },
    "similar": [
        {
            "id": 13
        }
    ],
    "__options_downloads": {
        "replace": true
    },
    "downloads": [
        {
            "id": 13
        }
    ],
    "__options_customerGroups": {
        "replace": true
    },
    "customerGroups": [
        {
            "id": 13
        }
    ],
    "__options_images": {
        "replace": false
    },
    "images": [
        {
            "id": 13
        }
    ],
    "__options_variants": {
        "replace": true
    },
    "variants": [
        {
            "id": 13
        }
    ],
    "mainDetail": {
        "__options_prices": {
            "replace": true
        },
        "prices": []
    }
}
```
