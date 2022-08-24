---
layout: default
title: Payment
github_link: shopware-enterprise/b2b-suite/component-guide/payment.md
indexed: true
menu_title: Payment
menu_order: 17
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Component Guide
---

### Clearance Payment Method
For order clearance, a payment method with the internal name `b2b_order_clearance_payment` will be created.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/backend-clearance-payment-method.png" style="width: 100%"/>

The payment method **has to be active** and will be **automatically selected** before placing an order for the clearance process.

`my-shop.de/checkout/shippingPayment`

<img src="{{ site.url }}/assets/img/b2b-suite/v2/frontend-checkout-clearance-payment-method-selection.png" style="width: 100%"/>

`my-shop.de/checkout/confirm`

<img src="{{ site.url }}/assets/img/b2b-suite/v2/frontend-checkout-clearance-payment-method.png" style="width: 100%"/>

### Risk Management Rule
A Shopware backend user can block a payment method for b2b or normal users with the risk management rule `B2B Account`.

Value `1` is for blocking the payment for b2b user and the value `0` for normal user.

<img src="{{ site.url }}/assets/img/b2b-suite/v2/backend-payment-risk-management.png" style="width: 100%"/>

### Payment Inheritance
Every contact inherits the debtor payment after login. You can change it for an order. After another login the contact inherits the debtor payment again.
