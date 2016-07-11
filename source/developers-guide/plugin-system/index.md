---
layout: default
title: The 5.2 Plugin System
github_link: developers-guide/plugin-system/index.md
indexed: true
shopware_version: 5.2
---

<div class="alert alert-warning">
This document is work in progress and not finished yet.
Please feel free to open a pull request on github to extend parts of this document.
</div>

## Parallel mode
The new plugin system runs fully in parallel to the "legacy" plugin system.

## Directory Structure

The 5.2 Plugins are located in the `custom/plugins/` directory. There is no seperation in `frontend`, `core` or `backend` like in the "legacy" plugin system.

## Plugin Name

The plugin name should always be prefixed with your developer prefix so it's unique in the Shopware universe.
To submit plugins to the [shopware store](http://store.shopware.com/) you have to obtain your developer prefix in the [Shopware Account](https://account.shopware.com).

In the following examples the developer prefix "Swag" will be used (short for shopware AG).

## Minimal Plugin Example

The most minimal Plugin is just a directory and one bootstrap file.
The directory must be named after the plugin name. The bootstrap file is called `SwagSloganOfTheDay.php`:

### Directory structure

```
SwagSloganOfTheDay
└──SwagSloganOfTheDay.php
```

### Plugin Bootstrap file

The Bootstrap `SwagSloganOfTheDay.php` must be namespaced with your plugin name and extend `\Shopware\Components\Plugin`:

```
<?php
namespace SwagSloganOfTheDay;

class SwagSloganOfTheDay extends \Shopware\Components\Plugin
{
}
```

### Plugin configuration
Plugin meta data and configurations will be configured by using xml files which will be placed like in the example below.

```
SwagSloganOfTheDay
├──plugin.xml
├── Resources
│   ├── config.xml
│   └── menu.xml
└──SwagSloganOfTheDay.php
```

You can find the schema of the xml files in `engine/Shopware/Components/Plugin/schema`.
 - **config.xml:** Defines the plugin configuration form which you can access by the `Basic Settings` or in the detail window of a plugin.
 - **menu.xml:** Defines new menu items in the backend menu structure of Shopware.
 - **plugin.xml:** Defines the meta data of your plugin, i.e. label, version, compatibility or the changelog. 
 
<div class="alert alert-warning">
At the moment it is necessary that the order of the xml elements is equal to the schema file, otherwise you will receive an exception. <br/>
You can use the CLI to install the plugin with extended error messages: <code>php ./bin/console sw:plugin:install SwagSloganOfTheDay -v</code>
</div>
 
`SwagSloganOfTheDay/plugin.xml`
```
<?xml version="1.0" encoding="utf-8"?>
<plugin xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="../../../engine/Shopware/Components/Plugin/schema/plugin.xsd">

    <label lang="de">Slogan des Tages</label>
    <label lang="en">Slogan of the day</label>

    <version>1.0.0</version>
    <link>http://example.org</link>
    <author>shopware AG</author>
    <compatibility minVersion="5.2.0" />

    <changelog version="1.0.0">
        <changes lang="de">Veröffentlichung</changes>
        <changes lang="en">Release</changes>
    </changelog>
</plugin>
```

`SwagSloganOfTheDay/Resources/config.xml`
```
<?xml version="1.0" encoding="utf-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="../../../../engine/Shopware/Components/Plugin/schema/config.xsd">

    <elements>
        <element required="true" type="text">
            <name>slogan</name>
            <label lang="de">Dein Slogan</label>
            <label lang="en">Your slogan</label>
            <value>XML is fun!</value>
        </element>
    </elements>
</config>
```

### Install and acivate

Now the plugin can be installed using the Shopware [CLI Commands](/developers-guide/shopware-5-cli-commands/) or the Plugin Manager in the backend.

```
$ php ./bin/console sw:plugin:refresh
Successfully refreshed
```

```
$ php ./bin/console sw:plugin:install --activate SwagSloganOfTheDay
Plugin SwagSloganOfTheDay has been installed successfully.
Plugin SwagSloganOfTheDay has been activated successfully.
```

At this point the plugin has no functionality at all.


## Pluginbootstrap as Event Subscriber

The Pluginbootstrap implements `\Enlight\Event\SubscriberInterface` so it can be used as a [Event Subscriber](/developers-guide/event-guide/#subscribers).

```
<?php
namespace SwagSloganOfTheDay;

class SwagSloganOfTheDay extends \Shopware\Components\Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Front_RouteStartup' => 'onRouteStartup'
        ];
    }

    public function onRouteStartup(\Enlight_Controller_EventArgs $args)
    {
        die("A rolling stone gathers no moss");
    }
}
```

### Access to the DI-Container

Inside the plugin bootstrap the DI-Container can be accessed with the `$this->container` property:

```php
    public function onRouteStartup(\Enlight_Controller_EventArgs $args)
    {
        $conn = $this->container->get('dbal_connection');
        $conn->.... // do some query
    }
```

## Autoloading

The plugin namespace is registered as a [PSR-4](http://www.php-fig.org/psr/psr-4/) Autoloading prefix.
For example the class `\SwagSloganOfTheDay\Log\Writer` will be loaded from file `SwagSloganOfTheDay/Log/Writer.php`.

## Plugin Install / Update

During plugin installation / deinstallation / update / activate / deactivate a method on the plugin bootstrap is called that can optionally be overwritten.

```
<?php
namespace SwagSloganOfTheDay;

use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class SwagSloganOfTheDay extends \Shopware\Components\Plugin
{
    public function install(InstallContext $context)
    {
    }

    public function update(UpdateContext $context)
    {
    }

    public function activate(ActivateContext $context)
    {
    }

    public function deactivate(DeactivateContext $context)
    {
    }

    public function uninstall(UninstallContext $context)
    {
    }
}
```

## Container Configuration

The [Symfony DependencyInjection Component](http://symfony.com/doc/current/components/dependency_injection/introduction.html)

The container configuration is the main extension point for shopware plugins.
In this configuration new services can be defined, core services can be decorated or replaced or functionality can be added to the system.

```
SwagSloganOfTheDay
├──plugin.xml
├── Resources
│   ├── config.xml
│   ├── menu.xml
│   └── services.xml
├──SloganPrinter.php
└──SwagSloganOfTheDay.php
```

```
<?php
namespace SwagSloganOfTheDay;

class SwagSloganOfTheDay extends \Shopware\Components\Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Front_RouteStartup' => 'onRouteStartup'
        ];
    }

    public function onRouteStartup(\Enlight_Controller_EventArgs $args)
    {
        $sloganPrinter = $this->container->get('swag_slogan_of_the_day.slogan_printer');
        $sloganPrinter->print();
    }
}
```

`SwagSloganOfTheDay/Resources/services.xml`

```
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="swag_slogan_of_the_day.slogan_printer" class="SwagSloganOfTheDay\SloganPrinter">
            <argument type="service" id="dbal_connection" />
        </service>
    </services>
</container>
```


## Extended Container Configuration

By overwriting the `build()`-method the `ContainerBuilder` can extended:

```
<?php
namespace SwagSloganOfTheDay;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class SwagSloganOfTheDay extends \Shopware\Components\Plugin
{
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('swag_slogan_of_the_day.plugin_dir', $this->getPath());
        $container->addCompilerPass(new SloganCompilerPass());

        parent::build($container);
    }
}
```

## Access to other plugins

Other plugins can be accessed via the `getPlugins()` method of the kernel. 

```php
$swagExample = Shopware()->Container()->get('kernel')->getPlugins()['SwagExample'];
$path = $swagExample->getPath();
```

## Example Plugins

- https://github.com/shyim/shopware-profiler
- https://github.com/bcremer/SwagModelTest

