---
layout: default
title: Code snippet collection
github_link: developers-guide/code-snippets/index.md
shopware_version: 5.2.0
indexed: true
---

This article will provide some useful code snippets that can help you when developing Shopware plugins. Please feel free to add additional snippets via GitHub!

## Read  config from a different shop than the current one

```php
/** @var \Shopware_Components_Config $config */
$config = \Shopware()->Container()->get('config');
$config->setShop($shop);
$config->get('nameDerConfig');
```
