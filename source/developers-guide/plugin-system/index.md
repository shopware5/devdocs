---
layout: default
title: The 5.2 Plugin System
github_link: developers-guide/plugin-system/index.md
indexed: true
shopware_version: 5.2
group: Developer Guides
subgroup: Developing plugins
menu_title: The 5.2 Plugin System
menu_order: 120
---

<div class="alert alert-warning">
This document is work in progress and not finished yet.
Please feel free to open a pull request on github to extend parts of this document.
</div>

<div class="toc-list"></div>

## Parallel mode
The new plugin system runs fully in parallel to the "legacy" plugin system.

## Directory Structure

The 5.2 Plugins are located in the `custom/plugins/` directory. There is no separation in `frontend`, `core` or `backend` like in the "legacy" plugin system.

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

```php
<?php
namespace SwagSloganOfTheDay;

class SwagSloganOfTheDay extends \Shopware\Components\Plugin
{
}
```


### Install and activate

Now the plugin can be installed using the Shopware [CLI Commands](/developers-guide/shopware-5-cli-commands/) or the Plugin Manager in the backend.

```bash
$ php ./bin/console sw:plugin:refresh
Successfully refreshed
```

```bash
$ php ./bin/console sw:plugin:install --activate SwagSloganOfTheDay
Plugin SwagSloganOfTheDay has been installed successfully.
Plugin SwagSloganOfTheDay has been activated successfully.
```

At this point the plugin has no functionality at all.


## Plugin bootstrap as Event Subscriber

The plugin bootstrap implements `\Enlight\Event\SubscriberInterface` so it can be used as an [Event Subscriber](/developers-guide/event-guide/#subscribers).

```php
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
        die('A rolling stone gathers no moss');
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

```php
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

The container configuration is the main extension point for Shopware plugins.
In this configuration new services can be defined, core services can be decorated or replaced or functionality can be added to the system.

```
SwagSloganOfTheDay
├── Resources
│   └── services.xml
├──SloganPrinter.php
└──SwagSloganOfTheDay.php
```

```php
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

```xml
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

### Decorate a service
The following example shows you how to decorate a service which implements an interface and gets defined in the Shopware dependency injection container.
```php
<?php

namespace SwagExample\Bundle\StoreFrontBundle;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ProductContextInterface;

class ListProductService implements ListProductServiceInterface
{
    private $service;

    public function __construct(ListProductServiceInterface $service)
    {
        $this->service = $service;
    }

    public function getList(array $numbers, ProductContextInterface $context)
    {
        $products = $this->service->getList($numbers, $context);
        //...
        return $products;
    }

    public function get($number, ProductContextInterface $context)
    {
        return array_shift($this->getList([$number], $context));
    }
}
```
The original `\Shopware\Bundle\StoreFrontBundle\Service\Core\ListProductService` defined with the service id `shopware_storefront.list_product_service`. The following service definition decorates this service using the service above:

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

For more information see http://symfony.com/doc/current/service_container/service_decoration.html

### Extended Container Configuration

By overwriting the `build()`-method the `ContainerBuilder` can be extended:

```php
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

### Event subscriber
The new plugin system has the ability to add event subscriber by adding subscribers in the `services.xml`.

```
SwagSloganOfTheDay
├── Resources
│   └── services.xml
├──SloganPrinter.php
├──RouteSubscriber.php
└──SwagSloganOfTheDay.php
```

The `onRouteStartup` subscriber above now will be encapsulated in a subscriber class. 

`SwagSloganOfTheDay/RouteSubscriber.php`

```php
<?php
namespace SwagSloganOfTheDay\Subscriber;

use Enlight\Event\SubscriberInterface;

class RouteSubscriber implements SubscriberInterface
{
    private $sloganPrinter;

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Front_RouteStartup' => 'onRouteStartup'
        ];
    }
    
    public function __construct(SloganPrinter $sloganPrinter)
    {
        $this->sloganPrinter = $sloganPrinter;
    }

    public function onRouteStartup(\Enlight_Controller_EventArgs $args)
    {
        $this->sloganPrinter->print();
    }
}
```

After adding the `RouteSubscriber.php`, the subscriber can be added to the `services.xml` as a tagged service ([Symfony - Working with Tagged Services](http://symfony.com/doc/current/components/dependency_injection/tags.html)).
This allows Shopware to load all event subscriber automatically so you don't need to register the subscriber manually.

`SwagSloganOfTheDay/Resources/services.xml`
```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="swag_slogan_of_the_day.subscriber.route" class="SwagSloganOfTheDay\Subscriber\Route">
            <argument type="service" id="swag_slogan_of_the_day.slogan_printer" />
            <tag name="shopware.event_subscriber" />
        </service>

        <service id="swag_slogan_of_the_day.slogan_printer" class="SwagSloganOfTheDay\SloganPrinter">
            <argument type="service" id="dbal_connection" />
        </service>
    </services>
</container>
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
        $this->container->get('template')->addTemplateDir(
            $this->getPath() . '/Resources/views/'
        );

        return $this->getPath() . '/Controllers/Frontend/MyController.php';
    }
}
```

### Controller auto-registration

The auto-registration is available in Shopware 5.2.7 or above.
To make use of it, create a file like `SwagControllerExample/Controllers/(Backend|Frontend|Widgets|Api)/MyController.php` 
and follow our controller naming conventions. After that, you'll be able to call `MyController`.
The registration of the template would be done, i.e. in the `preDispatch()`-Method of your controller.

```php
class Shopware_Controllers_Frontend_MyController extends \Enlight_Controller_Action
{
    public function preDispatch()
    {
        /** @var \Shopware\Components\Plugin $plugin */
        $plugin = $this->get('kernel')->getPlugins()['SwagControllerExample'];
        
        $this->get('template')->addTemplateDir($plugin->getPath() . '/Resources/views/');
        $this->get('snippets')->addConfigDir($plugin->getPath() . '/Resources/snippets/');
    }
}
```

## Add console commands

There are two ways to add Shopware [CLI Commands](/developers-guide/shopware-5-cli-commands/).

### Implement registerCommands

You can implement the method `registerCommands()` and add commands to the Console application:

```php
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

As of Shopware 5.2.2 you can also register commands as a service and tag it with `console.command` in the `Resources/services.xml`:

```xml
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


## Add backend emotion components
Since shopware 5.2.10 the `Shopware\Components\Emotion\ComponentInstaller` service can be used to generate backend emotion components inside plugin installations:
```php
public function install(InstallContext $context)
{
    $installer = $this->container->get('shopware.emotion_component_installer');

    $vimeoElement = $installer->createOrUpdate(
        $this->getName(),
        'Vimeo Video',
        [
            'name' => 'Vimeo Video',
            'xtype' => 'emotion-components-vimeo',
            'template' => 'emotion_vimeo',
            'cls' => 'emotion-vimeo-element',
            'description' => 'A simple vimeo video element for the shopping worlds.'
        ]
    );

    $vimeoElement->createTextField(
        [
            'name' => 'vimeo_video_id',
            'fieldLabel' => 'Video ID',
            'supportText' => 'Enter the ID of the video you want to embed.',
            'allowBlank' => false
        ]
    );
}
```

Registering the `Shopware\Components\Emotion\EmotionComponentViewSubscriber` as event subscriber allows to add the required template directory automatically:
```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="swag_emotion.emotion_view_subscriber" class="Shopware\Components\Emotion\EmotionComponentViewSubscriber">
            <argument>%swag_emotion.plugin_dir%</argument>
            <tag name="shopware.event_subscriber" />
        </service>
    </services>
</container>
```

By convention, the following template structure is required:
```
SwagEmotion
├── Resources
│   ├── views
│   │   └── emotion_components
│   │       ├── backend
│   │       │  └── vimeo_video.js
│   │       └── widgets
│   │           └── emotion
│   │               └── components
│   │                   └── emotion_vimeo.tpl   
│   └── services.xml
└──SwagEmotion.php
```
## Add a new payment method
Since Shopware 5.2.13 the `Shopware\Components\Plugin\PaymentInstaller` service can be used to add payment methods to the database inside plugin installations.

```php
public function install(InstallContext $context)
{
    /** @var \Shopware\Components\Plugin\PaymentInstaller $installer */
    $installer = $this->container->get('shopware.plugin_payment_installer');

    $options = [
        'name' => 'example_payment_invoice',
        'description' => 'Example payment method invoice',
        'action' => 'PaymentExample',
        'active' => 0,
        'position' => 0,
        'additionalDescription' =>
            '<img src="http://your-image-url"/>'
            . '<div id="payment_desc">'
            . '  Pay save and secured by invoice with our example payment provider.'
            . '</div>'
    ];
    $payment = $installer->createOrUpdate($this->getName(), $options);
}
```
## Plugin Resources

Plugin meta data and configurations will be configured by using xml files which will be placed like in the example below.
IDEs like PhpStorm support auto completion by default for these files if schema file location is valid.

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

### Plugin Metadata 
 
Entires can be separated with semicolons when documenting multiple changes in the changelog.

Example `plugin.xml`:
 
```xml
<?xml version="1.0" encoding="utf-8"?>
<plugin xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware/shopware/5.2/engine/Shopware/Components/Plugin/schema/plugin.xsd">
    <label lang="de">Slogan des Tages</label>
    <label lang="en">Slogan of the day</label>

    <version>1.0.0</version>
    <link>http://example.org</link>
    <author>shopware AG</author>
    <compatibility minVersion="5.2.0" />

    <changelog version="1.0.0">
        <changes lang="de">Farbe geändert; Schriftgröße geändert;</changes>
        <changes lang="en">changed color; changed font-size;</changes>
    </changelog>
</plugin>
```

### Plugin Configuration / Forms


Backend plugin configuration can be extended by `Resources/config.xml` file. This replaces the usage of `$this->Form()` on old `Shopware_Components_Plugin_Bootstrap`.


```xml
<?xml version="1.0" encoding="utf-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware/shopware/5.2/engine/Shopware/Components/Plugin/schema/config.xsd">
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

To read out the configuration of your plugin use this code snippet in your base plugin class:

```php
$config = $this->container->get('shopware.plugin.config_reader')->getByPluginName($this->getName());
```
Use it like this in other places:
```php
$config = Shopware()->Container()->get('shopware.plugin.config_reader')->getByPluginName('SwagSloganOfTheDay');
```
The config reader service will return an array with the config element names as keys.

#### add store values
As of Shopware 5.2.11 it is possible to define custom config stores directly inside your `config.xml`.

A custom config store is defined like this:
```xml
<?xml version="1.0" encoding="utf-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware/shopware/5.2/engine/Shopware/Components/Plugin/schema/config.xsd">
    <elements>
        <element type="select">
            <name>selectArray</name>
            <label>XML Store</label>
            <store>
                <option>
                    <value>1</value>
                    <label lang="de">DE 1</label>
                    <label lang="en">EN 1</label>
                </option>
                <option>
                    <value>TWO</value>
                    <label lang="de">DE 2</label>
                    <label lang="en">EN 2</label>
                </option>
                <option>
                    <value>3</value>
                    <label>Test</label>
                </option>
                <option>
                    <value>4</value>
                    <label>Test default</label>
                    <label lang="de">Test</label>
                </option>
            </store>
        </element>
        <element type="select">
            <name>selectExtjsStore</name>
            <label>Extjs Store</label>
            <store>Shopware.apps.Base.store.Category</store>
        </element>
    </elements>
</config>
```
There are two unique constraints:
* Inside a store, a value tag's value must only occur once
* Inside an option tag, a label tag's lang attribute value must only occur once

Additionally, the order is fixed. The value tag must be defined before the label tag(s).

There must be at least one option tag and inside each option tag there must be at least one value and one label tag. 

#### add buttons
Since Shopware 5.2.17 it is possible to place buttons on your configuration form.

Example:
```xml
<?xml version="1.0" encoding="utf-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="../../../../engine/Shopware/Components/Plugin/schema/config.xsd">
    <elements>
        <element type="button">
            <name>buttonTest</name>
            <label lang="de">Test Button</label>
            <label lang="en">Test Button</label>
            <options>
                <handler>
                    <![CDATA[
                    function(button) {
                        alert('Button');
                    }
                    ]]>
                </handler>
            </options>
        </element>
    </elements>
</config>
```
The given `label` is the display name of the button.
You can define an option `handler` as callback for click events.

### Backend Menu Items

Example `Resources/menu.xml`:

```xml
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
            <children>
                <entry>
                    <name>SloganOfTheDayChild</name>
                    <label lang="en">Child menu</label>
                    <label lang="de">Kindmenü</label>
                    <controller>SwagSloganOfTheDay</controller>
                    <action>detail</action>
                    <class>sprite-application-block</class>
                </entry>
            </children>
        </entry>
    </entries>
</menu>
```

For available parent controllers take a look into the table `s_core_menu` (column `controller`). For example you can use one of the following:
- Article
- Content
- Customer
- ConfigurationMenu
- Marketing

As you can see in the example above, you are also able to add child menu entries for your new menu item. Just add them under the `<children>` element

The menu item won't be displayed if controller and action are missing.

To know which class for which icon take a look at the <a href="/designers-guide/backend-icons/">Backend icon set overview</a>.

### Plugin Cronjob

Example `Resources/cronjob.xml`:

```xml
<?xml version="1.0" encoding="utf-8"?>
<cronjobs xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="../../../../engine/Shopware/Components/Plugin/schema/cronjob.xsd">

    <cronjob>
        <name>Send birthday email</name>
        <action>Shopware_CronJob_SendBirthdayMail</action>
        <active>true</active>
        <interval>86400</interval>
        <disableOnError>true</disableOnError>
    </cronjob>

</cronjobs>
```

## Access to other plugins

Other plugins can be accessed via the `getPlugins()` method of the kernel.

```php
$swagExample = Shopware()->Container()->get('kernel')->getPlugins()['SwagExample'];
$path = $swagExample->getPath();
```



## Update from legacy plugin system

Shopware recognizes whether the plugin is based on the legacy or 5.2 plugin system and moves it to the correct directory. Shopware does not support moving of extracted plugins based on the 5.2 plugin system, if they are placed in the legacy directory structure.
Further the zip archive structure changed. 

**Legacy zip structure:**
```
SwagSloganOfTheDay.zip
└──Frontend
   └──SwagSloganOfTheDay
      ├──Bootstrap.php
      └──...
```

**New 5.2 zip structure:**
```
SwagSloganOfTheDay.zip
└──SwagSloganOfTheDay
   ├──SwagSloganOfTheDay.php
   └──...
```

## Example Plugins

- <a href="https://github.com/shyim/shopware-profiler">github.com/shyim/shopware-profiler</a>
- <a href="https://github.com/bcremer/SwagModelTest">github.com/bcremer/SwagModelTest</a>
- <a href="https://github.com/shopwareLabs/SwagBackendOrder">github.com/shopwareLabs/SwagBackendOrder</a>
