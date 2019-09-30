---
layout: default
title: Shopware Session handling
github_link: sysadmins-guide/sessions/index.md
tags:
  - performance
  - session
  - memcache
  - memcached
  - redis
  - lock
  - transaction
redirect:
  - /sysadmins-guide/memcached-as-session-handler/
  - /sysadmins-guide/memcached-as-session-handler/index.html
indexed: true
group: System Guides
menu_title: Sessions
menu_order: 80
---

Shopware uses the database for session handling by default. This article will explain configuration options and
alternatives.


<div class="toc-list"></div>

## General concept

By default Shopware uses the database for storing user sessions. So in smaller and mid-size setups you will not need
to take care of the sessions. Even clustering with this configuration is possible, as all app server can use the
database as central session storage: No session stickiness required. For larger setups with much traffic, however, you will most probably want to configure alternative session storages such as memcache
in order to reduce the load on the database.

Shopware knows two types of sessions that can be configured independently in your `config.php`: `session` is used for
sessions in the shop frontend, `backendsession` is used for sessions in shopware's backoffice.


## Session locking

"Session locking" is a mechanism, that makes sure, that multiple requests writing to the same session will not overwrite
each other. As Shopware makes use of AJAX requests for dynamic content, this might happen in some rare situations.

For that reason, Shopware will lock sessions for the default database session adapter as of Shopware 5.2.14. So even if
multiple requests write to the session, no race conditions will occur. This comes with a little downside, however: Multiple
AJAX requests writing to the session cannot be handled parallel anymore: So request B will only be able to write to
the session once request A has finished. In practice this should not be too much of a problem, as Shopware limits the
number of session writes on most pages.

Sometimes you have the need to do some long running Ajax-request (e.g. one that calls a slow, external API) which blocks
consequent other Ajax-requests, leading to sluggish user experience. In that case you can close the session early in your
controller by calling the function `session_write_close()` manually, before doing any long running actions. 

## Available session adapters
The following list will explain the session adapters Shopware supports by default

### Memcached
Memcached is a popular cache server, that also can be used for sessions. As it supports session locking as well, we
recommend Memcached for bigger setups with high traffic.

#### Install

For Debian/Ubuntu based distributions:

```bash
sudo apt-get install memcached
```

Also the [PHP memcached extension](https://pecl.php.net/package/memcached) has to be installed.

For Debian/Ubuntu based distributions you can just install the `php-memcached` package:


```bash
sudo apt-get install php-memcached
```

For other distributions you can [compile](http://php.net/manual/en/memcached.installation.php) the extension by yourself.


#### Configuration

In this example the memcache server was installed locally ("localhost") on the app server. For cluster setups you will most probably
have a stand alone memcache instance in place:

```php
'session' => [
    'save_handler' => 'memcached',
    'save_path' => "localhost:11211",
],

'backendsession' => [
    'save_handler' => 'memcached',
    'save_path' => "localhost:11211",
],
```

### Redis
Redis is a popular key/value storage, that easily can be clustered for redundancy. It does not support session locking,
however.
When using a single redis instance with multiple Shopware installations (e.g. for staging environments) it would be wise to use separate prefixes for each installation. Otherwise, your session keys could be re-used between your installations and race conditions or strange session-related behavior may occur. A prefix can be configured using the connection uri. Please consult the official [phpredis documentation](https://github.com/phpredis/phpredis#php-session-handler).

#### Install

For Debian/Ubuntu based distributions:

```bash
sudo apt-get install redis-server
```

Also the corresponding PHP extension has to be installed:

For Debian/Ubuntu based distributions you can just install the `php-redis` package:

```bash
sudo apt-get install php-redis
```

#### Configuration
In this example the redis server is running locally (127.0.0.1) on port 6379:

```php
'session' => [
    'save_handler' => 'redis',
    'save_path' => "tcp://127.0.0.1:6379",
],

'backendsession' => [
    'save_handler' => 'redis',
    'save_path' => "tcp://127.0.0.1:6379",
],
```

### File
The "file" session adapter also supports session locking and will create sessions on the file system of each app server.
For that reason, it is not recommended to be used in cluster setups, as it will then require mechanisms for syncing or
session stickiness. Also ever read / write of session data will access the hard drive of the server - and might therefore
slow down response times.

#### Configuration

```php
'session' => array(
    'save_handler' => 'file',
),

'backendsession' => array(
    'save_handler' => 'file',
),
```

### Database
The database session handler is Shopware's default session handler.

#### Configuration

```php
'session' => [
    'save_handler' => 'db',
],

'backendsession' => [
    'save_handler' => 'db',
],
```

#### Disable locking
As of Shopware 5.2.13, you can disable the session locking for the database handler:

```php
'session' => [
    'save_handler' => 'db',
    'locking' => false
]
```

#### Blocking transactions
Until Shopware 5.2.16 the session garbage collector will clean up expired session every 100 requests. This is quite frequent
and might cause issues with blocked transactions in some situations. For those cases, you can configure the garbage
collection to a higher value:

```
'session' => [
    'save_handler' => 'db',
    'gc_probability' => 1,
    'gc_divisor' => 1000
],
```

For further information please see [Bejamin Eberlei's blog post regarding session collection](https://tideways.io/profiler/blog/php-session-garbage-collection-the-unknown-performance-bottleneck).

As of Shopware 5.2.17 Shopware will clean up the session every 200 requests (`gc_divisor = 200`). Furthermore Shopware
5.2.17 introduces a new command to clean up the session manually:

`php bin/console sw:session:cleanup`

This could be used in a cronjob, for example. 
