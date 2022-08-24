---
layout: default
title: Quick Start
github_link: shopware-enterprise/b2b-suite/component-guide/quick_start.md
indexed: true
menu_title: Quick start
menu_order: 17
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Component Guide
---

This document describes, how to quick start, if you have a demo package of the B2B-Suite. If you don't have
such a package yet, please contact us via [this form](https://enterprise.shopware.com/en/b2b-signup).

## Installation
We will provide the B2B-Suite as typical Shopware plugin. In your project, you can make use of the full framework architecture,
of course. But for a quick demo, the plugin is much easier to use.

The B2B-Suite requires the following plugins to be installed:
* [Cron](http://en.community.shopware.com/Plugin-Cron_detail_1606.html)

To [run the cronjob](http://en.community.shopware.com/Cronjobs_detail_1103.html), it has to be configured in your system.

You can install and activate the plugin from the [Plugin Manager in the Shopware backend](http://en.community.shopware.com/Plugin-Manager-from-Shopware-5_detail_1858.html#Installed).

Please clear the caches afterwards and reload the backend.

In Shopware 5.3.x or lower the B2B Suite requires always SSL or no SSL configuration. In Shopware 5.4.x the partial SSL option is no longer available.

In Shopware 5.4.x or newer, the B2B Suite can also be installed by the composer installation of shopware with the store-plugin-installer module. More detailed information can be found [here](https://github.com/shyim/store-plugin-installer).

## Creating a frontend user account
In order to test the B2B-Suite in the Shopware Frontend, [create a customer account first](http://en.community.shopware.com/Create_detail_1180_681.html). This can be done from the menu
`Customers->Create`. Now create a customer as usual. In order to upgrade this customer to a debtor account and grant access to the features of the
B2B-Suite, just tick the "Mark the account as debtor" box:

<img src="{{ site.url }}/assets/img/b2b-suite/v2/backend-debtor-flag.png" style="width: 100%"/>

## Using the B2B-Suite
Now visit your shop's frontend and log in as the customer you just created. After login, all <a href="{{ site.url }}/shopware-enterprise/b2b-suite/component-guide/">B2B features as described</a> are available.


