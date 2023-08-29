---
layout: default
title: Shopware 5 Plugin update guide
github_link: developers-guide/shopware-5-plugin-update-guide/index.md
indexed: true
group: Developer Guides
subgroup: Developing plugins
menu_title: Plugin update guide
menu_order: 100
---

<div class="toc-list"></div>

## Introduction

In this guide we provide you with all essential information you need to keep your plugins Shopware 5.3 compatible.

## Migrate the Bootstrap.php
To migrate the bootstrap.php we create a new plugin base file which has the technical name of the plugin.
Make sure the namespace and the class is called like the technical name of your plugin. 
Extend the class from `Shopware\Components\Plugin\Plugin`.

```php
<?php

namespace SwagUpdatePlugin; 

use Shopware\Components\Plugin;

class SwagUpdatePlugin extends Plugin
{
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
    }
}
```

## Template extensions
To ensure your templates files are extensible, neither __extendsTemplate__ nor __extendsBlock__ methods should be used for responsive template. Instead, you should use Shopware's auto loading mechanism.
The following example shows how template extension plugins need to be updated to achieve the best possible result for Shopware 5.3 templates.
The following source code displays a top seller slider and a banner in the product detail page:

### 1. Add the template directory
Use the early "Enlight_Controller_Action_PreDispatch" event to register the template directory of your plugin.

```php
<?php

namespace SwagUpdatePlugin\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Template_Manager;

class TemplateRegistration implements SubscriberInterface
{
    /**
     * @var Enlight_Template_Manager
     */
    private $templateManager;

    /**
     * @var string
     */
    private $pluginBaseDirectory;

    /**
     * @param Enlight_Template_Manager $templateManager
     * @param string $pluginBaseDirectory
     */
    public function __construct(Enlight_Template_Manager $templateManager, $pluginBaseDirectory)
    {
        $this->templateManager = $templateManager;
        $this->pluginBaseDirectory = $pluginBaseDirectory;
    }

    /**
     * Use the early "Enlight_Controller_Action_PreDispatch" event to register the template directory of the plugin.
     *
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch' => 'onPreDispatch'
        ];
    }

    public function onPreDispatch()
    {
        $this->templateManager->addTemplateDir($this->pluginBaseDirectory . '/Resources/views');
    }
}
```
Register the event subscriber file by using the `services.xml`. To register a event subscriber use the **"shopware.event_subscriber"** tag.
`/.../SwagUpdatePlugin/Resources/services.xml`
```xml
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>

        <service id="swag_update_plugin.subscriber.template_registration"
                 class="SwagUpdatePlugin\Subscriber\TemplateRegistration">
            <argument type="service" id="template"/>
            <argument>%swag_update_plugin.plugin_dir%</argument>
            <tag name="shopware.event_subscriber"/>
        </service>

    </services>
</container>
```
### 2. Create the template
The goal is to make this plugin compatible with the new Shopware 5 templates. For this purpose, the following should be considered:

    Inside the PostDispatch event, we have to load the corresponding template files.
    The extendsTemplate function should not be used in the new template, otherwise the plugin template cannot be overwritten by other templates.

    In order for the plugin template to be easily extended by others, the template adjustments should be extracted into a separate file.

First, the template structure is revised. The example1.tpl file is now divided into two new files:

    *SwagExample1/Resources/views/frontend/detail/index.tpl (Entry point to extend the template)
    *SwagExample1/Resources/views/frontend/swag_example1/detail_extension.tpl (Contains the source code for the extension)

The new files contain the following source code:
`/.../SwagUpdatePlugin/Resources/views/frontend/detail/index.tpl`
```smarty
{extends file="parent:frontend/detail/index.tpl"}

{block name="frontend_detail_index_detail"}
    {include file="frontend/swag_example1/detail_extension.tpl"}
{/block}
```

`/.../SwagUpdatePlugin/Resources/views/frontend/swag_example1/detail_extension.tpl`
```html
{block name="frontend_detail_example"}
    <div class="example--own-topseller">
        {block name="frontend_detail_example_headline"}
            <h1 class="own-topseller--headline">My topseller</h1>
        {/block}

        {block name="frontend_detail_example_img"}
            <img class="own-topseller--img" src="{link file={$mediaSelection}}" alt="Test" />
        {/block}

        {block name="frontend_detail_example_topseller"}
            <div class="own-topseller--container">
                {action module=widgets controller=listing action=top_seller sCategory=3}
            </div>
        {/block}
    </div>
{/block}
```

Notice: Template extensions for the responsive template are loaded via the inheritance hierarchy based on the file system. Therefore, this template should be extends via {extends file = ".."}.

The SwagExample1/Resources/views/frontend/detail/index.tpl file serve only as entry points into the original template. The source code for displaying the top seller sliders and the banner element, which was previously located directly in the extended template file, has now been made available globally in a separate template file, and is now simply included by the template. This has the following advantages:

    Avoid duplicate source code
    Extensible plugin template for other developers

__Notice: To allow other templates to easily extend your templates, you should provide your code in a separate file. Include that file in other templates, inside Smarty blocks, thus allowing different entry point.__

### 3. Add the plugin configuration
For adding plugin configuration you add a new xml file to the Resources directory.

`/.../SwagUpdatePlugin/Resources/config.xml`
```xml
<?xml version="1.0" encoding="utf-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware5/shopware/5.3/engine/Shopware/Components/Plugin/schema/config.xsd">

    <elements>
        <element type="mediaselection">
            <name>mediaSelection</name>
            <label lang="de">Bildauswahl</label>
            <label lang="en">MediaSelection</label>
        </element>
    </elements>

</config>
```
Add a new element of the type `mediaselection`, set the name and the label. 
For more information about plugin configuration have a look [here](https://developers.shopware.com/developers-guide/plugin-configuration/).

Now we can read the configuration from the the `DBALConfigReader` and assign the value to the view.

For this we create a new subscriber file called DetailSubscriber. In this file, we register the 'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' event and append our configurations variable to the view.
`/.../SwagUpdatePlugin/Resources/services.xml`
```xml
<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
           
    <services>

        <service id="swag_update_plugin.subscriber.template_registration"
                 class="SwagUpdatePlugin\Subscriber\TemplateRegistration">
            <argument type="service" id="template"/>
            <argument>%swag_update_plugin.plugin_dir%</argument>
            <tag name="shopware.event_subscriber"/>
        </service>

        <service id="swag_update_plugin.subscriber.detail_subscriber"
                 class="SwagUpdatePlugin\Subscriber\DetailSubscriber">
            <argument type="service" id="shopware.plugin.config_reader"/>
            <argument>%swag_update_plugin.plugin_name%</argument>
            <tag name="shopware.event_subscriber"/>
        </service>

    </services>
</container>
```
`/.../SwagUpdatePlugin/Subscriber/DetailSubscriber.php`

```php
<?php

namespace SwagUpdatePlugin\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Plugin\DBALConfigReader;

class DetailSubscriber implements SubscriberInterface
{
    /**
     * @var DBALConfigReader
     */
    private $configReader;

    /**
     * @var string
     */
    private $pluginName;

    /**
     * @param DBALConfigReader $configReader
     * @param $pluginName
     */
    public function __construct(DBALConfigReader $configReader, $pluginName)
    {
        $this->configReader = $configReader;
        $this->pluginName = $pluginName;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => 'onPostDispatchFrontendDetail'
        ];
    }

    public function onPostDispatchFrontendDetail(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Frontend_Detail $subject */
        $subject = $args->get('subject');

        $config = $this->configReader->getByPluginName($this->pluginName);

        $subject->View()->assign('mediaSelection', $config['mediaSelection']);
    }
}
```

## Uninstall

During the uninstall process, the user will now be prompted which data he wishes to remove.
Existing __uninstall()__ method should remove all data. 
Use the `UninstallContext` which contains the __keepUserData()__ method. If the return value is `true` you should only remove non-user related data.

### How to 'secureUninstall'

```php
<?php

namespace SwagUpdatePlugin; // Set the namespace like the technical name of your plugin

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\UninstallContext;

// Call the class and file like the technical name of your plugin and extend from Shopware\Components\Plugin\Plugin
class SwagUpdatePlugin extends Plugin
{
    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
    }
    
    public function uninstall(UninstallContext $context)
    {
        if ($context->keepUserData()) {
            return;
        }

        // Delete all data.
    }
}
```

#### Integration of Less and Js files
Less and Js file registration will be done automatically. Just place the resources into the following directories:

    /.../SwagUpdatePlugin/Resources/frontend/css/**.css
    /.../SwagUpdatePlugin/Resources/frontend/js/**.js
    /.../SwagUpdatePlugin/Resources/frontend/less/all.less

The css and js directories may contain arbitrary sub directories. @imports in all.less will be resolved.

### Less
Besides traditional CSS, Shopware 5 includes Less support in new templates. Less is a CSS pre-processor, which can be used when styling your Shopware 5 templates. Less is a very powerful tool, and Shopware 5 extends its default feature set by adding some commonly used functions.

For more information on how to use Less, have a look [here](http://lesscss.org/).

#### Structure convention

Like in the example above, in most cases there is only one .less file to compile - the __all.less__. It includes additional files named by their content. Most likely the __all.less__ file includes the __modules.less__ and __variables.less__ files, which are often needed by default. These files include additional files from same named directories, e.g. modules.less includes files from the directory called __"_modules/"__.

As an example, if the file contains styles for Shopware's checkout page, it would be called __"checkout.less"__ and place in the ___modules__ directory.
The files inside ___variables/__ should only contain variable definitions made in Less.

Directory/File    | Utility
-------------- | ---------------------------------------------
_modules       | Contains less styles of modules
_variables     | Contains less variables
all.less       | Includes "modules.less" and "variables.less"
modules.less   | Includes all files in "_modules"
variables.less | Includes all files in "_variables"

##### Example all.less
```less
@import "modules";
```

##### Example modules.less
```less
@import "_modules/checkout";
```
##### Example _modules/checkout.less
```less
.yourOwnSelector {
    width: 100%;
}
```

#### Breakpoint sizes

Our responsive template uses media queries with specific breakpoint, so you can implement different styles for different browser window sizes.

##### structure.less - Less variables for the different device sizes
```less
@phoneLandscapeViewportWidth: 30em;     // 480px
@tabletViewportWidth: 48em;             // 768px
@tabletLandscapeViewportWidth: 64em;    // 1024px
@desktopViewportWidth: 78.75em;         // 1260px
```

##### Example usage
```less
.myOwnElement {
    width: 90%;
}

@media screen and (min-width: @tabletViewportWidth) {
    .myOwnElement {
        width: 70%;
    }
}
```

#### Mixins
Each child theme of the Shopware responsive template has access to some very useful mixins like '.unitize()' or '.clearfix()'
A mixin is a useful function being used in our .less files.
E.g. our new "unitize" mixin can be used to calculate __rem__ values, which we use in our new template, replacing __px__ values.

##### Example usage for unitize()
```less
.myOwnElement {
    //Would output font-size: 0.625rem;
    //First parameter is your desired px value, in this case 10px.
    //Second parameter is the base value which, in this case, means the default font-size of 16px.
    //You won't have to change the second parameter in most of the cases. The .unitize-method now calculates 10/16 = 0.625.
    //Third parameter is the actual style to be used
    .unitize(10, 16, font-size);

    //For more examples of "unitize" mixin usage, please refer to yourShopSystem/Themes/Frontend/Responsive/frontend/_public/src/less/_mixins/unitize.less
}
```

In __Themes/Frontend/Responsive/frontend/_public/src/less/_mixins__ you can find other useful mixins provided by Shopware 5.

#### Messages
If you want to display message to the shop customer, you can use the __messages__ template file.
Examples:

##### account/password.tpl - Display a success message.
```html
{include file="frontend/_includes/messages.tpl" type="success" content="{s name='PasswordInfoSuccess'}{/s}"}
```
![Success Message](message-success.png)

##### account/orders.tpl - Display a warning message.
```html
{include file="frontend/_includes/messages.tpl" type="warning" content="{s name='OrdersInfoEmpty'}{/s}"}
```
![Warning Message](message-warning.png)

##### blog/comment/form.tpl - Display an error message
```html
{include file="frontend/_includes/messages.tpl" type="error" content="{s name='BlogInfoFailureFields'}{/s}"}
```
![Error Message](message-error.png)

Additional documentation can be found in __Themes/Frontend/Bare/frontend/_includes/messages.tpl__.

#### Other things
- Use the new CSS classes, e.g. "btn is--primary", "is--bold" or "has--border". For further information, have a look at the new style tile.
- Use the CSS class name convention ("<parent>--<child>" , e.g. "abo--detail-container > detail-container–image")
- If possible, build small images and icons with CSS

#### jQuery plugins
Shopware 5 already includes several jQuery plugins you can use to implement useful features, like sliders or search fields. All of these can be found in the 'Themes/Frontend/Responsive/frontend/_public/src/js/' directory.

#### Write own jQuery plugins
If you want to write your own jQuery plugin, you should use our new plugin base class. It provides all the basic operations every jQuery plugin needs to have.

##### _public/src/js/jquery.plugin-base.js - Example how to register and call a jquery-plugin
```javascript
// Register your plugin
$.plugin('yourName', {
   defaults: { exampleValue: 'value' },

   init: function() {
       // ...initialization code

       //The applyDataAttributes function merges data attributes into plugin options. Example element: <div class="test" data-exampleValue="value2"></div>
       //The default "exampleValue" variable will be overwritten with "value2".
       //This value is then available in this.opts.exampleValue variable

           this.applyDataAttributes();
   },

   destroy: function() {
     // ...your destruction code

     // Use the force! Use the internal destroy method.
     me._destroy();
   }
});

// Call the plugin
$('.test').yourName();
```
#### Data attributes
In Shopware 5, Javascript files are no longer parsed by Smarty. To assign a Smarty variable value to a Javascript variable, use the HTML5 Data attributes (accessible in Javascript with "me.applyDataAttributes()"). Refer to the previous code snippet for an usage example.

### Other
Implement as many (useful) Smarty blocks as possible, so your templates are extensible.

## Search Bundle
In listings, you should use the new listing logic (Conditions, ConditionHandler, Facet, FacetHandler, etc.).
Keep the required additional data in mind, e.g. the new view variable "pageSizes".

For more information about the searchBundle, have a look [here](https://developers.shopware.com/developers-guide/shopware-5-search-bundle/#search-results)
## Questions?
For further questions you should read the complete Shopware 5 upgrade guide.
