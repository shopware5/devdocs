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

The difference between `throwExceptions` and `showExceptions` is how an exception will be handled.

The option `showException` keeps the Shopware error handler enabled, catches the PHP exception and prints the message instead of showing the generic "Oops! An error has occurred!" message.

In contrast, the option `throwExceptions` skips the Shopware error handler and outputs the pure PHP exception. This is important to understand, because some errors need to be catched by the Shopware error handler for self-healing processes e.g. CSRF Token invalidation.

### Template

```
    'template' => [
        ...
        'forceCompile' => false,
        ...
    ],
```

This option controls the smarty template caching. Normally you have to clear your cache after every change on the template, but if you set `forceCompile` to `true` your template will be compiled on every reload. This should be an essential option for every developer. Keep in mind that it does have a great impact on loading times and should never be used in production.

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
        'throwException' => true,
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
