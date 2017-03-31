---
layout: default
title: Service extensions
github_link: developers-guide/shopware-5-core-service-extensions/index.md
indexed: true
group: Developer Guides
subgroup: General Resources
menu_title: Service extensions
menu_order: 130
---

<div class="toc-list"></div>

## Introduction

The new bundle services like __StoreFrontBundle__, __SearchBundle__ or __SearchBundleDBAL__
don't contain events or hooks, but can still be replaced or extended.

All services are defined in Shopware's dependency injection container, also known as DI container or DIC. A plugin can replace these services in the DIC:

```php
public function onDispatchEventListener()
{
    $newService = new ReplacementServiceImplementation();
    Shopware()->Container()->set('shopware_storefront.list_product_service', $newService);
}
```

In this scenario, the __ReplacementServiceImplementation__ completely replaces the previous implementation, and is now fully responsible for providing the expected functionality.

In most cases, however, custom services want to extend the default behaviour, rather than completely replace it. In these scenarios, it's possible to use a __decorator pattern__ to modify the original service's behaviour:

```php
public function onDispatchEventListener()
{
    $coreService = Shopware()->Container()->get('shopware_storefront.list_product_service');
    $newService = new DecoratorServiceImplementation($coreService);
    Shopware()->Container()->set('shopware_storefront.list_product_service', $newService);
}
```

The __DecoratorServiceImplementation__ gets the existing implementation as a constructor argument, and can use it internally in its own logic. Another great advantage of using a decorator pattern over a full service replacement is that multiple implementations can provide their only logic on top of the previously existing service, regardless of it being the core service itself or an already decorated version/replacement of it.

In the next paragraphs we will further explain how and why you should implement each of these approaches, and provide demo code that you can use as a base for your own implementation.


## Your extension plugin

If you want to develop a plugin that replaces or decorates a core service, you first need to have it hook into the core logic, so that your code is executed. Additionally, since services can be reused in multiple locations in the Shopware core (and even in other plugins), it's important to replace/decorate them at the right moment, or there might be unexpected behaviour

```php
<?php

use ShopwarePlugins\ServiceExtension\StoreFrontBundle\ServiceExtensionImplementation;

class Shopware_Plugins_Frontend_ServiceExtension_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_AfterInitResource_shopware_storefront.list_product_service',
            'registerService',
            500
        );
        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace('ShopwarePlugins\ServiceExtension', $this->Path());
    }

    public function registerService()
    {
        // implement your replacement/decoration logic here
    }
}
```

## Replacing an existing service

In some scenarios, it might be convenient to fully discard the core implementation in favour of a completely new logic. Suppose you want to implement a plugin that loads your products from a __Redis__ instance, rather than the __MySql__ database (for now, let's ignore how the __Redis__ instance is populated). In this scenario, your logic has nothing in common with the default service, so you can simply replace it with your custom implementation:

```php
<?php

use ShopwarePlugins\SwagRedis\StoreFrontBundle\RedisProductService;

class Shopware_Plugins_Frontend_SwagRedis_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_InitResource_shopware_storefront.list_product_service',
            'replaceListProductService',
            200
        );
        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace('ShopwarePlugins\SwagRedis', $this->Path());
    }

    public function replaceListProductService()
    {
        return new RedisProductService();
    }
}
```

The Enlight_Bootstrap_InitResource allows to handle service initialisation.
As you can see, the old __shopware_storefront.list_product_service__ instance that existed in the DIC is overwritten by the __RedisProductService__ instance you return.
```php
<?php

namespace ShopwarePlugins\SwagRedis;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

class RedisProductService implements ListProductServiceInterface
{
    /**
     * @var RedisConnection A connection to the actual Redis service
     */
    private $connection;

    function __construct()
    {
        // Actual Redis connection object
        $this->connection = new RedisConnection();
    }

    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        // Load the product list from Redis
        $redisProducts = $this->connection->get(...);

        return $redisProducts;
    }

    public function get($number, Struct\ProductContextInterface $context)
    {
        // Load the product from Redis
        $redisProduct = $this->connection->get(...);

        return $redisProduct;
    }
}
```

Notice that the __RedisProductService__ class must implement the __ListProductServiceInterface__ and all of its methods and logic. In this example, it does so using the Redis connection exclusively.

## Decorating an existing service

In the previous example, for academic purposes, we assumed that our __Redis__ instance already contained all the necessary product data. Let's consider now that, besides handling data retrieval from __Redis__, our plugin is also responsible for populating __Redis__ itself, using data stored in __MySql__. But, as it turns out, we already have a very convenient service that fetches that data from __MySql__ for us: the default __shopware_storefront.list_product_service__ implementation. We can use that service implementation inside our own custom service by using a __decorator pattern__

```php
<?php

use ShopwarePlugins\SwagRedis\StoreFrontBundle\RedisProductService;

class Shopware_Plugins_Frontend_SwagRedis_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_AfterInitResource_shopware_storefront.list_product_service',
            'decorateService',
            200
        );
        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace('ShopwarePlugins\SwagRedis', $this->Path());
    }

    public function decorateService()
    {
        $coreService  = Shopware()->Container()->get('shopware_storefront.list_product_service');
        $redisService = new RedisProductService($coreService);
        Shopware()->Container()->set('shopware_storefront.list_product_service', $redisService);
    }
}
```

This implementation is very similar to the one presented before. However, if you look closely, you will notice that the default __shopware_storefront.list_product_service__ implementation is not discarded, but passed to the __RedisProductService__ constructor.

```php
<?php

namespace ShopwarePlugins\SwagRedis;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

class RedisProductService implements ListProductServiceInterface
{
    /**
     * @var RedisConnection A connection to the actual Redis service
     */
    private $connection;

    /**
     * @var ListProductServiceInterface The previously existing service
     */
    private $service

    function __construct(ListProductServiceInterface $service)
    {
        // Actual Redis connection object
        $this->connection = new RedisConnection();

        $this->service = $service;
    }

    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        // first try to get data over Redis
        $redisProducts = $this->connection->get(...);

        // if some data is missing, fallback to the database connection
        $coreProducts = $this->service->getList(...);

        // and add the missing data to Redis
        $this->connection->put($coreProducts, ...);

        return array_merge($redisProducts, $coreProducts);
    }

    public function get($number, Struct\ProductContextInterface $context)
    {
        // ...

        return $product;
    }
}
```

As you can see, this new version of the __RedisProductService__ is able to use the previous service to load data from the __MySql__ database and store it __Redis__. In subsequent requests, as the data is already available in __Redis__, __MySql__ is not queried, resulting in improved performance. Thus, __Redis__ is used as a product cache (again, for academic reasons, product updates were intentionally ignored).


## Multiple cascading decorators

As we've seen before, decorator services can reuse the core services and reuse or extend their functionality. However, if you look closely, you will notice that our __RedisProductService__ decorator class doesn't actually depend on the __DBAL__ implementation of the __shopware_storefront.list_product_service__, but rather on a generic __ListProductServiceInterface__ implementation. Enter a __SwagElasticSearch__ plugin:

```php
<?php

use ShopwarePlugins\SwagElasticSearch\StoreFrontBundle\ElasticSearchProductService;

class Shopware_Plugins_Frontend_SwagElasticSearch_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_AfterInitResource_shopware_storefront.list_product_service',
            'decorateService',
            400
        );
        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace('ShopwarePlugins\SwagElasticSearch', $this->Path());
    }

    public function decorateService()
    {
        $coreService  = Shopware()->Container()->get('shopware_storefront.list_product_service');
        $elasticSearchService = new ElasticSearchProductService($coreService);
        Shopware()->Container()->set('shopware_storefront.list_product_service', $elasticSearchService);
    }
}
```

This __SwagElasticSearch__ example is very similar to the __Redis__ integration plugin we discussed before. The underlying implementation will also look familiar:

```php
<?php

namespace ShopwarePlugins\SwagElasticSearch;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

class ElasticSearchProductService implements ListProductServiceInterface
{
    /**
     * @var ElasticSearchConnection A connection to the actual ElasticSearch service
     */
    private $connection;

    /**
     * @var ListProductServiceInterface The previously existing service
     */
    private $service

    function __construct(ListProductServiceInterface $service)
    {
        // Actual elastic search connection object
        $this->connection = new ElasticSearchConnection();

        $this->service = $service;
    }

    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        // first try to get data from Elastic Search
        $elasticSearchProducts = $this->connection->get(...);

        // if some data is missing, fallback to the database connection
        $coreProducts = $this->service->getList(...);

        // and add the missing data to Elastic Search
        $this->connection->put($coreProducts, ...);

        return array_merge($elasticSearchProducts, $core);
    }

    public function get($number, Struct\ProductContextInterface $context)
    {
        // ...

        return $product;
    }
}
```

Since both plugin use a decorator pattern, they extend the previously exiting service, that doesn't necessarily have to be the Shopware default __shopware_storefront.list_product_service__ implementation. Suppose that, for some reason, you want to use __Redis__ AND __ElasticSearch__ simultaneously on your Shopware shop. The above implementations can be used together.

The specified event priority in the Bootstrap files determines which event listener is executed first. In this case, __SwagElasticSearch__ has higher priority, so it will execute first, picking up the default Shopware __shopware_storefront.list_product_service__ implementation and decorating it with our very naive __ElasticSearch__ based cache.

Following this, the event listener on __SwagRedis__ is called to decorate the __shopware_storefront.list_product_service__. Notice that, at this point, the __shopware_storefront.list_product_service__ implementation in the DIC no longer contains an instance of the default Shopware core class, but rather an instance of __ElasticSearchProductService__. However, this is no problem at all. The __RedisProductService__ will decorate the __ElasticSearchProductService__ instead, using it as a fallback, in case the product we are looking for is not yet loaded on __Redis__.

## Other examples

The above examples show how you can use service decoration to use a data source other than __MySql__ in Shopware. And, as we also showed before, you can even replace the __MySql__ access altogether with a completely different data source, provided that your service implements the expected interface and behaviour.

However, you can use service decoration for tasks other than this.

```php
<?php

use ShopwarePlugins\SwagLiveShopping\LiveShoppingService;

class Shopware_Plugins_Frontend_SwagLiveShopping_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_AfterInitResource_shopware_storefront.list_product_service',
            'decorateService',
            600
        );
        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace(
            'ShopwarePlugins\SwagLiveShopping',
            $this->Path()
        );
    }

    public function decorateService()
    {
        $coreService = Shopware()->Container()->get('shopware_storefront.list_product_service');
        $newService = new LiveShoppingService($coreService);
        Shopware()->Container()->set('shopware_storefront.list_product_service', $newService);
    }
}
```

The above __SwagLiveShopping__ plugin (not related in any way with Shopware's LiveShopping premium plugin) uses a pattern already familiar to us, in order to decorate the __shopware_storefront.list_product_service__ service.

```php
<?php

namespace ShopwarePlugins\SwagLiveShopping;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

class LiveShoppingService implements ListProductServiceInterface
{
    /**
     * @var ListProductServiceInterface
     */
    private $service;

    /**
     * @param ListProductServiceInterface $service
     */
    function __construct(ListProductServiceInterface $service)
    {
        $this->service = $service;
    }

    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        $products = $this->service->getList($numbers, $context);

        foreach ($products as $product) {
            $product->addAttribute(
                'live_shopping',
                new Struct\Attribute(['live_shopping_id' => 1])
            );
        }

        return $products;
    }

    public function get($number, Struct\ProductContextInterface $context)
    {
        $product = $this->service->get($number, $context);

        $product->addAttribute(
            'live_shopping',
            new Struct\Attribute(['live_shopping_id' => 1])
        );

        return $product;
    }
}
```

The actual implementation uses the previously existing data source and, for each product retrieved from it, adds a custom attribute. Using this same approach, you can manipulate any resulting __Struct\ListProduct__ using whichever logic or criteria you which to implement.

Again, notice that using the decorator pattern allows us to not worry about the underlying implementation that we are decorating, or any other decoration that might be applied on top of ours. The __LiveShoppingService__ can decorate or be decorated by __ElasticSearchProductService__ or __RedisProductService__, individually or simultaneously, and the end result would still be the same.

This is rule is, of course, not always applicable, as undesired interactions or conflicts can occur, depending on the customizations performed by each decorator, or the order in which they are executed. However, this example does demonstrate that it is very easy to provide non-conflicting implementations of different decorators, which can work together to implement multiple features on your Shopware shop.

## Conclusions

This example is very academic, and not adequate for production environments, but it does illustrate a very important idea: decorating over replacing allows multiple plugins to extend the same service without causing conflicts or breaks in behaviour. It's even possible that your plugin never actually decorates the core implementation of a service, but an instance provided by another plugin you have installed in your current installation. If implemented correctly, this can be made fully transparent to you and your plugins, and multiple decoration layers can be added on the same service without undesired side effects.

This doesn't mean you need to always decorate existing services. In some scenarios, full replacement might be a better solution than decorating. Just keep in mind that other plugins might want to decorate your service as if it where the core implementation, to ensure both your and other plugins work as expected.

You can find a small <a href="{{ site.url }}/exampleplugins/SwagPluginSystem.zip">example plugin here</a>.
