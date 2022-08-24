---
layout: default
title: Price
github_link: shopware-enterprise/b2b-suite/component-guide/price.md
indexed: true
menu_title: Price
menu_order: 13
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Component Guide
---
<div class="alert alert-info">
    This feature got replaced by the <a href="{{ site.url }}/pricing-engine/">Pricing Engine</a> within Version 3.0.
</div>

The B2B-Suite also adds individual prices for debtors which you can define over the API.
You can find them in the <a href="https://gitlab.com/shopware/shopware/enterprise/b2b/-/blob/minor/swagger.json" target="_blank">swagger.json</a>.

For example if you want to add a price to a product SW10009.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/price-before-update.png" style="border: 1px solid #52606b;"/>

First you get the id of the product from the s_articles_details table.
The id is 15 in our case.

With the identifier you are able to fire the API call.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/swagger-price-update.png"/>

And then you see the price update.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/price-updated.png" style="border: 1px solid #52606b;"/>


