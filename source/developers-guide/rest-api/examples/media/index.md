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

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.


## Image assignment

With the variant resource it is possible to create images for variants. This configuration is implemented in two ways.

### Configuration 1

The image is assigned by the mediaId.
If the image array contains a mediaId, the resource first checks whether the media file is already assigned as an product image.
If no image with the given media ID exists, the resource creates a new product image with this media ID.

### Configuration 2

Passing an image URL.
This function is copied from the article image array. The image URL can be a local file, base64, or some other type supported by the article and media resource.
Both configurations automatically create the child data sets in <code>s_articles_img</code> and the relation for the backend configuration.

<b>Example:</b> (Updates an existing variant using the resource <code>variants</code> and assigns two types of images.)

```php
// PUT /api/variants/1042
array(
    'id' => 1042,
    'articleId' => 278,
    'images' => array(
        array('mediaId' => 2),
        array('link' => 'http://.....')
    ),
);
```

## The configuration for image assignment

In addition to the image mapping option, it is possible to create new assignments by using the article resource.
This way generates no child data sets in <code>s_articles_img</code> because a product could contain 10.000 variants. The generation for each relation would be too slow.
It is necessary to call a additional API request to generate images for each variant. 

The following example creates a product with two variants. The first variant with <code>"0,2 Liter"</code> and the second variant with <code>"0,5 Liter"</code>.
In addition the <code> "0,2 Liter"</code> variant becomes a variant image.

```php
array(
    'name' => 'Testartikel',
    'description' => 'Test description',
    'active' => true,
    'mainDetail' => array(
        'number' => 'swTEST52b04c97da770',
        'inStock' => 15,
        'unitId' => 1,
        'prices' => array(
            array('customerGroupKey' => 'EK','from' => 1,'to' => '-', 'price' => 400)
        )
    ),
    'taxId' => 1,
    'supplierId' => 2,
    'images' => array(
        array(
            'mediaId' => 236,
            'options' => array(
                array(
                    array('name' => '0,2 Liter')
                )
            )
        )
    ),
    'configuratorSet' => array(
        'name' => 'Test-Set',
        'groups' => array(
            array(
                'id' => 5,
                'name' => 'Flascheninhalt',
                'options' => array(
                    array('id' => 11, 'name' => '0,2 Liter'),
                    array('id' => 35, 'name' => '0,5 Liter'),
                )
            )
        )
    ),
    'variants' => array(
        array(
            'number' => 'swTEST52b04c97dd280',
            'inStock' => 100,
            'unitId' => 1,
            'prices' => array(
                array('customerGroupKey' => 'EK','from' => 1,'to' => '-','price' => 400)
            ),
            'configuratorOptions' => array(
                array('option' => '0,2 Liter', 'groupId' => 5)
            )
        ),
        array(
            'number' => 'swTEST52b04c97dd28f',
            'inStock' => 100,
            'unitId' => 1,
            'prices' => array(
                array('customerGroupKey' => 'EK', 'from' => 1, 'to' => '-', 'price' => 400)
            ),
            'configuratorOptions' => array(
                array('option' => '0,5 Liter', 'groupId' => 5)
            )
        )
    )
);
```

### The following example creates two assignments for the passed image.
* Show the image if the customer selects <code>"0,5 Liter"</code> and "Rot".
* Show the image if the customer selects "Blau".

```php
array(
    'name' => 'Testartikel',
    'images' => array(
        array(
            'mediaId' => 236,
            'options' => array(
                array(
                    array('name' => '0,5 Liter'),
                    array('name' => 'rot')
                ),
                array(
                    array('name' => 'blau')
                )
            ),
        ),
    ),
    'configuratorSet' => array(
        'name' => 'Test-Set',
        'groups' => array(
            array(
                'id' => 5,
                'name' => 'Flascheninhalt',
                'options' => array(
                    array('id' => 11,'name' => '0,2 Liter'),
                    array('id' => 35,'name' => '0,5 Liter'),
                    array('id' => 12,'name' => '0,7 Liter'),
                    array('id' => 32,'name' => '1,0 Liter'),
                ),
            ),
            array(
                'id' => 6,
                'name' => 'Farbe',
                'options' => array(
                    array('id' => 13,'name' => 'weiss'),
                    array('id' => 14,'name' => 'schwarz'),
                    array('id' => 15,'name' => 'blau'),
                    array('id' => 28,'name' => 'rot'),
                ),
            ),
        ),
    ),
    'variants' => array(...)
);
```

### Assignment
 
The first level of the option array defines how many assignment are created. 
You can define <code>AND / OR</code> assignment for each assignment.

```php
'options' => array(    
    array(
        array('name' => '0,5 Liter'),
        // AND
        array('name' => 'rot')
    ),
    // OR
    array(
        array('name' => 'blau')
    )
)
```

### optimize the API performance

To optimize the API performance it is necessary to send a second API request to create the child data sets in <code>s_articles_img</code>.

```php
PUT /api/generateArticleImages/1
PUT /api/generateArticleImages/SW-200?useNumberAsId=true
```




