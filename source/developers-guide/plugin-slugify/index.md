---
layout: default
title: Create custom url slugger 
github_link: developers-guide/plugin-slugify/index.md
indexed: true
group: Developer Guides
subgroup: Tutorials
menu_title: Create custom url slugger
menu_order: 35
shopware_version: 5.2.5
---

From Shopware 5.2.5 we implemented the slugify-Framework. Slugify rewrites special characters like ñ, Ñ, ¿, é or Ó to n,n,-,e or o. If you named your article "tomàtiga de ramellet" the URL will be created like this "tomatiga-de-ramellet". Please note the slash will not be rewritten. That means that the article name „tomàtiga de ramellet / ecológica“ will be rewritten to "tomatiga-de-ramellet/ecologica".

## Configure slugify ruleset

By default, the slugify framework is defined with its default settings.

As part of the implementation in Shopware, you can overwrite the parameters in the dependency injection container by creating a `Resources/services.xml` file in your plugin.

```xml
<parameter key="shopware.slug.config" type="collection">
    <parameter key="regexp">/([^A-Za-z0-9\.]|-)+/</parameter>
    <parameter key="lowercase">false</parameter>
</parameter>
```

You can download an example plugin with this changes <a href="{{ site.url }}/exampleplugins/SwagCustomSlugConfig.zip">here</a>.

## Decorate the slugify service

Another approach could be to decorate the existing service and implement your own logic using the `Shopware\Components\Slug\SlugInterface`.

You can download an example plugin with this changes <a href="{{ site.url }}/exampleplugins/SwagCustomSlugService.zip">here</a>.