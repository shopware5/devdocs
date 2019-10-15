---
layout: default
title: REST API - Examples using the media resource
github_link: developers-guide/rest-api/examples/media/index.md
menu_title: The media resource
menu_order: 70
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

<div class="toc-list"></div>

## Introduction

In this article, you will find examples of the provided resource usage for different operations. For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[media API resource](/developers-guide/rest-api/api-resource-media/)** if you haven't yet, to get more information about the media resource and the data it provides.

## Image assignment

With the variant resource it is possible to create images for variants. This configuration is implemented in two ways.

### Configuration 1

The image is assigned by the mediaId.
If the image array contains a mediaId, the resource first checks whether the media file is already assigned as an product image.
If no image with the given media ID exists, the resource creates a new product image with this media ID.

### Configuration 2

Passing an image URL.
This function is copied from the article image array. The image URL can be a local file, base64, or some other type supported by the article and media resource.

**Types:**
http, https, file, ftp, ftps
Example for file on server `file:///var/www/shopware/media/upload/test.jpg`

Both configurations automatically create the child data sets in `s_articles_img` and the relation for the backend configuration.

**Example:** (Updates an existing variant using the resource `variants` and assigns two types of images.)

{% include 'api_badge.twig' with {'route': '/api/variants/1042', 'method': 'PUT', 'body': true} %}
```json
{
    "id": 1042,
    "articleId": 278,
    "images": [
        {
            "mediaId": 2
        },
        {
            "link": "http:\/\/example.com\/example.png"
        }
    ]
}
```

## The configuration for image assignment

In addition to the image mapping option, it is possible to create new assignments by using the article resource.
This way generates no child data sets in `s_articles_img` because a product could contain 10.000 variants. The generation for each relation would be too slow.
It is necessary to call a additional API request to generate images for each variant. 

The following example creates a product with two variants. The first variant with `"0,2 Liter"` and the second variant with `"0,5 Liter"`.
In addition a variant image is assigned to the `"0,2 Liter"` variant.

{% include 'api_badge.twig' with {'route': '/api/articles', 'method': 'POST', 'body': true} %}
```json
{
    "name": "Testartikel",
    "description": "Test description",
    "active": true,
    "mainDetail": {
        "number": "swTEST52b04c97da770",
        "inStock": 15,
        "unitId": 1,
        "prices": [
            {
                "customerGroupKey": "EK",
                "from": 1,
                "to": "-",
                "price": 400
            }
        ]
    },
    "taxId": 1,
    "supplierId": 2,
    "images": [
        {
            "mediaId": 236,
            "options": [
                [
                    {
                        "name": "0,2 Liter"
                    }
                ]
            ]
        }
    ],
    "configuratorSet": {
        "name": "Test-Set",
        "groups": [
            {
                "id": 5,
                "name": "Flascheninhalt",
                "options": [
                    {
                        "id": 11,
                        "name": "0,2 Liter"
                    },
                    {
                        "id": 35,
                        "name": "0,5 Liter"
                    }
                ]
            }
        ]
    },
    "variants": [
        {
            "number": "swTEST52b04c97dd280",
            "inStock": 100,
            "unitId": 1,
            "prices": [
                {
                    "customerGroupKey": "EK",
                    "from": 1,
                    "to": "-",
                    "price": 400
                }
            ],
            "configuratorOptions": [
                {
                    "option": "0,2 Liter",
                    "groupId": 5
                }
            ]
        },
        {
            "number": "swTEST52b04c97dd28f",
            "inStock": 100,
            "unitId": 1,
            "prices": [
                {
                    "customerGroupKey": "EK",
                    "from": 1,
                    "to": "-",
                    "price": 400
                }
            ],
            "configuratorOptions": [
                {
                    "option": "0,5 Liter",
                    "groupId": 5
                }
            ]
        }
    ]
}
```

### The following example creates two assignments for the passed image.
* Show the image if the customer selects `"0,5 Liter"` and "Rot".
* Show the image if the customer selects "Blau".

{% include 'api_badge.twig' with {'route': '/api/articles/1234', 'method': 'PUT', 'body': true} %}
```json
{
    "name": "Testartikel",
    "images": [
        {
            "mediaId": 236,
            "options": [
                [
                    {
                        "name": "0,5 Liter"
                    },
                    {
                        "name": "rot"
                    }
                ],
                [
                    {
                        "name": "blau"
                    }
                ]
            ]
        }
    ],
    "configuratorSet": {
        "name": "Test-Set",
        "groups": [
            {
                "id": 5,
                "name": "Flascheninhalt",
                "options": [
                    {
                        "id": 11,
                        "name": "0,2 Liter"
                    },
                    {
                        "id": 35,
                        "name": "0,5 Liter"
                    },
                    {
                        "id": 12,
                        "name": "0,7 Liter"
                    },
                    {
                        "id": 32,
                        "name": "1,0 Liter"
                    }
                ]
            },
            {
                "id": 6,
                "name": "Farbe",
                "options": [
                    {
                        "id": 13,
                        "name": "weiss"
                    },
                    {
                        "id": 14,
                        "name": "schwarz"
                    },
                    {
                        "id": 15,
                        "name": "blau"
                    },
                    {
                        "id": 28,
                        "name": "rot"
                    }
                ]
            }
        ]
    },
    "variants": [
      {}
    ]
}
```

### Assignment
 
The first level of the option array defines how many assignment are created. 
You can define `AND / OR` assignment for each assignment.

```json
{
    "configuratorSet": {
        // ...
        "groups": [
            {
            "options": [
                    [
                        {
                            "name": "0,5 Liter"
                        },
                        // AND
                        {
                            "name": "rot"
                        }
                    ],
                    // OR
                    [
                        {
                            "name": "blau"
                        }
                    ]
                ]
            }
        ]
    }
}
```

### optimize the API performance

To optimize the API performance it is necessary to send a second API request to create the child data sets in `s_articles_img`.

{% include 'api_badge.twig' with {'route': '/api/generateArticleImages/1', 'method': 'PUT'} %}

{% include 'api_badge.twig' with {'route': '/api/generateArticleImages/SW-20001?useNumberAsId=true', 'method': 'PUT'} %}





