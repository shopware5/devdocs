---
layout: default
title: Modify jQuery plugins 
github_link: designers-guide/modify-jquery-plugins/index.md
shopware_version: 5.0.2
indexed: true
group: Frontend Guides
subgroup: General Resources
menu_title: Modify jQuery plugins
menu_order: 80
---

<div class="toc-list"></div>

## Introduction
The storefront of Shopware 5, with its new Responsive Theme, relies massively on jQuery plugins to provide a rich interface with a great usability. The plugins are optimized for the Responsive Theme, but may not be ideal for custom themes, where you may want to alter certain functionalities. All of our jQuery Plugins are built upon a ```publish / subscribe``` pattern, which makes it easy to add new functionality to existing plugins, but sometimes you need more. You may want to override certain methods in a plugin to match your interface behaviors, which can't be configure or accomplish using events.
With Shopware 5.0.2, we're providing an easy to use way to override a plugin's behavior. The following guide will cover everything you need to know about the new functionality and on how to use it.

## Override a jQuery plugin
Modifying a plugin is easier than ever before. We introduced a new method called `$.overridePlugin`, which is bond to the jQuery object. jQuery itself is globally available and therefore can be used anywhere in the storefront.

If you want to override a jQuery plugin, you basically need to know the plugin's name and the name of the method you want to override. In the following example, we will override the `swSearch` plugin, which provides the live suggestion search functionality in the storefront. The source files of the `swSearch` plugin is located in the Responsive theme under ```frontend/_public/src/js/jquery.search.js```.

We want to modify the animation of the search result. Instead of just showing the result list, we want to have a nice slide down animation.

### Basic syntax
Each override uses the new method we provide in Shopware 5.0.2. Here's its basic syntax:

```
$.overridePlugin('<pluginName>', {
    '<override the methods>'
});
```

### Modify the animation of the search result
Basically, you need to alter the method implementation to change the animation. Therefore, we have to override the whole method and replace it with our own implementation.

```javascript
$.overridePlugin('swSearch', {
    showResult: function(response) {
        var me = this;
        me.$loader.fadeOut(me.opts.animationSpeed);
        me.$results.empty().html(response).addClass(me.opts.activeCls).slideToggle('fast');
    }
});
```

### Extending the default implementation of the method
We also added the ability to call the original method and add additional logic to it.

You have access to the original plugin implementation using the object property ```superclass```.

```javascript
$.overridePlugin('swSearch', {
    showResult: function() {
        var me = this;

        me.superclass.showResult.apply(this, arguments);
    }
});
```

As you can see in the example above, we call the original implementation of the ```showResult``` method. Now we can call the overlay and modify the ```z-index``` property of the search result and the search form to position it over the overlay.

```javascript
$.overridePlugin('swSearch', {
    showResult: function() {
        var me = this;

        me.superclass.showResult.apply(this, arguments);

        me.$searchField.parents('form').css('z-index', 9999);
        me.$results.css('z-index', 9999);

        $.overlay.open();
    }
});
```

The last thing we have to do is close the overlay when the search result is closed and reset the ```z-index``` property.

```javascript
$.overridePlugin('swSearch', {
    showResult: function() {
        var me = this;

        me.superclass.showResult.apply(this, arguments);

        me.$searchField.parents('form').css('z-index', 9999);
        me.$results.css('z-index', 9999);

        $.overlay.open();
    },

    closeResult: function() {
        var me = this;
        me.superclass.closeResult.apply(this, arguments);

        me.$searchField.parents('form').removeAttr('style');
        me.$results.removeAttr('style');

        $.overlay.close();
    }
});
```
