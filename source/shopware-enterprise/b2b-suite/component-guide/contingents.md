---
layout: default
title: Contingents
github_link: shopware-enterprise/b2b-suite/component-guide/contingents.md
indexed: true
menu_title: Contingents
menu_order: 6
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Component Guide
---

This component is managed in the company module, direct link to the company module: `my-shop.de/b2bcompany`.

Contingents are used to limit the ability to order without clearance for specific contact accounts or whole roles.
The B2B-Suite defaults to disallow all direct orders and put them into the clearance process.

A contingent group is a container for a specific rule set.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/contingent-index.png" style="width: 100%"/>

### Add contingent groups

In the contingent module you can create new contingent groups by clicking on the "Create contingent group" button.
After submitting the form you will be forwarded to the contingent detail page.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/contingent-detail-master.png" style="width: 100%"/>

### Add contingent rules

After creating the contingent group you can define corresponding rules. You have to set *Contingent rules* in Order to allow contacts to direct order or clear orders themselves.

There are three types of rules that can be configured.

* Order amount
* Order item quantity
* Order quantity

The rules supports three types of restrictions:

#### Restriction Types

**Order amount**

Handles the maximum order amount net per time unit.

**Order item quantity**

Handles the maximum quantity of products per time unit.

**Order quantity**

Handles the maximum allowed orders per time unit.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/contingent-detail-rules.png" style="width: 100%"/>

### Add contingent restrictions

A contingent restriction disallows orders that are otherwise whitelisted through the contingents rule set. Currently you can directly disable products from certain categories or their subcategories. Also are restrictions based on product price and product ordernumber available. 

### Contingent group deletions
To remove contingent groups you can use the trash button to delete the selected group which is no longer required.
