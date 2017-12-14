---
layout: default
title: The legacy Plugin System
github_link: developers-guide/plugin-system/index.md
indexed: true
shopware_version: 4.3.6
group: Developer Guides
subgroup: Developing plugins
menu_title: The legacy Plugin System
menu_order: 120
---

<div class="toc-list"></div>

## Directory Structure

The legacy Plugins are located in the `/.../engine/Shopware/Plugins/( Community | Default  | Local )/` directory. There is a separation in `Frontend`, `Core` or `Backend`.

```
engine
└──Shopware
    └──Plugins
        └──Community
        └──Default
        └──Local
            └── Backend
            └── Core
            └── Frontend
                └──SwagSloganOfTheDay
                    └──Bootstrap.php
```

## Plugin Name

The plugin name should always be prefixed with your developer prefix so it's unique in the Shopware universe.
To submit plugins to the [shopware store](http://store.shopware.com/) you have to obtain your developer prefix in the [Shopware Account](https://account.shopware.com).

In the following examples the developer prefix "Swag" will be used (short for shopware AG).

## Minimal Plugin Example

The most minimal Plugin is just a directory and one bootstrap file.
The directory must be named after the plugin name. The bootstrap file is called `Bootstrap.php`:

### Plugin Bootstrap file

The Bootstrap `Bootstrap.php` has no namespace and extend the class `Shopware_Components_Plugin_Bootstrap`:

```php
<?php
class Shopware_Plugins_Frontend_SwagSloganOfTheDay_Bootstrap extends Shopware_Components_Plugin_Bootstrap
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

## Register Event Subscriber

In the install method of the plugin you can register your event subscriber classes.

```php
<?php
class Shopware_Plugins_Frontend_SwagSloganOfTheDay_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent('Enlight_Controller_Front_StartDispatch', 'registerSubscriber');
        return true;
    }

    public function registerSubscriber()
    {
        $this->get('events')->addSubscriber(
            new SubscriberClass(
                $this->Path(),
                $this->get('dbal_connection')
            )
        );
    }
}
```

### A subscriber class
A subscriber class implements the `Shopware\Plugins\Subscribers\SubscriberInterface`

```php
<?php

namespace Shopware\Plugins\Subscribers;

class SearchBundleSubscriber implements SubscriberInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $pluginDir;

    /**
     * @param $pluginDir
     * @param Connection $connection
     */
    public function __construct($pluginDir, Connection $connection)
    {
        $this->pluginDir = $pluginDir;
        $this->connection = $connection;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'extendListingTemplate'
        ];
    }

    public function extendListingTemplate(\Enlight_Event_EventArgs $args)
    {
        $args->getSubject()->View()->addTemplateDir(
            $this->pluginDir . '/Views/'
        );
    }
}
```

### Access to the DI-Container

Inside the plugin bootstrap the DI-Container can be accessed via `$this->get()`

```php
    public function install(\Enlight_Controller_EventArgs $args)
    {
        $conn = $this->get('dbal_connection');
        $conn->.... // do some query
    }
```

## Plugin Install / Update

During plugin installation / deinstallation / update / activate / deactivate a method on the plugin bootstrap is called that can optionally be overwritten. You can do a lot of things with the provided context, e.g.:

- stop process by throwing an exception and notify user with a message
- notify user on success with a message
- flush specified caches
- within update(), addtionally: get currently installed version number of your plugin
- wihtin secureUninstall() keep user generated data, if he wishes so

Checkout the examples:

```php
<?php

class Shopware_Plugins_Frontend_SwagSloganOfTheDay_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function afterInit()
    {
        $this->get('Loader')->registerNamespace(
            'ShopwarePlugins\PluginName',
            $this->Path()
        );
    }
    
    public function install()
    {
        
    }
    
    public function uninstall()
    {
            
    }

    public function secureUninstall()
    {
        
    }

    public function update()
    {
        // Check if Shopware version matches
        if (!$this->assertMinimumVersion('5.2.0')) {
            throw new Exception('This plugin requires Shopware 5.2.0 or a later version');
        }
               
    }
    
    public function enable()
    {
        return ['success' => true, 'invalidateCache' => ['proxy', 'frontend', 'backend', 'theme']];
    }
    
    public function disable()
    {
        return ['success' => true, 'invalidateCache' => ['proxy', 'frontend', 'backend', 'theme']];
    }

}
```

### Decorate a service
The following example shows you how to decorate a service which implements an interface and gets defined in the Shopware dependency injection container.

```php
<?php

namespace SwagExample\Bundle\StoreFrontBundle;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ProductContextInterface;

class ListProductServiceDecorator implements ListProductServiceInterface
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

To use the after init resource event **Enlight_Bootstrap_AfterInitResource_...**  is quite important in this case.

```php
$this->subscribeEvent('Enlight_Bootstrap_AfterInitResource_shopware_list_product_service', 'decorateService');
 
 ...
 
public function decorateService()
{
    $originalService = $this->container->get('shopware_storefront.list_product_service');
    
    $this->container->set(
        'shopware_storefront.list_product_service',
        new ListProductServiceDecorator($originalService)
    );
}
```

## Register plugin controller with template in a subscriber
```php
<?php
namespace PluginName\Subscriber;

class ControllerSubscriber extends SubscriberInterface
{
    /**
     * @var Container
     */
    private $container;
    
    private $path;
    
    public function __construct(Container $container, $path) 
    {
        $this->container = $container;
        $this->path = $path;
    }
    
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
            $this->path . '/Views'
        );

        return $this->path . '/Controllers/Frontend/MyController.php';
    }
}
```
Controller:
```php
<?php

class Shopware_Controllers_Frontend_MyController extends Enlight_Controller_Action
{
    public function indexAction()
    {
    }
}
```

### Frontend resources registration

Additions to CSS, LESS and JavaScript resources had to be registered via `Theme_Compiler_Collect_Plugin_*` events.

```php
public function install()
{
    $this->subscribeEvent('Theme_Compiler_Collect_Plugin_Less', 'addLessFiles');
    $this->subscribeEvent('Theme_Compiler_Collect_Plugin_Javascript', 'addJsFiles');
}

/**
 * @return ArrayCollection
 */
public function addLessFiles()
{
    return new Doctrine\Common\Collections\ArrayCollection([
        new LessDefinition(
            [],
            [__DIR__ . '/Views/frontend/_public/src/less/all.less'],
            __DIR__
        ),
    ]);
}

/**
 * @return ArrayCollection
 */
public function addJsFiles()
{
    return new ArrayCollection([
        __DIR__ . '/Views/frontend/_public/src/js/jquery.swag_live_shopping.js',
    ]);
}
```

## Add console commands
For register a console command create a command file which extends the class "ShopwareCommand".
Then register the event `Shopware_Console_Add_Command` and return a new ArrayCollection with your created commands.

```php
$this->subscribeEvent(
    'Shopware_Console_Add_Command',
    'onAddConsoleCommand'
);

/**
 * Adds the console commands
 *
 * @return ArrayCollection
 */
public function onAddConsoleCommand()
{
    return new ArrayCollection(
        [
          new Command1(),
          new Command2(),
        ]
    );
}
```

```php
<?php

class Command1 extends ShopwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        
    }
    
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
    }
}
```

## Add backend emotion components
For creating custom shopping world elements Shopware provides some helper functions which can be used in the Bootstrap.php of a Shopware plugin. So all you have to do is to create a simple plugin where you can register one ore more elements via the createEmotionComponent() method. As an example for this tutorial we will create a Vimeo element for adding videos to the shopping world.
 
```php
$vimeoElement = $this->createEmotionComponent([
    'name' => 'Vimeo Video',
    'xtype' => 'emotion-components-vimeo',
    'template' => 'emotion_vimeo',
    'cls' => 'emotion-vimeo-element',
    'description' => 'A simple vimeo video element for the shopping worlds.'
]);
```
In the install() method of our plugin we register a new element and save it in the variable $vimeoElement for later use. The createEmotionComponent() method expects a configuration array.

### Adding configuration fields to the element
After registering the new element we can add different form fields to the element which can be filled by the user to configure the element. For each type of field there is a helper function which can be called on the newly registered component. We will add some configuration fields to our example element for the different embed options the Vimeo platform offers.

```php
$vimeoElement->createTextField([
    'name' => 'vimeo_video_id',
    'fieldLabel' => 'Video ID',
    'supportText' => 'Enter the ID of the video you want to embed.',
    'allowBlank' => false
]);

$vimeoElement->createHiddenField([
    'name' => 'vimeo_video_thumbnail'
]);

$vimeoElement->createTextField([
    'name' => 'vimeo_interface_color',
    'fieldLabel' => 'Interface Color',
    'supportText' => 'Enter the #hex color code for the video player interface.',
    'defaultValue' => '#0096FF'
]);

$vimeoElement->createCheckboxField([
    'name' => 'vimeo_autoplay',
    'fieldLabel' => 'Autoplay',
    'defaultValue' => false
]);

...

```
### Creating a frontend template for the element
After registering the element and creating all the configuration fields we already see a full functional shopping world element in the backend module which can be placed on the design canvas. All we have to do now is to provide a frontend template to define the layout in the store. In the `Views` directory of our plugin we create the necessary directory structure to the file. Template files for shopping world elements can automatically be added by creating the hierarchy structure in the special directory called `emotion_components`. The full path to the template file would be `Views/emotion_components/widgets/emotion/components/{name}.tpl`.

The name of the file has to match the definition in your createEmotionComponent() method. You can access your configuration fields inside the template file as properties of the $Data smarty variable. Let's create the embed code for displaying the Vimeo video.

```smarty
{block name="widgets_emotion_components_vimeo_element"}

    {$videoURL = "https://player.vimeo.com/video/{$Data.vimeo_video_id}?color={$Data.vimeo_interface_color|substr:1}"}

    {if !$Data.vimeo_show_title}
        {$videoURL = "{$videoURL}&title=0"}
    {/if}

    {if !$Data.vimeo_show_portrait}
        {$videoURL = "{$videoURL}&portrait=0"}
    {/if}

    {if !$Data.vimeo_show_author}
        {$videoURL = "{$videoURL}&byline=0"}
    {/if}

    {if $Data.vimeo_loop}
        {$videoURL = "{$videoURL}&loop=1"}
    {/if}

    {if $Data.vimeo_autoplay}
        {$videoURL = "{$videoURL}&autoplay=1"}
    {/if}

    <iframe src="{$videoURL}"
            width="100%"
            height="100%"
            frameborder="0"
            webkitallowfullscreen
            mozallowfullscreen
            allowfullscreen>
    </iframe>
{/block}
```
#### Process the element data before output
When you have to process the saved element data before it is passed to the frontend, you have the possibility to register to the Shopware_Controllers_Widgets_Emotion_AddElement controller event. Here you get the original data to manipulate the output.

```php
public function install()
{
    // ...
    
    $this->subscribeEvent(
        'Shopware_Controllers_Widgets_Emotion_AddElement',
        'onEmotionAddElement'
    );
}

public function onEmotionAddElement(Enlight_Event_EventArgs $args)
{
    $element = $args->get('element');

    if ($element['component']['xType'] !== 'emotion-components-vimeo') {
        return;
    }

    $data = $args->getReturn();
    
    // Do some stuff with the element data
    
    $args->setReturn($data);
}
```
Because the event is called for every element we have to do a check before processing the data. You can get the element info from the event arguments with $args->get('element'). To test for a specific element we can validate the defined xType. When the element is the right one we can get the data of the configuration form with $args->getReturn(). After processing the data we have to set the new output for the frontend with $args->setReturn($data).

#### Advanced: Adding a custom emotion component in ExtJS
If you want to go a little further by creating custom configuration fields for your element you have the possibility to create your own ExtJS component for the element. Here you have full access to the configuration form in ExtJS. You can manipulate existing fields or add new fields which are more complex than the standard form elements.

The file for the component is also located in the emotion_components directory, so it will be detected automatically. The complete path to the file is Views/emotion_components/backend/{name}.js.

For the Vimeo example we use the custom ExtJS component to make a call to the Vimeo api for receiving information about the preview image of the video and save it in the hidden input we already created via the helper functions.

```js
//{block name="emotion_components/backend/vimeo_video"}
Ext.define('Shopware.apps.Emotion.view.components.VimeoVideo', {

    extend: 'Shopware.apps.Emotion.view.components.Base',

    alias: 'widget.emotion-components-vimeo',

    initComponent: function () {
        var me = this;

        me.callParent(arguments);

        me.videoThumbnailField = me.getForm().findField('vimeo_video_thumbnail');
        me.videoIdField = me.getForm().findField('vimeo_video_id');

        me.videoIdField.on('change', Ext.bind(me.onIdChange, me));
    },

    onIdChange: function (field, value) {
        var me = this;

        me.setVimeoPreviewImage(value);
    },

    setVimeoPreviewImage: function (vimeoId) {
        var me = this;

        if (!vimeoId) {
            return false;
        }

        var url = Ext.String.format('https://vimeo.com/api/v2/video/[0].json', vimeoId),
            xhr = new XMLHttpRequest(),
            response;

        xhr.onreadystatechange =  function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                response = Ext.JSON.decode(xhr.responseText);

                if (response[0]) {
                    me.videoThumbnailField.setValue(response[0]['thumbnail_large']);
                }
            }
        };

        xhr.open('GET', url, true);
        xhr.send();
    }
});
//{/block}

```
#### Advanced: Adding a custom designer component in ExtJS
Since Shopware 5.2 you are able to create a custom ExtJS component for the designer elements. Here you have the possibility to add an icon and a preview template for the element, which gets shown in the grid of the designer.

For extending the designer components we have to do a classic template extension of the backend files. So we create a new file in the necessary template hierarchy Views/backend/emotion/{pluginName}/view/detail/elements.

Otherwise than the custom emotion component we have to register the template manually by extending the template inheritance system with our new file. We can subscribe to the PostDispatch event of the emotion module in the install() method of our plugin to do so.

```php
public function install()
{
    // ...
    
    $this->subscribeEvent(
        'Enlight_Controller_Action_PostDispatchSecure_Backend_Emotion',
        'onPostDispatchBackendEmotion'
    );
}

public function onPostDispatchBackendEmotion(Enlight_Controller_ActionEventArgs $args)
{
    $controller = $args->getSubject();
    $view = $controller->View();

    $view->addTemplateDir($this->Path() . 'Views/');
    $view->extendsTemplate('backend/emotion/vimeo_element/view/detail/elements/vimeo_video.js');
}
```
Now that we added our template file we can add our custom component by extending the Smarty {block} of the base class.

```js
//
//{block name="backend/emotion/view/detail/elements/base"}
//{$smarty.block.parent}
Ext.define('Shopware.apps.Emotion.view.detail.elements.VimeoVideo', {

    extend: 'Shopware.apps.Emotion.view.detail.elements.Base',

    alias: 'widget.detail-element-emotion-components-vimeo',

    componentCls: 'emotion--vimeo-video',

    icon: 'data:image/png;base64,...',

    createPreview: function () {
        var me = this,
            preview = '',
            image = me.getConfigValue('vimeo_video_thumbnail'),
            style;

        if (Ext.isDefined(image)) {
            style = Ext.String.format('background-image: url([0]);', image);

            preview = Ext.String.format('<div class="x-emotion-banner-element-preview" style="[0]"></div>', style);
        }

        return preview;
    }
});
//{/block}
```

## Add a new payment method
Use the "createPayment" method to add payment methods to the database inside plugin installations.

```php
public function install(InstallContext $context)
{
    $this->createPayment(
        [
            'name' => 'payment name',
            'description' => 'Pay by the new Payment',
            'action' => 'payment_paymentName',
            'active' => 0,
            'position' => 0,
            'additionalDescription' => 'Lorem ipsum ... ',
         ]
    );
}
```

### Plugin Configuration / Forms

Backend plugin configuration can be extended by the usage of `$this->Form()->setElement()` in the install method of the plugin Bootstrap.php. 

```php
$form = $this->Form();

// Now we can use this instance of Shopware\Models\Config\Form in $form to add elements to it via `setElement().

$form->setElement(
    
    'color', 
    'yourSetting',
    [
        'label' => 'Your label',
        'description' => Lorem ipsum ...',
        'value' => '#ffffff',
        'scope' => Element::SCOPE_SHOP,
    ]
);
```

A textfield would be defined as followed:
```php
public function createConfiguration()
{
    $form = $this->Form();
 
    $form->setElement(
        'text', 
        'simpleTextField',
        [
            'label' => 'Text',
            'value' => 'Preselection',
            'scope' => Shopware\Models\Config\Element::SCOPE_SHOP,
            'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut',
            'required' => true
        ]
    ); 
    
    $translations = [
        'en_GB' => [
            'simpleTextField' => [
                'label' => 'Translated text',
                'description' => 'Translated description'
            ]
        ]
    ];
    
    $this->addFormTranslations($translations);
}
```

#### Options parameter
The options parameter of the setElement function allows to set several configurations on the form element.
* **Label**
The label parameter allows to create a simple descriptional label for the form element.
* **Value**
The value parameter stands for the default value of the field if this hasn´t been edited yet. It will directly be shown in the configuration element.
* **Scope**
With help of the scope parameter it is possible to generate subshop specific configurations. Leaving this option out results in a configuration option that applies for all subshops.
* **Description**
The description parameter allows to provide a more detailed description of the configuration element.
* **Required**
The required parameter specifies whether the configuration item is mandatory or not.

#### Possible elements: 
* color
* date
* datetime 
* html 
* interval 
* mediaselection, 
* number, 
* select, 
* text, 
* textarea 
* time

#### Read the configuration
To read out the configuration of your plugin use this code snippet in your plugin bootstrap:

```php
$setting = $this->Config()->get('yourSetting')
```

### Backend Menu Items

Example in the install method of the plugin Bootstrap.php:

```php
$this->createMenuItem(
    [
        'label' => 'Your plugin laben',
        'controller' => 'your backend controller',
        'class' => 'your-icon',
        'action' => 'Index',
        'active' => 1,
        'parent' => $this->Menu()->findOneBy(['controller' => 'Marketing']),
    ]
);
```

For available parent controllers take a look into the table `s_core_menu` (column `controller`). For example you can use one of the following:
- Article
- Content
- Customer
- ConfigurationMenu
- Marketing

To know which class for which icon take a look at the <a href="{{ site.url }}/designers-guide/backend-icons/">Backend icon set overview</a>.

### Plugin Cronjob
Create a cronJob by using the "createCronJob" method of the plugin Bootstrap.php.

```php
$this->createCronJob(
    'ImportExport - AutoImport',
    'yourCronJobEvent',
    '86400',
    true
);
```

Register a new listener to listen to the "CronAutoImport" event

```php
$this->subscribeEvent(
    'Shopware_CronJob_yourCronJobEvent',
    'onCronJobCall'
);
```  

Implement the method in the plugin Bootstrap.php.

```php
    /**
     * Event listener for thecron job
     */
    public function onCronJobCall()
    {
        // do some fancy things
    }
```
