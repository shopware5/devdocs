---
layout: default
title: REST API - Payment and payment instances
github_link: developers-guide/rest-api/examples/media/index.md
menu_title: Payment
menu_order: 120
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article, you will find examples of the provided resource usage for different operations. For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[category API resource](/developers-guide/rest-api/api-resource-categories/)** if you haven't yet, to get more information about the category resource and the data it provides.

These examples assume you are using the provided **[demo API client](/developers-guide/rest-api/#using-the-rest-api-in-your-own-application)**.

## Usage

The Rest API calls for customer data ('''/api/customers''' und '''/api/customers/{id}''') supports payment informations.
This means you can list, create or update the payment data for customers.

Calls for order details ('''/api/orders/{id}''') covers the payment instances.
This contains the information about the payment and orders.
This field could not be changed. Creating or updating are not supported.

<b>Example:</b>

```php
// PUT /api/customers/1
array(
    "paymentData" => array(
        array(
            "paymentMeanId"   => 2,
            "accountNumber" => "Account",
            "bankCode"      => "55555555",
            "bankName"      => "Bank",
            "accountHolder" => "Max Mustermann",
        ),
    ),
);
```
