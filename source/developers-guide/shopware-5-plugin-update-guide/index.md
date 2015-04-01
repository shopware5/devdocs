---
layout: default
title: Shopware 5 Plugin Update Guide
github_link: developers-guide/shopware-5-plugin-update-guide/index.md
---
## Introduction

In this guide we provide you with all essential information you need to keep your plugins Shopware 5.0 compatible!

The most changes are optional, so the old syntax should still work.

## Template extensions
You should neither use the method __extendsTemplate__ nor __extendsBlock__ for responsive templates anymore, since your templates won't be extensible then. Instead you should use the auto-loading of shopware.
The following example shows how template extensions plugins need to be updated, to achieve the best possible result for the shopware 5 templates.
The following source code is taken from the SwagExample1 plugin for shopware 4, which displays a top seller slider and a banner to the article detail page:

#### SwagExample1/Bootstrap.php
```php
<?php
class Shopware_Plugins_Frontend_SwagExample1_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail', 'onSecureDetailPostDispatch');

        $form = $this->Form();
        $form->setElement(
            'mediaselection',
            'mediaselection',
            ['label' => 'Media', 'value' => NULL]
        );

        return true;
    }

    public function onSecureDetailPostDispatch(Enlight_Event_EventArgs $arguments)
    {
        /**@var $controller Shopware_Controllers_Frontend_Listing*/
        $controller = $arguments->getSubject();
        $controller->View()->addTemplateDir($this->Path() . 'Views/');
        $controller->View()->extendsTemplate('frontend/detail/example1.tpl');
        $controller->View()->mediaSelection = $this->Config()->mediaselection;
    }
}
```

#### SwagExample1/Views/frontend/detail/example1.tpl
```smarty
{block name="frontend_detail_index_detail"}
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
{/block}

```

The goal is to make this plugin now compatible with the new shopware 5 template and the old shopware 4 template:
For this purpose, the following should be considered:
<ul>
    <li>Inside the PostDispatch, we have to distinguished between the different template version, to load different templates.</li>
    <li>The extendsTemplate function should not be used in the new template, otherwise the plugin template can not be overwritten by other templates.</li>
    <li>In order for the plugin template can be easily extended by others, the template adjustments should be extracted to a separate file.</li>
</ul>

First, the template structure is revised. The example1.tpl is now divided into three new files:
<ul>
    <li>SwagExample1/Views/emotion/detail/example1.tpl (Entry point to extends the emotion template)</li>
    <li>SwagExample1/Views/responsive/detail/index.tpl (Entry point to extends the responsive template)</li>
    <li>SwagExample1/Views/common/frontend/swag_example1/detail_extension.tpl (Contains the source code for the extension)</li>
</ul>

The new files contain the following source code:
#### SwagExample1/Views/common/frontend/swag_example1/detail_extension.tpl
```smarty
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

#### SwagExample1/Views/emotion/frontend/detail/example1.tpl
```smarty
{block name="frontend_detail_index_detail"}
    {include file="frontend/swag_example1/detail_extension.tpl"}
{/block}
```

#### SwagExample1/Views/responsive/frontend/detail/index.tpl
```smarty
{extends file="parent:frontend/detail/index.tpl"}

{block name="frontend_detail_index_detail"}
    {include file="frontend/swag_example1/detail_extension.tpl"}
{/block}
```
<br>
__Notice: Template extensions for the responsive template are loaded via the inheritance hierarchy based on the file system. Therefore, this template should be extends via {extends file = ".."}.__

The files SwagExample1/Views/responsive/frontend/detail/index.tpl and SwagExample1/Views/emotion/frontend/detail/example1.tpl only serve as an entry point into the original template.
The source code for displaying the top seller sliders and the banner element, which was previously located directly in the extended template file, has now been made available globally in a separate template file.
This file is now included simply by both templates.
This has the following advantages:
<ul>
    <li>Avoid duplicate source code</li>
    <li>Extensible plugin template for other developers</li>
</ul>

Now only the bootstrap has to be adapted, that it loads the correct template directory for the corresponding Template Version.
The new plugin Bootstrap now looks like this:
```php
<?php

class Shopware_Plugins_Frontend_SwagExample1_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail', 'onSecureDetailPostDispatch');

        $form = $this->Form();
        $form->setElement(
            'mediaselection',
            'mediaselection',
            ['label' => 'Media', 'value' => NULL]
        );

        return true;
    }

    public function onSecureDetailPostDispatch(Enlight_Event_EventArgs $arguments)
    {
        /**@var $controller Shopware_Controllers_Frontend_Listing*/
        $controller = $arguments->getSubject();

        $controller->View()->addTemplateDir($this->Path() . 'Views/common/');

        if (Shopware()->Shop()->getTemplate()->getVersion() >= 3) {
            $controller->View()->addTemplateDir($this->Path() . 'Views/responsive/');
        } else {
            $controller->View()->addTemplateDir($this->Path() . 'Views/emotion/');
            $controller->View()->extendsTemplate('frontend/detail/example1.tpl');
        }

        $controller->View()->mediaSelection = $this->Config()->mediaselection;
    }
}
```

The following has been changed:
<ul>
    <li>First, the "Views/common/" directory is registered as a template directory. Templates inside this directory are now available for both template versions.</li>
    <li>Then it will check which shop template version is active to load the different templates</li>
    <li>Responsive - Template files for the responsive template loaded automatically over the inheritance by using the same names. Therefore, only the template directory must registered here</li>
    <li>Emotion - In the emotion template we can load the template extension as well as in shopware 4. After the template directory is registered, the template file is loaded via __extendsTemplate__.</li>
</ul>

__Notice: Do not use "_default/" or "_emotion/" at the beginning of the extend-call. Use "parent:" instead.__

__Notice: To allow other templates can easily extend the plugin templates, should firstly the template are in a separate file and provided to the other with own Smarty blocks to allow different entry point.

## Uninstall

During the uninstallation process, the user can now be prompted which data he wishes to remove.
Existing __uninstall()__ method should remove all data, and the new __secureUninstall()__ method should only remove non-user related data.

##### Bootstrap.php - How to use 'secureUninstall()'

```php
// Set the new secureUninstall capability
public function getCapabilities()
{
    return array(
        'install' => true,
        'enable' => true,
        'update' => true,
        'secureUninstall' => true
    );
}

// Remove all data
public function uninstall()
{
    $this->secureUninstall();
    $this->removeDatabase();
    return true;
}


// Remove only non-user related data.
public function secureUninstall()
{
    return true;
}
```

### Less
For styling our responsive template we used Less, which can be used exactly like CSS.
Anyway Less implements nice features to improve and simplify your css-code.
Therefore it would be great to see if you use it as well.

For more information on how to use Less, have a look [here](http://lesscss.org/).


#### Integration
Less-files are loaded by creating a new event in the install-method of our plugin.

##### Bootstrap.php - Using .less files in responsive template

```php
/**
 * Registers all necessary events and hooks.
 */
private function subscribeEvents()
{
    // Subscribe the needed event for less merge and compression
    $this->subscribeEvent(
        'Theme_Compiler_Collect_Plugin_Less',
        'addLessFiles'
    );
}

/**
 * Provide the file collection for less
 *
 * @param Enlight_Event_EventArgs $args
 * @return \Doctrine\Common\Collections\ArrayCollection
 */
public function addLessFiles(Enlight_Event_EventArgs $args)
{
    $less = new \Shopware\Components\Theme\LessDefinition(
    //configuration
	array(),

        //less files to compile
        array(
            __DIR__ . '/Views/responsive/frontend/_public/src/less/all.less'
        ),

        //import directory
        __DIR__
    );

    return new Doctrine\Common\Collections\ArrayCollection(array($less));
}

```
#### Structure convention

Like in the example above, in most cases there is only one .less file to compile - the __all.less__.
It includes additional files named by its content.
Most likely the all.less includes a modules.less and a variables.less, which we both need quite often by default.
These files include additional files from same named folders, e.g. modules.less includes files from the folder called __"_modules/"__.

If it contains styles for the checkout-page of Shopware, we would call the file __"checkout.less"__ and place it into ___modules__.
The files inside of ___variables/__ should only contain variable-definitions made in Less.

Folder/File    | Utility
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

####Breakpoint sizes
Our responsive template uses media queries with the following breakpoints.
This way we can implement styles, which are only being used when a certain min-width is reached.
#####structure.less - Take notice of the less variables for the different device sizes
```less
@phoneLandscapeViewportWidth: 30em;     // 480px
@tabletViewportWidth: 48em;             // 768px
@tabletLandscapeViewportWidth: 64em;    // 1024px
@desktopViewportWidth: 78.75em;         // 1260px
```

##### Example-usage
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
A mixin is basically just a useful function being used in our .less-files.
E.g. our new "unitize"-mixin can be used to calculate rem-values, which we use in our new template, no more px-values.

##### Example usage unitize()
```less
.myOwnElement {
	//Would output font-size: 0.625rem;
	//First parameter is your desired px-value, in this case 10px.
	//Second parameter is the base-value, which in this case means the default font-size of 16px.
	//You won't have to change the second parameter in 99% of the cases. The .unitize-method now calculates 10/16 = 0.625.
	//Third parameter is the actual style to be used
	.unitize(10, 16, font-size);

	//There are way more usages of our "unitize"-mixin, take a look at yourShopSystem/Themes/Frontend/Responsive/frontend/_public/src/less/_mixins/unitize.less
}
```

Have a further look at __Themes/Frontend/Responsive/frontend/_public/src/less/_mixins__ to explore which other useful mixins we offer.

####Messages
If you want to show a message to the shop customer, you should use the message template file for it.
Examples:

##### account/password.tpl - Show the user a success message.
```smarty
{include file="frontend/_includes/messages.tpl" type="success" content="{s name='PasswordInfoSuccess'}{/s}"}
```
![Success Message](message-success.png)

##### account/orders.tpl - Show the user a warning.
```smarty
{include file="frontend/_includes/messages.tpl" type="warning" content="{s name='OrdersInfoEmpty'}{/s}"}
```
![Warning Message](message-warning.png)

##### blog/comment/form.tpl - Show the user an error message
```smarty
{include file="frontend/_includes/messages.tpl" type="error" content="{s name='BlogInfoFailureFields'}{/s}"}
```
![Error Message](message-error.png)

Additional documentation can be found in __Themes/Frontend/Bare/frontend/_includes/messages.tpl__.

#### Other things
- Use the new CSS classes, e.g. "btn is--primary", "is--bold" or "has--border". For further information, have a look at the new style tile.
- Use the CSS class name convention ("<parent>--<child>" , e.g. "abo--detail-container > detail-container–image")
- If possible, build small images and icons with CSS

### Javascript
Javascript should now be merged and compressed.

#### Integration

##### Bootstrap.php - Using Javascript merge and compression
```php
/**
 * Registers all necessary events and hooks.
 */
private function subscribeEvents()
{
    // Subscribe the needed event for js merge and compression
    $this->subscribeEvent(
        'Theme_Compiler_Collect_Plugin_Javascript',
        'addJsFiles'
    );
}

/**
 * Provide the file collection for js files
 *
 * @param Enlight_Event_EventArgs $args
 * @return \Doctrine\Common\Collections\ArrayCollection
 */
public function addJsFiles(Enlight_Event_EventArgs $args)
{
    $jsFiles = array(__DIR__ . '/Views/responsive/frontend/_public/src/js/script.js');
    return new Doctrine\Common\Collections\ArrayCollection($jsFiles);
}
```
#### jQuery plugins
Shopware offers you a bunch of jQuery plugins you can use, e.g. for sliders.
Therefore have a look at 'Themes/Frontend/Responsive/frontend/_public/src/js/...'

#### Write own jQuery plugins
If you have to write your own jQuery plugin, you should use our new plugin base class. It provides all the basic operations every jQuery plugin needs to have.

##### _public/src/js/jquery.plugin-base.js - Example how to register and call a jquery-plugin
```javascript
// Register your plugin
$.plugin('yourName', {
   defaults: { exampleValue: 'value' },

   init: function() {
       // ...initialization code

       //applyDataAttributes merges data-attributes into plugin-options, example element <div class="test" data-exampleValue="value2"></div>
       //The default-option mentioned above would be overwritten with "value2" now.
       //This option is then available in this.opts.exampleValue

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
####Data-Attributes
Smarty won't be longer parsed by Javascript.
To assign a Smarty variable to Javascript use HTML5 Data-Attributes (access with JS-Code "me.applyDataAttributes()").
Those are also explained in the example above.

### Other
Implement as many (useful) Smarty blocks as possible, so your own template is also extensible.

## Search Bundle
In listings, you should use the new listing logic (Conditions, ConditionHandler, Facet, FacetHandler, etc.).
Keep the required additional data in mind, e.g. the new view variable "pageSizes".

## Updating an example plugin
In the following steps we'll update our "SwagExample1"-Plugin to be running with Shopware 5 and fully responsive.

### Creating folder structure
In the first step of updating our plugin to be compatible with Shopware 5, we'll simply create the new folder-structure.
The current folder-structure looks like this:
![Current structure](current-structure.png)

Now we simply create two new folders inside of "Views":
__emotion_ and __responsive_.
Additionally, we move the existing "frontend"-folder into the "emotion"-folder.

All we need to do for now is adding some basic-folders to the responsive-folder, too.
Create them like this:
```
Views/
|->responsive/
  |->frontend/
  |->_public/
     |->src/
       |->less/
```
Basically we only added a "frontend"-folder and created the structure for the less-files, which we include later.


### Implement new less-structure
We already created the basic folders in a step above.
As mentioned in the tutorial, our new resource-files like css/less, javascript or images are placed into the ___public__ folder, which contains another __src__ folder.
Inside the __src__-folder we also got a new __less__-folder.

At first we start by creating a file called "all.less" inside the __less__-folder, which has to be included in php now.
Therefore we use a new event called __"Theme_Compiler_Collect_Plugin_Less"__, that has to be registered in the install-method first:

#### Registering the new event in the Bootstrap.php
```php
public function install()
{
	...
	// Subscribe the needed event for less merge and compression
	$this->subscribeEvent(
		'Theme_Compiler_Collect_Plugin_Less',
		'addLessFiles'
	);
	...
}
...

/**
 * Provide the file collection for less
 */
public function addLessFiles(Enlight_Event_EventArgs $args)
{
	$less = new \Shopware\Components\Theme\LessDefinition(
	//configuration
		array(),
		//less files to compile
		array(
			__DIR__ . '/Views/responsive/frontend/_public/src/less/all.less'
		),

		//import directory
		__DIR__
	);

	return new Doctrine\Common\Collections\ArrayCollection(array($less));
}
```

In the method __addLessFiles__ we basically add an array with all the less-files, which have to be included, and return them in a doctrine array-collection.

After reinstalling the plugin, the __all.less__-file should be included and working already.
Let's go back to the less-file to create our new less-structure.

As mentioned in the tutorial, the __all.less__ is needed to include other less-files.
Therefore we create another file called __"modules.less"__ and additionally create a folder called __"_modules"__.
What we're going to do now is implement the __modules.less__ inside of the all.less, which then proceeds to implement all the .less-files inside of the __"_modules"__-folder.
Inside of the __"_modules"__-folder we create less-files for the actual styles grouped by their usage in the frontend.
E.g. we need styles for the detail-page, as we're extending the detail-page - so we create a new file called __"detail.less"__ inside of the __"_modules"__-folder.

#### all.less
```less
@import "modules";
```

#### modules.less
```less
@import "_modules/detail";
```

We finally are able to start using actual less-styles within the __detail.less__.
In the old __example1.tpl__ were some inline-styles placed on the img-tag.
Let's place them by using the new class of the img-element:

#### detail.less
```less
.own-topseller--img {
	width: 50%;
	display: block;
}
```

### We're done
Actually we're done now and the example-plugin is now shopware 5 compatible and is being responsive.
The last thing we could do is add some smarty-blocks to the html-code, so the template will be extensible.
By installing the plugin and switching to any detail-page, we should see the newly created responsive-template.

Below we'll list up all files we've changed:

#### Bootstrap.php
```php
<?php

class Shopware_Plugins_Frontend_SwagExample1_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail', 'onSecureDetailPostDispatch');

        // Subscribe the needed event for less merge and compression
        $this->subscribeEvent(
            'Theme_Compiler_Collect_Plugin_Less',
            'addLessFiles'
        );

        $form = $this->Form();
        $form->setElement(
            'mediaselection',
            'mediaselection',
            ['label' => 'Media', 'value' => NULL]
        );

        return true;
    }


    public function onSecureDetailPostDispatch(Enlight_Event_EventArgs $arguments)
    {
        /**@var $controller Shopware_Controllers_Frontend_Listing*/
        $controller = $arguments->getSubject();

        $controller->View()->addTemplateDir($this->Path() . 'Views/common/');

        $template = Shopware()->Shop()->getTemplate();
        if ($template->getVersion() >= 3) {
            $controller->View()->addTemplateDir($this->Path() . 'Views/responsive/');
        } else {
            $controller->View()->addTemplateDir($this->Path() . 'Views/emotion/');
            $controller->View()->extendsTemplate('frontend/detail/example1.tpl');
        }

        $controller->View()->mediaSelection = $this->Config()->mediaselection;
    }

    /**
     * Provide the file collection for less
     */
    public function addLessFiles(Enlight_Event_EventArgs $args)
    {
        $less = new \Shopware\Components\Theme\LessDefinition(
        //configuration
            array(),
            //less files to compile
            array(
                __DIR__ . '/Views/responsive/frontend/_public/src/less/all.less'
            ),

            //import directory
            __DIR__
        );

        return new Doctrine\Common\Collections\ArrayCollection(array($less));
    }
}
```

#### Views/responsive/frontend/_public/src/less/all.less
```less
@import "modules";
```

#### Views/responsive/frontend/_public/src/less/modules.less
```less
@import "_modules/detail";
```

#### Views/responsive/frontend/_public/src/less/_modules/detail.less
```less
.own-topseller--img {
	width: 50%;
	display: block;
}

@media screen and (min-width: @phoneLandscapeViewportWidth) {
    //Styles only being used when the size of the screen is at least 480px.
	//All the sizes are mentioned in yourShopSystem/Themes/Frontend/Responsive/frontend/_public/src/less/_variables/structure.less
}

@media screen and (min-width: @desktopViewportWidth) {
	//Styles only being used when the size of the screen is at least 1260px.
	//All the sizes are mentioned in yourShopSystem/Themes/Frontend/Responsive/frontend/_public/src/less/_variables/structure.less
}
```

#### SwagExample1/Views/common/frontend/swag_example1/detail_extension.tpl
```smarty
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

#### SwagExample1/Views/emotion/frontend/detail/example1.tpl
```smarty
{block name="frontend_detail_index_detail"}
    {include file="frontend/swag_example1/detail_extension.tpl"}
{/block}
```

#### SwagExample1/Views/responsive/frontend/detail/index.tpl
```smarty
{extends file="parent:frontend/detail/index.tpl"}

{block name="frontend_detail_index_detail"}
    {include file="frontend/swag_example1/detail_extension.tpl"}
{/block}
```

#### New folder-structure
![New folder structure](new-structure.png)

## Questions?
For further questions you should read the complete Shopware 5 upgrade guide.
