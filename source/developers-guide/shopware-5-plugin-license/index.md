---
layout: default
title: Developer's Guide
github_link: developers-guide/shopware-5-plugin-license/index.html
---

## Overview

If you have ordered the Shopware license check for your plugin, you will find the automatically generated code snippet in your account, that you need to include in your plugin's Bootstrap.php.
This document describes the basic procedure.



## The Snippet

The generated code-snippet usually looks like this:

```php
    public function checkLicense($throwException = true)
    {
        static $r, $m = 'SwagMyPlugin';
        if (!CHECK) {
        	DIFFERENT
        	LINES
        	LICENSE-CHECK
        }
        if ($throwException) {
            throw new \Exception('License check for module "' . $m . '" has failed.');
        }

        return $r;
    }
```

You may implement this method anywhere in you Bootstrap-class in order to be able to execute checkLicense inside your bootstrap to check if there is a valid license for your plugin.

## Executing the checkLicense

The checkLicense will not be executed automatically - you have to do this manually. If you do not call this function, your plugin will not be secured via the a license check!
The checkLicense has an optional parameter called "throwException" which is true by default. Is throwException set to true, a failed license check will
throw an exception which cancels the execution of the request. If *"throwException"* is set to false the method simply returns a boolean which indicates whether the license check was successful or not.
Usually exceptions should be thrown during *installation*, *update* or when executing backend operations, to confront the user about the problem. Never throw exceptions in the frontend because this may
cripple your shop.

## Example

```php
<?php

final class Shopware_Plugins_Frontend_SwagMyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getLabel()
    {
        return 'My Plugin';
    }

    public function getInfo()
    {
        return array(
            'version' => $this->getVersion(),
            'label' => $this->getLabel()
        );
    }

    public function update($oldVersion)
    {
        // an exception occurs, if no license is available
        $this->checkLicense();

        return true;
    }


    public function install()
    {
        // an exception occurs, if no license is available
        $this->checkLicense();

        $this->subscribeEvent('Enlight_Controller_Dispatcher_ControllerPath_Backend_MyPlugin', 'onGetControllerPath');
        $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Frontend_Listing', 'onPostDispatchFrontendListing');

        … more custom install logic…

        return true;
    }

    public function onGetControllerPath(\Enlight_Event_EventArgs $args)
    {
        // an exception occurs, if no license is available
        // this is ok for e.g. backend controllers
        $this->checkLicense();
        return __DIR__ . '/Controllers/Backend/MyPlugin.php';
    }

    public function onPostDispatchFrontendListing(\Enlight_Event_EventArgs $args)
    {
        // don't throw an exception here - just don't load the plugin's extension silently
        if (!$this->checkLicense(false)) {
            return;
        }

        /** @var $action \Enlight_Controller_Action */
        $action = $args->getSubject();
        $view = $action->View();

        … your template extension…
	}

    public function checkLicense($throwException = true)
    {
        …your custom license check…
    }


}
```

In this example you can see, that *checkLicense* in the methods install, update and *onGetControllerPath* is being called without a parameter, that means, it would throw exceptions. During Installation, Update
and when executing the backend controller, the user would be confronted with the exception, if no valid license was found on the system. In the method *onPostDispatchFrontendListing* the following call
will be used:


```php
if (!$this->checkLicense(false)) {
    return;
}
```
In this case, no exception message will be thrown, so that the regular shopping behaviour is still available. Instead, we jump out of the method with *"return"*

## Summary and best practice

* Copy *checkLicense* from your shopware account into the plugin's bootstrap-class
* Calling the *exception mode* during installation, update and when calling a backend controller
* In the frontend *checkLicense* should be called with the parameter *false*. Instead make sure that the plugin's logic will not be executed.
* Bootstrap-classes usally should be declared as *final* (final class Shopware_Plugins_Frontend_SwagMyPlugin_Bootstrap)