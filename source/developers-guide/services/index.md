---
layout: default
title: Plugin services
github_link: developers-guide/services/index.md
tags:
  - services
  - dependency injection
  - di
indexed: true
menu_title: Services
menu_order: 60
group: Developer Guides
subgroup: Developing plugins
---

When writing object oriented code, you will usually need small, reusable services that encapsulate certain parts of your business logic.
This tutorial will cover the creation of services and how to use them together with Shopware's DI container.

<div class="toc-list"></div>

## Services
Ideally a service is just a simple PHP class with only one responsibility:

```php
<?php

namespace SwagServicePlugin\Components;

class TaxCalculator
{
    public function calculate($netPrice, $tax)
    {
        return $netPrice * $tax;
    }
}
```

Instead of creating the `TaxCalculator` class everywhere you need it, you can make it available with the DI container.
This way the same instance of this class can be accessed everywhere in Shopware - even by other plugins.

## Registering the service
First of all, you should register the namespace of your plugin in your plugin's bootstrap:

```php
<?php

class Shopware_Plugins_Frontend_SwagServicePlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function afterInit()
    {
        $this->get('Loader')->registerNamespace(
            'ShopwarePlugins\SwagService',
            $this->Path()
        );
    }
}
```

Now the actual service can be registered using an event:

```php
<?php

class Shopware_Plugins_Frontend_SwagServicePlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_InitResource_swag_service_plugin.tax_calculator',
            'onInitTaxCalculator'
        );

        return true;
    }
}
```

The event name `Enlight_Bootstrap_InitResource_swag_service_plugin.tax_calculator` consists of two parts:

* `Enlight_Bootstrap_InitResource_`: The base event name, which is emitted by the DI container when a service is looked up
* `swag_service_plugin.tax_calculator`: The unique service name. It is best practice to have the plugin name `swag_service_plugin`
and the service name `tax_calculator` in it.

The event callback now just needs to return an instance of the requested service:

```php
public function onInitTaxCalculator()
{
    return new \ShopwarePlugins\SwagService\Component\TaxCalculator();
}
```

Be aware that the event will only be emitted (and thus the callback will only be called) when the service is actually requested.

## Calling the service
The new `TaxCalculator` can now be requested using the Shopware DI container:

```php
$this->get('swag_service_plugin.tax_calculator');
```

This will work e.g. from controllers or plugin bootstraps. You can also use the global Shopware container singleton, if needed:


```php
Shopware()->Container()->get('swag_service_plugin.tax_calculator');
```

Keep in mind that any subsequent calls will return the same instance of the object - the container will keep a reference to the
object you returned the first time and **will not** call your event callback another time. If you need to return new
instances every time the service is requested, a [factory pattern](https://en.wikipedia.org/wiki/Factory_method_pattern) might be helpful.

## Injecting other services
In many cases, your services might depend on other services. Usually you will inject those using constructor injection:

```php
<?php

namespace ShopwarePlugins\SwagService\Component;

class TaxCalculator
{
    private $logger;

    public function __construct(\Shopware\Components\Logger $logger)
    {
        $this->logger = $logger;
    }

    public function calculate($netPrice, $tax)
    {
        $this->logger->debug('Calculating price for tax: ' . $tax);
        return $netPrice * $tax;
    }
}
```

In order to inject this dependency, a simple update of the event callback is needed:

```php
public function onInitTaxCalculator()
{
    return new \SwagServicePlugin\Components\TaxCalculator(
        $this->get('pluginlogger')
    );
}
```

## Download
A simple example plugin can be found <a href="{{ site.url }}/exampleplugins/SwagService.zip">here</a>
