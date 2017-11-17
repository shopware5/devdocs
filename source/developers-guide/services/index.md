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
To register a service, you can add it to your plugins `services.xml` like this:

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="swag_service_plugin.tax_calculator" class="SwagServicePlugin\Components\TaxCalculator" />
    </services>

</container>
```

## Calling the service
The new `TaxCalculator` can now be requested from e.g. a controller using the Shopware DI container:

```php
$this->container->get('swag_service_plugin.tax_calculator');
```

Keep in mind that any subsequent calls will return the same instance of the object - the container will keep a reference to the
object you returned the first time and **will not** call your event callback another time. If you need to return new
instances every time the service is requested, a [factory pattern](https://en.wikipedia.org/wiki/Factory_method_pattern) might be helpful.

## Injecting other services
In many cases, your services might depend on other services. Usually you will inject those using constructor injection:

```php
<?php

namespace SwagServicePlugin\Components;

use Shopware\Components\Logger;

class TaxCalculator
{
    /**
     * @var $logger Logger
     */
    private $logger;
    
    /**
     * @param $logger Logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * @param $netPrice float
     * @param $tax float
     * @return float
     */
    public function calculate($netPrice, $tax)
    {
        $this->logger->debug('Calculating price for tax: ' . $tax);
        return $netPrice * $tax;
    }
}
```

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="swag_service_plugin.tax_calculator" class="SwagServicePlugin\Components\TaxCalculator">
            <argument type="service" id="corelogger"/>
        </service>
    </services>

</container>
```

## Download
A simple example plugin can be found <a href="{{ site.url }}/exampleplugins/SwagService.zip">here</a>
