---
layout: default
title: REST API - Examples using the article resource
github_link: developers-guide/rest-api/examples/article/index.md
menu_title: The article resource
menu_order: 20
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

<div class="toc-list"></div>

## Introduction

In this article, you will find examples of the article resource usage for different operations. For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[article API resource](/developers-guide/rest-api/api-resource-article/)** if you haven't yet, to get more information about the article resource and the data it provides.

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**. One of its advantages is that, instead of providing query arguments directly in the URL, you can do so by means of method argument. The client application will, internally, handle the full URL generation. You can also place variables using this technique. An example call would look like this:

```
$client->post('articles', array(
    'name' => 'NewId',
    'taxId' => 1,
    'mainDetail' => array(
        'number' => 'SW123456'
    )
));
```

## Example 1 - GET
These example show you how to obtain information about a single article, by using either its ID or article number. The API calls look, respectively, like this:
```
$client->get('articles/3');
$client->get('articles/SW10003?useNumberAsId=true');
```

### Result:

```
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
            "descriptionLong":"<p>Io copia moeror immo pro audio modestia. Permaneo animosus etsi furax, aversor, faenum Pecus, mus me dux ferociter interpellatio certo. infrequentia Illis Quamquam Invidus, indutus [...] "
            "shopId":2
         }
      }
   },
   "success":true
}
```

## Example 2 - GET (List)
This example shows you how to obtain information about a single article.
With the optional `limit` parameter, it's possible to specify how many articles you wish the API call to return.

```
$client->get('articles');
$client->get('articles?limit=2);
```

### Result
```
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

## Example 3 - Update article data
To update an article it's always required to provide the id of the article to update.
In this example, we will update the name of the article with the id 3
```
$client->put('articles/3', array(
    'name' => 'NewName'
));
```
### Result
```
{
   "success":true,
   "data":{
      "id":3,
      "location":"http:\/\/localhost\/master\/api\/articles\/3"
   }
}
```

## Example 4 - Delete an article
To delete an article, it's always required to provide the id of the article to delete. If the number is provided, an error will be returned with the response code 500.

**Attention, this action can not be undone**

```
$client->delete('articles/3');
```

### Result
```
{
    success: true
}
```

## Example 5 - Article configuration / variation

### Step 1 - Create a new article
```
$minimalTestArticle = array(
    'name' => 'Sport Shoes',
    'active' => true,
    'tax' => 19,
    'supplier' => 'Sport Shoes Inc.',
    'categories' => array(
        array('id' => 15),
    ),
    'mainDetail' => array(
        'number' => 'turn',
        'active' => true,
        'prices' => array(
            array(
                'customerGroupKey' => 'EK',
                'price' => 999,
            ),
        )
    ),
);

$client->post('articles', $minimalTestArticle);


```


### Step 2 - Update the created article
```
$updateArticle = array(
   'configuratorSet' => array(
        'groups' => array(
            array(
                'name' => 'Size',
                'options' => array(
                    array('name' => 'S'),
                    array('name' => 'M'),
                    array('name' => 'L'),
                    array('name' => 'XL'),
                    array('name' => 'XXL'),
                )
            ),
            array(
                'name' => 'Color',
                'options' => array(
                    array('name' => 'White'),
                    array('name' => 'Yellow'),
                    array('name' => 'Blue'),
                    array('name' => 'Black'),
                    array('name' => 'Red'),
                )
            ),
        )
    ),
    'taxId'      => 1,
    'variants' => array(
        array(
            'isMain' => true,
            'number' => 'turn',
            'inStock' => 15,
            'additionaltext' => 'L / Black',
            'configuratorOptions' => array(
                array('group' => 'Size', 'option' => 'L'),
                array('group' => 'Color', 'option' => 'Black'),
            ),
            'prices' => array(
                array(
                    'customerGroupKey' => 'EK',
                    'price' => 1999,
                ),
            )
        ),
        array(
            'isMain' => false,
            'number' => 'turn.1',
            'inStock' => 15,
            'additionnaltext' => 'S / Black',
            'configuratorOptions' => array(
                array('group' => 'Size', 'option' => 'S'),
                array('group' => 'Color', 'option' => 'Black'),
            ),
            'prices' => array(
                array(
                    'customerGroupKey' => 'EK',
                    'price' => 999,
                ),
            )
        ),
        array(
            'isMain' => false,
            'number' => 'turn.2',
            'inStock' => 15,
            'additionnaltext' => 'S / Red',
            'configuratorOptions' => array(
                array('group' => 'Size', 'option' => 'S'),
                array('group' => 'Color', 'option' => 'Red'),
            ),
            'prices' => array(
                array(
                    'customerGroupKey' => 'EK',
                    'price' => 999,
                ),
            )
        ),
        array(
            'isMain' => false,
            'number' => 'turn.3',
            'inStock' => 15,
            'additionnaltext' => 'XL / Red',
            'configuratorOptions' => array(
                array('group' => 'Size', 'option' => 'XL'),
                array('group' => 'Color', 'option' => 'Red'),
            ),
            'prices' => array(
                array(
                    'customerGroupKey' => 'EK',
                    'price' => 999,
                ),
            )
        )
    )
);

$client->put('articles/193', $updateArticle);

```

### Result

```
{
    success: true
}
```

## Example 6 - Article Properties

It's also possible to add article properties using the article resource.
In order to perform this action, an array like this is required:

```
$filterTest = array(
    'name' => 'My awesome liquor',
    'description' => 'hmmmmm',

    'active' => true,
    'taxId'      => 1,

    'mainDetail' => array(
        'number' => 'brand1',
        'inStock' => 15,
        'active' => true,

        'prices' => array(
            array(
                'customerGroupKey' => 'EK',
                'from'  => 1,
                'price' => 50
            )
        )
    ),

    'filterGroupId' => 1,
    'propertyValues' => array(
        array(
            'option' => array('name' => "Alcohol content"),
            'value' => '10%'
        ),
        array(
            'option' => array('name' => "Color"),
            'value' => 'rot'
        )
    )
);

$client->post('articles', $filterTest);

```

Options (`option`) and values (`value`) can be identified by name (see example above) or by identifier (`id`). Since the values within option and options are unique in groups, it's possible to automatically recognize if a new one has to be added to the shop, or if an existing entry has to be updated. PropertyGroups (`filterGroupID`) have to be added through another resource **[PropertyGroups](../../api-resource-property-group)**.

```
$properties = array(
     "name" => "Liquor",
     'position' => 1,
     'comparable' => 1,
     'sortmode' => 2
);

$client->post('propertyGroups', $properties);

```
The returned identifier may be set as `filterGroupId`, just like the example `$filterTest` shows.

## Example 7 - Creating and referencing units

It's possible to specify units using the `unit` key. The snippet below shows how:

```
$articleWithUnit = array(
    'name' => 'Sport shoes',
    'tax' => 19,
    'supplier' => 'Sport shoes Inc.',

    'mainDetail' => array(
        'number' => 'turn33',
        'prices' => array(
            array(
                'customerGroupKey' => 'EK',
                'price' => 999,
            ),
        ),
        'unit' => array(
            'unit' => 'xyz',
            'name' => 'New Unit'
        )
    ),
);

$client->post('articles', articleWithUnit);

```

The API itself checks if the unit already exist. If it does, the old unit will be overwritten with the new values, otherwise it simply will be added as new unit to the shop.

## Further examples

```
$testArticle = array(
    'name'     => 'NewTestArticle',
    'active'   => true,
    'tax'      => 19,          // alternatively 'taxId' => 1,
    'supplier' => 'Test Supplier', // alternatively 'supplierId' => 2,

    'categories' => array(
        array('id' => 15),
        array('id' => 16),
    ),

    'images' => array(
        array('link' => 'http://lorempixel.com/640/480/food/'),
        array('link' => 'http://lorempixel.com/640/480/food/'),
    ),

    'mainDetail' => array(
        'number' => 'swTEST' . uniqid(),
        'inStock' => 16,
        'prices' => array(
            array(
                'customerGroupKey' => 'EK',
                'price' => 99.34,
            ),
        )
    ),
);
$client->post('articles', $testArticle);
```

```
$updateInStock = array(
    'mainDetail' => array(
        'inStock' => 66
    )
);
$client->put('articles/3', $updateInStock);
```

```
$updateVariantInStock = array(
    'variants' => array(
        array(
            // update per primary key
            'id'      => 726,
            'inStock' => 99,
        ),
        array(
            // update per ordernumber key
            'number' => 'SW10204.5',
            'inStock' => 999,
        ),
    )
);
$client->put('articles/205', $updateVariantInStock);
```

```

$configuratorArticle = array(
    'name' => 'ConfiguratorTest',
    'description' => 'A test article',
    'descriptionLong' => '<p>I\'m a <b>test article</b></p>',
    'active' => true,
    'taxId' => 1,
    'supplierId' => 2,

    'categories' => array(
        array('id' => 15),
    ),

   'mainDetail' => array(
        'number' => 'swTEST' . uniqid(),
        'prices' => array(
            array(
                'customerGroupKey' => 'EK',
                'price' => 999,
            ),
        )
    ),

    'configuratorSet' => array(
        'groups' => array(
            array(
                'name' => 'Size',
                'options' => array(
                    array('name' => 'S'),
                    array('name' => 'M'),
                    array('name' => 'L'),
                    array('name' => 'XL'),
                    array('name' => 'XXL'),
                )
            ),
            array(
                'name' => 'Color',
                'options' => array(
                    array('name' => 'White'),
                    array('name' => 'Yellow'),
                    array('name' => 'Blue'),
                    array('name' => 'Black'),
                )
            ),
        )
    ),
    'variants' => array(
        array(
            'isMain' => true,
            'number' => 'swTEST' . uniqid(),
            'inStock' => 15,
            'additionaltext' => 'S / Schwarz',
            'configuratorOptions' => array(
                array('group' => 'Size', 'option' => 'S'),
                array('group' => 'Color', 'option' => 'Black'),
            ),
            'prices' => array(
                array(
                    'customerGroupKey' => 'EK',
                    'price' => 999,
                ),
            )
        ),
        array(
            'number' => 'swTEST' . uniqid(),
            'inStock' => 10,
            'additionaltext' => 'S / Weiß',
            'configuratorOptions' => array(
                array('group' => 'Size', 'option' => 'S'),
                array('group' => 'Color', 'option' => 'White'),
            ),
            'prices' => array(
                array(
                    'customerGroupKey' => 'EK',
                    'price' => 888,
                ),
            ),
            'attribute' => array(
                'attr1' => 'S/White Attr1',
                'attr2' => 'SomeText',
            ),
        ),
        array(
            'number' => 'swTEST' . uniqid(),
            'inStock' => 5,
            'additionaltext' => 'XL / Blue',
            'configuratorOptions' => array(
                array('group' => 'Size', 'option' => 'XL'),
                array('group' => 'Color', 'option' => 'Blue'),
            ),
            'prices' => array(
                array(
                    'customerGroupKey' => 'EK',
                    'price' => 555,
                ),
            )
        )
    ),
);

$client->post('articles', $configuratorArticle);
```
