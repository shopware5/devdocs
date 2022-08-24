---
layout: default
title: Installing the SwagEnterpriseSearch
github_link: search/installation.md
indexed: true
menu_title: Installation
group: Shopware Enterprise
subgroup: Enterprise Search
menu_order: 2
---

## Installation

##### Requirements

- PHP 7 or later.
- Shopware version 5.2.19 or later
- Elasticsearch 5.* (Elasticsearch 6.0 since Shopware version 5.5.0 or later)

##### Installation

First you have to configure your elasticsearch like it is described [here](https://developers.shopware.com/sysadmins-guide/elasticsearch-setup/#elasticsearch-installation-and-configuration).
After configuration, SES can be installed as every other plugin in Shopware. You can find the plugin's source code [here](https://gitlab.com/shopware/shopware/enterprise/swagenterprisesearch/-/tree/major/components/SwagEnterpriseSearch).

After indexing the product catalogue with `php bin/console sw:es:index:populate` the search can be used.
