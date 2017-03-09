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

The following page of the devdocs covers the REST API. By using the REST API, shop owners can grant access to almost all data stored
in their shop to 3rd party applications. It also allows direct manipulation of the shop data, regardless of the application or system used.

## Basic Settings

To enable access to the REST API, the shop owner must authorize one (or more) users in the Shopware backend. Simply open the Shopware backend and open the `User Administration` window, under `Settings`.
From the list of existing users displayed on this window, select `Edit` for the desired user and mark the `enabled` checkbox in the `API Access` section.
You will get a randomly generated API access key, which needs to be included in your API requests for authentication. After clicking `Save`, the changes will take effect.
If the edited user is currently logged in, you might need to empty the backend cache, and then log out an log in for your changes to take effect.

## List of API Resources

The API has multiple resources, each responsible for managing a specific data type. These resources can be found in the `engine/Shopware/Controllers/Api/` directory of your Shopware installation. Each resource has a correspondent URI, and supports a different set of operations.

To get more details about the data provided by each specified resource, click on its name.

| Name                                                              |  Access URL                 | GET                | GET (List)      | PUT             | PUT (Batch)      | POST             | DELETE          | DELETE (Batch)  |
|-------------------------------------------------------------------|-----------------------------|--------------------|-----------------|-----------------|------------------|------------------|-----------------|-----------------|
| **[Address](api-resource-address/)**                              |  /api/addresses             |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)    | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![No](img/no.png) |
| **[Article](api-resource-article/)**                              |  /api/articles              |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png)  | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![Yes](img/yes.png) |
| **[Cache](api-resource-cache/)**                                  |  /api/caches                |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![No](img/no.png)   | ![No](img/no.png)    | ![No](img/no.png)    | ![Yes](img/yes.png) | ![Yes](img/yes.png) |
| **[Categories](api-resource-categories/)**                        |  /api/categories            |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)    | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Countries](api-resource-countries/)**                          |  /api/countries             |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)    | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[CustomerGroups](api-resource-customer-group/)**                |  /api/customerGroups        |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)    | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Customer](api-resource-customer/)**                            |  /api/customers             |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)    | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[GenerateArticleImage](api-resource-generate-article-images/)** |  /api/generateArticleImages |  ![No](img/no.png)     | ![No](img/no.png)   | ![Yes](img/yes.png) | ![No](img/no.png)    | ![No](img/no.png)    | ![No](img/no.png)   | ![No](img/no.png)   |
| **[Media](api-resource-media/)**                                  |  /api/media                 |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![No](img/no.png)   | ![No](img/no.png)    | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Manufacturers](api-resource-manufacturers/)**                  |  /api/manufacturers         |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)    | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Orders](api-resource-orders/)**                                |  /api/orders                |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)    | ![Yes](img/yes.png)  | ![No](img/no.png)   | ![No](img/no.png)   |
| **[PropertyGroups](api-resource-property-group/)**                |  /api/propertyGroups        |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)    | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Shops](api-resource-shops/)**                                  |  /api/shops                 |  ![Yes](img/yes.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![No](img/no.png)    | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![No](img/no.png)   |
| **[Translations](api-resource-translation/)**                     |  /api/translations          |  ![No](img/no.png)     | ![Yes](img/yes.png) | ![Yes](img/yes.png) | ![Yes](img/yes.png)  | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![Yes](img/yes.png) |
| **[Variants](api-resource-variants/)**                            |  /api/variants              |  ![Yes](img/yes.png)   | ![No](img/no.png)   | ![Yes](img/yes.png) | ![Yes](img/yes.png)  | ![Yes](img/yes.png)  | ![Yes](img/yes.png) | ![Yes](img/yes.png) |
| **[Version](api-resource-version/)**                              |  /api/version               |  ![Yes](img/yes.png)   | ![No](img/no.png)   | ![No](img/no.png)   | ![No](img/no.png)    | ![No](img/no.png)    | ![No](img/no.png)   | ![No](img/no.png)   |

## Using the REST API in your own application

To connect to the REST API, you need a client application. As REST is widely used as an inter-application communication protocol, several client applications and integration libraries already exist, both free and commercially, for different platforms and languages. The following class illustrates a fully functional (yet basic) implementation of a REST client. Note that this example code is not maintained, and it's highly recommended that you don't use it in production environments.

```
<?php
class ApiClient
{
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';
    protected $validMethods = [
        self::METHOD_GET,
        self::METHOD_PUT,
        self::METHOD_POST,
        self::METHOD_DELETE,
    ];
    protected $apiUrl;
    protected $cURL;

    public function __construct($apiUrl, $username, $apiKey)
    {
        $this->apiUrl = rtrim($apiUrl, '/') . '/';
        //Initializes the cURL instance
        $this->cURL = curl_init();
        curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->cURL, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($this->cURL, CURLOPT_USERAGENT, 'Shopware ApiClient');
        curl_setopt($this->cURL, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($this->cURL, CURLOPT_USERPWD, $username . ':' . $apiKey);
        curl_setopt(
            $this->cURL,
            CURLOPT_HTTPHEADER,
            ['Content-Type: application/json; charset=utf-8']
        );
    }

    public function call($url, $method = self::METHOD_GET, $data = [], $params = [])
    {
        if (!in_array($method, $this->validMethods)) {
            throw new Exception('Invalid HTTP-Methode: ' . $method);
        }
        $queryString = '';
        if (!empty($params)) {
            $queryString = http_build_query($params);
        }
        $url = rtrim($url, '?') . '?';
        $url = $this->apiUrl . $url . $queryString;
        $dataString = json_encode($data);
        curl_setopt($this->cURL, CURLOPT_URL, $url);
        curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $dataString);
        $result = curl_exec($this->cURL);
        $httpCode = curl_getinfo($this->cURL, CURLINFO_HTTP_CODE);

        return $this->prepareResponse($result, $httpCode);
    }

    public function get($url, $params = [])
    {
        return $this->call($url, self::METHOD_GET, [], $params);
    }

    public function post($url, $data = [], $params = [])
    {
        return $this->call($url, self::METHOD_POST, $data, $params);
    }

    public function put($url, $data = [], $params = [])
    {
        return $this->call($url, self::METHOD_PUT, $data, $params);
    }

    public function delete($url, $params = [])
    {
        return $this->call($url, self::METHOD_DELETE, [], $params);
    }

    protected function prepareResponse($result, $httpCode)
    {
        echo "<h2>HTTP: $httpCode</h2>";
        if (null === $decodedResult = json_decode($result, true)) {
            $jsonErrors = [
                JSON_ERROR_NONE => 'No error occurred',
                JSON_ERROR_DEPTH => 'The maximum stack depth has been reached',
                JSON_ERROR_CTRL_CHAR => 'Control character issue, maybe wrong encoded',
                JSON_ERROR_SYNTAX => 'Syntaxerror',
            ];
            echo '<h2>Could not decode json</h2>';
            echo 'json_last_error: ' . $jsonErrors[json_last_error()];
            echo '<br>Raw:<br>';
            echo '<pre>' . print_r($result, true) . '</pre>';

            return;
        }
        if (!isset($decodedResult['success'])) {
            echo 'Invalid Response';

            return;
        }
        if (!$decodedResult['success']) {
            echo '<h2>No Success</h2>';
            echo '<p>' . $decodedResult['message'] . '</p>';
            if (array_key_exists('errors', $decodedResult) && is_array($decodedResult['errors'])) {
                echo '<p>' . join('</p><p>', $decodedResult['errors']) . '</p>';
            }

            return;
        }
        echo '<h2>Success</h2>';
        if (isset($decodedResult['data'])) {
            echo '<pre>' . print_r($decodedResult['data'], true) . '</pre>';
        }

        return $decodedResult;
    }
}
```

### Creating the API client
To successfully use this client, we need to initialize it. When creating it, we give the client an API URL, an user name and the API key.

```
$client = new ApiClient(
    //URL of shopware REST server
    'http://www.ihredomain.de/api',
    //Username
    'myUsername',
    //User's API-Key
    'myAPIKey'
);
```

### Triggering a call with the API client
The newly created client now gives us the ability to call all resources. The first parameter describes the resource that should be queried. As the client already knows the resource's URL, we don't need to provide that information again. and can use only the resource's URI.

So, for example, the article with the ID `3` can be queried like so:

```
$client->get('articles/3');
```

When creating or updating data, a second parameter needs to be given to these calls. This parameter must be an array containing the data which should be changed or created.

## Communicating with the API

### Query encoding

It is important that, when communicating with the Shopware API, all transmitted queries are UTF8-encoded.
The date must be in ISO 8601 format.

More info about ISO can be found here:

* [ISO_8601](http://en.wikipedia.org/wiki/ISO_8601)

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

Every API comes with a set of default parameters which can be used to modify the given result. All parameters can be combined in a single request.

The following examples are snippets for our API client above.

#### Filter

Filtering a results can be done using the `filter` parameter in your request. The available properties can be extracted from the Shopware models below.

Each filter can have the following properties:

* property (Required)
* value (Required)
* expression (Default: `LIKE`, available: all MySQL expressions)
* operator (If set, concats the filter using `OR` instead of `AND`) 

**Example: Active articles with at least 1 pseudo sale**

```
$params = [
    'filter' => [
        [
            'property' => 'pseudoSales',
            'expression' => '>=',
            'value' => 1
        ],
        [
            'property' => 'active',
            'value' => 1
        ]
    ]
];

$client->get('articles', $params);
```

**Example: Active articles or articles containing "beach" in their name**

```
$params = [
    'filter' => [
        [
            'property' => 'name',
            'value' => '%beach%',
            'operator' => 1
        ],
        [
            'property' => 'active',
            'value' => 1
        ]
    ]
];

$client->get('articles', $params);
```

**Example: Orders from customer which email address is "test@example.com"**

Keep in mind, that the related entity must be joined in the query builder.

```
$params = [
    'filter' => [
        [
            'property' => 'customer.email',
            'value' => 'test@example.com'
        ]
    ]
];

$client->get('orders', $params);
```

#### Sort

The sorting syntax nearly equals to the filter section above. It uses the `sort` parameter in the request.

Each sorting can have the following properties:

* property (Required)
* direction (Default: `ASC`)

**Example: Sort by article name**

```
$params = [
    'sort' => [
        ['property' => 'name']
    ]
];

$client->get('articles', $params);
```

**Example: First, sort by order time and then by invoice amount in descending order**

```
$params = [
    'sort' => [
        ['property' => 'orderTime'],
        ['property' => 'invoiceAmount', 'direction' => 'DESC']
    ]
];

$client->get('orders', $params);
```

#### Limit / Offset

By default, Shopware uses a soft limit on the API with a value of `1000`. If you need more than `1000` results, increase it to your needs. The limiting uses the parameter `limit`, the offset `start`.

**Example: Retrieve the first 50 results**

```
$params = [
    'limit' => 50
];

$client->get('orders', $params);
```

**Example: Retrieve 50 results, skipping the first 20**

```
$params = [
    'limit' => 50,
    'start' => 20
];

$client->get('orders', $params);
```

## Models

You can find a list of all models at the following page:

* **[Models](models/)**
