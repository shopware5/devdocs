---
layout: default
title: How to safely deal with less and js collection
github_link: developers-guide/safely-deal-with-less-and-js-collection/index.md
indexed: true
tags:
  - plugin
  - less
  - js
  - console
---

## Introduction

This article is about good practices and what happens if you don't follow them.   
In particular it deals with the collection and compilation of `.less` and `.js` files in plugins.

So let's start with how less and javascript (js) are handled in shopware.
<a href="http://lesscss.org/">Less</a> is covered in detail in "<a href="{{ site.url }}/designers-guide/less/">Getting started with LESS</a>".   
In short, less is a CSS pre-processor:   
Shopware picks up your less file, converts them together with the less files from other plugins etc. to CSS and includes the resulting CSS in the storefront.
Similar to that, js files are collected, minified, combined and included in the storefront.

This approach makes sure that multiple plugins used in a store do not include individual CSS files each. It's imaginable this would slow down the store, as each plugin would require extra requests to the server for it's css and js.

The catch is, Shopware has to know about the less and js files it needs to include.   
Shopware is a very dynamic piece of software and there usually are multiple ways to achieve something.
This is true as well when it comes to less and js inclusion, however only *one way* will yield the desired result in *every* case.

## The root of the matter

To let Shopware know about your files, you simply subscribe to an event. Well, in fact two events, one for javascript and one for less files:

- `Theme_Compiler_Collect_Plugin_Less`
- `Theme_Compiler_Collect_Plugin_Javascript`
 
Easy enough. The trick question is **where** to subcribe to these Events in your code. Short answer: *Only* in the install() method of your `Bootstrap.php`.

Why? Again, in short: Because that's the only way your less files are included no matter what.

Now for the extended answers.

## How to do it

```
<?php

class Shopware_Plugins_Frontend_MyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent(
            'Theme_Compiler_Collect_Plugin_Javascript',
            'onCollectJavascriptFiles'
        );
        
        $this->subscribeEvent(
            'Theme_Compiler_Collect_Plugin_Less',
            'onCollectLessFiles'
        );
        
        return true;
    }

    public function onCollectJavascriptFiles(\Enlight_Event_EventArgs $args)
    {
        // Not scope of this article
    }

    public function onCollectLessFiles(\Enlight_Event_EventArgs $args)
    {
        // Not scope of this article
    }
}
```
What is important here?

**`subscribeEvent` must be called in the `install()` method of your `Bootstrap.php` for _less_ and _js_ files!**

The rest is pretty much up to you.
 
## How not to do it

Just for the record: **Don't do this.**

```
<?php

class Shopware_Plugins_Frontend_MyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        // No problem so far...
        $this->subscribeEvent(
             'Enlight_Controller_Front_StartDispatch',
             'onStartDispatch'
        );
        
        return true;
    }

    public function onStartDispatch(Enlight_Event_EventArgs $args)
    {
        // Everything good here!
        $this->registerMyTemplateDir();
        $this->registerMyComponents();
        // ...
    
        $subscribers = array(
            new \Shopware\MyPlugin\Subscriber\ControllerPath(), // Everything good
            new \Shopware\MyPlugin\Subscriber\Container(), // Fine, go on!
            
            new \Shopware\MyPlugin\Subscriber\LessAndJsFiles(), // DON'T DO THIS! This is bad!

        );
    
        foreach ($subscribers as $subscriber) {
            $this->Application()->Events()->addSubscriber($subscriber);
        }        
    }
}
```

```
<?php
namespace Shopware\MyPlugin\Subscriber;

class LessAndJsFiles implements \Enlight\Event\SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            // NEVER subscribe to these Events in a "Sub-Subscriber"! Only in Bootstrap::install()
            'Theme_Compiler_Collect_Plugin_Javascript' => 'onCollectJavascriptFiles', // No, don't!
            'Theme_Compiler_Collect_Plugin_Less' => 'onCollectLessFiles', // No, don't!
        );
    }
    
    //....
}
```

## What happens if you do it the wrong way

Although there's nothing wrong with using Event subscribers in general (in fact it's recommended), you have to pay very close attention to a simple, yet often overlooked fact:

If you subscribe to events like `Enlight_Controller_Front_StartDispatch` (or similar), all code in the function you are registering will *never be called* if this event isn't thrown.

Your question at this point may be "_In what obscure scenario would the Front StartDispatch event be omitted?_". The answer is: When using the console.
 
In case you never heard of the shopware console, it can be used to automate various tasks in shopware and lives in `bin/console`, read more about it in the <a href="http://en.community.shopware.com/_detail_1653.html#Introduction">4.2 article about bin/console</a>.
That means, if the theme or plugin files are compiled via `grunt` using `sw:theme:dump:configuration` the storefront potentially looks awful afterwards because your css is missing.
 
## Summary

- Only use the `install()` method in your `Bootstrap.php` to subsribe to:
  - Theme_Compiler_Collect_Plugin_Javascript
  - Theme_Compiler_Collect_Plugin_Less
- Be careful when using "sub-subscribers", since the are only triggered if the main event is thrown, which may not always be the case.