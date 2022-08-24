---
layout: default
title: Order List
github_link: shopware-enterprise/b2b-suite/component-guide/order-list.md
indexed: true
menu_title: Order List
menu_order: 9
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Component Guide
---

Direct-Link to the module: `my-shop.de/b2borderlist`

<div class="toc-list"></div>

### Order List Overview
A list with all available order lists.
A user can delete, copy or add the items to the basket for every order list.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/order-list-overview.png" style="width: 100%"/>

### Create Order List
With a click on the `create order list` Button a user can create a new order list.
To create a list, a name is required.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/order-list-create.png" style="width: 100%"/>

### Edit Order List
A click on the row or the edit button opens an Order List.

### Master Data
After clicking, a modal window opens with the name of the list.
A User can always change the name of the order list.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/order-list-masterdata.png" style="width: 100%"/>

### Positions
All positions are listed in the modal window.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/order-list-positions.png" style="width: 100%"/>

With the arrow buttons, you can sort the order list positions.
Deleting is done by clicking the delete button in the row.
Through editing, a user can change a position´s quantity and write a comment.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/order-list-positions-edit.png" style="width: 100%"/>

### Add Products
A user can add a position by clicking the add item Button.
After clicking the modal window ask the two **required values** `ordernumber` and `quantity`.
The `ordernumber` mus be a **valid** item order number from a normal item (`mode = 0`) in the shop.
The comment is an **optional** value.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/order-list-positions-add.png" style="width: 100%"/>

### Add Products from Detail Page
Optionally a user can add products to a specific order list on the product detail page.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/detail-order-list.png" style="width: 100%"/>
<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/detail-order-list-add-success.png" style="width: 100%"/>

If the product is already on the list, an error message will be shown.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/detail-order-list-add-failure.png" style="width: 100%"/>

### Add Products from Cart
On the confirm `my-shop.de/checkout/confirm` and cart page `my-shop.de/checkout/cart`, a user can add all normal products (`modus = 0`), to a specific order list.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/checkout-order-list-add-to-list.png" style="width: 100%"/>

The user can also create a new order list from the cart, by selecting **create new order list**.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/checkout-order-list-create-list.png" style="width: 100%"/>

Every product position from a cart can also be particular added to an order list.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/order-list/checkout-position-add-order-list.png" style="width: 100%"/>
