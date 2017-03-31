---
layout: default
title: REST API - Examples using the order resource
menu_title: The order resource
menu_order: 40
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

<div class="toc-list"></div>

## Introduction

In this article you can read more about using the orders resource.
The following part will show you examples including provided data and data you need to provide if you want to use this resource.
Please read **[Orders](/developers-guide/rest-api/api-resource-orders/)** if you did not yet, to get more information about the orders resource and the data it provides.
Also we are using the API client of the following document **[API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.

## Example 1 - Load all orders
This example shows you how to get all orders of the shop. You may also limit the result count by providing a limit parameter.

```
$client->get('orders');
$client->get('orders?limit=20'); //Will only get 20 orders
```

### Result
```
{
   "data":[
      {
         "id":15,
         "number":"20001",
         "customerId":2,
         "paymentId":4,
         "dispatchId":9,
         "partnerId":"",
         "shopId":1,
         "invoiceAmount":998.56,
         "invoiceAmountNet":839.13,
         "invoiceShipping":0,
         "invoiceShippingNet":0,
         "orderTime":"2012-08-30T10:15:54+0200",
         "transactionId":"",
         "comment":"",
         "customerComment":"",
         "internalComment":"",
         "net":1,
         "taxFree":0,
         "temporaryId":"",
         "referer":"",
         "clearedDate":null,
         "trackingCode":"",
         "languageIso":"1",
         "currency":"EUR",
         "currencyFactor":1,
         "remoteAddress":"217.86.205.141",
         "paymentStatusId":17,
         "orderStatusId":0
      },
      {
         "id":52,
         "number":"0",
         "customerId":1,
         "paymentId":2,
         "dispatchId":10,
         "partnerId":"",
         "shopId":1,
         "invoiceAmount":1700.93,
         "invoiceAmountNet":1429.36,
         "invoiceShipping":75,
         "invoiceShippingNet":63.03,
         "orderTime":"2012-08-30T15:16:55+0200",
         "transactionId":"",
         "comment":"",
         "customerComment":"",
         "internalComment":"",
         "net":0,
         "taxFree":0,
         "temporaryId":"9a0271fe91e7fc853a4a7a1e7ca789c812257d74",
         "referer":"",
         "clearedDate":null,
         "trackingCode":"",
         "languageIso":"1",
         "currency":"EUR",
         "currencyFactor":1,
         "remoteAddress":"",
         "paymentStatusId":0,
         "orderStatusId":-1
      },
      {
         "id":54,
         "number":"0",
         "customerId":1,
         "paymentId":2,
         "dispatchId":9,
         "partnerId":"",
         "shopId":1,
         "invoiceAmount":42.6,
         "invoiceAmountNet":35.8,
         "invoiceShipping":3.9,
         "invoiceShippingNet":3.28,
         "orderTime":"2012-08-30T17:30:02+0200",
         "transactionId":"",
         "comment":"",
         "customerComment":"",
         "internalComment":"",
         "net":0,
         "taxFree":0,
         "temporaryId":"06hue2s27mjbapgsdnd4alffe4",
         "referer":"",
         "clearedDate":null,
         "trackingCode":"",
         "languageIso":"1",
         "currency":"EUR",
         "currencyFactor":1,
         "remoteAddress":"",
         "paymentStatusId":0,
         "orderStatusId":-1
      },
      {
         "id":57,
         "number":"20002",
         "customerId":1,
         "paymentId":4,
         "dispatchId":9,
         "partnerId":"",
         "shopId":1,
         "invoiceAmount":201.86,
         "invoiceAmountNet":169.63,
         "invoiceShipping":0,
         "invoiceShippingNet":0,
         "orderTime":"2012-08-31T08:51:46+0200",
         "transactionId":"",
         "comment":"",
         "customerComment":"",
         "internalComment":"",
         "net":0,
         "taxFree":0,
         "temporaryId":"",
         "referer":"",
         "clearedDate":null,
         "trackingCode":"",
         "languageIso":"1",
         "currency":"EUR",
         "currencyFactor":1,
         "remoteAddress":"217.86.205.141",
         "paymentStatusId":17,
         "orderStatusId":0
      }
   ],
   "total":4,
   "success":true
}
```

## Example 2 - Loading a specific order

To load a specific order, you have to provide either the identifier or the number of the order.

```
$client->get('orders/15');
$client->get('orders/2002?useNumberAsId=true');
```

### Result

```
{
   "data":{
      "id":15,
      "number":"20001",
      "customerId":2,
      "paymentId":4,
      "dispatchId":9,
      "partnerId":"",
      "shopId":1,
      "invoiceAmount":998.56,
      "invoiceAmountNet":839.13,
      "invoiceShipping":0,
      "invoiceShippingNet":0,
      "orderTime":"2012-08-30T10:15:54+0200",
      "transactionId":"",
      "comment":"",
      "customerComment":"",
      "internalComment":"",
      "net":1,
      "taxFree":0,
      "temporaryId":"",
      "referer":"",
      "clearedDate":null,
      "trackingCode":"",
      "languageIso":"1",
      "currency":"EUR",
      "currencyFactor":1,
      "remoteAddress":"217.86.205.141",
      "details":[
         {
            "id":42,
            "orderId":15,
            "articleId":197,
            "taxId":1,
            "taxRate":19,
            "statusId":0,
            "number":"20001",
            "articleNumber":"SW10196",
            "price":836.134,
            "quantity":1,
            "articleName":"ESD Download Artikel",
            "shipped":0,
            "shippedGroup":0,
            "releaseDate":"-0001-11-30T00:00:00+0053",
            "mode":0,
            "esdArticle":1,
            "config":"",
            "ean":null,
            "unit":null,
            "packUnit":null,
            "attribute":{
               "id":1,
               "orderDetailId":42,
               "attribute1":"",
               "attribute2":"",
               "attribute3":"",
               "attribute4":"",
               "attribute5":"",
               "attribute6":""
            }
         },
         {
            "id":43,
            "orderId":15,
            "articleId":0,
            "taxId":0,
            "taxRate":19,
            "statusId":0,
            "number":"20001",
            "articleNumber":"SHIPPINGDISCOUNT",
            "price":-2,
            "quantity":1,
            "articleName":"Warenkorbrabatt",
            "shipped":0,
            "shippedGroup":0,
            "releaseDate":"-0001-11-30T00:00:00+0053",
            "mode":4,
            "esdArticle":0,
            "config":"",
            "ean":null,
            "unit":null,
            "packUnit":null,
            "attribute":{
               "id":2,
               "orderDetailId":43,
               "attribute1":"",
               "attribute2":"",
               "attribute3":"",
               "attribute4":"",
               "attribute5":"",
               "attribute6":""
            }
         },
         {
            "id":44,
            "orderId":15,
            "articleId":0,
            "taxId":0,
            "taxRate":19,
            "statusId":0,
            "number":"20001",
            "articleNumber":"sw-payment-absolute",
            "price":5,
            "quantity":1,
            "articleName":"Zuschlag f\u00fcr Zahlungsart",
            "shipped":0,
            "shippedGroup":0,
            "releaseDate":"-0001-11-30T00:00:00+0053",
            "mode":4,
            "esdArticle":0,
            "config":"",
            "ean":null,
            "unit":null,
            "packUnit":null,
            "attribute":{
               "id":3,
               "orderDetailId":44,
               "attribute1":"",
               "attribute2":"",
               "attribute3":"",
               "attribute4":"",
               "attribute5":"",
               "attribute6":""
            }
         }
      ],
      "documents":[

      ],
      "payment":{
         "id":4,
         "name":"invoice",
         "description":"Rechnung",
         "template":"invoice.tpl",
         "class":"invoice.php",
         "table":"",
         "hide":false,
         "additionalDescription":"Sie zahlen einfach und bequem auf Rechnung. Shopware bietet z.B. auch die M\u00f6glichkeit, Rechnung automatisiert erst ab der 2. Bestellung f\u00fcr Kunden zur Verf\u00fcgung zu stellen, um Zahlungsausf\u00e4lle zu vermeiden.",
         "debitPercent":0,
         "surcharge":5,
         "surchargeString":"",
         "position":3,
         "active":true,
         "esdActive":true,
         "embedIFrame":"",
         "hideProspect":0,
         "action":"",
         "pluginId":null,
         "source":null
      },
      "paymentStatus":{
         "id":17,
         "description":"Offen",
         "position":0,
         "group":"payment",
         "sendMail":0
      },
      "orderStatus":{
         "id":0,
         "description":"Offen",
         "position":1,
         "group":"state",
         "sendMail":1
      },
      "customer":{
         "id":2,
         "paymentId":4,
         "groupKey":"H",
         "shopId":1,
         "priceGroupId":null,
         "encoderName":"md5",
         "hashPassword":"352db51c3ff06159d380d3d9935ec814",
         "active":true,
         "email":"mustermann@b2b.de",
         "firstLogin":"2012-08-30T00:00:00+0200",
         "lastLogin":"2012-08-30T11:43:17+0200",
         "accountMode":0,
         "confirmationKey":"",
         "sessionId":"66e9b10064a19b1fcf6eb9310c0753866c764836",
         "newsletter":0,
         "validation":"0",
         "affiliate":0,
         "paymentPreset":4,
         "languageId":"1",
         "referer":"",
         "internalComment":"",
         "failedLogins":0,
         "lockedUntil":null,
         "debit":{
            "id":3,
            "customerId":2,
            "account":"",
            "bankCode":"",
            "bankName":"",
            "accountHolder":""
         }
      },
      "paymentInstances":[

      ],
      "billing":{
         "id":1,
         "orderId":15,
         "customerId":2,
         "countryId":2,
         "company":"B2B",
         "department":"Einkauf",
         "salutation":"company",
         "number":"",
         "firstName":"H\u00e4ndler",
         "lastName":"Kundengruppe-Netto",
         "street":"Musterweg 55",
         "zipCode":"00000",
         "city":"Musterstadt",
         "phone":"012345 \/ 6789",
         "fax":"",
         "vatId":"",
         "country":{
            "id":2,
            "name":"Deutschland",
            "iso":"DE",
            "isoName":"GERMANY",
            "position":1,
            "description":"",
            "shippingFree":false,
            "taxFree":0,
            "taxFreeUstId":0,
            "taxFreeUstIdChecked":0,
            "active":true,
            "iso3":"DEU",
            "displayStateInRegistration":false,
            "forceStateInRegistration":false,
            "areaId":1
         },
         "attribute":{
            "id":1,
            "orderBillingId":1,
            "text1":null,
            "text2":null,
            "text3":null,
            "text4":null,
            "text5":null,
            "text6":null
         }
      },
      "shipping":{
         "id":1,
         "orderId":15,
         "countryId":2,
         "customerId":2,
         "company":"B2B",
         "department":"Einkauf",
         "salutation":"company",
         "firstName":"H\u00e4ndler",
         "lastName":"Kundengruppe-Netto",
         "street":"Musterweg 55",
         "zipCode":"00000",
         "city":"Musterstadt",
         "attribute":{
            "id":1,
            "orderShippingId":1,
            "text1":null,
            "text2":null,
            "text3":null,
            "text4":null,
            "text5":null,
            "text6":null
         },
         "country":{
            "id":2,
            "name":"Deutschland",
            "iso":"DE",
            "isoName":"GERMANY",
            "position":1,
            "description":"",
            "shippingFree":false,
            "taxFree":0,
            "taxFreeUstId":0,
            "taxFreeUstIdChecked":0,
            "active":true,
            "iso3":"DEU",
            "displayStateInRegistration":false,
            "forceStateInRegistration":false,
            "areaId":1
         }
      },
      "shop":{
         "id":1,
         "mainId":null,
         "categoryId":3,
         "name":"Deutsch",
         "title":null,
         "position":0,
         "host":null,
         "basePath":"\/master",
         "baseUrl":null,
         "hosts":"",
         "secure":false,
         "alwaysSecure":false,
         "secureHost":null,
         "secureBasePath":null,
         "default":true,
         "active":true,
         "customerScope":false
      },
      "dispatch":{
         "id":9,
         "name":"Standard Versand",
         "type":0,
         "description":"",
         "comment":"",
         "active":true,
         "position":1,
         "calculation":1,
         "surchargeCalculation":3,
         "taxCalculation":0,
         "shippingFree":null,
         "multiShopId":null,
         "customerGroupId":null,
         "bindShippingFree":0,
         "bindTimeFrom":null,
         "bindTimeTo":null,
         "bindInStock":null,
         "bindLastStock":0,
         "bindWeekdayFrom":null,
         "bindWeekdayTo":null,
         "bindWeightFrom":null,
         "bindWeightTo":"1.000",
         "bindPriceFrom":null,
         "bindPriceTo":null,
         "bindSql":null,
         "statusLink":"",
         "calculationSql":null
      },
      "attribute":{
         "id":1,
         "orderId":15,
         "attribute1":"",
         "attribute2":"",
         "attribute3":"",
         "attribute4":"",
         "attribute5":"",
         "attribute6":""
      },
      "languageSubShop":{
         "id":1,
         "mainId":null,
         "categoryId":3,
         "name":"Deutsch",
         "title":null,
         "position":0,
         "host":null,
         "basePath":"\/master",
         "baseUrl":null,
         "hosts":"",
         "secure":false,
         "alwaysSecure":false,
         "secureHost":null,
         "secureBasePath":null,
         "default":true,
         "active":true,
         "customerScope":false,
         "locale":{
            "id":1,
            "locale":"de_DE",
            "language":"Deutsch",
            "territory":"Deutschland"
         }
      },
      "paymentStatusId":17,
      "orderStatusId":0
   },
   "success":true
}

```

## Example 3 - Update an order
**Currently, it's only possible to update the following fields of an order:**

```
paymentStatusId
orderStatusId
trackingCode
comment
transactionId
clearedDate
```

This example shows you how to update those fields:
```
$date = new DateTime();
$date->modify('+10 days');
$date = $date->format(DateTime::ISO8601);

$client->put('orders/15',  array(
    'paymentStatusId' => 10,
    'orderStatusId' => 8,
    'trackingCode' => '237948723894789234',
    'comment' => 'Neuer Kommentar',
    'transactionId' => '0',
    'clearedDate' => $date
));
```

### Result

```
(
    [id] => 2
    [location] => http://www.yourdomain.com/api/orders/2
)

```

## Example 4 - Creating an order

This example shows you how to create an order. Currently all referenced entities like customers need to be referenced by their id, no creation of sub-entities is currently done.

<div class="alert alert-danger">Please be aware: When an Order is created using the API no calculations for tax, shipping cost, etc. are done. Also no checks regarding validity of the values provided will be executed.</div>

If some field is missing from the request or some id provided does not exist, an exception is returned accordingly.

```
$client->post('orders', [
    "customerId" => 1,
    "paymentId" => 4,
    "dispatchId" => 9,
    "partnerId" => "",
    "shopId" => 1,
    "invoiceAmount" => 201.86,
    "invoiceAmountNet" => 169.63,
    "invoiceShipping" => 0,
    "invoiceShippingNet" => 0,
    "orderTime" => "2012-08-31 08:51:46",
    "net" => 0,
    "taxFree" => 0,
    "languageIso" => "1",
    "currency" => "EUR",
    "currencyFactor" => 1,
    "remoteAddress" => "217.86.205.141",
    "details" => [[
        "articleId" => 220,
        "taxId" => 1,
        "taxRate" => 19,
        "statusId" => 0,
        "articleNumber" => "SW10001",
        "price" => 35.99,
        "quantity" => 1,
        "articleName" => "Versandkostenfreier Artikel",
        "shipped" => 0,
        "shippedGroup" => 0,
        "mode" => 0,
        "esdArticle" => 0,
    ], [
        "articleId" => 219,
        "taxId" => 1,
        "taxRate" => 19,
        "statusId" => 0,
        "articleNumber" => "SW10185",
        "price" => 54.9,
        "quantity" => 1,
        "articleName" => "Express Versand",
        "shipped" => 0,
        "shippedGroup" => 0,
        "mode" => 0,
        "esdArticle" => 0,
    ], [
        "articleId" => 197,
        "taxId" => 1,
        "taxRate" => 19,
        "statusId" => 0,
        "articleNumber" => "SW10196",
        "price" => 34.99,
        "quantity" => 2,
        "articleName" => "ESD Download Artikel",
        "shipped" => 0,
        "shippedGroup" => 0,
        "mode" => 0,
        "esdArticle" => 1,
    ]],
    "documents" => [],
    "billing" => [
        "id" => 2,
        "customerId" => 1,
        "countryId" => 2,
        "stateId" => 3,
        "company" => "shopware AG",
        "salutation" => "mr",
        "firstName" => "Max",
        "lastName" => "Mustermann",
        "street" => "Mustermannstra\u00dfe 92",
        "zipCode" => "48624",
        "city" => "Sch\u00f6ppingen",
    ],
    "shipping" => [
        "id" => 2,
        "countryId" => 2,
        "stateId" => 3,
        "customerId" => 1,
        "company" => "shopware AG",
        "salutation" => "mr",
        "firstName" => "Max",
        "lastName" => "Mustermann",
        "street" => "Mustermannstra\u00dfe 92",
        "zipCode" => "48624",
        "city" => "Sch\u00f6ppingen"
    ],
    "paymentStatusId" => 17,
    "orderStatusId" => 0
]);
```

### Result

```
(
    [id] => 60
    [location] => http://www.yourdomain.com/api/orders/60
)
```

## Further examples

```
// filter by paymentStatusId
$filterByPaymentStatus = array(
    array(
        'property' => 'cleared',
        'value'    => 0
    ),
);

// filter by orderStatusId
$filterByOrderStatus = array(
    array(
        'property' => 'status',
        'value'    => 0
    ),
);

// filter by clearedDate
$filterByClearedDate = array(
    array(
        'property' => 'clearedDate',
        'expression' => '>=',
        'value'    => '2012-10-14'
    ),
);

$params = array(
    'filter' => $filterByPaymentStatus
);

$client->get('orders', $params);


// Change status
$updateOrder = array(
    'paymentStatusId' => 12,
    'clearedDate' => '2012-10-17',
);

//Using ordernumber as identifier
$params = array(
    'useNumberAsId' => true
);

$client->put('orders/20001', $updateOrder, $params);
```

**[Back to overview](../#examples)**
