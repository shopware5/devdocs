---
layout: default
title: jQuery plugins and the StateManager
github_link: designers-guide/javascript-statemanager-and-pluginbase/index.md
indexed: true
group: Frontend Guides
subgroup: Developing Themes
menu_title: jQuery plugins & the StateManager
menu_order: 70
---

<div class="toc-list"></div>

## Introduction
Javascript development can be painful especially when you have to deal with responsive websites where you have to adjust the behavior of the code based on the available screen real estate. Therefore we came up with a component called *StateManager*, which provides you with the ability to define states and triggers a *callback* function, if a state was entered or left.

On the other hand, we have our lovely jQuery plugins which are not always a pleasure to build. To simplify the process, we've implemented a plugin base class which features the best practices of jQuery plugin development that flawlessly integrates with the *StateManager*.

In the following document we want to give you a general overview of the provided functionality, which can come in handy for your next theme.

## Plugin base class
As mentioned, the jQuery plugin base class was built up with the best practices of jQuery plugin development. Here's feature set at a glance:

* Default configuration + ability to override it with a user configuration
* Ability to use HTML5 ```data``` attributes to configure the plugin
* Support for jQuery's method chaining
* Namespacing of events
* Built-in functionality to remove event listeners
* Preventing multiple instanciation on the same element
* Custom expression to check if an element uses a specific plugin
* Automatically binding the plugin to the element using jQuery's ```data```-method

As you can see, we've put a lot of effort in the provided feature set to provide you with an easy to use class for your next jQuery plugin.

### Getting started
Now it's time to take a look at the actual implementation process of a jQuery plugin using the plugin base class. Here's a commented example of a generic plugin:

```javascript
/**
 * Example jQuery plugin using the base class
 *
 * The $.plugin method bound to the globally available jQuery
 * object. The method needs two parameters, the first one is
 * simply the name of the plugin which will be used to bind
 * the plugin to jQuery's $.fn namespace. The second parameter
 * is an object which provides the default configuration and 
 * the actual implementation of the plugin.
 */
$.plugin('example', {
    
    /**
     * The default configuration object of the plugin. The
     * user can provide custom settings which will be automatically
     * merged into a new object which can be accessed using "this.opts"
     * in any plugin method which scope is in the plugin.
     */
    defaults: {
        activeCls: 'js--is-active'
    },
    
    /**
     * The "init" method acts like a constructor for the plugin.
     * Usually you'll cache necessary elements and registers the
     * event listeners for your plugin. Additionally you can switch
     * up the behavior of the plugin based on the provided configuration.
     */
    init: function() {
        var me = this;
        
        /**
         * Calling the "applyDataAttributes" method the base class
         * automatically reads out the all "data" attributes from
         * the element and overrides the configuration. It's especially
         * useful if you want to configure your plugin using HTML
         * markup instead of providing a configuration object.
         *
         * For example, we call this plugin on the following element:
         *    <div data-activeCls="some-other-class">...</div>
         *
         * ... the "data" attribute will override the "activeCls"
         * property with the value "some-other-class".
         */
        me.applyDataAttributes();
        
        /**
         * Now we're setting up a new event listener for the plugin
         * using the built-in "_on" method which is actually a proxy
         * method for jQuery's "on" method with some additional benefits.
         * The event listener and the event will be registered in a
         * plugin specific event collection. The collection will be 
         * automatically iterated and removes the registered event listeners
         * from the element.
         * Additionally, the event name will be namespaced on the fly which
         * provides us with a safe way to remove a specific event listener from
         * an element and doesn't affect other plugins which are listening on
         * the same event. 
         */
        me._on(me.$el, 'click', function(event) {
            event.preventDefault();
            
            /**
             * In the condition we're using the custom expression of the plugin
             * to terminate if the element uses our plugin.
             * Additionally you see that we're using the variable "this.$el" which
             * is the element that has instanciated the plugin.
             */
            if(me.$el.is('plugin-example')) {
                
                /**
                 * Now we're accessing the merged configuration of the plugin using
                 * the variable "this.opts".
                 */
                me.$el.toggleClass(me.opts.activeCls);
            }
        });
    },
    
    /**
     * The destroy method can either be called programmically from outside the plugin
     * or automatically using the "StateManager" when the defined states are left.
     * Usually, you remove classes which were added by your plugin to the element and
     * removes the event listeners from the element.
     */
    destroy: function() {
        var me = this;
    
        me.$el.removeClass(me.opts.activeCls);
        
        /**
         * Calling the "_destroy" method will remove all event listeners which were
         * registered using the "_on" method of the plugin base.
         * You can access the collection of the events in the plugin using the variable
         * "this._events" if you want to iterate over the event listeners yourself.
         */
        me._destroy();
    }
});
```
*Fully commented jQuery plugin using the base class.*

### Class properties
* ```_name : String```
    * Name of the plugin. 
* ```$el : jQuery```
    * The HTMLElement which instanciated the plugin as a jQuery object.
* ```opts : Object```
    * Result of the default configuration and the provided user configuration. Keep in mind that calling the ```this.applyDataAttributes()``` method overrides the property values in the object.
* ```_events : Array```
    * Collection, which contains all registered event listener which are added using the ```_on``` method.
    
### Class methods
* ```init()```
    * Template method which acts as the constructor of the plugin where you can cache the necessary HTML elements and set up the event listeners.
* ```destroy()```
    * Template method which destroys the plugin. Usually, you remove classes and event listeners which you've added to the element. The method should be implemented in your plugin, especially when you plan to provide the plugin functionality only for certain states.
* ```update()```
    * Template method which will be called when a certain state was entered / left to update the behavior of the plugin. This method is only necessary when you use the StateManager to instanciate the plugin.
* ```_destroy()```
    * Private method which iterates over the registered event listeners in the ```_events``` property of the plugin. Additionally, the method removes the in-memory binding of the plugin to the element using jQuery's ```removeData()``` method and fires an event on the globally available observer.
* ```_on()```
    * **Arguments**
        * ```element : jQuery | HTMLElement``` - The event target for the specified event listener.
        * ```event : String``` - A string representing the event type to listen for.
        * ```fn : Function``` - The object that receives a notification when an event of the specified type occurs.
    * Proxy method for jQuery's ```on()``` method which binds an event listener to the provided element and registers the listener in the ```_events``` event collection.
* ```_off()```
    * **Arguments**
        * ```element : jQuery | HTMLElement``` - The event target which has an event listener
        * ```event : String``` - One or more space-separated event types and optional namespaces, or just namespaces, such as "click" or "keydown.myPlugin"
* ```getName()```
    * Getter method for the plugin name.
* ```getEventName()```
    * **Arguments**
        * ```event : String | Array``` - One or more space-separated event types
    * Applies the event namespace to the provided event types.
* ```getElement()```
    * Getter method for the element which instanciate the plugin.
* ```getOptions()```
    * Getter method for the merged configuration object.
* ```getOption()```
    * **Arguments**
        * ```key : String``` - Key of the configuration property
    * Getter method for a certain configuration property
* ```setOption()```
    * **Arguments**
        * ```key : String``` - Key of the configuration property
        * ```value : Mixed``` - Value for the provided key
    * Setter method which overrides the value of the provided key with the provided value.
* ```applyDataAttributes()```
    * **Arguments**
        * ```[shouldDeserialize : Boolean = undefined]``` - Tries to parse the 
        given string values and returns the right value if its successful. 
        Supports boolean, null, number, json, string. This feature is enabled by default.
        Pass `false` to deactivate parsing.
        * ```[ignoreList : Array = []]``` - A list of options which will be excluded
         when applying the data attributes to the corresponding options. Introduced with Shopware 5.3.7
    * Fetches the provided configuration keys and overrides the values based on
        the elements ```data``` attributes. Hint: You don't need to convert
        ([camel|pascal](https://en.wikipedia.org/wiki/Letter_case#Special_case_styles))-case 
        java script variable names to ([dash|hyphend|kebab](https://en.wikipedia.org/wiki/Letter_case#Special_case_styles))-case 
        html attribute names.
    
## Global jQuery event observer

We've added a global event observer into Shopware 5 as well. It provides us with the ability to define events globally in the jQuery object and therefor every plugin can listen to these events:

```javascript
// Register a new event
$.publish('plugin/some-plugin/onInit', me);

// Listen for an event
$.subscribe('plugin/some-plugin/onInit', function() {
    console.log('onInit');
})

// Remove an event listener
$.unsubscribe('plugin/some-plugin/onInit');
```

Please keep in mind to register your event listeners with a namespace, otherwise you'll remove all subscribed event listeners for the certain event type.

```javascript
$.subscribe('plugin/some-plugin/onInit.my-plugin', function() {});

// Remove an event listener
$.unsubscribe('plugin/some-plugin/onInit.my-plugin');
```


## The StateManager
The StateManager helps you master different behaviors for different screen sizes.
It provides you with the ability to register different states that are handled
by breakpoints.

Those breakpoints are defined by entering and exiting points (in pixels)
based on the viewport width.
By entering the breakpoint range, the ```enter()``` functions of the registered
listeners are called.
When the defined points are reached, the registered ```exit()``` listener
functions will be called.

This way you can register callbacks that will be called on entering / exiting the defined state.

The StateManager provides you with multiple helper methods and polyfills which help you
master responsive design.

## Using the StateManager
The StateManager is self-containing and globally available in the global javascript scope in the storefront.

It's initialized with the following breakpoints:

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

### Adding an event listener
Registering or removing an event listener which uses the StateManager, is as easy as doing it in pure javascript.

The following example shows how to register an event listener:

```javascript
StateManager.registerListener([{
    state: 'xs',
    enter: function() { console.log('onEnter'); },
    exit: function() { console.log('onExit'); }
}]);
```

The registration of event listeners also supports wildcards, so the ```enter()``` and ```exit()``` methods are called by every change of the breakpoint:

```javascript
StateManager.registerListener([{
    state: '*',
    enter: function() { console.log('onEnter'); },
    exit: function() { console.log('onExit'); }
}]);
```


### Register additional breakpoints
The default breakpoints can be extended using the ```registerBreakpoint()``` method of the StateManager.  
**Note:** Breakpoint ranges are not allowed to overlap with other existing ranges.

```javascript
StateManager.registerBreakpoint({
    state: 'xxl',
    enter: 78.75,  // = 1260px
    exit: 90      // = 1440px
});
```

### Class properties
* ```EventEmitter```
    * Class constructor for an [`EventEmitter`](#the-eventemitter)

### Class methods
* ```init()```
    * **Arguments**
        * ```breakpoints : Array | Object``` - The states, which should be available on start up
    * Initializes the StateManager and registers the provided breakpoints, adds a browser specific class to the ```html``` element and sets a device specific cookie.
* ```registerBreakpoint()```
    * **Arguments**
        * ```breakpoints : Array | Object``` - The states, which should be available on start up
    * Registers an additional breakpoint to the StateManager.
* ```removeBreakpoint()```
    * **Arguments**
        *  ```state : String``` - State which should be removed, e.g. "xs" or "l"
    * Removes the provided state from the StateManager.
* ```registerListener()```
    * **Arguments**
        * ```listener : Array | Object``` - Either a single listener object or an array with multiple listener objects
    * Registers an event listener to the StateManager. The listener will be fired when the provided state is entered or left.
* ```addPlugin()```
    * **Arguments**
        * ```selector : String | HTMLElement | jQuery``` - Element selector
        * ```pluginName : String``` - Name of the plugin which should be added to the selector.
        * ```config : Object (optional)``` - Custom configuration for the plugin. Can be omitted.
        * ```viewport: Array | String``` - The states where the plugin should be active.
    * Registers a jQuery plugin to the StateManager. This functionality is especially useful when you want to provide certain behavior only for specific states.
* ```removePlugin()```
    * **Arguments**
        * ```selector : String | HTMLElement | jQuery``` - Element selector
        * ```pluginName : String``` - Name of the plugin which should be removed from the selector.
        * ```viewport: Array | String``` - A state where the plugin should be removed.
    * Removes a previously added plugin from an element for a certain state.
* ```updatePlugin()```
    * **Arguments**
        * ```selector : String | HTMLElement | jQuery``` - Element selector
        * ```pluginName : String``` - Name of the plugin which should be updated.
    * Programmatically update a plugin on an element. Usually the StateManager should call the ```update()``` method of the plugin themself. The method calls the ```update()``` method of the plugin.
* ```destroyPlugin()```
    * **Arguments**
        * ```selector : String | HTMLElement | jQuery``` - Element selector
        * ```pluginName : String``` - Name of the plugin which should be destroyed.
    * The method that removes the plugin from the StateManager. Unlike the ```removePlugin()``` method, the method calls the ```destroy()``` method of the provided plugin.
* ```getViewportWidth()```
    * Getter method which returns the current width of browser window.
* ```getViewportHeight()```
    * Getter method which returns the current height of browser window.
* ```getPreviousState()```
    * Returns the previous state. This can be either a ```String``` or ```null``` when no previous state was active.
* ```isPreviousState()```
    * **Arguments**
        * ```state : String``` - State which should be checked, e.g. "xs" or "l"
    * Determine if the argument passed was the previous active state.
* ```getCurrentState()```
    * Getter method which returns the currently active state.
* ```isCurrentState()```
    * **Arguments**
        * ```state : String``` - State which should be checked, e.g. "xs" or "l"
    * Determine if the argument passed is the currently active state.
* ```isPortraitMode()```
    * Determine if the device is in portrait mode.
* ```isLandscapeMode()```
    * Determine if the device is in landscape mode.
* ```getDevicePixelRatio()```
    * Determine the pixel device ratio of the device.
* ```isBrowser()```
    * **Arguments**
        * ```browser : String``` - Browser name to test, e.g. "firefox" or "safari"
    * Determines if the argument passed is the current browser of the user.
* ```getScrollBarHeight()```
    * Returns the default scrollbar width of the browser.
* ```matchMedia()```
    * ```matchMedia``` polyfill, which provides the ability to test CSS media queries in javascript.
* ```requestAnimationFrame()```
    * ```requestAnimationFrame``` polyfill for cross-browser support
* ```cancelAnimationFrame()```
    * ```cancelAnimationFrame``` polyfill for cross-browser support
* ```getVendorProperty()```
    * **Arguments**
        * ```property : String``` - The property which needs the vendor prefix
        * ```softError : Boolean``` - Truthy to return the provided property when no vendor was found, otherwise the method returns ```null```
    * Tests the provided CSS style property on an empty div with all vendor properties.

## The EventEmitter
The EventEmitter is a utility class, offering a simple base-class for event driven architecture. Currently the EventEmitter is used as the basis for the StateManager, but you can use it to let your own objects emit events.

An object which inherits from the EventEmitter exposes the functionality of subscribing and listening to events. Much like the [global event observer](#global-jquery-event-observer).

### Class methods
* ```on()```
    * **Arguments**
        * ```eventName : String``` - The name of the event which to listen to
        * ```callback : Function``` - The function which should be called, when the event is triggered
        * ```context : Any``` - An optional context, which to bind the callback to. e.g. `this`
    * Add an event listener
    * Chainable
* ```once()```
    * **Arguments**
        * ```eventName : String``` - The name of the event which to listen to
        * ```callback : Function``` - The function which should be called, when the event is triggered
        * ```context : Any``` - An optional context, which to bind the callback to. e.g. `this`
    * Behaves exactly like on, expect that the listener gets removed after the callback has been called once
    * Chainable
* ```off()```
    * **Arguments**
        * ```eventName : String``` - Optional, the name used for subscribing
        * ```callback : Function``` - Optional, the callback used for subscribing
        * ```context : Any``` - Optional, the context used for subscribing
    * Removes an event listener. It tries to find the listener you added by the parameters you've given. E.g. if there's a listener matching the `eventName` and the `callback` you've given, but not the `context` (if you've given one) the listener won't be removed. If you only supply the eventName, all listeners of that event will be removed. If you supply no parameters all event listeners will be removed.
    * Chainable
* ```trigger()```
    * **Arguments**
        * ```eventName : String``` - The name of the event to trigger
    * Triggers an event.
    * Chainable
* ```destroy()```
    * Can be called to clean up.
    * Chainable
    


## Working with stateful jQuery plugins
The combination of the StateManager paired with the jQuery plugin base class provides an easy-to-use way to register jQuery plugins for certain state. That provides us with the ability to provide different behavior for components based on the current active state. For example the Offcanvas menu plugin is only active on mobile devices (states "xs" and "s") and is disabled on tablets and desktop pc's.

The StateManager is available in the global javascript scope of the storefront. To register your plugin, simply call the addPlugin() method of the StateManager.

In the following example we register our own jQuery plugin for the XS and S states. The name of the plugin is "myPlugin" and we will bind it to the HTML DOM nodes which have the class .my-selector:

```javascript
StateManager.addPlugin('.my-selector', 'myPlugin', [ 'xs', 's' ]);
```

### Passing user configuration to the jQuery plugin
It's also possible to pass user configuration options to the plugin, which will be merged with the plugin's default configuration. The merged configuration is accessible using the this.opts object in your plugin.

```javascript
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
Or pass the configuration via html `data` attributes and call the `applyDataAttributes()` method in our `init()` method. 

```html
<div class="my-selector" data-myStringVar="myOverwrittenValue" myBooleanVar=true></div>
```

```javascript
$.plugin('myPlugin', {
  defaults: {
        myStringVar: 'myStandardValue',
        myBooleanVar: false
    },
    init: function() {
        var me = this;
        me.applyDataAttributes();
    }
}); 
```

If you need to pass modified configuration to your plugin for a specific viewport, you can use the following pattern:

```javascript
StateManager.addPlugin('.my-selector', 'myPlugin', {
    'speed': 300
}).addPlugin('.my-selector', 'myPlugin', {
    'speed': 2000
}, 's');
```


## Adding javascript files to your theme
Working with compressors isn't always as easy as adding the files to your HTML structure using ```script``` tags. The built-in javascript compressor is just as easy as this and perfectly suited to your workflow as a web developer.

Simply place your javascript files in the ```frontend/_public``` directory and add their paths to the ```$javascript``` array in your ```Theme.php``` and you're good to go.

```php
/** @var array Defines the files which should be compiled by the javascript compressor */
protected $javascript = array(
    'src/js/jquery.my-plugin.js'
);
```
