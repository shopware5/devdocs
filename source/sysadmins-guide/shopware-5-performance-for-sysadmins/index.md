---
layout: default
title: Shopware 5 performance guide for system administrators
github_link: sysadmins-guide/shopware-5-performance-for-sysadmins/index.md
indexed: true
tags:
  - performance
  - tips
  - mysql
  - php
  - apc
  - cache
redirect:
  - /sysadmins-guide/optimize-performance/
group: System Guides
menu_title: Performance Guide
menu_order: 50
---

In this document we detail performance related settings that you can set in your server to get the most out of it. Some of them were already part of previous Shopware releases, which we complemented with new dependencies, for optimized performance and scalability. In most cases, it's assumed that you have already installed and configured Shopware on your server, and that it's running properly. This document does not cover configuration options *needed* by Shopware (for example, increasing PHP's `memory_limit` to an acceptable level), and focus only on making an already working system perform better.

<div class="alert alert-warning">
<strong>Note:</strong> This guide only covers system configuration optimizations, and does not cover Shopware's configuration itself. However, there are several configuration options inside Shopware itself that you can use to improve you shop's performance. Please refer to the <a href="/developers-guide/shopware-5-performance-for-devs/">Shopware 5 performance guide for developers</a> for more details on this subject.
</div>

<div class="toc-list"></div>

## Server

Shopware performance optimization should start long before you install Shopware itself. If needed, you can contact one of our partners, that will help you determine which server requirements you will have, depending on your estimated shop size and incoming traffic. There is no rule of thumb for this, as each shop is unique and should be analysed on a case-by-case basis. There are, however, some rules you should observe when choosing your hosting provider:

- `Processor`: Different hosting providers and plans provide different options. More than speed, the key value here is the number of cores. Each core works separately from each other, meaning that the more you have, the more concurrent requests your shop will be able to handle. This is particularly relevant if you expect periods of high traffic on your shop (for example, a highly anticipated product release that causes an abnormal flow of requests to your shop) or you expect to frequently have multiple dozens of simultaneous requests on your shop. As a rule of thumb, even for small shops, a dual core processor is recommended.

- `Memory`: Memory is used by all parts of your system. Not only does Shopware consume memory, but so does your server's operating system, your database server, your web server, and any other application your server might be running. Additionally, in the sections bellow, we will cover different caching configurations, that you can use to speed up Shopware, at the expense of additional memory. So, even if your Shopware installation runs with minimal memory configurations like 1 or 2 GBs, it's recommended that you consider adding additional memory to your setup, especially if you plan on using other applications on your server simultaneously, or if you want to configure the caching features described below.

- `Hard drive`: Aside from disk space, which does not affect performance (unless the disk is full or close to it), hard drives are differentiated by their nature into one of two categories: `hard disc drive` (HDD) and `solid state drives` (SSD). The former are more commonly available, especially in entry level hosting solutions, but are gradually being phased out by most providers in favor of the latter, which are usually more expensive but offer significantly better performance. As the price difference between the two has been declining over the last few years, it's now possible to find hosting solution that use the faster SSD technology even for budget-level hosting solutions.

## Database configuration - MySQL

The following variables are the most relevant when it comes to fine tuning MySQL's performance:

- `innodb_buffer_pool_size`: The larger you set this value, the less disk I/O is needed to access the same data in tables more than once. On a dedicated database server, you might set this to up to 80% of the machine physical memory size.
<br>*<a target="_blank" href="http://dev.mysql.com/doc/refman/5.6/en/innodb-parameters.html#sysvar_innodb_buffer_pool_size">Source: dev.mysql.com</a>*

- `query_cache_size`: The amount of memory allocated for caching query results. **By default, the query cache is disabled**.
<br>*<a target="_blank" href="http://dev.mysql.com/doc/refman/5.6/en/server-system-variables.html#sysvar_query_cache_size">Source: dev.mysql.com</a>*

The MySQL documentation has a [dedicated page](http://dev.mysql.com/doc/refman/5.6/en/optimization.html) that covers optimization and performance improvements. For more details about the above mentioned variables, or other potential improvements, please refer to it.


### MariaDB users

The above settings are also applicable when using MariaDB with InnoDB or XtraDB engines. You can read more about MariaDB performance settings [on their website](https://mariadb.com/kb/en/mariadb/optimization-and-tuning/). Please keep in mind that, although you might be able to run Shopware while using MariaDB, no official support is provided.

## Web server

Improving your web server's configuration can also help your Shopware installation perform better, especially under heavy load periods, when multiple simultaneous requests are made to your server.

### Apache

Apache web server is the officially supported web server for hosting Shopware. While Shopware might also work in other web servers (nginx, for example), they are not officially supported. Apache uses extension modules that cover a multitude of tasks, ranging from URL rewriting for SEO purposes to increased security. These modules, many of them optional for Shopware's operation, might affect performance as well. Each module usually has a list of configuration option, which associated documentation you should read to optimize their performance.

#### Prefork MPM vs Worker MPM

Apache is the application responsible for handling all incoming requests to your server. If your request asks for a static file (an image or a CSS file, for example), Apache handles that request alone. If you ask for a dynamic page (for example, a Shopware article listing page), Apache handles that request over to PHP, which returns a response to Apache which, in turn, returns that response to the user's browser. As you can imagine, these processes have to be very fast and, more important, have to happen concurrently for multiple requests.

To do this, Apache uses one of several existing `Multi-Processing Modules` (MPMs), a module responsible for deciding how each concurrent request is handled. While several different MPMs exist, `Prefork` and `Worker` are the most commonly used. While usually considered faster, `Worker` is also not thread safe, meaning it does not meet the requirements to execute Shopware. As such, you need to use `Prefork` to run Shopware.

#### Configuring Prefork MPM

Prefork settings determine how many simultaneous requests Apache (and thus your server) will handle and how many requests will queue once it can no longer handle more simultaneous requests. These settings greatly impact your Apache's performance and system resource usage, so you might need to do a bit of trial and error before finding which setup offers better performance without overwhelming your server's processor and/or memory. Remember that these settings apply to the web server as a whole, meaning that they will also affect other content served by Apache. You should also take that into consideration when setting these values.

Apache configuration settings is often split into multiple files, to improve readability and maintainability, so you might have to search different files until you find the one that contains Prefork's configuration values. While not required, you will usually find these values inside a `<IfModule mpm_prefork_module> ... </IfModule>` block, to ensure that these values are not loaded if you decide to use another MPM. This configuration block may contain all or some of the following variables:

- `StartServers`: This number controls the number of server processes created when the server (Apache) is started. As the number of processes is later on handled dynamically, this setting has little impact on performance.

- `MinSpareServers` and `MaxSpareServers`: These settings control the number of idle server processes that are allowed to exist. Idle processes are processes that exist but are not actually doing any work. They consume resources (although, as they are idle, this consumption is relatively low) but are immediately available to answer a request once it comes in. The creation of these processes take time, which is why Apache keeps a few always available. The default value for these settings are usually 5 and 10 respectively, which is adequate for smaller servers. Increase them to scale performance on more powerful servers. Note that its generally recommended to use the same value for `MinSpareServers` and `StartServers`.

- `MaxRequestWorkers` and `ServerLimit`: The maximum number of active server processes that are allowed to simultaneously exist on your server. Setting these values too low may cause simultaneous requests to be queued or ignored, decreasing response time during times of heavy load. Setting them too high may exhaust your server memory, causing memory swaps and decreasing performance or even crashing the server altogether. These two values should be equal.

- `MaxConnectionsPerChild`: This value represents the number of connections each thread will accept and handle before its terminated by Apache. This setting is mostly used to prevent memory leakage from consuming a significant amount of server memory. The default 0 prevents processes from ever being terminated (unless they are idle and `MaxSpareServers` has been reached, or Apache itself is terminated). If you suspect your server is affected by memory leakage, set a different value to this variable.

### Nginx

Nginx has been known to run Shopware on several server setups. If you wish to, you can use this web server instead of Apache. Note that this setup is not supported, so our support team will not be able to assist you should you run into problems with it.

The [following post](http://nginx.com/blog/tuning-nginx/) on the official nginx blog might be a good place to start if you are looking for performance tips for your nginx configuration. You can also use the [nginx configuration for Shopware](https://github.com/bcremer/shopware-with-nginx) provided by [Benjamin Cremer](https://github.com/bcremer).

## PHP

At the time of this publication, the latest stable PHP version was 7.3, which includes several performance optimizations over PHP 7.0 and PHP 5.6. As such, we recommend that you use PHP 7.3 whenever possible, though Shopware currenty still supports PHP 7.2. Please check the supported PHP versions of your Shopware version before updating PHP.

### Opcode cache

Shopware's PHP code (and all PHP code) needs to be transformed from the format you see and understand into machine code your computer can actually execute. This process is complicated, and you don't need to know how it's done, but it is important to understand that it's executed on every incoming request to your server, meaning it can have a significant performance impact.

An Opcode cache can be used during this code transformation process, caching the resulting machine code so it's reused across multiple requests. Skipping that transformation process will, naturally, result in better performance in all requests after the first one.

#### Opcode cache in PHP 5.5 and later - OPcache

One of the main changes in PHP 5.5 was the addition of Zend Optimiser+ opcode cache, now known as OPcache extension. This means that the opcode cache is installed and enabled by default (on most systems), speeding up your shop. Shopware will automatically clear this cache when needed, in some situations (i.e. when a new plugin is installed). Please keep in mind that, in some situations and depending on your system configuration, you might need to manually clear this cache. Refer to OPcache extension documentation for more information.

The inclusion of OPcache extension in PHP 5.5 was one of the big performance improvements added in that version, but not the only one. Other features were added that will make Shopware (and most PHP projects) perform better in the newer version. PHP 5.6 also includes performance improvements over PHP 5.5, and it's safe to assume that future releases will continue to provide faster results over previous versions.

#### OPcache configuration

Depending on your system and PHP installation, OPcache might not be installed, installed but not enabled or enabled by default. Check your `phpinfo()` output for more info on your current settings. The [official project documentation page](http://php.net/manual/en/book.opcache.php) details all the configuration options you can set in order to fine tune OPcache's behaviour. Below we document some of these settings.

- `opcache.max_accelerated_files`: The maximum number of PHP files OPcache will handle. A typical clean Shopware 5 installation has around 7000 PHP files, but that does not include generated cache files, 3rd party or custom plugin files or other PHP files your web server might host simultaneously. Make sure that the value you put here is high enough to include all your PHP files (unless, for some reason, you have many files and not enough memory).

- `opcache.memory_consumption`: How much memory you wish to allocate to OPcache. The default value is 64MB, which should be enough for smaller shop installations. [Rasmus Lerdorf](https://github.com/rlerdorf) created a [simple PHP script](https://github.com/rlerdorf/opcache-status) that you can use to check your current OPcache settings and status, including hit/miss rates and memory status. Use this tool to monitor your cache status and decide if you need to fine tune this setting. As before, this value is server-wide, meaning that you might need to increase it if you host more PHP projects on your server or if your Shopware installation includes a significant amount of 3rd party or custom plugins.

- `opcache.revalidate_freq`: This setting tells OPcache how often (in seconds) it should check your files for changes. The default value is 2 (seconds), but you can safely increase it to a bigger value once your shop is in production and you don't expect frequent code changes

- `opcache.fast_shutdown`: This optimizes memory handling when deconstructing objects, resulting in improved performance.

- `opcache.save_comments` This setting defaults to 1 and must not be changed. Some Shopware code relies on annotations that don't work properly if this configuration value is set to 0.

#### Opcode cache in PHP 5.4 - APC

PHP 5.4 does not include a opcode cache out of the box, but you can (and we recommend) that you install APC opcode cache. APC is and stands for Alternative PHP Cache, and will allow you to increase your server performance if you are using PHP 5.4

#### APC configuration

You should check your `phpinfo()` output to determine if APC is installed and enabled. Installing and enabling APC depends on your system, but you can find information about this on related support sites or forums. You can find more details about APC configuration on the [project configuration page](http://php.net/manual/en/apc.configuration.php). Bellow you can see some of the most commonly customized values:

- `apc.shm_segments`: The number of memory segments allocated to APC. Typically you want 1 (default value). Increasing this value will multiply the memory consumption of APC, so you should be careful if you decide to change this value

- `apc.shm_size`: The memory size allocated to each segment. The default is 32M, which you should increase to at least 64M or more, depending on your system and expected workload.

- `apc.num_files_hint`: The number of files that APC will handle. A typical clean Shopware 5 installation has around 7000 PHP files, but that does not include generated cache files, 3rd party or custom plugin files or other PHP files your web server might host simultaneously. Make sure that the value you put here is high enough to include all your PHP files (unless, for some reason, you have many files and not enough memory).

- `apc.ttl` and `apc.user_ttl`: Once the cache fills up and new entries come in, some old entries need to be removed. These TTLs (in seconds) determine how long an old entry is allowed to stay in cache until its removed. Using 0 means that old entries will never be removed. The values for this settings greatly depend on your system setup, but it's recommended to not set them to 0.

### User data cache - APCu

While OPcache and APC speed up PHP code processing, APCu does the same for user data. If available, it's used by Shopware to store in memory commonly accessed data that would otherwise be stored in disk, and slower to read. For that reason, it's recommended that you install APCu on your production environment.

<div class="alert alert-warning">
<strong>Note:</strong> Be careful not to confuse APC (opcode cache) with APCu (user data cache). At the time, APC is not compatible with PHP 5.5 or greater, but APCu is. APCu's configuration variables are set in the `apc` namespace, and not in `apcu`, as you might expect. This means to abstract differences between the two libraries, and is intended. Your `phpinfo()` should display `APC support: Emulated`, meaning you are correctly using APCu but not APC. If you are using PHP 5.4, you might want to use APC to replace OPcache, which is not bundled into PHP itself in this version.
</div>

APCu uses the same configuration variables as APC, so you can see the above APC configuration section for more details.
 Please keep in mind that you need to change `apc.num_files_hint` and/or `apc.shm_size` depending on if you are using only APC, only APCu or both simultaneously.

## Cron

Some tasks associated with your shop can be executed in the background, like sending emails or refreshing search indexes. For these tasks, you can use Cron jobs. These execute certain processes in the background, automatically, at certain intervals, indirectly contributing to increased usage speed when a customer visits your shop. To find out more about Cron jobs in Shopware, read [the following wiki page](http://en.wiki.shopware.com/_detail_1103.html).

## HTTP Cache

One of the most commonly used tools for speeding up websites is an HTTP cache. These work by storing previously generated responses and returning them immediately when a similar requests comes in, preventing the server from generating a new, equal response, and the time and resource consumption associated with that job.

### Shopware HTTP Cache

Shopware 5, like previous versions, includes its own HTTP cache implementation, in PHP. You can read more details about it in the [Shopware 5 performance guide for developers](/developers-guide/shopware-5-performance-for-devs). While this is not the most performing HTTP cache implementation, it requires no additional system configuration, and should work properly even in entry level hosting solutions, where resources and configuration access are limited.

### Varnish

Varnish is also an HTTP cache implementation, but offers much better performance and customization than Shopware's PHP HTTP cache. It's a very scalable HTTP cache, meaning it can be used for small as well as enterprise grade shops. Shopware officially supports [Varnish configuration](/sysadmins-guide/varnish-setup/) for customers with Enterprise licenses.
