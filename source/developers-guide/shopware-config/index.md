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

## Important options

### CSRF Protection
default:
```
    'csrfProtection' => [
        'frontend' => true,
        'backend' => true
    ],
```
With these options you can activate/deactivate the CSRF attack protection. By default, both options are set 
to `true`. Deactivating them is for example necessary if you want to run mink tests 
with behat. For more information take a look at the complete guide: [CSRF Protection](/developers-guide/csrf-protection/)

### PHP settings
default:
```
    'phpsettings' => [
        'error_reporting' => E_ALL & ~E_USER_DEPRECATED,
        'display_errors' => 0,
        'date.timezone' => 'Europe/Berlin',
    ],
```
These PHP settings override the defaults of your `php.ini`. `display_errors` is the 
only important option to change for debugging. Set this to `1` to enable the output
of low-level php errors.  
The default value of `error_reporting` should be sufficient for developing.

### exceptions
default: 
```
    'front' => [
        ...
        'throwExceptions' => false,
        'showException' => false,
    ],
```
For developing or debugging purposes you should set these options to `true`. This
prevents the default "Oops! An error has occurred!" message and prints out the real 
error message.

### template
default:
```
    'template' => [
        ...
        'forceCompile' => false,
        ...
    ],
```
This option controls the smarty template caching. Normally you have to clear your cache after 
every change on the template, but if you set `forceCompile` to `true` your template will be
compiled on every reload. This should be an essential option for every developer.

### HTTP Cache
default:
```
    'httpcache' => [
        'enabled' => true,
        'debug' => false,
        ...
  ],
```
With these options you can set the http-cache base configuration. For debugging we only take a 
look at the `debug` option and set it to `true`. If you want to learn more about the other options
you can take a closer look on the complete guide: [HTTP cache](/developers-guide/http-cache/)

## Example for development / debugging
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
