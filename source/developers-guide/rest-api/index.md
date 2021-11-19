---
layout: default
title: REST API - Basics
github_link: developers-guide/rest-api/index.md
menu_title: REST API Basics
menu_order: 10
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

<div class="toc-list"></div>

## Introduction

The following page of the documentation covers the REST API.
By using the REST API, shop owners can grant access to almost all data stored in their shop to 3rd party applications.
It also allows direct manipulation of the shop data, regardless of the application or system used.

## Basic Settings

To enable access to the REST API, the shop owner must authorize one (or more) users in the Shopware backend.

Simply open the Shopware backend and open the `User Administration` window, under `Settings`.
From the list of existing users displayed on this window,
select `Edit` for the desired user and mark the `enabled` checkbox in the `API Access` section.

You will get a randomly generated API access key, which needs to be included in your API requests for authentication.
After clicking `Save`, the changes will take effect.
If the edited user is currently logged in, you might need to clear the backend cache,
and then log out and log in for your changes to take effect.

## List of API Resources

The API has multiple resources, each responsible for managing a specific data type.
These resources can be found in the `engine/Shopware/Controllers/Api/` directory of your Shopware installation.
Each resource has a correspondent URI and supports a different set of operations.

To get more details about the data provided by each specified resource, click on its name.

| Name                                                              | Access URL                 | GET                  | GET (List)          | PUT                 | PUT (Batch)         | POST                | DELETE              | DELETE (Batch)      |
|-------------------------------------------------------------------|-----------------------------|----------------------|---------------------|---------------------|---------------------|---------------------|---------------------|---------------------|
| **[Address](api-resource-address/)**                              |  /api/addresses             |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Product](api-resource-article/)**                              |  /api/articles              |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) |
| **[Cache](api-resource-cache/)**                                  |  /api/caches                |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![No](img/no.png)   | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) |
| **[Categories](api-resource-categories/)**                        |  /api/categories            |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Countries](api-resource-countries/)**                          |  /api/countries             |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Customer](api-resource-customer/)**                            |  /api/customers             |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[CustomerGroups](api-resource-customer-group/)**                |  /api/customerGroups        |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[GenerateProductImage](api-resource-generate-article-images/)** |  /api/generateArticleImages |  ![No](img/no.png)   | ![No](img/no.png)   | ![Yes](img/yes.png) | ![No](img/no.png)   | ![No](img/no.png)   | ![No](img/no.png)   | ![No](img/no.png)   |
| **[Manufacturers](api-resource-manufacturers/)**                  |  /api/manufacturers         |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Media](api-resource-media/)**                                  |  /api/media                 |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Orders](api-resource-orders/)**                                |  /api/orders                |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![No](img/no.png)   | ![No](img/no.png)   |
| **[PaymentMethods](api-resource-payment-methods/)**               |  /api/paymentMethods        |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[PropertyGroups](api-resource-property-group/)**                |  /api/propertyGroups        |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Shops](api-resource-shops/)**                                  |  /api/shops                 |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Translations](api-resource-translation/)**                     |  /api/translations          |  ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) |
| **[Users](api-resource-user/)**                                   |  /api/users                 |  ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Variants](api-resource-variants/)**                            |  /api/variants              |  ![Yes](img/yes.png) | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png) |
| **[Version](api-resource-version/)**                              |  /api/version               |  ![Yes](img/yes.png) | ![No](img/no.png)   | ![No](img/no.png)   | ![No](img/no.png)   | ![No](img/no.png)   | ![No](img/no.png)   | ![No](img/no.png)   |

## Authentication

We currently support two authentication mechanisms:

* Digest access authentication
* HTTP Basic authentication (introduced with Shopware 5.3.2)

### Digest access authentication

The Digest access authentication is based on a simple challenge-response paradigm.
The Digest scheme challenges using a nonce value.
A valid response contains a checksum (by default MD5) of the username, the password, the given nonce value, the HTTP method, and the requested URI.

This ensures, that the password is never sent as plain text.

You can find a detailed explanation of the digest access authentication
[here](https://tools.ietf.org/html/rfc2617#page-6)
and [here](https://en.wikipedia.org/wiki/Digest_access_authentication).


### HTTP Basic authentication

To use the HTTP Basic authentication, you just have to set an Authorization header which looks like this:

Authorization: Basic c2hvcDp3YXJl

The Authorization header has to follow this scheme:
1. Combine username and password with a single colon (:).
2. Encode the string into an octet sequence ([more info](https://tools.ietf.org/id/draft-reschke-basicauth-enc-00.html)).
3. Encode the string with Base64.
4. Prepend the authorization method and a space to the encoded string.

Please be aware that the Basic authorisation provides no confidentiality protection for the transmitted credentials.
Therefore, you should **always** use HTTPS when using Basic authentication.


The authentication methods are not exclusive, you can use both of them simultaneously!

## Using the REST API in your own application

To connect to the REST API, you need a client application.
As REST is widely used as an inter-application communication protocol,
several client applications and integration libraries already exist,
both free and commercially, for different platforms and languages.

The examples shown in this documentation will work with any HTTP-Client.
There's a variety of command-line or GUI applications which can be used for testing,
and the standard library of your programming language of choice most likely includes a compatible HTTP-Client as well.

Every example will be accompanied by a badge like this:

{% include 'api_badge.twig' with {'route': '/api/articles/4', 'method': 'GET'} %}

The first part shows the HTTP-request method and the second part shows the route.

Some requests come with a body containing additional data like product information,
these will have a code section attached to them and look like this:

{% include 'api_badge.twig' with {'route': '/api/articles/4', 'method': 'PUT', 'body': true} %}

```json
{
  "name": "New name"
}
```

## Communicating with the API

### Query encoding

It is important that, when communicating with the Shopware API, all transmitted queries are UTF8-encoded.
The date must be in ISO 8601 format.

More info about ISO can be found here:

* [ISO_8601](https://en.wikipedia.org/wiki/ISO_8601)

### Date formatting in PHP
```
//Generate valid ISO-8601
$now = new DateTime();
$string = $now->format(DateTime::ISO8601);
var_dump($string);
// output e.G.:
string(24) "2012-06-13T09:34:09+0200"

//Parse ISO-8601 Date
$string = "2012-06-13T09:34:09+0200";
$dateTime = new DateTime($string);
var_dump($dateTime);

// output
object(DateTime)#65 (3) {
  ["date"]=>
  string(19) "2012-06-13 09:34:09"
  ["timezone_type"]=>
  int(1)
  ["timezone"]=>
  string(6) "+02:00"
}
```

### Date formatting in JavaScript

```
// Generate valid ISO-8601
var date = new Date();
var string = date.toJSON();
console.log(string); // Output: 2012-06-13T07:32:25.706Z
// Parse ISO-8601 Date
var string = "2012-06-13T07:32:25.706Z"
var date = new Date(string);
```

### Filter, Sort, Limit, Offset

Every API comes with a set of default parameters which can be used to modify the given result.
All parameters can be combined in a single request.

#### Filter

Filtering a results can be done using the `filter` parameter in your request.
The available properties can be extracted from the Shopware models below.

Each filter can have the following properties:

* property (Required)
* value (Required)
* expression (Default: `LIKE`, available: all MySQL expressions)
* operator (If set, concats the filter using `OR` instead of `AND`)

**Example: Active articles with at least 1 pseudo sale**

{% include 'api_badge.twig' with {'route': '/api/articles', 'method': 'GET', 'body': true} %}
```json
{
    "filter": [
        {
            "property": "pseudoSales",
            "expression": ">=",
            "value": 1
        },
        {
            "property": "active",
            "value": 1
        }
    ]
}
```

**Example: Active articles or articles containing "beach" in their name**

{% include 'api_badge.twig' with {'route': '/api/articles', 'method': 'GET', 'body': true} %}
```json
{
    "filter": [
        {
            "property": "name",
            "value": "%beach%",
            "operator": 1
        },
        {
            "property": "active",
            "value": 1
        }
    ]
}
```

**Example: Orders from customer which email address is "test@example.com"**

Keep in mind, that the related entity must be joined in the query builder.

{% include 'api_badge.twig' with {'route': '/api/orders', 'method': 'GET', 'body': true} %}
```json
{
    "filter": [
        {
            "property": "customer.email",
            "value": "test@example.com"
        }
    ]
}
```

#### Sort

The sorting syntax nearly equals to the filter section above.
It uses the `sort` parameter in the request.

Each sorting can have the following properties:

* property (Required)
* direction (Default: `ASC`)

**Example: Sort by article name**

{% include 'api_badge.twig' with {'route': '/api/articles', 'method': 'GET', 'body': true} %}
```json
{
    "sort": [
        {
            "property": "name"
        }
    ]
}
```

**Example: First, sort by order time and then by invoice amount in descending order**

{% include 'api_badge.twig' with {'route': '/api/orders', 'method': 'GET', 'body': true} %}
```json
{
    "sort": [
        {
            "property": "orderTime"
        },
        {
            "property": "invoiceAmount",
            "direction": "DESC"
        }
    ]
}
```

#### Limit / Offset

By default, Shopware uses a soft limit on the API with a value of `1000`.
If you need more than `1000` results, increase it to your needs.
The limiting uses the parameter `limit`, the offset `start`.

**Example: Retrieve the first 50 results**

{% include 'api_badge.twig' with {'route': '/api/orders', 'method': 'GET', 'body': true} %}
```json
{
  "limit": 50
}
```

**Example: Retrieve 50 results, skipping the first 20**

{% include 'api_badge.twig' with {'route': '/api/orders', 'method': 'GET', 'body': true} %}
```json
{
  "limit": 50,
  "start": 20
}
```

## Models

You can find a list of all models at the following page:

* **[Models](models/)**
