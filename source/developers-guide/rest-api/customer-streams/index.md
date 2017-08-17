---
layout: default
title: REST API - Examples using customer streams
github_link: developers-guide/rest-api/customer-streams/index.md
menu_title: Customer Streams
menu_order: 110
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article, you will find examples which demonstrate how to create, delete, get and index **[Customer Streams](/developers-guide/customer-streams-extension)** and how to rebuild the search index. For each scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[REST API Basics](/developers-guide/rest-api/)** if you haven't yet.

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.


## Build Search Index
To assure high performance, even when working with large data sets,
all customer must be indexed regularly. The index is basically a cache which
contains all information which are needed for the Customer Stream module.

*Available arguments:*
| Argument            | Type         | Required            | Description                                                             |
|---------------------|--------------|---------------------|-------------------------------------------------------------------------|
| buildSearchIndex    | boolean      | Yes                 | Search index will be (re)build if set to true                           |


*Example code:*
```php
$client->post('customer_streams', [
    'buildSearchIndex' => true
]);
```

*Example output:*
```json
{
    "success": true
}
```

## Create a new customer stream
There are two types of Customer Streams in Shopware.:

* Dynamic: Is defined by a set of conditions
which are chained via AND. All customer which match all of these conditions
will be added to the stream if they are a) already analysed and b) if the stream
has been index. 

* Static: Is defined manually, which means that you assign customer ids to the
stream. Static streams will only change if you remove or add customers. 

### Dynamic streams
Define a new dynamic stream.

*Available arguments:*
| Argument            | Type         | Required            | Description                                                             |
|---------------------|--------------|---------------------|-------------------------------------------------------------------------|
| name                | string       | Yes                 | Name of the Customer Stream                                             |
| static              | boolean      | Yes                 | Stream type (true=static stream/false=dynamic stream)                   |
| description         | string       |                     | Description of the Customer Stream                                      |
| conditions          | string       | Yes                 | List of conditions (be aware of the format and escaping)                |
| indexStream         | boolean      |                     | Stream will be index if set to true                                     |

*Example code:*
```php
$client->post('customer_streams', [
    'name' => 'Dynamic api stream',
    'static' => false,
    'description' => 'Stream created over the api which will be indexed immediately',
    'conditions' => '{"Shopware\\\\Bundle\\\\CustomerSearchBundle\\\\Condition\\\\HasOrderCountCondition":{"operator":"=","minimumOrderCount":1}}',
    'indexStream' => true
]);
```
*Example output:*
```json
{
    "success": true,
    "data": {
        "id": 9,
        "location": "http://localhost/53/api/customer_streams/9"
    }
}
```

### Static streams
Define a new static stream and assign customers to it.

*Available arguments:*

| Argument            | Type         | Required            | Description                                                                                                                                                |
|---------------------|--------------|---------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------|
| name                | string       | Yes                 | Name of the Customer Stream                                                                                                                                |
| static              | boolean      | Yes                 | Stream type (true=static stream/false=dynamic stream)                                                                                                      |
| description         | string       |                     | Description of the Customer Stream                                                                                                                         |
| customers           | int array    |                     | List of customer which will be assigned to the stream                                                                                                      |
| freezeUp            | string       |                     | Date/Time until the stream is static (will be processed by DateTime). [Here](http://php.net/manual/de/datetime.formats.php) is a list of supported formats.  |


*Example code:*
```php
$client->post('customer_streams', [
    'name' => 'Static api stream',
    'static' => true,
    'description' => 'Static stream created over the api',
    'customers' => [1,2]
]);
```

*Example output:*
```json
{
    "success": true,
    "data": {
        "id": 5,
        "location": "http://localhost/53/api/customer_streams/5"
    }
}
```
## (Re)Index an existing stream
*Available arguments:*

| Argument            | Type         | Required            | Description                                                             |
|---------------------|--------------|---------------------|-------------------------------------------------------------------------|
| id                  | integer      | Yes                 | ID of the Customer Stream                                               |
| indexStream         | boolean      |                     | Stream will be reindex if set to true (only works with dynamic streams) |

*Example code:*

```php
$client->put('customer_streams/' . (int) $response['data']['id'], [
    'indexStream' => true,
]);
```

*Example output:*
```json
{
    "success": true,
    "data": {
        "id": 1,
        "location": "http://localhost/53/api/customer_streams/1"
    }
}
```

## Get information about an existing stream

List customers which are assigned to a given stream id.
You can add additional conditions or sorting.
You can also define an offset/limit to process the stream in chunks.

*Available arguments:*

| Argument            | Type         | Required            | Description                                                            |
|---------------------|--------------|---------------------|------------------------------------------------------------------------|
| id                  | integer      | Yes                 | ID of the Customer Stream                                              |
| offset              | integer      |                     | Offset (ideal for batch processing, when working with large data sets) |
| limit               | integer      |                     | Maximum number of returned data sets                                   |
| conditions          | string array |                     | Additional conditions                                                  |
| sortings            | string array |                     | Sorting handler which will be applied to the result set                |

*Example code:*
```php
$client->get('customer_streams/6', [
    'conditions' => '{"Shopware\\\\Bundle\\\\CustomerSearchBundle\\\\Condition\\\\HasOrderCountCondition":{"operator":"=>","minimumOrderCount":1}}',
    'sortings' => '{"Shopware\\\\Bundle\\\\CustomerSearchBundle\\\\Sorting\\\\NumberSorting":{"direction":"DESC"}}'
]);
```

*Example output:*
```json
{
    "data": [
        {
            "id": 1,
            "number": "20001",
            "email": "test@example.com",
            "attributes": {
                "search": {
                    "id": "1",
                    "customernumber": "20001",
                    "email": "test@example.com"
                }
            }
        },
        {
            "id": 2,
            "number": "20003",
            "email": "mustermann@b2b.de",
            "attributes": {
                "search": {
                    "id": "2",
                    "customernumber": "20003",
                    "email": "mustermann@b2b.de"
                }
            }
        }
    ],
    "total": 2,
    "success": true
}
```

## Get a list of all streams

List all streams and their attributes.

*Example code:*
```php
$client->get('customer_streams/');
```

*Example output:*
```json
{
    "data": [
        {
            "id": 1,
            "name": "Example stream",
            "description": "Example description",
            "conditions": "{\"Shopware\\\\Bundle\\\\CustomerSearchBundle\\\\Condition\\\\HasTotalOrderAmountCondition\":{\"operator\":\">=\",\"minimumOrderAmount\":150}}",
            "static": false,
            "freezeUp": null,
            "attribute": null,
            "customer_count": "2",
            "newsletter_count": "0"
        },
        {
            "id": 2,
            "name": "Static stream example",
            "description": "Example description",
            "conditions": "{\"Shopware\\\\Bundle\\\\CustomerSearchBundle\\\\Condition\\\\HasOrderCountCondition\":{\"operator\":\"=\",\"minimumOrderCount\":1}}",
            "static": true,
            "freezeUp": "2017-08-24T07:45:00+0200",
            "attribute": null,
            "customer_count": "1",
            "newsletter_count": "0"
        }
    ],
    "total": 2,
    "success": true
}
```