---
layout: default
title: Shopware 5 performance guide for developers
github_link: developers-guide/shopware-5-performance-for-devs/index.md
indexed: true
tags:
  - performance
  - tips
  - cache
redirect:
  - /developers-guide/shopware-5-performance/
---

In this document we cover the performance-related features of Shopware 5, that you should both use as a plugin developer and configure as a shop administrator. This document presents some of the techniques Shopware 5 uses to speed up internal processes, and that, most of the time, are available to you as a developer to take advantage of while developing your plugins. We also discuss core implementation details that are relevant for performance reasons, and that you should know of, even if they are not used directly by your plugins.

<div class="alert alert-warning">
<strong>Note:</strong> This guide only covers the details of Shopware 5 itself, and does not cover system configuration details. Please refer to the <a href="/sysadmins-guide/shopware-5-performance-for-sysadmins/">Shopware 5 performance guide system administrators</a> for more details on how you can fine tune your server for improved performance. 
</div>

## Caching

Shopware uses multiple caches for different contents. Most of them are included and enabled out of the box with Shopware, and require no additional configuration to perform adequately on most scenarios.

## HTTP Cache

The HTTP Cache is a caching layer between your application and the customer's browser. Shopware includes a PHP implemented HTTP Cache you can use without additional system dependencies associated with other equivalent tools. You can enable/disable the built in HTTP Cache by switching between "productive" and "development" modes in the Backend of your shop. The development mode disables the HTTP cache and allows you to prepare your shop before going live. After you modify all data, enable the shop to "productive" mode to enable the HTTP cache.

You can learn more about Shopware's HTTP cache, its configuration options, behaviour and alternatives by reading this
[article](http://wiki.shopware.com/Understanding-the-shopware-http-Cache_detail_1809_928.html)

Like mentioned before, the built in HTTP Cache is based on a PHP implementation, which is simple but has less than optimal performance. Should your site require it and your server support it, you can use tools like Varnish, which require additional installation and configuration steps, but can take full advantage of your server's capabilities to improve your shop's performance. We provide official Varnish configuration support for Enterprise customers.

## Theme Cache

### How the theme cache works

In previous Shopware versions, JavaScript and CSS files were served as-is to the browser. Additionally, if plugins or custom templates added additional content files, they would be loaded separately by the browser, resulting in additional HTTP calls to the server.

In Shopware 5, along with the introduction of the new theme system, we created theme cache system. The theme cache is responsible for greatly optimizing the above process:

- Registered LESS files are compiled into CSS
- All CSS and JavaScript files are merged into single .css and .js files
- The resulting files are minimized

This results in less server requests, less bandwidth usage and faster response times to the client. Both the core and plugin CSS and JavaScript files can be handled by both the LESS compiler and the theme cache. For info on how you can register your plugin resources to be handled by them, please refer to the [Theme startup guide](/designers-guide/theme-startup-guide).

Please keep in mind that the theme cache is only used when using Shopware 5 themes. If you choose to use a Shopware 4 template in a Shopware 5 installation, your assets will not be compressed by the theme cache. Also, the CSS/JavaScript cache files are shared across all language shops of each shop. Different subshop have different cache content.

### Using and configuring the theme cache

The process described above is ideal for production environments, as it saves bandwidth and provides a better, faster experience for the end user. However, during the development phase, you can configure the theme cache to better fit your needs and instantly reflect the changes you make to your code.

In the backend's Theme Manager, you can configure the theme cache behaviour using the 'Compiler configuration' section of the 'Settings' window

- Disable compiler caching: generated files are not cached between requests. Each page requests forces recompilations of LESS files into CSS. Significant performance impact, but ensures your LESS/CSS changes are immediately reflected in the frontend.
- Create a CSS source map: CSS debugging info displayed on your browser console will point to the LESS file, instead of the resulting CSS compiled file. Use when debugging LESS files.
- Compress CSS: If disable, CSS (including compiled LESS) content will not be minimized. Use when debugging, at a bandwidth usage impact.
- Compress javascript: If disable, javascript content will not be minimized. Use when debugging, at a bandwidth usage impact.

The cache content is generated in one of two occasions:

- When a frontend page request is received and the cache is empty
- When the explicitly generated in the backend

As the cache generation process can take a few seconds, we highly recommend that you do not rely on the first option, unless your are developing/debugging CSS or JavaScript content. When a Shopware 5 theme is configured and you clear the theme cache in the backend, a popup window will give you the possibility to warm up the theme cache. You can choose not to do this, but it's highly recommended that you warm up the cache at this point, specially in production environments. This process can last a few seconds per shop.

## Doctrine cache

Doctrine also has a caching layer that is enabled by default in Shopware 5. Your `config.php` file has an optional `model` section where you can, among other settings, change the storage mechanism for this cache. By default it detects whether you are using APCu (recommended) or XCache (in that order) and uses them if available. You can also explicitly declare a caching mechanism to be used, as long as you make sure it is properly configured in your system. Refer to [Doctrine's documentation page on caching](http://doctrine-orm.readthedocs.org/en/latest/reference/caching.html) for more details

## Other caches

Other caching layers exist that help boost Shopware's performance. These caches were not significantly changed during Shopware 5's development, and you can refer to their native documentation for help and details. You can provide these caches with configuration

# Article loading

Listing articles and their details is the most common operation in any shop. The amount of information it involves can also make it one of the most system demanding operations, so, in Shopware 5, we implemented a complete new article loading mechanism, that's both easy to customise and fast.

## The new article loading system

Previously, whenever you wanted to load article information, you would iterate over a list of article IDs and, for each of them, load the necessary information. Different methods existed to load more or less detailed information, depending on the current request's context. Most of them can be found on the sArticles class. This has the downside of resulting in multiple database queries for each article, multiplied by the number of articles to be loaded, creating a significant performance bottleneck.

In Shopware 5, a completely new article loading mechanism was introduced. The existing methods to load articles were maintained, but their implementation was refactored to use the new services, so you will always get the best performance possible, even if your plugins were not specifically designed for the new service system.

Whether you directly use the DIC's services or the old core methods, article loading is now done in batch mode, meaning that, no mather how many articles you are loading, the number of queries executed is always the same. Also, the data loading approach has been refactored: depending on exactly what data you require, different services work together to build one or multiple queries, that will load the necessary data from the database and populate the necessary structs, giving your plugins access to data in a consistent and easily to handle format.

The queries built by the article loading system have also been changed to improve performance: one-to-many and many-to-many database relations are not loaded in one single query, as this results in a performance penalty. Instead, the new article loading system handles each of these dimensions separately, in individual queries, and the data is then merged by PHP in memory, a much faster process. Again, batch queries are used, meaning that loading more articles do not result in more queries to the database.

## Strategy of aggregate functions
Shopware uses aggregate tables which improves the store front performance.
The following data sources are aggregated in Shopware:

- top seller (s_articles_top_seller_ro)
- similar shown (s_articles_similar_shown_ro)
- also bought (s_articles_also_bought_ro)
- search index & keywords (s_search_index & s_search_keywords)
- SEO urls (s_core_rewrite_urls)

Each of these aggregated data can have an own strategy when the data has to be built into the tables:

- Live **[default]** > on demand, when the data is requested in the store front
- Cronjob  > executed together with other Shopware's cron tasks
- Manual > only generated over the backend or using your own implementation

We recommend using the "Cronjob" strategy. Don't forget to configure you system to execute Shopware's cron tasks periodically.



