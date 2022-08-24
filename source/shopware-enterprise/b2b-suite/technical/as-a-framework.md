---
layout: default
title: Use the B2B-Suite as a framework
github_link: shopware-enterprise/b2b-suite/technical/as-a-framework.md
indexed: true
menu_title: Standalone Framework
menu_order: 18
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

Although the B2B-Suite comes with an example implementation in form of the SwagB2bPlugin it is also intended to be used as a framework. Referring to the [System Architecture](/shopware-enterprise/b2b-suite/technical/architecture/) guide you can use the different layers of the components separately.

## Installation

In order to access the B2B-Framework you need to make the components reachable through the autoloader. The easiest way to accomplish this is to just **install the plugin without activating it**. The installation process creates all necessary database tables and includes the framework through the autoloader. You can even update the framework through this process which will guarantee that all changes to the database will automatically be migrated.

## Requiring a Component

The B2B-Suite comes with a custom `B2BContainerBuilder` class that is used to resolve each components dependencies in the framework. For example the [Contact-Component](/shopware-enterprise/b2b-suite/technical/architecture/#the-whole-picture) depends on StoreFrontAuthentication and ACL, which will be automatically loaded by `B2BContainerBuilder`. So the first step must be always to instantiate this class.

```php
use Shopware\B2B\Common\B2BContainerBuilder;

$b2bContainerBuilder = B2BContainerBuilder::create();
```

Now you need to identify which component(s) you want to use. Each component layer has a directory named `DependencyInjection` that at least contains a `Configuration` suffixed PHP class. For our example we want to use the contact framework the configuration then must be located in `components/Contact/Framework/DependencyInjection/` and called `ContactFrameworkConfiguration`. All these classes can be instantiated without arguments so you can always write:

```php
use Shopware\B2B\Common\B2BContainerBuilder;
use Shopware\B2B\Contact\DependencyInjection\ContactFrameworkConfiguration;

$b2bContainerBuilder = B2BContainerBuilder::create();
$contactConfiguration = new ContactFrameworkConfiguration();
```

and add it to the `B2BContainerBuilder`

```php
use Shopware\B2B\Common\B2BContainerBuilder;
use Shopware\B2B\Contact\DependencyInjection\ContactFrameworkConfiguration;

$b2bContainerBuilder = B2BContainerBuilder::create();
$contactConfiguration = new ContactFrameworkConfiguration();
$b2bContainerBuilder->addConfiguration($contactConfiguration);
```

Now the B2BContainerBuilder knows that it has to load the contact framework and all contact framework dependencies but we still have to make this available through the Shopware container. We do this by passing the [ContainerBuilder](https://developers.shopware.com/developers-guide/plugin-system/#extended-container-configuration) to it.

```php
use Shopware\B2B\Common\B2BContainerBuilder;
use Shopware\B2B\Contact\DependencyInjection\ContactFrameworkConfiguration;

$b2bContainerBuilder = B2BContainerBuilder::create();
$contactConfiguration = new ContactFrameworkConfiguration();
$b2bContainerBuilder->addConfiguration($contactConfiguration);
$b2bContainerBuilder->registerConfigurations($shopwareContainerBuilder);
```

The easiest way to accomplish this is by overwriting the `build()` method in your plugin class.


```php
<?php declare(strict_types=1);

namespace B2BExample;

use Shopware\B2B\Common\B2BContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Shopware\B2B\Contact\DependencyInjection\ContactFrameworkConfiguration;

class B2BExample extends Shopware\Components\Plugin
{
    /* @param ContainerBuilder $shopwareContainerBuilder */
    public function build(ContainerBuilder $shopwareContainerBuilder)
    {
        $b2bContainerBuilder = B2BContainerBuilder::create();
        $contactConfiguration = new ContactFrameworkConfiguration();
        $b2bContainerBuilder->addConfiguration($contactConfiguration);
        $b2bContainerBuilder->registerConfigurations($shopwareContainerBuilder);

        parent::build($shopwareContainerBuilder);
    }
}
```

## Using the Framework

From now on you can use the components services in your own custom B2B implementation by loading them through the Shopware container, either in your own services file (recommended) or directly.

```php
$crudService = Shopware()->Container()->get('b2b_contact.crud_service')
```

```xml
<argument type="service" id="b2b_contact.crud_service"/>

```

To exemplify a little bit more what can be done with the framework please refer to our collection of [Example Plugins](/b2b-suite/example-plugins/).
