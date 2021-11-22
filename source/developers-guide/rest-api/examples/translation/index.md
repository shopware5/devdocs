---
layout: default
title: REST API - Examples using the translation resource
github_link: developers-guide/rest-api/examples/translation/index.md
menu_title: Translation examples
menu_order: 240
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article you can read more about using the translation resource.
The following part will show you examples including provided data and data you need to provide if you want to use this resource.
Please read the page covering the **[translation API resource](/developers-guide/rest-api/api-resource-translation/)** if you did not yet,
to get more information about the translation resource and the data it provides.

## Example 1 - Creating a new translation

This example shows how to create a new article translation

{% include 'api_badge.twig' with {'route': '/api/translations', 'method': 'POST', 'body': true} %}
```json
{
    "key": 200,
    "type": "article",
    "shopId": 2,
    "data": {
        "name": "Dummy translation",
        "__attribute_attr1": "Dummy attribute translation"
    }
}
```

## Example 2 - Updating a translation

{% include 'api_badge.twig' with {'route': '/api/translations/200', 'method': 'PUT', 'body': true} %}
```json
{
    "type": "article",
    "shopId": 2,
    "data": {
        "name": "Dummy translation",
        "__attribute_attr1": "Dummy attribute translation"
    }
}
```

## Example 3 - Creating a property group translation

{% include 'api_badge.twig' with {'route': '/api/translations', 'method': 'POST', 'body': true} %}
```json
{
    "key": 6,
    "type": "propertygroup",
    "shopId": 2,
    "data": {
        "groupName": "Dummy translation"
    }
}
```

## Example 4 - Updating a property group translation

{% include 'api_badge.twig' with {'route': '/api/translations/6', 'method': 'POST', 'body': true} %}
```json
{
    "type": "propertygroup",
    "shopId": 2,
    "data": {
        "groupName": "Dummy translation edited"
    }
}
```

## Example 5 - Creating a property option translation

{% include 'api_badge.twig' with {'route': '/api/translations', 'method': 'POST', 'body': true} %}
```json
{
    "key": 1,
    "type": "propertyoption",
    "shopId": 2,
    "data": {
        "optionName": "Dummy translation"
    }
}
```

## Example 6 - Updating a property option translation

{% include 'api_badge.twig' with {'route': '/api/translations/1', 'method': 'POST', 'body': true} %}
```json
{
    "type": "propertyoption",
    "shopId": 2,
    "data": {
        "optionName": "Dummy translation edited"
    }
}
```

## Example 7 - Creating a property value translation

{% include 'api_badge.twig' with {'route': '/api/translations', 'method': 'POST', 'body': true} %}
```json
{
    "key": 166,
    "type": "propertyvalue",
    "shopId": 2,
    "data": {
        "optionValue": "Dummy translation"
    }
}
```

## Example 8 - Updating a property value translation

{% include 'api_badge.twig' with {'route': '/api/translations/166', 'method': 'POST', 'body': true} %}
```json
{
    "type": "propertyvalue",
    "shopId": 2,
    "data": {
        "optionValue": "Dummy translation edited"
    }
}
```

## Example 9 - Updating multiple translations (batch mode)

{% include 'api_badge.twig' with {'route': '/api/translations', 'method': 'PUT', 'body': true} %}
```json
[
    {
        "key": 177,
        "type": "propertyvalue",
        "shopId": 2,
        "data": {
            "optionValue": "Dummy translation edited"
        }
    },
    {
        "key": 178,
        "type": "propertyvalue",
        "shopId": 2,
        "data": {
            "optionValue": "Another dummy translation edited"
        }
    }
]
```
