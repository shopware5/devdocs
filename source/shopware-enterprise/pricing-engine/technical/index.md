---
layout: default
title: Extension Guide
github_link: pricing-engine/technical/index.md
indexed: true
tags: [pricing engine, extension]
menu_title: Extension Guide
menu_order: 2
group: Shopware Enterprise
subgroup: Pricing Engine
---

## General

The Pricing Engine is designed to be highly flexible and extendable. So based on this purpose you have only to do three steps do create your own condition: 

* extends the CustomerContext
* decorate the ContextFactory
* create your own Condition and register it in the dependency injection container

In the following example we will add a condition with is based on the firstname of the logged in customer.

## Extending
First we have to extend the CustomerContext:

```php
<?php declare(strict_types=1);

namespace SwagCustomCondition\Source;

use SwagEnterprisePricingEngine\Source\Common\CustomerContextInterface;

class CustomerContextExtended implements CustomerContextInterface
{
    /**
     * @var CustomerContextInterface
     */
    private $originalCustomerContext;

    public function __construct(CustomerContextInterface $originalCustomerContext)
    {
        $this->originalCustomerContext = $originalCustomerContext;
    }

    public function getCustomerId(): int
    {
        return $this->originalCustomerContext->getCustomerId();
    }
    // and so on to implement the original interface 
    
    // our own condition method to provide the firstname
    public function getCustomerFirstName(): string
    {
        $userData = Shopware()->Modules()->Admin()->sGetUserData();

        return $userData['billingaddress']['firstname'] ?? '';
    }    
}
```

After that we will decorate the ContextFactory to return our new `CustomerContextExtended`

```php
<?php declare(strict_types=1);

namespace SwagCustomCondition\Source;

use Doctrine\DBAL\Connection;
use Shopware\B2B\StoreFrontAuthentication\Framework\AuthenticationService;
use Shopware\B2B\StoreFrontAuthentication\Framework\NotAuthenticatedException;
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use SwagEnterprisePricingEngine\Bridge\PricingEngineContextFactory;
use SwagEnterprisePricingEngine\Source\Common\CustomerContextInterface;
use SwagEnterprisePricingEngine\Source\PriceListCondition\PriceListConditionFactory;

class PricingEngineContextFactoryDecorated extends PricingEngineContextFactory
{
    /**
     * @param ContextServiceInterface $contextService
     * @param PriceListConditionFactory $conditionFactory
     * @param Connection $connection
     */
    public function __construct(
        ContextServiceInterface $contextService,
        PriceListConditionFactory $conditionFactory,
        Connection $connection,
        AuthenticationService $authenticationService = null
    ) {
        parent::__construct($contextService, $conditionFactory, $connection, $authenticationService);
    }

    /**
     * @return CustomerContextInterface
     */
    public function getCustomerContext(): CustomerContextInterface
    {
        return new CustomerContextExtended(parent::getCustomerContext());
    }
}
```

Now we will create our own condition for the Pricing Engine. To activate the condition it needs the tag `swag_enterprise_pricing_engine.price_list_condition` in the service.xml!

```php
<?php declare(strict_types=1);

namespace SwagCustomCondition\Source;

use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use SwagEnterprisePricingEngine\Source\Common\CustomerContextInterface;
use SwagEnterprisePricingEngine\Source\PriceListCondition\Conditions\AbstractCondition;

class CustomerFirstNameCondition extends AbstractCondition
{
    const NAME = 'CustomerFirstNameCondition';

    /**
     * @var string
     */
    private $firstName;

    public function __construct(string $firstName = null)
    {
        $this->firstName = $firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function checkValidity(ShopContextInterface $shopContext, CustomerContextInterface $customerContext): bool
    {
        return $customerContext->getCustomerFirstName() === $this->firstName;
    }
}
```

At least we have to register our components in the dependency injection container:

```xml
<?xml version="1.0" ?>
<container xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xmlns="http://symfony.com/schema/dic/services"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    <services>
        <service class="SwagCustomCondition\Source\PricingEngineContextFactoryDecorated"
                 id="swag_custom_condition.source.pricing_engine_context_factory_decorated"
                 decorates="swag_enterprise_pricing_engine.bridge.pricing_engine_context_factory"
                 public="false">
            <argument id="shopware_storefront.context_service" type="service"/>
            <argument id="swag_enterprise_pricing_engine.source.price_list_condition.price_list_condition_factory"
                      type="service"/>
            <argument id="dbal_connection" type="service"/>
            <argument id="b2b_front_auth.authentication_service" type="service" on-invalid="null"/>
        </service>

        <service class="SwagCustomCondition\Source\CustomerFirstNameCondition"
                 id="swag_custom_condition.source.customer_first_name_condition">
            <tag name="swag_enterprise_pricing_engine.price_list_condition"/>
        </service>
    </services>
</container>
```

## Conclusion 
To add a own condition is quite simple. If you need the whole plugin, please have a look into our [example-plugins](https://git.shopware.com/enterprise/PricingEngine/tree/master/example-plugins/) directory.
