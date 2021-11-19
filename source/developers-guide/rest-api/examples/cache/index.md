---
layout: default
title: REST API - Overview of the cache resources
github_link: developers-guide/rest-api/examples/cache/index.md
menu_title: Cache examples
menu_order: 60
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article, you will find examples of the provided resource usage for different operations.
For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[cache API resource](/developers-guide/rest-api/api-resource-cache/)** if you haven't yet,
to get more information about the cache resource and the data it provides.

## Cache Resources

### Delete all caches

{% include 'api_badge.twig' with {'route': '/api/caches', 'method': 'DELETE'} %}

### Delete the HTTP-cache

{% include 'api_badge.twig' with {'route': '/api/caches/http', 'method': 'DELETE'} %}

### Delete the template cache

{% include 'api_badge.twig' with {'route': '/api/caches/template', 'method': 'DELETE'} %}

### Retrieve information about the cache

{% include 'api_badge.twig' with {'route': '/api/caches', 'method': 'GET'} %}

### Retrieve information about the HTTP-cache

{% include 'api_badge.twig' with {'route': '/api/caches/http', 'method': 'GET'} %}

### Retrieve information about the template-cache

{% include 'api_badge.twig' with {'route': '/api/caches/template', 'method': 'GET'} %}
