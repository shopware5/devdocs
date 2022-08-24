---
layout: default
title: Advanced Cart
github_link: shopware-enterprise/b2b-suite/technical/advanced-cart.md
indexed: true
menu_title: Advanced Cart
menu_order: 21
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

## Compatibility

At the moment Advanced Cart won't be applied when you are logged in as a B2B-User.
This means that no items were saved in the basket when a user has logged out.

The reason for this is that the order clearance is not compatible with Advanced Cart.
Advanced Cart adds items to the basket of a user. 
But it also adds items from an order clearance after a user log out from the clearance process.
 
We use the AdvancedCartSubscriber to disable the AdvancedCart feature.

