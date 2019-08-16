---
layout: default
title: REST API - User resource
github_link: developers-guide/rest-api/api-resource-user/index.md
menu_title: The user resource
menu_order: 110
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

This chapter of the documentation is about the API's user resource.
With this resource, it's possible to retrieve, update, create and
delete backend user of your shop.

<div class="alert alert-info">
<strong>Notice</strong><br>
The /users endpoint was introduced with Shopware 5.3.5
and is not available for older versions.
</div>


For each scenario, we provide an example of the data
which is required, as well as an exemplary response.
Please read the page covering the **[REST API Basics](/developers-guide/rest-api/)** if you haven't yet.

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.


## Get a list of users
If you want to get multiple user at once, you can call the /users endpoint.

*Available arguments:*
| Argument            | Type         | Required            | Description                                                             |
|---------------------|--------------|---------------------|-------------------------------------------------------------------------|
| limit               | int          |                     | Max. number of returned data sets                                       |
| start               | int          |                     | Offset (ideal for batch processing, when working with large data sets)  |
| sort                | string array |                     | ORDER BY clause                                                         |
| filter              | string array |                     | Filter properties by expressions                                        |


*Example code:*
```php
$client->get('users', [
    'limit' => 10, // limit the number of users to 10
    'start' => 1, // skip the first one
    'sort' => [
        ['property' => 'username', 'direction' => 'DESC']
    ]
    
]);
```

*Example output:*
```json
{
    "total": 2,
    "data": [
        {
            "id": 1,
            "roleId": 1,
            "localeId": 1,
            "username": "demo",
            "password": "$2y$10$d7s.jETNFL1lZL3OzY7PneVWGk16aRZR9iuGyGHnw3X5EzssJ304W",
            "encoder": "bcrypt",
            "apiKey": "DaxN5BdfmcyglZMEwopy8Z46yADINhqViztSfcvI",
            "sessionId": "f0ga0998i8b86aaahfjhjva760",
            "lastLogin": "2017-11-01T11:10:24+0100",
            "name": "Demo user",
            "email": "demo@example.com",
            "active": 1,
            "failedLogins": 0,
            "lockedUntil": "2010-01-01T00:00:00+0100",
            "extendedEditor": false,
            "disabledCache": false
        },
        {
            "id": 2,
            "roleId": 6,
            "localeId": 1,
            "username": "test",
            "password": "$2y$10$SuT6CVqrHsnZbG29kqsVq.DYXhx.JbF4X13bLlkxOb9dl/a4OIQym",
            "encoder": "bcrypt",
            "apiKey": "ohwrzHP70iwUkBdzPEx6iUfSc3sLrHZ7678dy3Ie",
            "sessionId": "",
            "lastLogin": "2017-11-01T10:48:41+0100",
            "name": "asdasd",
            "email": "asdsad.asd@asd.de",
            "active": 1,
            "failedLogins": 0,
            "lockedUntil": "2017-11-01T10:48:41+0100",
            "extendedEditor": false,
            "disabledCache": false
        }
    ],
    "success": true
}
```

Attention: The properties apiKey, sessionId and password are missing,
if the API user neither has the update nor the create privilege.
+
## Get one user
If you want to get detailed information about a specific user,
you can call /users/{userId}


*Example code:*
```php
$client->get('users/2', []);
```
*Example output:*
```json
{
    "data": {
        "id": 2,
        "roleId": 6,
        "localeId": 1,
        "username": "test",
        "password": "$2y$10$SuT6CVqrHsnZbG29kqsVq.DYXhx.JbF4X13bLlkxOb9dl/a4OIQym",
        "encoder": "bcrypt",
        "apiKey": "ohwrzHP70iwUkBdzPEx6iUfSc3sLrHZ7678dy3Ie",
        "sessionId": "",
        "lastLogin": "2017-11-01T10:48:41+0100",
        "name": "asdasd",
        "email": "asdsad.asd@asd.de",
        "active": 1,
        "failedLogins": 0,
        "lockedUntil": "2017-11-01T10:48:41+0100",
        "extendedEditor": false,
        "disabledCache": false,
        "attribute": null
    },
    "success": true
}
```

### Update a user
If you want to update a user, you can send a PUT request to /users/{userId}


*Example code:*
```php
$client->put('users/2', [
    'username' => 'test2'
]);
```

*Example output:*
```json
{
    "success": true,
    "data": {
        "id": 2,
        "location": "http://localhost/shopware/api/users/2"
    }
}
```

## Create a new user
If you want to create a user, you can send a POST request to /users/


*Example code:*
```php
$client->post('users', [
    'roleId' => 1,
    'localeId' => 1,
    'username' => 'example',
    'name' => 'test',
    'email' => 'test@example.org',
    'active' => 1,
    'extendedEditor' =>  false,
    'disabledCache' => false
]);
```

Note: If you do not pass a password, the API will generate a
secure password and send it in the response.

*Example output:*
```json
{
    "success": true,
    "data": {
        "id": 3,
        "location": "http://localhost/shopware/api/users/3",
        "password": "Ar4dETspCp$jk$7"
    }
}
```

## Delete a user
If you want to update a user, you can send a DELETE request to /users/{userId}

*Example code:*

```php
$client->delete('users/2', []);
```

*Example output:*
```json
{
    "success": true
}
```

Attention: Due to a safety precaution, the API user who made the API call,
can not delete himself.
