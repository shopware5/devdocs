---
layout: default
title: How to not use event subscribers
github_link: developers-guide/how-to-not-use-event-subscribers/index.md
indexed: true
tags:
  - plugin
  - less
  - js
  - console
---

This article is about Shopware event subscribers and how they will affect your plugin or theme.

Especially the <a href="{{ site.url }}/designers-guide/best-practice-theme-development/">compilation of less and compression of javascript files via grunt</a> is problematic when using event subscribers. 

In your plugins or themes for Shopware you typically want to include your own stylesheets and javascript files.

Thanks to our event system, this is an easy task. As of now, all you have to do is subscribe to one or both of the following events:

- `Theme_Compiler_Collect_Plugin_Less`
- `Theme_Compiler_Collect_Plugin_Javascript`

However, it is important how you are actually subscribing to these events.   
You could do this either inside the `install()` method of your plugins `Bootstrap.php` file or inside an event subscriber.

When you choose to subscribe to events via event subscribers, please be aware this will have potentially unwanted side effects.
 
Event subscribers are always added on later events like `Enlight_Controller_Front_StartDispatch` or similar.
That means: If `Enlight_Controller_Front_StartDispatch` is not thrown (e.g. in console commands), your code inside the event subscribers will never be executed.

For example when the compiling of less files is triggered via the console command `sw:theme:dump:configuration`, there is no Frontend Dispatch event.

The only safe place to subscribe for the `Theme_Compiler_Collect_Plugin_Less` and `Theme_Compiler_Collect_Plugin_Javascript` events is inside the `install()` method of your plugins `Bootstrap.php` file.

The same principle of course applies to all event subscribers. If you want to make sure your subscribed method is called in every possible scenario, the subscription has to be directly inside the `install()` method.

**Example of save subscription inside `Bootstrap.php`:**

```php
<?php

class Shopware_Plugins_Frontend_MyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Theme_Compiler_Collect_Plugin_Javascript',
            'onCollectJavascriptFiles'
        );
        
    //...
    }
 //...
}
```

The order in which events are called is important as well. `Enlight_Controller_Front_StartDispatch` is not the first event in Shopware.
So in other scenarios with other events, the events you are subscribing to will be thrown before your event subscribers are executed.
As a result, your code won't be executed. This is because it wasn't registered before the event you subscribed to was thrown.
   
Another example of event subscriptions which will not be executed when registered in later events are:
 
- `Shopware_SearchBundleDBAL_Collect_Facet_Handlers` 
- `Shopware_SearchBundleDBAL_Collect_Sorting_Handlers`
- `Shopware_SearchBundleDBAL_Collect_Condition_Handlers`

These events are thrown before the front start dispatch. So the only safe place for subscribing to them is again the `install()` method in your `Bootstrap.php` file.

## Summary

Although event subscribers seem to be a good method to organize your code, only use them if you are aware of possible side effects.
 Since you are always registering your events after some other event and not on plugin initialization when using event subscribers, always make sure the order doesn't lead to problems.
 
When in doubt, always register your event directly inside the `install()` method of your `Bootstrap.php` file. 