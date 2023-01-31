---
layout: default
title: User Guide
github_link: pricing-engine/user-guide/index.md
indexed: true
tags: [pricing engine, guide, start, begin]
menu_title: User Guide
group: Shopware Enterprise
subgroup: Pricing Engine
menu_order: 1
---

<div class="toc-list"></div>

## General

The Pricing Engine stores several prices in price lists which can be assigned to different contexts with conditions. The whole architecture is designed for use cases where an ERP or PIM system is managing the prices. So no backend view is present for adding, inserting or deleting prices. So the backend offers only a view to manage the conditions for the imported price lists. For controlling purposes there is a separate tab added in the product detail view. In this view you can see all configured prices for this product and filter it by the existing price lists.

To import price lists and prices there is a powerful REST-Api present. You can find all definitions in our swagger.json [here](https://gitlab.com/shopware/shopware/enterprise/swagenterprisepricingengine/-/blob/master/swagger.json)


## Backend View
You can find the Pricelist backend view under Items -> Pricelists. 
<img src="{{ site.url }}/assets/img/pricing-engine/backend_menu_item.png"/>

Now you will see an overview of all imported pricelists. All information about each pricelist is present there like name, priority, price and condition count.
<img src="{{ site.url }}/assets/img/pricing-engine/backend_overview.png"/>

With clicking on the edit button the Pricelistdetailview will open and you can see the created conditions for this list.

<img src="{{ site.url }}/assets/img/pricing-engine/backend_pricelist_detailview.png"/>

In this view you are able to add, update and delete conditions for the previous selected price list. Below you can see an example how to add a new condition:
 
<img src="{{ site.url }}/assets/img/pricing-engine/backend_pricelist_add_view.png"/>

If you want to check which prices are imported for a product, the Pricing Engine adds a new tab to the product detail view. In this tab you can search for prices and filter by given price lists:

<img src="{{ site.url }}/assets/img/pricing-engine/backend_article_pricelist_tab.png"/> 
