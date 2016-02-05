---
layout: default
title: How to Set Up memcached as a Session Handler
github_link: sysadmins-guide/memcached-as-session-handler/index.md
tags:
  - performance
  - session
  - memcache
  - memcached

indexed: true
---

By default Shopware stores sessions in the database.
Alternativly [Memcached](http://memcached.org/) can be used as a session storage.

## Install the memcache Server

For Debian/Ubuntu based distributions:

```
sudo apt-get install memcached
```

## Install the PHP memcached Extension

Also the [PHP memcached extension](https://pecl.php.net/package/memcached) has to be installed.


For Debian/Ubuntu based distributions you can just install the `php5-memcached` package:


```
sudo apt-get install php5-memcached
```

For other distributions you can [compile](http://php.net/manual/en/memcached.installation.php) the extension by yourself.


## Configure Shopware

```php
'session' => array(
    'save_handler' => 'memcached',
    'save_path' => "localhost:11211",
),

'backendsession' => (
    'save_handler' => 'memcached',
    'save_path' => "localhost:11211",
),
```
