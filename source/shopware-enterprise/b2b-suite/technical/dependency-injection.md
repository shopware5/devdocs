---
layout: default
title: Dependency injection
github_link: shopware-enterprise/b2b-suite/technical/dependency-injection.md
indexed: true
menu_title: Dependency injection
menu_order: 3
menu_style: numeric
menu_chapter: true
group: Shopware Enterprise
subgroup: B2B-Suite
subsubgroup: Technical Documentation
---

<div class="toc-list"></div>

## Shopware DIC

The B2B-Suite registers with the DIC from [Shopware](https://developers.shopware.com/developers-guide/shopware-5-core-service-extensions/). Be sure you are familiar with the basic usage patterns and practices. Especially service decoration is an equally important extension point.

## Dependency Injection Extension B2B

The B2B-Suite provides an abstract `DependencyInjectionConfiguration` class, that is used throughout the Suite as an initializer of DI-Contents across all components.

```php
<?php

namespace Shopware\B2B\Common;

abstract class DependencyInjectionConfiguration
{
    /**
     * @return string[] array of service xml files
     */
    abstract public function getServiceFiles(): array;

    /**
     * @return Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface[]
     */
    abstract public function getCompilerPasses(): array;

    /**
     * @return DependencyInjectionConfiguration[] child components required by this component
     */
    abstract public function getDependingConfigurations(): array;
}
```

Every macro layer of every component defines its own dependencies. That way you can just require the up most components you want to use and every other dependency is injected automatically.

For example this code will enable the contact component your own plugin.

```php
<?php

namespace MyB2bPlugin;

use Shopware\B2B\Common\B2BContainerBuilder;
use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MyB2bPlugin extends Plugin
{
    [...]

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $containerBuilder = B2BContainerBuilder::create();
        $containerBuilder->addConfiguration(new Shopware\B2B\Contact\Framework\DependencyInjection\ContactFrameworkConfiguration());
        $containerBuilder->registerConfigurations($container);
    }
}
```

## Tags

Additionally the B2B-Suite makes heavy use of service tags as a more modern replacement for collect events. They are used to help you extend central B2B services with custom logic. Please take a look at the example plugins and there usage of that extension mechanism. Be sure you know [the basics](http://symfony.com/doc/current/service_container/tags.html).
