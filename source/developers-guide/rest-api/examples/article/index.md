---
layout: default
title: REST API - Examples using the product resource
github_link: developers-guide/rest-api/examples/article/index.md
menu_title: Product examples
menu_order: 40
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

<div class="toc-list"></div>

## Introduction

In this article, you will find examples of the `article` resource usage for different operations.
For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[article API resource](/developers-guide/rest-api/api-resource-article/)** if you haven't yet,
to get more information about the article resource and the data it provides.

## Example 1 - GET
These example show you how to obtain information about a single product, by using either its ID or product number.
The API calls look, respectively, like this:

{% include 'api_badge.twig' with {'route': '/api/articles/3', 'method': 'GET'} %}

{% include 'api_badge.twig' with {'route': '/api/articles/SW10003?useNumberAsId=true', 'method': 'GET'} %}

### Result:

```json
{
   "data":{
      "id":3,
      "mainDetailId":3,
      "supplierId":2,
      "taxId":1,
      "priceGroupId":null,
      "filterGroupId":1,
      "configuratorSetId":null,
      "name":"M\u00fcnsterl\u00e4nder Aperitif 16%",
      "description":"ubi ait animadverto poema adicio",
      "descriptionLong":"<p>Poraliter Sufficio, cum aut pax se Erro, diu Ingressus qui Honestas roto vos hos vix Distinguo humus dignor. Cui leno ex suspicor Amor quibus res occido Consido oro noster lauvabrum sed Inquam haec eia Cumulus, ius lux Castr [...] ",
      "added":"2012-08-15T00:00:00+0200",
      "active":true,
      "pseudoSales":30,
      "highlight":false,
      "keywords":"",
      "metaTitle":null,
      "changed":"2012-08-20T15:16:45+0200",
      "priceGroupActive":false,
      "lastStock":false,
      "crossBundleLook":0,
      "notification":false,
      "template":"",
      "mode":0,
      "availableFrom":null,
      "availableTo":null,
      "mainDetail":{
         "id":3,
         "articleId":3,
         "unitId":1,
         "number":"SW10003",
         "supplierNumber":"",
         "kind":1,
         "additionalText":"",
         "active":1,
         "inStock":25,
         "stockMin":0,
         "weight":"0.000",
         "width":null,
         "len":null,
         "height":null,
         "ean":null,
         "position":0,
         "minPurchase":null,
         "purchaseSteps":null,
         "maxPurchase":null,
         "purchaseUnit":"0.7000",
         "referenceUnit":"1.000",
         "packUnit":"Flasche(n)",
         "shippingFree":false,
         "releaseDate":"2012-06-13T00:00:00+0200",
         "shippingTime":"",
         "prices":[
            {
               "id":3,
               "articleId":3,
               "articleDetailsId":3,
               "customerGroupKey":"EK",
               "from":1,
               "to":"beliebig",
               "price":12.563025210084,
               "pseudoPrice":0,
               "basePrice":0,
               "percent":0,
               "regulationPrice":0,
               "customerGroup":{
                  "id":1,
                  "key":"EK",
                  "name":"Shopkunden",
                  "tax":true,
                  "taxInput":true,
                  "mode":false,
                  "discount":0,
                  "minimumOrder":10,
                  "minimumOrderSurcharge":5
               }
            }
         ],
         "attribute":{
            "id":3,
            "articleId":3,
            "articleDetailId":3,
            "attr1":"",
            "attr2":"",
            "attr3":"",
            "attr4":"",
            "attr5":"",
            "attr6":"",
            "attr7":"",
            "attr8":"",
            "attr9":"",
            "attr10":"",
            "attr11":"",
            "attr12":"",
            "attr13":"",
            "attr14":"",
            "attr15":"",
            "attr16":"",
            "attr17":null,
            "attr18":"",
            "attr19":"",
            "attr20":""
         },
         "configuratorOptions":[

         ]
      },
      "tax":{
         "id":1,
         "tax":"19.00",
         "name":"19%"
      },
      "propertyValues":[
         {
            "id":24,
            "value":"0,7 Liter",
            "position":2,
            "optionId":2,
            "valueNumeric":"0.00"
         },
         {
            "id":28,
            "value":"rot",
            "position":2,
            "optionId":4,
            "valueNumeric":"0.00"
         },
         {
            "id":34,
            "value":"fruchtig",
            "position":2,
            "optionId":6,
            "valueNumeric":"0.00"
         },
         {
            "id":35,
            "value":"Gek\u00fchlt",
            "position":0,
            "optionId":7,
            "valueNumeric":"0.00"
         },
         {
            "id":39,
            "value":"< 20%",
            "position":7,
            "optionId":1,
            "valueNumeric":"0.00"
         }
      ],
      "supplier":{
         "id":2,
         "name":"Feinbrennerei Sasse",
         "image":"media\/image\/sasse.png",
         "link":"http:\/\/www.sassekorn.de",
         "description":"",
         "metaTitle":null,
         "metaDescription":null,
         "metaKeywords":null
      },
      "propertyGroup":{
         "id":1,
         "name":"Edelbr\u00e4nde",
         "position":0,
         "comparable":true,
         "sortMode":2
      },
      "customerGroups":[

      ],
      "images":[
         {
            "id":6,
            "articleId":3,
            "articleDetailId":null,
            "description":"",
            "path":"Muensterlaender_Aperitif_Flasche",
            "main":1,
            "position":1,
            "width":0,
            "height":0,
            "relations":"",
            "extension":"jpg",
            "parentId":null,
            "mediaId":8
         }
      ],
      "configuratorSet":null,
      "links":[
         {
            "id":2,
            "articleId":3,
            "name":"Feinbrennerei Sasse",
            "link":"http:\/\/www.sassekorn.de\/",
            "target":""
         }
      ],
      "downloads":[

      ],
      "categories":{
         "14":{
            "id":14,
            "name":"Edelbr\u00e4nde"
         },
         "21":{
            "id":21,
            "name":"Produktvergleiche & Filter"
         },
         "50":{
            "id":50,
            "name":"Brandies"
         }
      },
      "similar":[
         {
            "id":2,
            "name":"M\u00fcnsterl\u00e4nder Lagerkorn 32%"
         },
         {
            "id":4,
            "name":"Latte Macchiato 17%"
         },
         {
            "id":5,
            "name":"Emmelkamp Holunder Lik\u00f6r 18%"
         },
         {
            "id":6,
            "name":"Cigar Special 40%"
         }
      ],
      "related":[
         {
            "id":10,
            "name":"Aperitif-Glas Demi Sec"
         }
      ],
      "details":[

      ],
      "seoCategories":[

      ],
      "translations":{
         "2":{
            "name":"Munsterland Aperitif 16%",
            "descriptionLong":"<p>Io copia moeror immo pro audio modestia. Permaneo animosus etsi furax, aversor, faenum Pecus, mus me dux ferociter interpellatio certo. infrequentia Illis Quamquam Invidus, indutus [...] ",
            "shopId":2
         }
      }
   },
   "success":true
}
```

## Example 2 - GET (List)
This example shows you how to obtain information about a product list.
With the optional `limit` parameter, it's possible to specify how many products you wish the API call to return.

{% include 'api_badge.twig' with {'route': '/api/articles?limit=2', 'method': 'GET'} %}

A maximum of 1000 entries is returned. By using the `start` parameter you can get the following entries.

{% include 'api_badge.twig' with {'route': '/api/articles?start=1001', 'method': 'GET'} %}

### Result
```json
{
   "data":[
      {
         "id":3,
         "mainDetailId":3,
         "supplierId":2,
         "taxId":1,
         "priceGroupId":null,
         "filterGroupId":1,
         "configuratorSetId":null,
         "name":"M\u00fcnsterl\u00e4nder Aperitif 16%",
         "description":"ubi ait animadverto poema adicio",
         "descriptionLong":"<p>Poraliter Sufficio, cum aut pax se Erro, diu Ingressus qui Honestas roto vos hos vix Distinguo humus dignor. Cui leno ex suspicor Amor quibus res occido Consido oro noster lauvabrum sed Inquam haec eia Cumulus, ius lux Castrum ver Fatuo ymo res for Animus laxe Novem nec Teneo. Ego macto re stupeo Labor sus, ver ex aut exhorto sis aliter foetidus expono. Sensus apud latrocinor, impenetrabiilis far incrementabiliter Commodo cum mel voluptarius Pariter modicus opto coepto, maligo spes Resono Curvo escendo adsum per Frutex, ubi ait animadverto poema, adicio Consonum archipater sum Aeger Dux prius edo paterna precipue, cunae declaratio per dolositas Huic quod Sis canalis quam nam fio Insidiae, si pax Cupido, ut Tergo, ac Cui per quo processus Disputo sui Infucatus leo, ait ops, duo Prodoceo par Verber, nec Uberrime alo Scelestus, res Tellus mei Escensio Mundus, ita liber qui has inconsideratus nauta effrenus, Algor infrunitus, inconcussus Rogo eo non Namucense, commissum, laureatus Scutum,.<\/p>",
         "added":"2012-08-15T00:00:00+0200",
         "active":true,
         "pseudoSales":30,
         "highlight":false,
         "keywords":"",
         "metaTitle":null,
         "changed":"2012-08-20T15:16:45+0200",
         "priceGroupActive":false,
         "lastStock":false,
         "crossBundleLook":0,
         "notification":false,
         "template":"",
         "mode":0,
         "availableFrom":null,
         "availableTo":null
      },
      {
         "id":4,
         "mainDetailId":4,
         "supplierId":2,
         "taxId":1,
         "priceGroupId":null,
         "filterGroupId":1,
         "configuratorSetId":null,
         "name":"Latte Macchiato 17%",
         "description":"Fas Proinde quandoquidem sol Iubeo quasi imago C",
         "descriptionLong":"<p>Spiro praeclarus Desero alica for Amoena, qui apto Zephyr fabre Felix era Ferratus prosum amicabiliter, ops statua ops is Labo curriculum Paene tum lea aut plane Subdo eia Permetior luxuria. Se Velamen ora advoco.Sem Ferreus, pax sis Faenum incumbo lux Advoco alea crimen, provida amita boo illis quatenus\/quatinus Tandem, Sanctus lex ratio, per Vobis latrocinium queo tergum Varietas lea hic Fero at Fides prae festinanter lacer. Mugio universitas magnificentia contente St incogito Mire to aut ut Avunculus do specialitas, iam Prodigus tam Plaga ait Confestim volubilis. Ymo Humilitas, ex palpo Obses te ruo praetermissio, senex cum Stips sed vas sesquimellesimus nemo, fas differo, sui episcopalis Inhabito me cornu, hos induco veho, ars saevio, emo lac, Cito eia. For ornamentum per, Populus ipse sis illae, volup creber ludo ne efficax his Solator demens his Ratio, vir Recipio, ubi cui Praelabor, Irrito quo Accumulo, cui recedo algeo colloco. Fas Proinde quandoquidem sol Iubeo quasi imago C.<\/p>",
         "added":"2012-06-13T00:00:00+0200",
         "active":true,
         "pseudoSales":0,
         "highlight":true,
         "keywords":"",
         "metaTitle":null,
         "changed":"2012-08-20T15:20:08+0200",
         "priceGroupActive":false,
         "lastStock":false,
         "crossBundleLook":0,
         "notification":false,
         "template":"",
         "mode":0,
         "availableFrom":null,
         "availableTo":null
      }
   ],
   "total":225,
   "success":true
}
```

## Example 3 - Update product data
To update a product it's always required to provide the id of the product to update.
In this example, we will update the name of the product with the id 3

{% include 'api_badge.twig' with {'route': '/api/articles/3', 'method': 'PUT', 'body': true} %}
```json
{
  "name": "NewName"
}
```

### Result
```json
{
   "success":true,
   "data":{
      "id":3,
      "location":"http:\/\/localhost\/master\/api\/articles\/3"
   }
}
```

## Example 4 - Delete a product
To delete a product, it's always required to provide the id of the product to delete.
If the number is provided, an error will be returned with the response code 500.

**Attention, this action can not be undone**

{% include 'api_badge.twig' with {'route': '/api/articles/3', 'method': 'DELETE'} %}

### Result
```json
{
    "success": true
}
```

## Example 5 - Product configuration / variation

### Step 1 - Create a new product

{% include 'api_badge.twig' with {'route': '/api/articles', 'method': 'POST', 'body': true} %}
```json
{
    "name": "Sport Shoes",
    "active": true,
    "tax": 19,
    "supplier": "Sport Shoes Inc.",
    "categories": [
        {
            "id": 15
        }
    ],
    "mainDetail": {
        "number": "turn",
        "active": true,
        "prices": [
            {
                "customerGroupKey": "EK",
                "price": 999
            }
        ]
    }
}
```


### Step 2 - Update the created product

{% include 'api_badge.twig' with {'route': '/api/articles/193', 'method': 'PUT', 'body': true} %}
```json
{
    "configuratorSet": {
        "groups": [
            {
                "name": "Size",
                "options": [
                    {
                        "name": "S"
                    },
                    {
                        "name": "M"
                    },
                    {
                        "name": "L"
                    },
                    {
                        "name": "XL"
                    },
                    {
                        "name": "XXL"
                    }
                ]
            },
            {
                "name": "Color",
                "options": [
                    {
                        "name": "White"
                    },
                    {
                        "name": "Yellow"
                    },
                    {
                        "name": "Blue"
                    },
                    {
                        "name": "Black"
                    },
                    {
                        "name": "Red"
                    }
                ]
            }
        ]
    },
    "taxId": 1,
    "variants": [
        {
            "isMain": true,
            "number": "turn",
            "inStock": 15,
            "additionaltext": "L \/ Black",
            "configuratorOptions": [
                {
                    "group": "Size",
                    "option": "L"
                },
                {
                    "group": "Color",
                    "option": "Black"
                }
            ],
            "prices": [
                {
                    "customerGroupKey": "EK",
                    "price": 1999
                }
            ]
        },
        {
            "isMain": false,
            "number": "turn.1",
            "inStock": 15,
            "additionaltext": "S \/ Black",
            "configuratorOptions": [
                {
                    "group": "Size",
                    "option": "S"
                },
                {
                    "group": "Color",
                    "option": "Black"
                }
            ],
            "prices": [
                {
                    "customerGroupKey": "EK",
                    "price": 999
                }
            ]
        },
        {
            "isMain": false,
            "number": "turn.2",
            "inStock": 15,
            "additionaltext": "S \/ Red",
            "configuratorOptions": [
                {
                    "group": "Size",
                    "option": "S"
                },
                {
                    "group": "Color",
                    "option": "Red"
                }
            ],
            "prices": [
                {
                    "customerGroupKey": "EK",
                    "price": 999
                }
            ]
        },
        {
            "isMain": false,
            "number": "turn.3",
            "inStock": 15,
            "additionaltext": "XL \/ Red",
            "configuratorOptions": [
                {
                    "group": "Size",
                    "option": "XL"
                },
                {
                    "group": "Color",
                    "option": "Red"
                }
            ],
            "prices": [
                {
                    "customerGroupKey": "EK",
                    "price": 999
                }
            ]
        }
    ]
}
```

### Result

```json
{
    "success": true
}
```

## Example 6 - Product Properties

It's also possible to add product properties using the `article` resource.
In order to perform this action, an array like this is required:

{% include 'api_badge.twig' with {'route': '/api/articles', 'method': 'POST', 'body': true} %}
```json
{
    "name": "My awesome liquor",
    "description": "hmmmmm",
    "active": true,
    "taxId": 1,
    "mainDetail": {
        "number": "brand1",
        "inStock": 15,
        "active": true,
        "prices": [
            {
                "customerGroupKey": "EK",
                "from": 1,
                "price": 50
            }
        ]
    },
    "filterGroupId": 1,
    "propertyValues": [
        {
            "option": {
                "name": "Alcohol content"
            },
            "value": "10%"
        },
        {
            "option": {
                "name": "Color"
            },
            "value": "rot"
        }
    ]
}
```

Options (`option`) and values (`value`) can be identified by name (see example above) or by identifier (`id`).
Since the values within option and options are unique in groups,
it's possible to automatically recognize if a new one has to be added to the shop, or if an existing entry has to be updated.
PropertyGroups (`filterGroupID`) have to be added through another resource **[PropertyGroups](../../api-resource-property-group)**.

{% include 'api_badge.twig' with {'route': '/api/propertyGroups', 'method': 'POST', 'body': true} %}
```json
{
    "name": "Liquor",
    "position": 1,
    "comparable": 1,
    "sortmode": 2
}
```
The returned identifier may be set as `filterGroupId`, just like the example `$filterTest` shows.

## Example 7 - Link new or existing images to a property

Its possible to assign images to a specific product property.

{% include 'api_badge.twig' with {'route': '/api/articles', 'method': 'POST', 'body': true} %}
```json
{
    "name": "My awesome liquor",
    "description": "hmmmmm",
    "active": true,
    "taxId": 1,
    "mainDetail": {
        "number": "brand1",
        "inStock": 15,
        "active": true,
        "prices": [
            {
                "customerGroupKey": "EK",
                "from": 1,
                "price": 50
            }
        ]
    },
    "images": [
        {
            "link": "http:\/\/example.org\/test.jpg",
            "main": 1,
            "position": 1,
            "options": {
                "name": "Alcohol content"
            }
        },
        {
            "mediaId": 57,
            "main": 0,
            "position": 2,
            "options": {
                "name": "Color"
            }
        }
    ],
    "filterGroupId": 1,
    "propertyValues": [
        {
            "option": {
                "name": "Alcohol content"
            },
            "value": "10%"
        },
        {
            "option": {
                "name": "Color"
            },
            "value": "rot"
        }
    ]
}
```

## Example 8 - Creating and referencing units

It's possible to specify units using the `unit` key. The snippet below shows how:

{% include 'api_badge.twig' with {'route': '/api/articles', 'method': 'POST', 'body': true} %}
```json
{
    "name": "Sport shoes",
    "tax": 19,
    "supplier": "Sport shoes Inc.",
    "active": true,
    "mainDetail": {
        "number": "turn33",
        "active": true,
        "prices": [
            {
                "customerGroupKey": "EK",
                "price": 999
            }
        ],
        "unit": {
            "unit": "xyz",
            "name": "New Unit"
        }
    }
}

```

The API itself checks if the unit already exist.
If it does, the old unit will be overwritten with the new values, otherwise it simply will be added as new unit to the shop.

## Further examples

### Linking images

{% include 'api_badge.twig' with {'route': '/api/articles', 'method': 'POST', 'body': true} %}
```json
{
    "name": "NewTestArticle",
    "active": true,
    "tax": 19,
    "supplier": "Test Supplier",
    "categories": [
        {
            "id": 15
        },
        {
            "id": 16
        }
    ],
    "images": [
        {
            "link": "http:\/\/lorempixel.com\/640\/480\/food\/"
        },
        {
            "link": "http:\/\/lorempixel.com\/640\/480\/food\/"
        }
    ],
    "mainDetail": {
        "number": "swTEST5d9b1a3c6521f",
        "active": true,
        "inStock": 16,
        "prices": [
            {
                "customerGroupKey": "EK",
                "price": 99.34
            }
        ]
    }
}
```

### Updating the number of products in stock

{% include 'api_badge.twig' with {'route': '/api/articles/3', 'method': 'PUT', 'body': true} %}
```json
{
    "mainDetail": {
        "inStock": 66
    }
}
```

### Updating the number of variants in stock

{% include 'api_badge.twig' with {'route': '/api/articles/205', 'method': 'PUT', 'body': true} %}
```json
{
    "variants": [
        {
            "id": 726,
            "inStock": 99
        },
        {
            "number": "SW10204.5",
            "inStock": 999
        }
    ]
}
```

### Creating a configuratorSet and variants

{% include 'api_badge.twig' with {'route': '/api/articles', 'method': 'POST', 'body': true} %}
```json
{
    "name": "ConfiguratorTest",
    "description": "A test article",
    "descriptionLong": "<p>I'm a **test article<\/b><\/p>",
    "active": true,
    "taxId": 1,
    "supplierId": 2,
    "categories": [
        {
            "id": 15
        }
    ],
    "mainDetail": {
        "number": "swTEST5d9b1a9cbcc9d",
        "active": true,
        "prices": [
            {
                "customerGroupKey": "EK",
                "price": 999
            }
        ]
    },
    "configuratorSet": {
        "groups": [
            {
                "name": "Size",
                "options": [
                    {
                        "name": "S"
                    },
                    {
                        "name": "M"
                    },
                    {
                        "name": "L"
                    },
                    {
                        "name": "XL"
                    },
                    {
                        "name": "XXL"
                    }
                ]
            },
            {
                "name": "Color",
                "options": [
                    {
                        "name": "White"
                    },
                    {
                        "name": "Yellow"
                    },
                    {
                        "name": "Blue"
                    },
                    {
                        "name": "Black"
                    }
                ]
            }
        ]
    },
    "variants": [
        {
            "isMain": true,
            "number": "swTEST5d9b1a9cbcc9f",
            "inStock": 15,
            "additionaltext": "S \/ Schwarz",
            "configuratorOptions": [
                {
                    "group": "Size",
                    "option": "S"
                },
                {
                    "group": "Color",
                    "option": "Black"
                }
            ],
            "prices": [
                {
                    "customerGroupKey": "EK",
                    "price": 999
                }
            ]
        },
        {
            "number": "swTEST5d9b1a9cbcca0",
            "inStock": 10,
            "additionaltext": "S \/ Wei\u00df",
            "configuratorOptions": [
                {
                    "group": "Size",
                    "option": "S"
                },
                {
                    "group": "Color",
                    "option": "White"
                }
            ],
            "prices": [
                {
                    "customerGroupKey": "EK",
                    "price": 888
                }
            ],
            "attribute": {
                "attr1": "S\/White Attr1",
                "attr2": "SomeText"
            }
        },
        {
            "number": "swTEST5d9b1a9cbcca1",
            "inStock": 5,
            "additionaltext": "XL \/ Blue",
            "configuratorOptions": [
                {
                    "group": "Size",
                    "option": "XL"
                },
                {
                    "group": "Color",
                    "option": "Blue"
                }
            ],
            "prices": [
                {
                    "customerGroupKey": "EK",
                    "price": 555
                }
            ]
        }
    ]
}
```

### Updating the SEO category of a product

{% include 'api_badge.twig' with {'route': '/api/articles/SW10239?useNumberAsId=true', 'method': 'PUT', 'body': true} %}
```json
{
    "seoCategories": [
        {
            "shopId": 1,
            "categoryId": 15
        }
    ],
    "categories": [
        {
            "id": 15
        }
    ]
}
```

If you just want to add another seo category, you have to add the value: `__options_seoCategories' => false`
