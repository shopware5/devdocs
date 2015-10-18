---
layout: default
title: REST API - Examples using the translation resource
github_link: developers-guide/rest-api/examples/translation/index.md
indexed: false
---

## Introduction

In this article you can read more about using the translation resource.
The following part will show you examples including provided data and data you need to provide if you want to use this resource.
Please read **[Translation](../api-resource-translation/)** if you did not yet, to get more information about the orders resource and the data it provides.
Also we are using the API client of the following document **[API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-a)**.

## Example 1 - Creating a new translation

This example shows how to create a new article translation

```
$api->post('translations/200, array(
    				'key' => 200,            #  s_articles.id
   					'type' => 'article',
    				'localeId' => 2,         # s_core_locales.id
    				'data' => array(
        			'name' => 'Dummy translation',
        			...
    )
));

```

## Example 2 - Updating a translation

```
$client->put('translations/200', array(
     			    'type' => 'article',
        			'localeId' => 2,         # s_core_locales.id
        			'data' => array(
            		'name' => 'Dummy translation',
            		...
        			)
));

```
