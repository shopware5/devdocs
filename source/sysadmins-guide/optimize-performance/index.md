---
layout: default
title: Optimize Shopware Performance
github_link: sysadmins-guide/optimize-performance/index.md
tags:
  - performance
  - tips
  - mysql
  - php
  - apc
  - cache
---

## Database configuration
The following variables are most relevant for a performance improvement.

- innodb_buffer_pool_size
> The larger you set this value, the less disk I/O is needed to access the same data in tables more than once. On a dedicated database server, you might set this to up to 80% of the machine physical memory size.
<br>*<a target="_blank" href="http://dev.mysql.com/doc/refman/5.6/en/innodb-parameters.html#sysvar_innodb_buffer_pool_size">Source: dev.mysql.com</a>*

- query_cache_size
> The amount of memory allocated for caching query results. **By default, the query cache is disabled**.
<br>*<a target="_blank" href="http://dev.mysql.com/doc/refman/5.6/en/server-system-variables.html#sysvar_query_cache_size">Source: dev.mysql.com</a>*

- *<a target="_blank" href="http://dev.mysql.com/doc/refman/5.6/en/optimization.html">More information about database optimization</a>*

## Recent PHP Version and Byte Code Cache

The PHP OPcache included in PHP 5.5 improves PHP performance by storing precompiled script bytecode in shared memory, thereby removing the need for PHP to load and parse scripts on each request.
<br>*<a target="_blank" title="dev.mysql.com" href="http://php.net/manual/en/intro.opcache.php">Source: php.net</a>*
<br>*<a target="_blank" title="dev.mysql.com" href="http://php.net/manual/en/book.opcache.php">More information about OPcache</a>*

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

## Productive mode (Http cache)
You have the opportunity to switch between "productive" and "development" mode. The development mode disables the HTTP cache and allows you to prepare your shop before going live.
After you modify all data, enable the shop to "productive" mode to enable the HTTP cache.

