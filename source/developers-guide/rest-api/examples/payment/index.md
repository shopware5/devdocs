---
layout: default
title: REST API - Examples using the payment resource
github_link: developers-guide/rest-api/examples/media/index.md
menu_title: Payment examples
menu_order: 200
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

## Introduction

In this article, you will find examples of the provided resource usage for different operations.
For each analyzed scenario, we provide an example of the data that you are expected to provide to the API, as well as an example response.
Please read the page covering the **[payment method API resource](/developers-guide/rest-api/api-resource-payment-methods/)** if you haven't yet,
to get more information about the payment method resource and the data it provides.

## Usage

The Rest API calls for customer data (`/api/customers` and `/api/customers/{id}`) support payment information.
This means you can list, create or update the payment data for customers.

Calls for order details (`/api/orders/{id}`) covers the payment instances.
This contains the information about the payment and orders.
This field could not be changed. Creating or updating are not supported.

**Example:**

{% include 'api_badge.twig' with {'route': '/api/customers/1', 'method': 'PUT', 'body': true} %}
```json
{
    "paymentData": [
        {
            "paymentMeanId": 2,
            "accountNumber": "Account",
            "bankCode": "55555555",
            "bankName": "Bank",
            "accountHolder": "Max Mustermann"
        }
    ]
}
```
