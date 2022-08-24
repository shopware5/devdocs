---
layout: default
title: SwagEssentials
github_link: shopware-enterprise/performance/essentials.md
indexed: true
menu_title: Essentials
group: Shopware Enterprise
subgroup: Performance
menu_order: 2
shopware_version: Shopware 5.3.0
---

Shopware Essentials is a tool collection for developers. It provides components such as

* additional, low level cache layers
* cache invalidation for multi appserver environments
* read / write query splitting
* high concurrency number incrementer

<div class="alert alert-info">
SwagEssentials is a toolset for developer that helps you to tackle more sophisticated Shopware projects. We only
recommend it for experienced developers as it requires advanced knowledge of Shopware and the toolset itself which cannot
be imparted in the context of the shopware support.
</div>

<div class="alert alert-info">
SwagEssentials requires advanced administrative tasks such as setting up and managing a redis server and a primary/replica database cluster.
Setting up, managing and supporting these environments is the responsibility of the customer / implementing partner.
</div>


<div class="toc-list"></div>

## Using SwagEssentials
SwagEssentials can be installed just as any other Shopware plugin. However, the developer selects the components he wants
to use for the Shopware environment. You can activate and configure SwagEssentials via parameters in the `config.php`. 
If you have all modules activated your config file should look like this:

```php
require_once __DIR__ . '/custom/plugins/SwagEssentials/Redis/Store/RedisStore.php';
require_once __DIR__ . '/custom/plugins/SwagEssentials/Redis/Factory.php';
require_once __DIR__ . '/custom/plugins/SwagEssentials/Redis/RedisConnection.php';
return [
'db' =>
        [
            'host' => 'mysql',
            'port' => '3306',
            'username' => 'app',
            'password' => 'app',
            'dbname' => 'shopware',
            'factory' => '\SwagEssentials\PrimaryReplica\PdoFactory',
            'replicas' => [
                'replica-backup' => [
                    'username' => 'app',
                    'password' => 'app',
                    'dbname' => 'shopware',
                    'host' => '10.123.123.41',
                    'port' => '',
                ]
            ]
        ],
    'swag_essentials' =>
        [
            'modules' =>
                [
                    'CacheMultiplexer' => false,
                    'Caching' => false,
                    'PrimaryReplica' => false,
                    'RedisNumberRange' => true,
                    'RedisPluginConfig' => false,
                    'RedisProductGateway' => false,
                    'RedisStore' => true,
                    'RedisTranslation' => true,
                ],
            'redis' =>
                [
                    0 =>
                        [
                            'host' => 'app_redis',
                            'port' => 6379,
                            'persistent' => true,
                            'dbindex' => 0,
                            'auth' => 'app',
                        ],
                ],
            'cache_multiplexer_hosts' =>
                [
                    0 =>
                        [
                            'host' => 'http://10.123.123.31/api',
                            'user' => 'demo',
                            'password' => 'demo',
                        ],
                    1 =>
                        [
                            'host' => 'http://10.123.123.32/api',
                            'user' => 'demo',
                            'password' => 'demo',
                        ],
                ],
            'caching_enable_urls' => true,
            'caching_enable_list_product' => true,
            'caching_enable_product' => true,
            'caching_ttl_urls' => 3600,
            'caching_ttl_list_product' => 3600,
            'caching_ttl_product' => 3600,
            'caching_ttl_plugin_config' => 3600,
            'caching_ttl_translation' => 3600,
        ],
    'httpcache' =>
        [
            'storeClass' => 'SwagEssentials\\Redis\\Store\\RedisStore',
            'redisConnections' =>
                [
                    0 =>
                        [
                            'host' => 'app_redis',
                            'port' => 6379,
                            'persistent' => true,
                            'dbindex' => 0,
                            'auth' => 'app',
                        ],
                ],
        ],
];
```

## Enterprise Cache / Redis Cache

**What it does**: Uses Redis for HTTP caching. This way, multiple appserver do share the same cache.

**Needed for**: Cluster setups, with more than one appserver


### Abstract
Usually Shopware can be used with either the built in Cache or Varnish. Both have their pros and cons. With our Redis Cache
for Enterprise environments, we also provide one solution, that combines the benefits of both existing cache alternatives

|   | Built in| Varnish | Enterprise Cache |
|:-:|:-:|:-:|:-:|
| Easy to setup / maintain| ✓ |   | ✓ |
| Central cache for multiple appservers |   |  ✓ | ✓ |
| Cluster setup|   |  Varnish Plus | ✓ |
| Optimized handling of ESI tags| ✓ |  Varnish Plus | ✓ |

Using the Enterprise Cache, all Shopware appserver are able to share the same cache. This increases the general cache hit rate
massively.

### Setup
Assuming that SwagEssentials is available at `custom/plugins/SwagEssentials` in your Shopware root directory, change your config.php like this:

```
<?php

require_once __DIR__ . '/custom/plugins/SwagEssentials/Redis/Store/RedisStore.php';
require_once __DIR__ . '/custom/plugins/SwagEssentials/Redis/Factory.php';
require_once __DIR__ . '/custom/plugins/SwagEssentials/Redis/RedisConnection.php';
return [
    'db' => [...],
    'swag_essentials' =>
        [
            'modules' =>
                [
                    ...
                    'RedisStore' => true,
                ],
            'redis' =>
                [
                    0 =>
                        [
                            'host' => 'app_redis',
                            'port' => 6379,
                            'persistent' => true,
                            'dbindex' => 0,
                            'auth' => 'app',
                        ],
                ],
        ],
    'httpcache' =>
        [
            'storeClass' => 'SwagEssentials\\Redis\\Store\\RedisStore',
            'keyPrefix'  => '', //this is only needed when running multiple shops on one Redis-Cluster 
            'compressionLevel' => 9,
            'redisConnections' =>
                [
                    0 =>
                        [
                            'host' => 'app_redis',
                            'port' => 6379,
                            'persistent' => true,
                            'dbindex' => 0,
                            'auth' => 'app',
                        ],
                ],
        ],

    // rest of your config.php
];
```

New are the both configuration keys `storeClass` and `redisConnections` in the `httpcache` config array.
`storeClass` configures the cache backend, in this case, you can just use `require_once 'custom/plugins/SwagEssentials/RedisStore/loader.php'`
in order to setup the cache correctly. You can provide a custom compression level for cached pages to control the tradeoff between redis memory usage (with low compression level) and time consumption for compression (with high compression level) when warming up the cache. The default value of 9 yields maximum compression, a value of 0 no compression at all.
`redisConnections` defines the redis connections you want to use for your HTTP cache. Available options are:

* `persistent`: If the redis connection should be persistent (default)
* `port`: Redis port, default 6379
* `host`: IP / host name of your redis server
* `timeout`: Timeout for the connection, default 30s
* `auth`: your authentification key, default empty
* `dbindex`: The redis database to be used, default 0

### Warming the cache

Shopware provides a script for warming your HTTP caches:

```
php bin/console sw:warm:http:cache
```

It allows you to configure the number of parallel workers to warm the HTTP cache:

```
php bin/console sw:warm:http:cache -b10
```

For Shopware-Instances below v5.5.0 please use the script which SwagEssentials provides you:


```
php bin/console sw:cache:siege
```

It also allows you to configure the number of parallel workers to warm the HTTP cache:

```
php bin/console sw:cache:siege -c10
```

Depending on the number of workers and the performance of your system, this will massively decrease the time needed to warm all shop pages.

<div class="alert alert-info">
<strong>Please notice:</strong> This command requires <strong>siege</strong> to be available. On debian based distributions it can be
installed using <strong>sudo apt-get install siege</strong>
</div>

## CacheMultiplexer
**What it does**: Multiplexes cache invalidation (e.g. from the cache/performance module) to multiple instances of shopware.

**Needed for**: Cluster setups, where you need to invalidate multiple appservers at once

### How to configure:
#### How to enable
In order to enable the submodule you have to import the parameters in your `config.php`:

```php
'db' => [...],
'swag_essentials' =>
        [
            'modules' =>
                [
                    ...,
                    'CacheMultiplexer' => true,
                ],
            'cache_multiplexer_hosts' =>
                [
                    [
                        'host' => 'http://10.123.123.31/api',
                        'user' => 'demo',
                        'password' => 'demo',

                    ],
                    [
                        'host' => 'http://10.123.123.32/api',
                        'user' => 'demo',
                        'password' => 'demo',

                    ],
                ],
        ],
```

#### Configuration
In the following example you can see how to configure an appserver. The credentials are used for the shopware API

```php
'cache_multiplexer_hosts' =>
    [
        [
            'host' => 'http://10.123.123.31/api',
            'user' => 'demo',
            'password' => 'demo',

        ],
    ],
```

## Primary / replica
**What it does**: Use multiple databases for shopware. Will split write queries to primary connection and read queries to replica connections.

**Needed for**: Cluster setups and setups with high load on the primary database connection

### How to configure
#### How to enable
Install the SwagEssentials plugin and enable `PrimaryReplica` in your config.php and enable the primary/replica setup in two steps:

 1. `require_once __DIR__ . '/custom/plugins/SwagEssentials/PrimaryReplica/PdoFactory.php'`;
 2. Add `'factory' => '\SwagEssentials\PrimaryReplica\PdoFactory',` to the `db` array
 3. configure at least one replica database in the `db.replicas` array

The result could look like this:

```
<?php

require_once __DIR__ . '/custom/plugins/SwagEssentials/PrimaryReplica/PdoFactory.php'`;

return [
    'db' => [
        'username' => 'root',
        'password' => 'root',
        'dbname' => 'training',
        'host' => 'localhost',
        'factory' => '\SwagEssentials\PrimaryReplica\PdoFactory',
        'port' => '',
        'replicas' => [
            'replica-backup' => [
                'username' => 'root',
                'password' => 'root',
                'dbname' => 'training',
                'host' => '192.168.0.30',
                'port' => '',
            ],
            'replica-redundancy' => [
                'username' => 'root',
                'password' => 'root',
                'dbname' => 'training',
                'host' => '192.168.0.31',
                'port' => '',
            ]
        ],
        'modules' =>
            [
                ...
                'PrimaryReplica' => true,
            ],  
    ]
];
```


#### Additional Configuration:
In the main `db` array of your `config.php` you can set additional options:
 * `includePrimary`: Also make the primary connection part of the "read" connection pool. Default: `false`
 * `stickyConnection`: Within a request, choose one random read connection from the replica pool and stick to that connection.  If disabled, for every request a new random connection will be chosen. Default: `true`

Furthermore you can set a `weight` for every connection (also for the primary connection). This way you can define,
how often a connection should be choosen in comparison to other connections.

#### Using a proxy for replica connections
In more advanced setups, you probably don't want to maintain a list of all database replicas in the application itself. If you have some sort of load balancer / proxy for your database replicas in place, you can just configure it as (the only) replica connection.
This has several advantages:

 * the proxy takes care of query distribution acrooss the replica pool
 * only the proxy needs to "know" of all replicas
 * the proxy can take care of e.g. health checks etc. 
 * solutions with haproxy or nginx are quite common 

## NumberRange
**What it does**: Allows you to remove the s_order_number ussage via mysql

**Needed for**: Cluster setups and setups with high load on the primary database connection

### How to configure:
#### How to enable
In order to enable the submodule, import it in your `config.php`:

```php
'db' =>[...],    
'swag_essentials' =>
    [
        'modules' =>
            [
                ...
                'RedisNumberRange' => true,
            ],
    ],
```

#### Configuration
To activate the NumberRange export the existing numbers via the following cli command:

```bash
./bin/console numberrange:sync --to-redis
``` 

To save the incrementions from redis to the database you can use this command:

```bash
/bin/console numberrange:sync --to-shopware
```


### Importing the numbers
As soon as the component is activated, Shopware will use Redis for storing the number ranges. In order to import your
current number ranges to Redis, please run:

```
php ./bin/console numberrange:sync  --to-redis
```

in your Shopware root. In order to import your Redis number ranges back to Shopware, just run

```
php ./bin/console numberrange:sync  --to-shopware
```

in your Shopware root.


## Caching
**What it does**: Allows you to cache additional resources in Shopware

**Needed for**: Uncached pages, Shopware instances without HTTP cache

### How to configure:
#### How to enable
In order to enable the submodule, import it in your `config.php`:

```php
'db' =>[...],    
'swag_essentials' =>
    [
        'modules' =>
            [
                ...
                'Caching' => true,
            ],        
        'caching_enable_urls' => true,
        'caching_enable_list_product' => true,
        'caching_enable_product' => true,
        'caching_ttl_urls' => 3600,
        'caching_ttl_list_product' => 3600,
        'caching_ttl_product' => 3600,
    ],
```

#### Configuration
Generally, you can configure the submodule for the following resources:

 * `urls`: Caching of generated SEO urls
 * `list_product`: Caching for listings
 * `product`: Caching for detail pages

 Each of these resources can be enabled / disabled separately:

```php
    'caching_enable_urls' => true,
    'caching_enable_list_product' => true,
    'caching_enable_product' => true,
```

Also each of these resources can have an individual TTL (caching time):

```php
    'caching_ttl_urls' => 3600,
    'caching_ttl_list_product' => 3600,
    'caching_ttl_product' => 3600,
```


## PluginConfigCaching
**What it does**: Allows you to cache the Shopware Plugin Configuration

**Needed for**: Uncached pages, Shopware instances without HTTP cache

### How to configure:
#### How to enable
In order to enable the submodule, import it in your `config.php`:

```php
'db' =>[...],
'swag_essentials' =>
    [
        'modules' =>
            [
                ...
                'RedisPluginConfig' => true,
            ],
        ...
        'caching_ttl_plugin_config' => 3600,        
    ],
```

#### Configuration
You can configure the cache TTL (time to live) for this module:

```php
    'caching_ttl_plugin_config' => 3600,
```

## ProductGatewayCaching
**What it does**: Allows you to cache the ListProduct Structs from Shopware in Redis

**Needed for**: Uncached pages, Shopware instances without HTTP cache

### How to configure:
#### How to enable
In order to enable the submodule, import it in your `config.php`:

```php
'db' =>[...],    
'swag_essentials' =>
    [
        'modules' =>
            [
                ...
                'RedisProductGateway' => true,
            ],        
        ...
    ],
```


## RedisHttpCaching
**What it does**: The Redis HTTP Cache allows to store the complete HTTP cache inside Redis.

**Needed for**: Cluster setups and setups with high load

### How to configure:
#### How to enable
In order to enable the submodule, import it in your `config.php`:

```php
require_once __DIR__ . '/custom/plugins/SwagEssentials/Redis/Store/RedisStore.php';
require_once __DIR__ . '/custom/plugins/SwagEssentials/Redis/Factory.php';
require_once __DIR__ . '/custom/plugins/SwagEssentials/Redis/RedisConnection.php';
return [
    'db' => [...],
    'swag_essentials' =>
        [
            'modules' =>
                [
                    ...
                    'RedisStore' => true,
                ],
            'redis' =>
                [
                    0 =>
                        [
                            'host' => 'app_redis',
                            'port' => 6379,
                            'persistent' => true,
                            'dbindex' => 0,
                            'auth' => 'app',
                        ],
                ],
        ],
    'httpcache' =>
        [
            'storeClass' => 'SwagEssentials\\Redis\\Store\\RedisStore',
            'redisConnections' =>
                [
                    0 =>
                        [
                            'host' => 'app_redis',
                            'port' => 6379,
                            'persistent' => true,
                            'dbindex' => 0,
                            'auth' => 'app',
                        ],
                ],
        ],
];
```

## TranslationCaching
**What it does**: Allows you to cache the translation calls against the mysql db 

**Needed for**: Uncached pages, Shopware instances without HTTP cache, Cluster setups and setups with high load on the primary database connection

### How to configure:
#### How to enable
In order to enable the submodule, import it in your `config.php`:

```php
'db' =>[...],    
'swag_essentials' =>
    [
        'modules' =>
            [
                ...
                'RedisTranslation' => true,
            ],
        ...
        'caching_ttl_translation' => 3600,
    ],
```

#### Configuration
You can configure the cache TTL (time to live) for this module:

```php
    'caching_ttl_translation' => 3600,
```


## Github repository
Access to the [Github repository](https://gitlab.com/shopware/shopware/enterprise/swagessentials) is granted on request.
