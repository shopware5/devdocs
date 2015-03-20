---
layout: default
title: Shopware 5 performance
github_link: developers-guide/shopware-5-performance.md
---

# Shopware 5 performance

## Introduction

In this document we detail the performance related details of Shopware 5. Some of them were already part of previous Shopware releases, which we complemented with new addictions, for optimized performance and scalability.

# Caching

Shopware uses multiple caches for different contents. Most of them are included and enabled out of the box with Shopware, and require no additional configuration to perform adequately on most scenarios.

## HTTP Cache

The HTTP Cache is a caching layer between your application and the customer's browser. Shopware includes a PHP implementation of an HTTP Cache, which is simple but has less than optimal performance. Should your site require it and your server support it, you can use tools like Varnish, which require additional installation and configuration steps, but can take full advantage of your server's capabilities to improve your shop's performance.

You can learn more about Shopware's HTTP cache, its configuration options, behaviour and alternatives by reading this 
[article](http://wiki.shopware.com/Understanding-the-shopware-http-Cache_detail_1809_928.html)

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

## PHP version and Opcode cache

Shopware's PHP code (and all PHP code) needs to be transformed from the format you see and understand into machine code your computer can actually execute. This process is complicated, and you don't need to know how it's done, but it is important to understand that it's executed on every incoming request to your server, meaning it can have a significant performance impact.

An Opcode cache can be used during this code transformation process, caching the resulting machine code so it's reused across multiple requests. Skipping that transformation process will, naturally, result in better performance in all requests after the first one.

### Opcode cache in PHP 5.5 and later

One of the main changes in PHP 5.5 was the addition of Zend Optimiser+ opcode cache, now know as OPcache extension. This means that the opcode cache is installed and enabled by default (on most systems), speeding up your shop. Shopware will automatically clear this cache when needed, in some situations (i.e. when a new plugin is installed). Please keep in mind that, in some situations and depending on your system configuration, you might need to manually clear this cache. Refer to OPcache extension documentation for more information.

### Opcode cache in PHP 5.4

PHP 5.4 does not include a opcode cache out of the box, but you can (and we recommend) that you install APC opcode cache. Shopware is able to detect and clear this cache on some situations, when required, but you might need to clear it manually yourself when performing changes to your code. More info is available on the project's documentation page.

### Other advantages to using PHP 5.5 or newer

The inclusion of OPcache extension in PHP 5.5 was one of the big performance improvements added in that version, but not the only one. Other features were added that will make Shopware (and most PHP projects) perform better in the newer version. PHP 5.6 also includes performance improvements over PHP 5.5, and it's safe to assume that future releases will continue to provide faster results over previous versions.

Shopware 5.0 will have PHP 5.4 as minimum requirement, which is, at the time of the release, the oldest supported PHP version. However, PHP 5.4 support will be dropped during Shopware 5's lifetime, and the minimum requirement will be raised to PHP 5.5. As such, we recommend using, whenever possible, PHP 5.5 or higher, not only for performance reasons, but also to ensure your system will support future releases of Shopware 5.

## Other caches

Other caching layers exist (Smarty, Doctrine, etc) that help boost Shopware's performance. These caches were not significantly changed during Shopware 5's development, and you can refer to their native documentation for help and details.
 
# Article loading

Listing articles and their details is the most common operation in any shop. The amount of information it involves can also make it one of the most system demanding operations, so, in Shopware 5, we implemented a complete new article loading mechanism, that's both easy to customise and fast.

## The new article loading system

Previously, whenever you wanted to load article information, you would iterate over a list of article IDs and, for each of them, load the necessary information. Different methods existed to load more or less detailed information, depending on the current request's context. Most of them can be found on the sArticles class. This has the downside of resulting in multiple database queries for each article, multiplied by the number of articles to be loaded, creating a significant performance bottleneck. 

In Shopware 5, a completely new article loading mechanism was introduced. The existing methods to load articles were maintained, but their implementation was refactored to use the new services, so you will always get the best performance possible, even if your plugins were not specifically designed for the new service system.

Whether you directly use the DIC's services or the old core methods, article loading is now done in batch mode, meaning that, no mather how many articles you are loading, the number of queries executed is always the same. Also, the data loading approach has been refactored: depending on exactly what data you require, different services work together to build one or multiple queries, that will load the necessary data from the database and populate the necessary structs, giving your plugins access to data in a consistent and easily to handle format.

The queries built by the article loading system have also been changed to improve performance: one-to-many and many-to-many database relations are not loaded in one single query, as this results in a performance penalty. Instead, the new article loading system handles each of these dimensions separately, in individual queries, and the data is then merged by PHP in memory, a much faster process. Again, batch queries are used, meaning that loading more articles do not result in more queries to the database.