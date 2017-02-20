---
layout: default
title: Plugin License
github_link: developers-guide/plugin-license/index.md
indexed: true
group: Developer Guides
subgroup: Developing plugins
menu_title: Plugin License
menu_order: 90
---

<div class="toc-list"></div>

## Overview

Should you wish to, you can have your plugin run a license check when used in a Shopware shop. This will ensure that the target shop has permission to use your plugin. This document covers the steps necessary to implement this check.

## Requesting the license check

To request a license check, you must first login into your [Shopware account](http://account.shopware.com) and request a license validation in the detail page of your plugin. Once you have done so, you will be given a code snippet similar to this:

```
public function checkLicense($throwException = true)
{
    static $r, $m = 'SwagMyPlugin';

    // ...
    // Validation logic
    // ...

    if ($throwException) {
        throw new \Exception('License check for module "' . $m . '" has failed.');
    }

    return $r;
}
```

This method validates if the current shop has a valid license for your plugin. It should be placed inside your plugin's Bootstrap class, and used whenever you want to ensure that the current shop has a valid license for your plugin (for example, during plugin installation).

## Executing the license check

Now that your plugin includes a `checkLicense` method, you must make sure you use it. **The license check is not called automatically, you must do so explicitly.** Typical places where you should call the `checkLicense` method are the `install` and `update` methods, to ensure the shop can only perform those actions if it has a valid license for your plugin.

The checkLicense has an optional `throwException` parameter which, when set to false, will prevent a license check fail from throwing an exception. As you might have noticed above, this variable's value defaults to true. It's usually recommended to throw exception only in backend actions (plugin install or update, or during backend controller execution). Doing so in the frontend might cripple the customer facing end of the shop, which is why we recommend not throwing an exception in this case.

## Example

```
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
        // If no license is available, an exception is thrown
        $this->checkLicense();

        return true;
    }


    public function install()
    {
        // If no license is available, an exception is thrown
        $this->checkLicense();

        $this->subscribeEvent('Enlight_Controller_Dispatcher_ControllerPath_Backend_MyPlugin', 'onGetControllerPath');
        $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Frontend_Listing', 'onPostDispatchFrontendListing');

        // ...

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
        // If no license is available, the method gracefully exits
        if (!$this->checkLicense(false)) {
            return;
        }

        /** @var $action \Enlight_Controller_Action */
        $action = $args->getSubject();
        $view = $action->View();

        // ...
    }

    public function checkLicense($throwException = true)
    {
        // license check code available on your Shopware account
    }
}
```

In this example, you can see that the `checkLicense` method is called during installation and update. In the `onGetControllerPath` method, it is being called without an argument, meaning it will throw an exception in case the validation fails. This would display a visible, informative message to the shop owner, warning him about this failure.

In the `onPostDispatchFrontendListing` method, the validation will not throw an exception, but instead make the method prematurely return, causing the plugin to not perform its expected action, but also not informing the frontend user of this validation issue.

## Custom validation

While you are free to customize the `checkLicense` method, we highly recommend that you don't. Should you require any additional checks besides the license check provided by us, you should create a wrapper method to implement your own logic

```
<?php

final class Shopware_Plugins_Frontend_SwagMyPlugin_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->checkShop();

        // ...

        return true;
    }

    public function checkShop($throwException = true)
    {
        if ($this->checkIfDependenciesAreMet($throwException)) {
            return $this->checkLicense($throwException);
        }
    }

    public function checkIfDependenciesAreMet($throwException = true)
    {
        // For example, check if a certain dependency is met
    }

    public function checkLicense($throwException = true)
    {
        // license check code available on your Shopware account
    }
}
```
