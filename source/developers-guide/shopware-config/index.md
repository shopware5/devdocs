---
layout: default
title: config.php settings
github_link: developers-guide/shopware-config/index.md
indexed: true
group: Developer Guides
subgroup: General Resources
menu_title: config.php settings
menu_order: 45
---
 
<div class="toc-list"></div>

## Introduction

In this guide we will take a closer look at the configuration file `config.php`.
This file is in the root folder of a shopware installation. Normally it is generated during the
installation process and filled with your database credentials. 
It should look like this:

```
<?php
return [
    'db' => [
        'username' => 'yourUsername',
        'password' => 'yourPassword',
        'dbname' => 'yourDbname',
        'host' => 'yourHost',
        'port' => 'yourPort'
    ],
];
```

During this guide you will get to know some important options of the configuration.
For a complete list of options you can look at the `engine/Shopware/Configs/Default.php` file 
which holds all possible configuration options and their default values. You only
need to specify options in your `config.php` if you want to override the defaults.  
But keep in mind that most of these options should only be used for __debugging and testing__ 
and should be removed for your live system.

### Environment-specific config

To be able to set different configs for different environments, you can place a file called `config_ENVIRONMENT.php` in the Shopware root directory. `ENVIRONMENT` should be replaced with the environment the kernel gets initialized and defaults to `production`, e.g. `config_production.php`. The environment-specific config file is preferred over the normal one.

Blog post with a more advanced use case: [Configuring multiple Shopware environments](https://developers.shopware.com/blog/2016/01/26/configuring-multiple-shopware-environments/)

### Session locking

As of Shopware 5.2.13 session locking is enabled by default. This prevents unsuspected failures when concurrent ajax requests work with the same session variables. With enabled locking ajax requests are processed one after another.

```
    'session' => [
        ...
        'locking' => true,
    ],
```

### CSRF Protection

```
    'csrfProtection' => [
        'frontend' => true,
        'backend' => true
    ],
```

With these options you can activate/deactivate the CSRF attack protection. By default, both options are set 
to `true`. Deactivating them is for example necessary if you want to run mink tests 
with behat. For more information take a look at the complete guide: [CSRF Protection](/developers-guide/csrf-protection/)

### PHP runtime settings

```
    'phpsettings' => [
        'error_reporting' => E_ALL & ~E_USER_DEPRECATED,
        'display_errors' => 0,
        'date.timezone' => 'Europe/Berlin',
    ],
```

These PHP settings override the defaults of your `php.ini`.

`display_errors` is the only important option to change for debugging. Set this to `1` to enable the output
of low-level php errors.

The default value of `error_reporting` should be sufficient for developing.

### Exceptions

```
    'front' => [
        ...
        'throwExceptions' => false,
        'showException' => false,
    ],
```

The difference between `throwExceptions` and `showException` is how an exception will be handled.

The option `showException` keeps the Shopware error handler enabled, catches the PHP exception and prints the message instead of showing the generic "Oops! An error has occurred!" message.

In contrast, the option `throwExceptions` skips the Shopware error handler and outputs the pure PHP exception. This is important to understand, because some errors need to be caught by the Shopware error handler for self-healing processes e.g. CSRF Token invalidation.

### Template

```
    'template' => [
        ...
        'forceCompile' => false,
        ...
    ],
```

This option controls the smarty template caching. Normally you have to clear your cache after every change on the template, but if you set `forceCompile` to `true` your template will be compiled on every reload. This should be an essential option for every developer. Keep in mind that it does have a great impact on loading times and should never be used in production.

### Template security

```
    'template_security' => [
        'php_modifiers' => ['shell_exec', 'strpos'],
        'php_functions' => ['shell_exec', 'strpos'],
    ],
```
This option is available since version 5.2.26 and controls the smarty security configuration. Normally shopware has a whitelist of allowed php modifiers and functions for smarty template, but if you need additional php function in your template, you can extend the whitelist by this configuration.

### Cache

```
    'cache' => [
        'backend' => 'auto',
        'backendOptions' => [
            ...
        ],
        'frontendOptions' => [
            ...
        ]
    ],
```

These settings configure the caching implementation to be used inside of Shopware as well as everything necessary to set up that implementation. The `backend` option defines which cache implementation the cache should use, the available implementations can be found in `engine/Library/Zend/Cache/Backend`.

The `backendOptions` configure the settings for the selected cache implementation. A list of available settings can be found at the `$_options` member of the main class `Zend_Cache_Backend` and the respective backend class.

The `frontendOptions` work similar to the `backendOptions`, you can find the available settings in the classes in `engine/Library/Zend/Cache/Frontend`.

### HTTP Cache

```
    'httpcache' => [
        'enabled' => true,
        'debug' => false,
        ...
  ],
```

With these options you can set the HTTP Cache base configuration. For debugging we only take a look at the `debug` option and set it to `true`. If you want to learn more about the other options you can take a closer look on the complete guide: [HTTP cache](/developers-guide/http-cache/)

### Elasticsearch

```
    'es' => [
        'prefix' => 'sw_shop', // set a prefix for the ES indices
        'enabled' => false, // enable ES
        'write_backlog' => true, // enable backlog
        'number_of_replicas' => null, // set the number of replicase (e.g. 0 for development environments)
        'number_of_shards' => null, // set the number of shards 
        'total_fields_limit' => null, // set the maximum number of fields in an index
        'max_result_window' => 10000, // set the maximum number of results per window
        'wait_for_status' => 'green', // wait until cluster is in the specified state
        'batchsize' => 500, // set the documents batchsize 
        'backend' => [
            'write_backlog' => false, // enable backlog for the backend
            'enabled' => false, // enable ES for the backend
        ],
        'client' => [
            'hosts' => [
                'localhost:9200', // set the ES host
            ],
        ],
        'logger' => [
            'level' => $this->Environment() !== 'production' ? Logger::DEBUG : Logger::ERROR, // set the logger level (production environments should use Logger::ERROR only!)
        ],
        'max_expansions' => [ // set the max_expansions value of the phrase_prefix query ..
            'name' => 2, // .. for name
            'number' => 2, // .. for number
        ],
    ],
```
With these options you can change the elasticsearch configuration. Usually only the `enabled` and `client` options are needed to setup a runnable elasticsearch configuration. 
The `max_result_window` option (since SW 5.5.2) can be useful if you're having more than 10000 products per category. For this case you should increase the value to a bit more than the product amount of these categories. 
The `max_expansions` option comes with SW 5.5.5 and allows you to change the ES expansions value of the `phrase_prefix` query for `name` and `number`. This can be useful if you want to show more results while searching e.g. for an product number like "SW1000". By default only products with an up to two-digit longer number will be shown as well (e.g. SW1000XX). Increase the value for `max_expansions` to also get products with more than a two-digit longer product number. 
You can use this option to set own fields for a `phrase_prefix` query as well. For example `'manufacturer.name' => 4` would be possible to search for products which start with the given manufacturer's name or an up to four characters longer name. 

## Example development config

```
<?php
return [
    'db' => [
        'username' => 'yourUsername',
        'password' => 'yourPassword',
        'dbname' => 'yourDbname',
        'host' => 'yourHost',
        'port' => 'yourPort'
    ],
    
    'front' => [
        'throwExceptions' => true,
        'showException' => true
    ],

    'phpsettings' => [
        'display_errors' => 1
    ],

    'template' => [
        'forceCompile' => true
    ],

    'csrfProtection' => [
        'frontend' => true,
        'backend' => true
    ],
    
    'httpcache' => [
        'debug' => true
    ]
];
```

## Redis configuration
With Shopware 5.3 it is possible to use [redis](https://redis.io/) as cache adapter:

```
'model' => [
    'redisHost' => '127.0.0.1',
    'redisPort' => 6379,
    'redisDbIndex' => 0,
    'cacheProvider' => 'redis'
],
'cache' => [
    'backend' => 'redis', // e.G auto, apcu, xcache
    'backendOptions' => [
        'servers' => [
            [
                'host' => '127.0.0.1',
                'port' => 6379,
                'dbindex' => 0,
                'redisAuth' => ''
            ],
        ],
    ],
]
```
Be aware, that for Zend_Cache::CLEANING_MODE_ALL the cache implementation will issue "FLUSHDB" and therefore clear the current redis db index. For that reason, the db index for the cache should not be used for persistent data. 

## Changing MySQL Timezone
With Shopware 5.6.2 it is possible to define a custom timezone for the connection of your Shopware instance.

```
    'db' => [
        'username' => 'someuser',
        'password' => 'somedb',
        ...
        timezone' => null, // Something like: 'UTC', 'Europe/Berlin', '-09:30',
    ],
    ...
```
Please check the system info in your Shopware backend after changing this value to make sure the timezone is known to MySQL (use a relative offset if in doubt) and that there is no time difference between PHP and MySQL.
