---
layout: default
title: Checkout
github_link: shopware-enterprise/b2b-suite/component-guide/checkout.md
indexed: true
menu_title: Checkout
menu_order: 12
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Component Guide
---

Direct-Link to the module: `my-shop.de/checkout/confirm`

The B2B-Suite adds two more functions to the checkout of Shopware:
* An order reference number
* A requested delivery date

The fields have multiple features:
* Both fields are not affecting Shopware during the order process.
* Backend users can access these information in the order detail panel.
* Users can also add more specific information to these fields like "ES-271" or "only mondays".

<img src="{{ site.url }}/assets/img/b2b-suite/v2/checkout.png" style="width: 100%"/>

<img src="{{ site.url }}/assets/img/b2b-suite/v2/backend-checkout.png" style="width: 100%"/>

### Order Lists
If the cart is filled with an order list, the B2B-Suite will show the name of the list below every products.

`my-shop.de/checkout/cart`
<img src="{{ site.url }}/assets/img/b2b-suite/v2/checkout-cart.png" style="width: 100%"/>

`my-shop.de/checkout/confirm`
<img src="{{ site.url }}/assets/img/b2b-suite/v2/checkout-confirm.png" style="width: 100%"/>

`my-shop.de/checkout/finish`
<img src="{{ site.url }}/assets/img/b2b-suite/v2/checkout-finish.png" style="width: 100%"/>

### Budgets
Every b2b user, except debtors, must have a sufficient budget to place or accept an order.

The confirm page includes a budget selection with a small overview of the selected budget.
Only sufficient budgets can be chosen.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/checkout-confirm-budget.png" style="width: 100%"/>

### Contingent Rules Messages
You can see all contingent rules and restrictions on the `my-shop.de/checkout/confirm` page if they are fulfilled or not

<img src="{{ site.url }}/assets/img/b2b-suite/v2/checkout-confirm-contingent-rules.png" style="width: 100%"/>
