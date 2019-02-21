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

<div class="alert alert-info">

The license check works only for plugins for Shopware versions older than 5.5.0.
If you want your plugin to be compatible with Shopware 5.5, no license check should be implemented.
Read [here](/developers-guide/shopware-5-upgrade-guide-for-developers/#system-requirements-changes) more about that.

</div>

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

This method validates if the current shop has a valid license for your plugin. It should be placed inside your plugin base class, services, subscribers, or whenever you want to ensure that the current shop has a valid license (for example, during plugin installation).

## Executing the license check

Now that your plugin includes a `checkLicense` method, you must make sure you use it. **The license check is not called automatically, you must do so explicitly.** Typical places where you should call the `checkLicense` method are the `install` and `update` methods, to ensure the shop can only perform those actions if it has a valid license for your plugin.

The checkLicense has an optional `throwException` parameter which, when set to false, will prevent a license check fail from throwing an exception. As you might have noticed above, this variable's value defaults to true. It's usually recommended to throw exception only in backend actions (plugin install or update, or during backend controller execution). Doing so in the frontend might cripple the customer facing end of the shop, which is why we recommend not throwing an exception in this case.

## Example

```php
<?php

namespace YourPlugin;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;

class YourPlugin extends Plugin
{
    public function install(InstallContext $installContext)
    {
        $this->checkLicense();

        // ...
    }

    public function update(UpdateContext $updateContext)
    {
        $this->checkLicense();

        // ...
    }

    private function checkLicense($throwException = true)
    {
        // license check code available on your Shopware account
    }
}
```

In this example, you can see that the `checkLicense` method is called during installation and update.
If you use the checkLicense method in the frontend, set the `$ throwException` parameter to false to prevent the validation from throwing an exception, but instead make the method prematurely return, causing the plugin to not perform its expected action, but also not informing the frontend user of this validation issue.

## Custom validation

While you are free to customize the `checkLicense` method, we highly recommend that you don't. Should you require any additional checks besides the license check provided by us, you should create a wrapper method to implement your own logic

```php
<?php

namespace YourPlugin;

class YourPlugin extends Plugin
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
