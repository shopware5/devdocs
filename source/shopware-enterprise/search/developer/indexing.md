---
layout: default
title: Indexing Additional Data
github_link: search/developer/indexing.md
indexed: true
menu_title: Indexing Additional Data
group: Shopware Enterprise
subgroup: Enterprise Search
subsubgroup: Developer
menu_order: 3
---

For content search Shopware Enterprise Search does index additional data such as blogs, categories, static pages,
shopping worlds and manufacturers. In order to index additional entities, please follow the [corresponding guide](https://developers.shopware.com/developers-guide/elasticsearch/#indexing-additional-data)
in out devdocs.

After doing so, the default search extension system of Shopware applies: By adding a custom condition to your search
you will be able to perform additional queries to ElasticSearch with your custom content pages as [described here](https://developers.shopware.com/developers-guide/elasticsearch/#extend-product-search-query).
