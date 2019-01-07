---
layout: default
title: Preparing themes for the Community Store
github_link: designers-guide/preparing-themes-for-the-community-store/index.md
indexed: true
group: Frontend Guides
subgroup: Developing Themes
menu_title: Preparing themes for the Community Store
menu_order: 90
---

<div class="toc-list"></div>

## Introduction Coding Standards for store plugins
This guide show you the required coding standard of plugins and explains how to gernerally prepare your plugins and make the ready to be downloaded or purchased from the [Shopware Community Store](http://store.shopware.com/en/).

##1. All required meta data had to be defined in the plugin.xml

##### plugin.xml
```xml
<?xml version="1.0" encoding="utf-8"?>
<plugin xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware/shopware/5.2/engine/Shopware/Components/Plugin/schema/plugin.xsd">
    <label lang="de">Mein Plugin-Name</label>
    <label lang="en">My plugin name</label>
    <label>MyPluignName</label>
    <version>1.0.0</version>
    <copyright>(c) by my company ltd.</copyright>
    <license>MIT</license>
    <link>https://my-website.com</link>
    <author>My company ltd.</author>
    <compatibility minVersion="5.2.0"/>
    <changelog version="1.0.0">
        <changes lang="de">Erstveröffentlichung</changes>
        <changes lang="en">First release</changes>
    </changelog>
        
</plugin>
```
##2. Allowed and required standard values to be set in the plugin.xml

###Basic translations*:
<label lang="de">Mein Plugin-Name</label>
<label lang="en">My plugin name</label>

###Version number*:
<version>1.0.0</version>

not allowed:
<version>V1.0.0</version>
<version>V1.0</version>
<version>1.0</version>
<version>1</version>

###Copyright*:
<copyright>(c) by my company ltd.</copyright>

###License:
<license>proprietary</license>

###Link:
<link>https://store.shopware.com</link>

###Author*:
<author>My company ltd.</author>

###Compatibility ?
<compatibility minVersion="5.3.0"/>

not allowed:
<compatibility minVersion="5.3"/>
<compatibility minVersion="5"/>
<compatibility minVersion="5.0.0"/>

###Changelog - version*:
<changelog version="1.0.0">

not allowed:
<changelog version="V1.0.0">
<changelog version="V1.0">
<changelog version="1.0">
<changelog version="1">

###Changelog - changes*
<changes lang="de">Erstveröffentlichung</changes>
<changes lang="en">First release</changes>

##3. Pluign image had to bes set.
The plugin image, which appear in the plugin mananger in the backend of shopware, had to be stored in the root path of the plugin (wherer the plugin.xml appears).

The image dimension is 16px x 16px. The image hd to be stored in png.


## Introduction
This guide explains how to prepare your custom themes, wrap them in plugins that can be installed with the Shopware plugin manager and make them ready to be downloaded or purchased from the [Shopware Community Store](http://store.shopware.com/en/).

To publish your plugins in the Shopware Community Store, you need to register your own developer prefix in your [Shopware account](https://account.shopware.com). The result of the theme that was created in the [getting-started](../getting-started/) guide will be used as an example of this tutorial.

![community store image](img-store.jpg)

## Plugin structure
The plugin directory has to have a specific structure in order to work inside Shopware 5. The plugin directory name has to match the Shopware plugin naming pattern, which consists of the `developer prefix` and the `plugin name`. In this example the plugin directory name is "SwagTutorialTheme" (`Swag` as the prefix for shopware AG, `TutorialTheme` as the plugin name).

```
[developer prefix][plugin name]
```

## 5.2 plugin system

Themes in the Shopware Community Store have to be wrapped inside plugins in order to be installable with the plugin manager. The plugin requires a *plugin base file* and a `plugin.xml` file in the root directory and the custom theme, that was previously created. It has to be located inside the `Resources/Themes/Frontend` directory (just as it would be inside the normal Shopware installation).

**Attention: the directory and file names are case sensitive.**

##### Plugin directory
```
SwagTutorialTheme
 ├── Resources
 │    ├── Themes
 │    │    ├── Frontend
 │    │    │    ├──TutorialTheme
 │    │    │    │    ├── preview.png
 │    │    │    │    ├── Theme.php
 │    │    │    │    └── frontend
 ├── SwagTutorialTheme.php
 └── plugin.xml
```

### Creating the plugin

Just create a plain *plugin base file*. Because the `TutorialTheme` directory is located inside the `Resources/Themes/Frontend`, the plugin automatically detects its content.

##### SwagTutorialTheme.php - *the plugin base file*
```php
<?php
namespace SwagTutorialTheme;

use Shopware\Components\Plugin;

class SwagTutorialTheme extends Plugin
{
}
```

You need to create a `plugin.xml` file in the same directory, which defines the meta data of your plugin, i.e. label, version, compatibility or the changelog.

##### plugin.xml
```xml
<?xml version="1.0" encoding="utf-8"?>
<plugin xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/shopware/shopware/5.2/engine/Shopware/Components/Plugin/schema/plugin.xsd">
    <label lang="de">Swag Tutorial Theme</label>
    <label>Swag tutorial theme</label>
    <version>1.0.0</version>
    <copyright>(c) by shopware AG</copyright>
    <license>MIT</license>
    <link>http://store.shopware.com</link>
    <author>shopware AG</author>
    <compatibility minVersion="5.2.0"/>
</plugin>
```

The plugin should now be displayed inside the Shopware plugin manager, where it can be installed. Once the plugin is activated, the theme will be available and selectable in the theme manager, along with all other existing themes. If this is the case, the plugin is ready to be published in the Shopware Community Store.
![Inside the plugin manager](img-pm.jpg)

### Result
The example plugin as download:

+   [Final store ready plugin - Download](/exampleplugins/SwagTutorialTheme.zip)


## Legacy plugin system

Themes in the Shopware Community Store have to be wrapped inside plugins in order to be installable with the plugin manager. The plugin requires a `Bootstrap.php` file in the root directory and the custom theme, that was previously created. It has to be located inside the `Themes/Frontend` directory (just as it would be inside the normal Shopware installation).

**Attention: the directory and file names are case sensitive.**

##### Plugin directory
```
SwagTutorialTheme
 ├── Themes
 │    ├── Frontend
 │    │    ├──TutorialTheme
 │    │    │    ├── preview.png
 │    │    │    ├── Theme.php
 │    │    │    └── frontend
 └── Bootstrap.php
```

### Creating the plugin

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

### Result
The example plugin as download:

+   [Legacy plugin system plugin - Download](/exampleplugins/SwagLegacyTutorialTheme.zip)
