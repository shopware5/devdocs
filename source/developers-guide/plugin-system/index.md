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

<div class="toc-list"></div>

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

### Install and activate

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
├── Resources
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

## Register plugin controller with template
```php
<?php
namespace SwagControllerExample;

use Shopware\Components\Plugin;

class SwagControllerExample extends Plugin
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_MyController' => 'registerController',
        ];
    }

    public function registerController(\Enlight_Event_EventArgs $args)
    {
        $this->container->get('Template')->addTemplateDir(
            $this->getPath() . '/Resources/views/'
        );

        return $this->getPath() . "/Controllers/Frontend/MyController.php";
    }
}
```

## Add console commands

There are two ways to add Shopware [CLI Commands](/developers-guide/shopware-5-cli-commands/).

### Implement registerCommands

You can implement the method `registerCommands()` and add commands to the Console application:

```
<?php
namespace SwagCommandExample;

use Shopware\Components\Plugin;
use Shopware\Components\Console\Application;
use SwagCommandExample\Commands\FirstCommand;
use SwagCommandExample\Commands\SecondCommand;

class SwagCommandExample extends Plugin
{
    public function registerCommands(Application $application)
    {
        $application->add(new FirstCommand());
        $application->add(new SecondCommand());
    }
}
```

### Commands as Services

As of Shopware 5.2.2 you can also register commands as a service and tag it with `console.command`:

```xml
<!-- Resources/services.xml -->
<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service
            id="swag_command_example.commands.first_command"
            class="SwagCommandExample\Commands\FirstCommand">
            <tag name="console.command"/>
        </service>
    </services>
</container>
```

You can read more in the Symfony Documentation: [How to Define Commands as Services](https://symfony.com/doc/2.8/cookbook/console/commands_as_services.html).

## Add menu items to backend

```xml
<!-- Resources/menu.xml -->
<?xml version="1.0" encoding="utf-8"?>
<menu xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware/shopware/5.2/engine/Shopware/Components/Plugin/schema/menu.xsd">
	<entries>
		<entry>
			<name>SloganOfTheDay</name>
			<label lang="en">Slogan of the Days</label>
			<label lang="de">Spruch des Tages</label>
			<controller>SwagSloganOfTheDay</controller>
			<action>index</action>
			<class>sprite-metronome</class>
			<parent identifiedBy="controller">Marketing</parent>
		</entry>
	</entries>
</menu>
```

For available parent controllers take a look into table s_core_menu (column controller). For example you can use one of the following:
- Article
- Content
- Customer
- ConfigurationMenu
- Marketing

Menuitem won't be displayed if controller and action are missing.

How to know which class for which icon take a look at:
<a href="https://github.com/mankerst/shopware-backend-icons">github.com/mankerst/shopware-backend-icons </a>

## Access to other plugins

Other plugins can be accessed via the `getPlugins()` method of the kernel.

```php
$swagExample = Shopware()->Container()->get('kernel')->getPlugins()['SwagExample'];
$path = $swagExample->getPath();
```

## Resources

You can add xml files which are imported by Shopware via `SwagSloganOfTheDay/Resources/*` in plugin installation workflow. IDE`s like PhpStorm support auto completion by default for these files if schema file location is valid.

```
Resources
└──config.xml
└──menu.xml
```

### Plugin configuration

Backend plugin configuration can be extended by `config.xml` file. This replaces the usage of `$this->Form()` on old `Shopware_Components_Plugin_Bootstrap`

```xml
<!-- Resources/config.xml -->
<?xml version="1.0" encoding="utf-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="../../../../engine/Shopware/Components/Plugin/schema/config.xsd">

    <elements>
        <element>
            <label>My Config Label</label>
            <name>my_config_value</name>
        </element>
    </elements>
</config>
```

Configuration is accessible by following code snippet:

```
Shopware()->Config()->getByNamespace('SwagSloganOfTheDay', 'my_config_value'),
```

## Example Plugins

- <a href="https://github.com/shyim/shopware-profiler">github.com/shyim/shopware-profiler</a>
- <a href="https://github.com/bcremer/SwagModelTest">github.com/bcremer/SwagModelTest</a>

