---
layout: default
title: REST API - Overview of the cache resources
github_link: developers-guide/rest-api/examples/cache/index.md
menu_title: The cache resources
menu_order: 90
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article, you will find examples of the provided resource usage for different operations. For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[category API resource](/developers-guide/rest-api/api-resource-categories/)** if you haven't yet, to get more information about the category resource and the data it provides.

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.


## Cache Resources

<table>
    <thead>
    <tr>
        <th>
            <div>Resources</div>
        </th>
        <th >
            <div>HTTP</div>
        </th>
        <th colspan="1">
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            $cacheResource-&gt;delete('all');
        </td>
        <td>
            DELETE api/caches/
        </td>
        <td colspan="1">
            <div>Deletes all caches</div>
        </td>
    </tr>
    <tr>
        <td>
            $cacheResource-&gt;delete('http');
        </td>
        <td>
            DELETE api/caches/http
        </td>
        <td colspan="1">
            <div>Deletes the HTTP cache</div>
        </td>
    </tr>
    <tr>
        <td>
            $cacheResource-&gt;delete('template');
        </td>
        <td>
            DELETE api/caches/template
        </td>
        <td colspan="1">
            <div>Deletes the template cache</div>
        </td>
    </tr>
    <tr>
        <td>
            $cacheResource-&gt;getList();
        </td>
        <td>
            GET api/caches/
        </td>
        <td colspan="1">
            <div>Gets the cache information</div>
        </td>
    </tr>
    <tr>
        <td colspan="1">
            $cacheResource-&gt;getOne('http'); 
        </td>
        <td colspan="1">
            GET api/caches/http
        </td>
        <td colspan="1">
            <div>Gets the HTTP cache information</div>
        </td>
    </tr>
    <tr>
        <td colspan="1">
            $cacheResource-&gt;getOne('template');
        </td>
        <td colspan="1">
            GET api/caches/template
        </td>
        <td colspan="1">
            <div>Gets the template cache information</div>
        </td>
    </tr>
    </tbody>
</table>
