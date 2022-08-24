---
layout: default
title: Product Search
github_link: shopware-enterprise/b2b-suite/technical/product-search.tpl
indexed: true
menu_title: Product Search
menu_order: 8
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---


## Description

Our product search is a small jQuery Plugin which allows you to create input fields with autocompletion for products. A small example is shown below. The plugin deactivates the default autocompletion for this field from your browser. 

```html
<div class="b2b--search-container">
    <input type="text" name="" data-product-search="{url controller=b2bproductsearch action=searchProduct}" value="" />
</div>
```

### Elasticsearch

While using Elasticsearch you have to enable the variants filter in the filter menu of the basic settings to show all variants in the product search.

![image](/assets/img/b2b-suite/features/variant-filter.png)
