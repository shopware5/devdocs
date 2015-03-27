---
layout: default
title: Shopware 5 Theme Startup Guide
github_link: designers-guide/theme-startup-guide/index.md
---
![Responsive theme](screen-responsive.jpg)

*Category listing in the Responsive theme*

## Introducing
As part of Shopware 5, we're pleased to introduce our new default "Responsive" theme. The theme is a cross browser compatible, retina ready, responsive HTML5 / CSS3 theme with touch support. It features a clean and unique design and many new features, such as a powerful backend module, new products displays and infinite scrolling.

We've optimized the Responsive theme for the following devices:

- Smartphones in portrait and landscape modes
- Tablets in portrait and landscape modes
- Nettops, Notebooks and Desktop PCs

### Feature overview
- Theme inheritance system is now totally transparent and can easily be modified
- Theme specific registration of Smarty Plugins
- Snippets are now be directly included in the theme directory
- Fully restructured HTML5 structure with backward compatibility in mind
	- Mobile first approach
	- HTML5 form validation
	- Rich snippets based on [schema.org](http://http://schema.org)
	- Massive increase of Smarty blocks in the theme

- Retina ready adaptive images
	- State of the art implementation using the HTML5 ```picture``` element
	- Automatically creation of high dpi images for products and emotion worlds using the Media Manager module

- Fully configurable using the Theme Manager
	- Easily change the color of the complete theme
	- 9 pre-configured color sets
	- Changing your logo is as easy as selecting an image in the Media Manager module

- Built-in LESS compiler
	- CSS source maps for easier debugging
	- Component based styling
	- Over 20 provided mixins
	- All variables are configurable using the Theme Manager module in the Shopware backend

- Built-in Javascript compressor
	- Concatenates all provided files to reduce the amount of HTTP requests
	- Strips all whitespaces and inline comments for a smaller footprint

- Responsive Javascript State Manager and own jQuery plugin system
	- Runs your jQuery plugin only for a specific breakpoint
	- Simplifies the development of jQuery plugins
	- Automatically unbinding of event listeners
	- Destroys automatically jQuery plugins which aren't used in the certain viewport
	- Global event system for easier communication between jQuery plugins

- Fully customizable off-canvas panel
- Infinite scrolling mode for the product listings
- State of the art technologies
	- [bower](http://bower.io/) as the package manager for third-party components
	- Feature detection using [Modernizr](http://modernizr.com/)
	- Pure CSS responsive grid system using [PocketGrid](http://arnaudleray.github.io/pocketgrid/)
	- [jQuery](http://jquery.com/) 2.1.11 included
	- CSS3 Animations with a jQuery fallback using [jQuery Transit](http://ricostacruz.com/jquery.transit/)
	- Scalable icon set with 295 pre defined icons
- Ajaxified the emotion worlds, note functionality and compare function


#### Mobile view

![iPhone Portrait](screen-iphone-portrait.png)

![iPhone Landscape](screen-iphone-landscape.png)

#### Tablet view

![iPad Portrait](screen-ipad-portrait.png)

![iPad Landscape](screen-ipad-landscape.png)

### Compatibility note
We built the theme with maximal backward compatibility in mind and are proud to announce that all Smarty blocks which were be available in the "Emotion" template can also be found in the new "Responsive" theme.

As part of the restructuring of the theme, we updated the list of browsers which are officially supported:

* Chrome version 34 or above
* Firefox version 29 or above
* Safari, Mac OS X only. Support for the windows version has been discontinued
* Opera version 15 with Blink engine or above
* Internet Explorer version 9 or above

Please keep in mind that older browsers doesn't support all available HTML5 and CSS3 features.

### Comparison with the Shopware 4 template

|                                | Shopware 4 | Shopware 5 |
|--------------------------------|------------|------------|
| Total Smarty blocks            |        918 |       1831 |
| Javascript file size in total  |      365KB |      295KB |
| CSS file size in total         |    325.9KB |      279KB |
| HTTP requests on home page*    |         32 |         11 |

*Bare installation without any demo data.


## Getting started
Creating a theme is as easy as it was in Shopware 4, but more powerful than ever. We've added an awesome feature set that you can use to personalize your custom theme right away.

### Theme.php
The ```Theme.php``` is the base file of each and every theme. It provides the basic information about the author, the license and a short description for the Theme Manager. Additionally, it provides access to the following features:

- LESS compiler
- Javascript compiler
- Adding customizable options for the theme user
- Adding configuration sets

The following example shows a demo ```Theme.php``` file for a theme named "Example":

```
<?php
namespace Shopware\Themes\Example;

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Components\Form as Form;
use Shopware\Components\Theme\ConfigSet;

class Theme extends \Shopware\Components\Theme
{
	/** @var string Defines the parent theme */
	protected $extend = 'Bare';

	/** @var string Defines the human readable name */
	protected $name = 'Example';

	/** @var string Description of the theme */
	protected $description = 'An awesome Shopware theme';

	/** @var string The author of the theme */
	protected $author = 'shopware AG';

	/** @var string License of the theme */
	protected $license = 'MIT';
}
```

#### Adding javascript files to your theme
Working with compressors isn't always as easy as adding the files to your HTML structure using ```script``` tags. The built-in javascript compressor is as easy as this and perfectly suited your workflow as a web developer.

Simply place your javascript files in the ```frontend/_public``` folder and add their paths to the ```$javascript``` array in your ```Theme.php```, and you're good to go.

```
/** @var array Defines the files which should be compiled by the javascript compressor */
protected $javascript = array(
	'src/js/jquery.my-plugin.js'
);
```

#### Adding LESS files to your theme
The built-in LESS compiler searches for a file named ```all.less``` in the ```frontend/_public/src/less``` directory. You just have to create the necessary directory structure and your LESS code will automatically converted to CSS on the fly.

##### I don't know LESS, what can I do?
You can add a ```$css``` array to your ```Theme.php``` file, similar to the ```$javascript``` array, with the paths of your CSS files:

```
/** @var array Defines the files which should be compiled by the javascript compressor */
protected $css = array(
	'src/css/my-styles.css'
);
```

## What should I know about the LESS integration?
Less is a CSS pre-processor, meaning that it extends the CSS language, adding features that allow variables, mixins, functions and many other techniques that allow you to make CSS that is more maintainable, customizable and extendable.

### Responsive adjustment with LESS
We're using relative measuring units, like ```em``` or ```rem``` throughout the code base. Working with them can be at times troublesome. To simplify the process, we include a LESS mixin called ```unitize```.

It provides the ability to create ```rem``` values with a pixel based fallback for older browser.

The following example shows how to use the mixin using a ```12px font-size```:

```
p {
	.unitize(12, 16, font-size);
}
```

The second parameter defines the base value for the ```rem``` calculation. Relative measuring units are always based on the ```font-size``` of the ```html``` element. In almost every case, the default browser's ```font-size``` is 16px and that's why we use the value 16 here.

### Available variables

```
// Breakpoints
@phoneLandscapeViewportWidth: 30em;     // 480px
@tabletViewportWidth: 48em;             // 768px
@tabletLandscapeViewportWidth: 64em;    // 1024px
@desktopViewportWidth: 78.75em;         // 1260px

// Basic color definition
@brand-primary: #d9400b;
@brand-primary-light: saturate(lighten(@brand-primary,12%), 5%);
@brand-secondary: #5f7285;
@brand-secondary-dark: darken(@brand-secondary, 15%);

// Grey tones
@gray: #f5f5f8;
@gray-light: lighten(@gray, 1%);
@gray-dark:  darken(@gray-light, 10%);
@border-color: @gray-dark;

// Highlight colors
@highlight-success: #2ecc71;
@highlight-error: #e74c3c;
@highlight-notice: #f1c40f;
@highlight-info: #4aa3df;

//Scaffolding
@body-bg: darken(@gray-light, 5%);
@overlay-bg: #555555;
@text-color: @brand-secondary;
@text-color-dark: @brand-secondary-dark;
@link-color: @brand-primary;
@link-hover-color: darken(@brand-primary, 10%);
@rating-star-color: @highlight-notice;

// Base configuration
@font-size-base: 14;
@font-base-weight: 500;
@font-light-weight: 300;
@font-bold-weight: 600;
@font-base-stack: "Open Sans", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
@font-headline-stack: @font-base-stack;

// Heading font sizes
@font-size-h1: 26;
@font-size-h2: 21;
@font-size-h3: 18;
@font-size-h4: 16;
@font-size-h5: @font-size-base;
@font-size-h6: 12;

// Shopware font directory
@font-directory: "../../fonts/";

// Open Sans font directory
@OpenSansPath: "../../fonts/open-sans-fontface";

// Button text sizes
@btn-font-size: 14;
@btn-icon-size: 10;

// Default Button
@btn-default-top-bg: #FFFFFF;
@btn-default-bottom-bg: @gray-light;
@btn-default-hover-bg: #FFFFFF;
@btn-default-text-color: @text-color;
@btn-default-hover-text-color: @brand-primary;
@btn-default-border-color: @border-color;
@btn-default-hover-border-color: @brand-primary;

// Primary Button
@btn-primary-top-bg: @brand-primary-light;
@btn-primary-bottom-bg: @brand-primary;
@btn-primary-hover-bg: @brand-primary;
@btn-primary-text-color:#FFFFFF;
@btn-primary-hover-text-color: @btn-primary-text-color;

// Secondary Button
@btn-secondary-top-bg: @brand-secondary;
@btn-secondary-bottom-bg: @brand-secondary-dark;
@btn-secondary-hover-bg: @brand-secondary-dark;
@btn-secondary-text-color: #FFFFFF;
@btn-secondary-hover-text-color: @btn-secondary-text-color;

// Panels
@panel-header-bg: @gray-light;
@panel-header-font-size: 14;
@panel-header-color: @text-color;
@panel-border: @border-color;
@panel-bg: #FFFFFF;

// Labels
@label-font-size: 12;
@label-color: @text-color;

// Form base
@input-font-size: 16;
@input-bg: @gray-light;
@input-color: @brand-secondary;
@input-placeholder-color: lighten(@text-color, 15%);
@input-border: @border-color;

// Form states
@input-focus-bg: #FFFFFF;
@input-focus-border: @brand-primary;
@input-focus-color: @brand-secondary;
@input-error-bg: desaturate(lighten(@highlight-error, 38%), 20%);
@input-error-border: @highlight-error;
@input-error-color: @highlight-error;
@input-success-bg: #FFFFFF;
@input-success-border: @highlight-success;
@input-success-color: @brand-secondary-dark;

// Tables
@panel-table-header-bg: @brand-secondary-dark;
@panel-table-header-color: #FFFFFF;
@table-row-bg: #FFFFFF;
@table-row-color: @brand-secondary;
@table-row-highlight-bg: darken(@table-row-bg, 4%);
@table-header-bg: @brand-secondary;
@table-header-color: #FFFFFF;

// Badges, Hints
@badge-discount-bg: @highlight-error;
@badge-discount-color: #FFFFFF;
@badge-newcomer-bg: @highlight-notice;
@badge-newcomer-color: #FFFFFF;
@badge-recommendation-bg: @highlight-success;
@badge-recommendation-color: #FFFFFF;
@badge-download-bg: @highlight-info;
@badge-download-color: #FFFFFF;
```

Please keep in mind that all these values are customizable in the Theme Manager module in the backend.

## Customizing your theme
It's possible to add custom configuration options to your theme. Using this method, the user can fully customize the theme without editing CSS files.

### Creating configuration elements
To create configuration elements it's necessary to add a ```createConfig()``` method to your ```Theme.php```. The method specifies the elements you need for the configuration form. The first parameter is the container element of type ```Shopware\Components\Form\Container\TabContainer``` where you can add additional fields as well as other container elements.

```
/**
 * @param Form\Container\TabContainer $container
 */
public function createConfig(Form\Container\TabContainer $container)
{

    $tab = $this->createTab(
        'responsive_colors_tab',
        'Responsive colors'
    );
    $container->addTab($tab);
}
```

#### Container elements
The ```$container``` also accepts other container elements like a tab or a fieldset.

```
/**
 * @param Form\Container\TabContainer $container
 */
public function createConfig(Form\Container\TabContainer $container)
{
    $fieldset = $this->createFieldSet(
        'responsive_fieldset',
        'My responsive settings'
    );
    $tab = $this->createTab(
        'responsive_colors_tab',
        'Responsive colors'
    );
    $tab->addElement($fieldset)

    $container->addTab($tab);
}

```

#### Adding elements to the configuration container
Now you can add the necessary elements to the ```$container```. The following elements are available:

- ```createTextField```
- ```createNumberField```
- ```createCheckboxField```
- ```createDateField```
- ```createEmField```
- ```createColorPickerField```
- ```createMediaField```
- ```createPercentField```
- ```createPixelField```
- ```createSelectField```
- ```createTextAreaField```


All elements have a similar syntax:

```
$this->createTextField([unique name], [label], [default value]);
```


In the following example we created a textfield with the label ```Basic font size``` and the name ```basic_font_size```. The name of any field is mandatory and has to be unique. It will be used to assign the value of the field to the storefront.

```
/**
 * @param Form\Container\TabContainer $container
 */
public function createConfig(Form\Container\TabContainer $container)
{
	// Create the fieldset which is the container of our field
    $fieldset = $this->createFieldSet(
        'responsive_fieldset',
        'My responsive settings'
    );

    // Create the textfield
    $textField = $this->createTextField(
    	'basic_font_size',
    	'Basic font size',
    	'16px'
    );

    $fieldset->addElement($textField);

    // Create the tab which will be named "Responsive settings"
    $tab = $this->createTab(
        'responsive_colors_tab',
        'Responsive settings'
    );

    // ...add the fieldset to the tab
    $tab->addElement($fieldset)

    // ...last but not least add the tab to the container, which is a tab panel.
    $container->addTab($tab);
}

```

After saving the ```Theme.php```, you will be able to get the value of the field in the storefront like so:

```
{$theme.basic_font_size}
```


## The "Bare" theme
We're aware that our theme is used by thousands of customers and agencies. To simplifying the process of creating your very own theme for Shopware 5, we are pleased to introduce our "Bare" theme. It's built using the latest in web standards and provides a rock solid foundation which helps you build fast, robust and adaptable web shops.

### Using the "Bare" theme as a parent theme
Using the "Bare" theme as the foundation for your own theme is easy.

To modify the parent theme of your custom theme, open your ```Theme.php``` file and modify the following property:

```
<?php
namespace Shopware\Themes\Example;

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Components\Form as Form;
use Shopware\Components\Theme\ConfigSet;

class Theme extends \Shopware\Components\Theme
{
	/** @var string Defines the parent theme */
	protected $extend = 'Bare';
}
```

### Theme structure
The structure of a Shopware 5 theme is very similar to the one already existing in Shopware 4. It is still based on the available sections of Shopware, but it has been refined for easier maintaining. The new structure looks like:

```
├── documents
├── frontend
│   ├── _includes
│   ├── account
│   ├── blog
│   │   └── comment
│   ├── campaign
│   ├── checkout
│   │   └── items
│   ├── compare
│   ├── custom
│   ├── detail
│   │   ├── comment
│   │   └── tabs
│   ├── error
│   ├── forms
│   ├── home
│   ├── index
│   ├── listing
│   │   ├── actions
│   │   ├── filter
│   │   └── product-box
│   ├── newsletter
│   ├── note
│   ├── paypal
│   ├── plugins
│   │   ├── compare
│   │   ├── index
│   │   ├── notification
│   │   ├── payment
│   │   └── seo
│   ├── register
│   ├── robots_txt
│   ├── search
│   ├── sitemap
│   ├── sitemap_xml
│   └── tellafriend
├── newsletter
│   ├── alt
│   ├── container
│   └── index
└── widgets
    ├── checkout
    ├── compare
    ├── emotion
    │   └── components
    ├── index
    ├── listing
    └── recommendation
```

#### Differences between the Shopware 4 and Shopware 5 structure
Shopware 5 themes, like in Shopware 4, are still divided in great sections, with multiple subsections each. In addition we've divided the template files even smaller parts to increase the reusability and maintainability.

For example, we splitted the product box template file ```box_article.tpl``` in smaller parts which can be found in the ```listing/product-box``` folder.

We've also created a new folder named ```_includes```, which contains components which are used across the whole Shopware theme.

## The "Responsive" theme
The "Responsive" theme is our new default theme in Shopware 5. It based on the "Bare" theme and provides its styling and client side functionalities.

### Theme structure
As it's based on the "Bare" theme, the "Responsive" theme only contains the LESS and javascript files, as well as the third party libraries:

```
└── _public
    ├── src
    │   ├── css
    │   ├── fonts
    │   ├── img
    │   │   ├── icons
    │   │   └── logos
    │   ├── js
    │   │   └── vendors
    │   │       ├── modernizr
    │   │       └── raphael
    │   └── less
    │       ├── _components
    │       ├── _mixins
    │       ├── _modules
    │       └── _variables
    └── vendors
        ├── css
        │   └── pocketgrid
        ├── fonts
        │   └── open-sans-fontface
        │       ├── Bold
        │       ├── ExtraBold
        │       ├── Light
        │       ├── Regular
        │       └── Semibold
        ├── js
        │   ├── jquery
        │   ├── jquery.event.move
        │   ├── jquery.event.swipe
        │   ├── jquery.transit
        │   ├── masonry
        │   └── picturefill
        └── less
            ├── normalize-less
            └── open-sans-fontface
```

Please notice that the ```_resources``` folder was renamed to ```_public```. This folder now contains separated third-party and Shopware specific source files in its subfolders.

The third-party libraries can now be found under ```_public/vendors``` and the Shopware specific code under ```_public/src```.


## Installing third-party components using bower
Open the ```bower.json``` file, which can be found in the root directory of the theme, and add your third-party component in the ```dependencies``` object:

```
...
"dependencies": {
    "jquery": "2.1.1"
}
...
```

Now install the development dependencies using ```npm install``` in the root directory of the theme.

After installing the development dependencies, you just have to run ```grunt``` and your newly added third-party component will be installed in the directory ```frontend/_public/vendors```.

## The state manager and the new jQuery plugin pattern
The state manager helps you master different behaviors for different screen sizes.
It provides you with the ability to register different states that are handled
by breakpoints.

Those breakpoints are defined by entering and exiting points (in pixels)
based on the viewport width.
By entering the breakpoint range, the ```enter()``` functions of the registered
listeners are called.
When the defined points are reached, the registered ```exit()``` listener
functions will be called.

This way you can register callbacks that will be called on entering / exiting the defined state.

The manager provides you multiple helper methods and polyfills which help you
master responsive design.

### Using the state manager
The state manager is self-containing and globally available in the global javascript scope in the storefront.

It has been initialized with the following breakpoints:

* State XS
	* Range between ```0``` and ```479``` pixels
	* Usually used for smartphones in portrait mode
* State S
	* Range between ```480``` and ```767``` pixels
	* Usually used for smartphones in landscape mode
* State M
	* Range between ```768``` and ```1023``` pixels
	* Usually used for tablets in portrait mode
* State L
	* Range between ```1024``` and ```1259``` pixels
	* Usually used for tablets in landscape mode, netbooks and desktop PCs
* State XL
	* Range between ```1260``` and ```5160``` pixels
	* Usually used for desktop PCs with a high resolution monitor

#### Adding an event listener
Registering or removing an event listener which uses the state manager is as easy as doing it in pure javascript.

The following example shows how to register an event listener:

```
StateManager.registerEventListener([{
	state: 'xs',
	enter: function() { console.log('onEnter'); },
	exit: function() { console.log('onExit'); }
}]);
```

The registration of event listeners also supports wildcards, so the ```enter()``` and ```exit()``` methods are called by every change of the breakpoint:

```
StateManager.registerEventListener([{
	state: '*',
	enter: function() { console.log('onEnter'); },
	exit: function() { console.log('onExit'); }
}]);
```

### Using the new jQuery plugin pattern
The plugin pattern simplifies the process of creating jQuery plugins and allows you to rapidly prototype with it. It also helps you to minimize the memory usage of the storefront as much as possible by unbinding the event listeners when the plugin is destroyed like, for example, when the user changes breakpoint.

The plugin pattern uses best practices from the jQuery plugin development like namespaced event names or allow the user to modify the configuration of the plugin using ```data``` attributes on the HTML-DOM node.

Here's the necessary boilerplate code for it:

```
// Register your plugin
$.plugin('yourName', {
   defaults: { key: 'value' },

   init: function() {
       // ...initialization code
   },

   destroy: function() {
     // ...your destruction code

     // Use the force! Use the internal destroy method.
     me._destroy();
   }
});
```

### Using the automatically destroying of event listeners
When using stateful javascript, like we do in the new responsive theme, it can be frustrating to unbind all event listeners at once, when changing breakpoints.

To handle that, an easy to use functionality was implemented, which does that for you, out-of-the-box.

We created a proxy method after [jQuery's on() method](http://api.jquery.com/on/), which accepts the same parameter as the original method:

```
$.plugin('yourName', {
   init: function() {
       this._on(this.$el, 'click', function() {
           alert('Yay, I got clicked');
       });
   }
});
```

Either by calling the ```destroy()``` method of the plugin programmatically or using the state manager, all event listeners which were registered with the ```_on()``` method will be unbinded.

### Accessing and modifying the configuration
It's good practice to develop plugins that can be easily customizable using the provided options. Our plugin pattern supports exactly that and even more.

To provide customization options for your plugin, just declare a object named ```defaults``` in your plugin:

```
$.plugin('yourName', {
	defaults: {
		'showThumbnail': true,
		'animationSpeed': 300,
		'showOverlay': true
	}
});
```

The plugin pattern automatically merges the passed user configuration with the default configuration and provides the merged configuration in the object ```this.opts```:

```
$.plugin('yourName', {
	defaults: {
		'showThumbnail': true,
		'animationSpeed': 300,
		'showOverlay': true
	},

	init: function() {
		console.log(this.opts);
	}
});
```

#### Using HTML "data" attributes to modify the configuration
To make the process of configurating a plugin even easier, we've added the ability to override the configuration with HTML ```data``` attributes. It's as easy as adding a ```class``` attribute to an element.

On the plugin side, you only need to call the ```this.applyDataAttributes()``` method inside your plugin's ```init()``` method.

```
// Javascript
$.plugin('yourName', {
	defaults: {
		'showThumbnail': true,
		'animationSpeed': 300,
		'showOverlay': true
	},

	init: function() {
		this.applyDataAttributes();
	}
});

// HTML
<a href="#a-link" data-showThumbnails="false">...</a>
```


### How to register your jQuery plugin using the state manager
The state manager is available in the global javascript scope of the storefront. To register your plugin, simply can call the ```addPlugin()``` method of the state manager.

In the following example we register our own jQuery plugin for the XS and S states. The name of the plugin is "myPlugin" and we will bind it to the HTML DOM nodes which have the class ```.my-selector```:

```
StateManager.addPlugin('.my-selector', 'myPlugin', [ 'xs', 's' ]);
```

#### Passing a user configuration to the jQuery plugin

It's also possible to pass user configuration options to the plugin, which will be merged with the plugin's default configuration. The merged configuration is accessible using the ```this.opts``` object in your plugin.

```
// your plugin
$.plugin('myPlugin', {
	defaults: {
		'speed': 300
	}
});

// Registration of the plugin
StateManager.addPlugin('.my-selector', 'myPlugin', {
	'speed': 2000
}, [ 'xs', 's' ]);
```

If you need to pass a modified configuration to your plugin for a specific viewport, you can use the following pattern:

```
StateManager.addPlugin('.my-selector', 'myPlugin', {
	'speed': 300
}).addPlugin('.my-selector', 'myPlugin', {
	'speed': 2000
}, 's');
```

## Register your own theme specific Smarty plugins
With the new theme we added the much requested ability to register your theme-specific Smarty plugins to the theme.

If you want to, for example, add the ability to parse [Markdown](http://daringfireball.net/projects/markdown/syntax) text in your template files, you can do that with your own Smarty plugin.

To add a Smarty plugin to your theme, simply add a folder named ```_private``` to the root of your theme folder and create a ```smarty``` folder inside it.

```
└── _private
    └── smarty
       └── function.markdown.php

```

## Get your Shopware 4 template working in Shopware 5
Using your Shopware 4 template in Shopware 5 is quite easy. We provide a download link in Shopware Community Center which contains the deprecated ```templates``` directory.

Place the downloaded ```templates``` folder in the root directory of your Shopware 5 installation.

### if your Shopware 4 template is part of a plugin
If your Shopware 4 template is part of a Shopware plugin, go to the backend, open the Plugin Manager module and install the plugin.

### Your Shopware 4 template is a plain folder
If your Shopware 4 template is a plain folder, you need to place it in the ```templates``` directory in your Shopware 5 installation and select it using the Theme Manager module in the backend.

## License
The themes are licensed under the MIT License.

> Copyright (c) shopware AG and individual contributors.
>
> Permission is hereby granted, free of charge, to any person obtaining a copy
> of this software and associated documentation files (the "Software"), to deal
> in the Software without restriction, including without limitation the rights
> to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
> copies of the Software, and to permit persons to whom the Software is
> furnished to do so, subject to the following conditions:
>
> The above copyright notice and this permission notice shall be included in
> all copies or substantial portions of the Software.
>
> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
> IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
> FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
> AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
> LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
> OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
> THE SOFTWARE.
