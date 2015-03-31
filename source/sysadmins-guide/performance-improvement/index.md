---
layout: default
title: Shopware 5 performance improvement
github_link: sysadmins-guide/performance-improvement/index.md
---

## Database configuration
The following variables are relevant for a performance improvement.

- innodb_buffer_pool_size
The larger you set this value, the less disk I/O is needed to access the same data in tables more than once. On a dedicated database server, you might set this to up to 80% of the machine physical memory size.
<br>*<a target="_blank" title="dev.mysql.com" href="http://dev.mysql.com/doc/refman/5.6/en/innodb-parameters.html#sysvar_innodb_buffer_pool_size">source: dev.mysql.com</a>*

- query_cache_size
The amount of memory allocated for caching query results. **By default, the query cache is disabled**.
<br>*<a target="_blank" title="dev.mysql.com" href="http://dev.mysql.com/doc/refman/5.6/en/server-system-variables.html#sysvar_query_cache_size">source: dev.mysql.com</a>*

- *<a target="_blank" title="dev.mysql.com" href="http://dev.mysql.com/doc/refman/5.6/en/optimization.html">More information about database optimization</a>*

## PHP 5.5 & OPcache
OPcache improves PHP performance by storing precompiled script bytecode in shared memory, thereby removing the need for PHP to load and parse scripts on each request.
<br>*<a target="_blank" title="dev.mysql.com" href="http://php.net/manual/en/intro.opcache.php">source: php.net</a>*
<br>*<a target="_blank" title="dev.mysql.com" href="http://php.net/manual/en/book.opcache.php">More information about OPcache</a>*

## Strategy of aggregate functions
Shopware uses aggregate tables which improves the store front performance.
The following data sources are aggregated in shopware:

- top seller (s_articles_top_seller_ro)
- similar shown (s_articles_similar_shown_ro)
- also bought (s_articles_also_bought_ro)
- search index & keywords (s_search_index & s_search_keywords)
- seo urls (s_core_rewrite_urls)

Each of this aggregated data can have an own strategy when the data has to be build into the tables:

- Live **[default]** > on demand, when the data is requested in the store front
- Cronjob  > configured shopware cron job at night
- Manual > only over the backend or own implementation

It is recommend to configure the "Cronjob" strategy.

## Productive mode (Http cache)
You have the opportunity to switch between "productive" and "development" mode. The development mode disables the http cache and allows you to prepare your shop to going live.
After you modified all data, enable the "productive" mode and the http cache is enabled.

