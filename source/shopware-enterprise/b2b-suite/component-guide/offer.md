---
layout: default
title: Offer
github_link: shopware-enterprise/b2b-suite/component-guide/Offer.md
indexed: true
menu_title: Offer
menu_order: 18
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Component Guide
---

Direct-Link to the module: `my-shop.de/b2boffer`

The B2B-Suite introduces the possibility to create offers. 
With these your customers have the chance to ask for
* special discounts 
* special prices 
* free products

As an example, you can grant a customer a discount of 500€ for an order or you sell your 1000 products for a price of 5€ per product instead of 6€.

### Feature Overview
* Ask for an offer
* Get an overview of all your offers
* Edit / delete offers
* Create an order out of an offer
* Get an overview of offers from the admin view
* See the changes of an offer
* Get notified about changes

## Ask for an offer
As a B2B-Customer, you can create an offer.

This can be done through converting your basket into an offer from the checkout process. 
Notice that you don't have to check the terms and conditions checkbox.
 
<img src="{{ site.url }}/assets/img/b2b/Basket-to-offer.png">

After having taken these actions, you will be redirected to an overview of your new offer.	

<img src="{{ site.url }}/assets/img/b2b/new-offer.png">

There you can:
* Go back to cart
* Add / delete / edit products 
* Add / delete / Edit discount prices
* Add / delete / edit a discount

After processing your offer request you can send it to an admin user.

## Get an overview of all your offers 
You can access an overview of all offer requests.

<img src="{{ site.url }}/assets/img/b2b/offer-grid.png">

They can be searched and sorted by different criteria.

Clicking on the row or detail button opens the detail view.

## Detail view
The offer detail view provides three different views.
 * An overview which shows the most important information. These are the original prices, the discount prices, the state and event dates.
 * A grid view of all items. There you can edit, delete and add items to the offer
 * A change view which provides a changelog of all actions done in the context of this offer e.g. adding items or comments.
 
<img src="{{ site.url }}/assets/img/b2b/offer-detail.png">
 
## Backend overview
Also, an additional view is added for the Shopware backend.

It can be accessed through the new menu item in the customers index tab.

<img src="{{ site.url }}/assets/img/b2b/offer-customers.png">

This view shows a list of all offers which have to be processed by the admin.

<img src="{{ site.url }}/assets/img/b2b/offer-backend-grid.png">

## Backend detail view
With the backend overview the admin can access the detail view of all offer requests.

<img src="{{ site.url }}/assets/img/b2b/offer-backend-detail.png">

The admin can modify the positions or the total discount of an offer to create an counter-offer.
After the modification from the admin, the status will be automatically set to admin declined.
This can be done by changing the offer and sending it back.

But there are also actions which can be performed all the time.
* Setting an expiration date for an offer
* Comment the offer to communicate with the customer

## Create an Order
After an order has been accepted by the customer and the admin it can be converted to an order.

view of the accepted offer request.  This can be done with a button, which appears in the detail overview of the accepted offer request.

<img src="{{ site.url }}/assets/img/b2b/offer-accept-offer.png">

It redirects you to the checkout page.

You can still change the quantity to a value higher than the offer value and still add items to the cart.

Here you can order it or create an order clearance if necessary.

After creating the order it will be shown in the order overview. 
Also the changelog of the offer will be displayed in the the order comment history.

## See the changes of your offer

This overview mainly serves the purpose to show you the current state and the history of your offer. 
You will see if the offer already got approved, declined, or even sent. 
Furthermore, all other changes will be shown there like changes of the discount, added items, removed items and price changes.

You can access it from the history in the offer detail overview.

There you can also find a button for creating comments.

<img src="{{ site.url }}/assets/img/b2b/b2b_offer_menu_history.png" style="display: block">

<img src="{{ site.url }}/assets/img/b2b/b2b_offer_history.png" style="display: block">

<img src="{{ site.url }}/assets/img/b2b/b2b_new_comment.png" style="display: block">

The same functionality can be accessed from the backend.

The site can be accessed from the history tab of the offer detail view.

<img src="{{ site.url }}/assets/img/b2b/b2b_backend_menu.png" style="display: block">

<img src="{{ site.url }}/assets/img/b2b/b2b_backend_auditlog.png" style="display: block">

## Get notified about changes

Every time the status of an offer (associated with you) changes you will get notified by an email.

Beeing a backend user you can choose to receive emails or not by marking the corresponding checkbox in the user administration.

<img src="{{ site.url }}/assets/img/b2b&b2b_offer_notification_checkbox.png" style="display: block">
