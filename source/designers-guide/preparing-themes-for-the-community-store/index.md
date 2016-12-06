---
layout: default
title: Preparing themes for the community store
github_link: designers-guide/preparing-themes-for-the-community-store/index.md
indexed: true
group: Frontend Guides
subgroup: Developing Themes
menu_title: Preparing themes for the community store
menu_order: 90
---

<div class="toc-list"></div>

## Introduction
This guide explains how to prepare your custom themes, wrap them in plugins that can be installed with the Shopware plugin manager and make them ready to be downloaded or purchased from the [Shopware Community Store](http://store.shopware.com/en/).

To publish your plugins in the Shopware Community Store, you need to register your own developer prefix in your [Shopware account](https://account.shopware.com). The result of the theme that was created in the [getting-started](../getting-started/) guide will be used as an example of this tutorial.

![community store image](img-store.jpg)

## Plugin structure
The plugin directory has to have a specific structure in order to work inside Shopware 5. The plugin directory name has to match the Shopware plugin naming pattern, which consists of the `developer prefix` and the `plugin name`. In this example the plugin directory name is "SwagTutorialTheme" (`Swag` as the prefix for shopware AG, `TutorialTheme` as the plugin name).

```
[developer prefix][plugin name]
```

Themes in the Shopware Community Store have to be wrapped inside plugins in order to be installable with the plugin manager. The plugin requires a `Bootstrap.php` file in the root directory and the custom theme, that was previously created. It has to be located inside the `Themes/Frontend` directory (just as it would be inside the normal Shopware installation).

**Attention: the directory and file names are case sensitive.**


##### Plugin directory
```
SwagTutorialTheme
 ├── Themes
 │    ├──Frontend
 │    │   ├──TutorialTheme
 │    │   │    ├── preview.png
 │    │   │    ├── Theme.php
 │    │   │    └── frontend
 └── Bootstrap.php
```

## Creating the plugin

The only requirements the `Bootstrap.php` file has, in this case, are the plugin label (name that is displayed in the plugin manager later on) and the version number. So, with that in mind, you would create the file and add the 2 required functions to it. Because the `TutorialTheme` directory is located inside the `Themes/Frontend`, the plugin automatically detects its content.

##### Bootstrap.php
```php
<?php
class Shopware_Plugins_Frontend_SwagTutorialTheme_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**Returns a marketing friendly name of the plugin.*/
    public function getLabel()
    {
        return 'Your custom theme as a plugin';
    }

    /**Returns the version of the plugin.*/
    public function getVersion()
    {
        return '1.0.0';
    }
}
```

The plugin should now be displayed inside the Shopware plugin manager, where it can be installed. Once the plugin is enabled, the theme will be available and selectable in the theme manager, along with all other existing themes. If this is the case, the plugin is ready to be published in the Shopware Community Store.
![Inside the plugin manager](img-pm.jpg)

## Result
The example plugin as download:

+   [Final store-ready plugin - Download](SwagTutorialTheme.zip)
