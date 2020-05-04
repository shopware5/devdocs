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

All services are defined in Shopware's dependency injection container, also known as DI container or DIC. A plugin (`SwagExample` in this case) can replace these services in the DIC by overriding them in its `services.xml` file:

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="shopware_storefront.list_product_service" class="SwagExample\Bundle\StoreFrontBundle\ListProductService" />
    </services>

</container>
```

In this scenario, the plugins __SwagExample\Bundle\StoreFrontBundle\ListProductService__ completely replaces the previous implementation, and is now fully responsible for providing the expected functionality.

In most cases, however, custom services want to extend the default behaviour, rather than completely replace it. In these scenarios, it's possible to use a __decorator pattern__ to modify the original service's behaviour:

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="swag_example.list_product_service"
                 class="SwagExample\Bundle\StoreFrontBundle\ListProductService"
                 decorates="shopware_storefront.list_product_service"
                 public="false">

            <argument type="service" id="swag_example.list_product_service.inner"/>
        </service>
    </services>
</container>
```

The plugins __SwagExample\Bundle\StoreFrontBundle\ListProductService__ gets the existing implementation as a constructor argument, and can use it internally in its own logic. Another great advantage of using a decorator pattern over a full service replacement is that multiple implementations can provide their own logic on top of the previously existing service, regardless of it being the core service itself or an already decorated version/replacement of it.

In the next paragraphs we will further explain how and why you should implement each of these approaches, and provide demo code that you can use as a base for your own implementation.


## Your extension plugin

If you want to develop a plugin that replaces or decorates a core service, you need a basic plugin structure, so your code is executed:

```php
<?php
// SwagExample/SwagExample.php

namespace SwagExample;

use Shopware\Components\Plugin;

class SwagExample extends Plugin {}
```

```php
<?php
// SwagExample/Bundle/StoreFrontBundle/ListProductService.php

namespace SwagExample\Bundle\StoreFrontBundle;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;

class ListProductService implements ListProductServiceInterface
{
    private $originalService;

    public function __construct(ListProductServiceInterface $service)
    {
        $this->originalService = $service;
    }

    public function getList(array $numbers, ProductContextInterface $context)
    {
        $products = $this->originalService->getList($numbers, $context);

        // Modify product list
        
        return $products;
    }
}

```

To actually decorate the existing service you have to override it in your `services.xml` as described in the [introduction](#introduction):

```xml
<!-- SwagExample/Resources/services.xml -->

<service id="swag_example.list_product_service"
         class="SwagExample\Bundle\StoreFrontBundle\ListProductService"
         decorates="shopware_storefront.list_product_service"
         public="false">

    <argument type="service" id="swag_example.list_product_service.inner"/>
</service>
```

## Replacing an existing service

In some scenarios, it might be convenient to fully discard the core implementation in favour of a completely new logic. Suppose you want to implement a plugin that loads your products from a __Redis__ instance, rather than the __MySql__ database (for now, let's ignore how the __Redis__ instance is populated). In this scenario, your logic has nothing in common with the default service, so you can simply replace it with your custom implementation:

```xml
<!-- SwagRedis/Resources/services.xml -->

<service id="shopware_storefront.list_product_service" class="SwagRedis\Bundle\StoreFrontBundle\RedisProductService" />
```

As you can see, the old __shopware\_storefront.list\_product\_service__ instance that existed in the DIC is overwritten by the __RedisProductService__ instance.

```php
<?php
// SwagRedis/Bundle/StoreFrontBundle/RedisProductService.php

namespace SwagRedis\Bundle\StoreFrontBundle;

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

In the previous example, for academic purposes, we assumed that our __Redis__ instance already contained all the necessary product data. Let's consider now that, besides handling data retrieval from __Redis__, our plugin is also responsible for populating __Redis__ itself, using data stored in __MySql__. But, as it turns out, we already have a very convenient service that fetches that data from __MySql__ for us: the default __shopware_storefront.list_product_service__ implementation. We can use that service implementation inside our own custom service by using a __decorator pattern__:

```xml
<!-- SwagRedis/Resources/services.xml -->

<service id="swag_redis.list_product_service"
         class="SwagRedis\Bundle\StoreFrontBundle\RedisProductService"
         decorates="shopware_storefront.list_product_service"
         public="false">

    <argument type="service" id="swag_redis.list_product_service.inner"/>
</service>
```

This implementation is very similar to the one presented before. However, if you look closely, you will notice that the default __shopware_storefront.list_product_service__ implementation is not discarded, but passed to the __RedisProductService__ constructor as an argument via the `<argument>` tag. It is important to append the `.inner` to the id here - this way the original service is addressed instead of our own implementation of it.

```php
<?php
// SwagRedis/Bundle/StoreFrontBundle/RedisProductService.php

namespace SwagRedis\Bundle\StoreFrontBundle;

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
    private $originalService

    function __construct(ListProductServiceInterface $service)
    {
        // Actual Redis connection object
        $this->connection = new RedisConnection();

        $this->originalService = $service;
    }

    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        // first try to get data over Redis
        $redisProducts = $this->connection->get(...);

        // if some data is missing, fallback to the database connection
        $coreProducts = $this->originalService->getList(...);

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

As you can see, this new version of the __RedisProductService__ is able to use the previous service to load data from the __MySql__ database and store it in __Redis__. In subsequent requests, as the data is already available in __Redis__, __MySql__ is not queried, resulting in improved performance. Thus, __Redis__ is used as a product cache (again, for academic reasons, product updates were intentionally ignored).

## Multiple cascading decorators

As we've seen before, decorator services can reuse the core services and reuse or extend their functionality. However, if you look closely, you will notice that our __RedisProductService__ decorator class doesn't actually depend on the __DBAL__ implementation of the __shopware_storefront.list_product_service__, but rather on a generic __ListProductServiceInterface__ implementation. Enter a __SwagElasticSearch__ plugin:

```php
<?php
// SwagElasticSearch/SwagElasticSearch.php

namespace SwagElasticSearch;

use Shopware\Components\Plugin;

class SwagElasticSearch extends Plugin {}
```

```xml
<!-- SwagElasticSearch/Resources/services.xml -->

<service id="swag_elastic_search.list_product_service"
         class="SwagElasticSearch\Bundle\StoreFrontBundle\ElasticSearchProductService"
         decorates="shopware_storefront.list_product_service"
         public="false">

    <argument type="service" id="swag_elastic_search.list_product_service.inner"/>
</service>
```

This __SwagElasticSearch__ example is very similar to the __Redis__ integration plugin we discussed before. The underlying implementation will also look familiar:

```php
<?php
// SwagElasticSearch/Bundle/StoreFrontBundle/ElasticSearchProductService.php

namespace SwagElasticSearch\Bundle\StoreFrontBundle;

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
    private $originalService

    function __construct(ListProductServiceInterface $service)
    {
        // Actual elastic search connection object
        $this->connection = new ElasticSearchConnection();

        $this->originalService = $service;
    }

    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        // first try to get data from Elastic Search
        $elasticSearchProducts = $this->connection->get(...);

        // if some data is missing, fallback to the database connection
        $coreProducts = $this->originalService->getList(...);

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

## Other examples

The above examples show how you can use service decoration to use a data source other than __MySql__ in Shopware. And, as we also showed before, you can even replace the __MySql__ access altogether with a completely different data source, provided that your service implements the expected interface and behaviour.

However, you can use service decoration for tasks other than this.

```php
<?php
// SwagLiveShopping/SwagLiveShopping.php

namespace SwagLiveShopping;

use Shopware\Components\Plugin;

class SwagLiveShopping extends Plugin {}
```

```xml
<!-- SwagLiveShopping/Resources/services.xml -->

<service id="swag_live_shopping.list_product_service"
         class="SwagLiveShopping\Bundle\StoreFrontBundle\LiveShoppingProductService"
         decorates="shopware_storefront.list_product_service"
         public="false">

    <argument type="service" id="swag_live_shopping.list_product_service.inner"/>
</service>
```

The above __SwagLiveShopping__ plugin (not related in any way with Shopware's LiveShopping premium plugin) uses a pattern already familiar to us, in order to decorate the __shopware_storefront.list_product_service__ service.

```php
<?php

namespace SwagLiveShopping\Bundle\StoreFrontBundle;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

class LiveShoppingProductService implements ListProductServiceInterface
{
    /**
     * @var ListProductServiceInterface
     */
    private $originalService;

    /**
     * @param ListProductServiceInterface $service
     */
    function __construct(ListProductServiceInterface $service)
    {
        $this->originalService = $service;
    }

    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        $products = $this->originalService->getList($numbers, $context);

        foreach ($products as $product) {
            $product->addAttribute('live_shopping', new Struct\Attribute(['live_shopping_id' => 1]));
        }

        return $products;
    }

    public function get($number, Struct\ProductContextInterface $context)
    {
        $product = $this->originalService->get($number, $context);

        $product->addAttribute('live_shopping', new Struct\Attribute(['live_shopping_id' => 1]));

        return $product;
    }
}
```

The actual implementation uses the previously existing data source and, for each product retrieved from it, adds a custom attribute. Using this same approach, you can manipulate any resulting __Struct\ListProduct__ using whichever logic or criteria you wish to implement.

Again, notice that using the decorator pattern allows us to not worry about the underlying implementation that we are decorating, or any other decoration that might be applied on top of ours. The __LiveShoppingProductService__ can decorate or be decorated by __ElasticSearchProductService__ or __RedisProductService__, individually or simultaneously, and the end result would still be the same.

This rule is, of course, not always applicable, as undesired interactions or conflicts may occur, depending on the customizations performed by each decorator, or the order in which they are executed. However, this example does demonstrate that it is very easy to provide non-conflicting implementations of different decorators, which can work together to implement multiple features on your Shopware shop.

## Conclusions

This example is very academic, and not adequate for production environments, but it does illustrate a very important idea: decorating over replacing allows multiple plugins to extend the same service without causing conflicts or breaks in behaviour. It's even possible that your plugin never actually decorates the core implementation of a service, but an instance provided by another plugin you have installed in your current installation. If implemented correctly, this can be made fully transparent to you and your plugins, and multiple decoration layers can be added on the same service without undesired side effects.

This doesn't mean you always need to decorate existing services. In some scenarios, full replacement might be a better solution than decorating. Just keep in mind that other plugins might want to decorate your service as if it were the core implementation, to ensure both your and other plugins work as expected.

You can find a small <a href="{{ site.url }}/exampleplugins/SwagPluginSystem.zip">example plugin here</a>.
