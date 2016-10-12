---
layout: default
title: Legacy template development in Shopware 5
github_link: designers-guide/legacy-template-development-in-shopware-5/index.md
indexed: true
group: Frontend Guides
subgroup: General Resources
menu_title: Legacy template development
menu_order: 120
---

<div class="toc-list"></div>

## Introduction

Shopware 5 includes the new, ready to use Responsive theme, as well as the Bare theme that you can use as a starting point for your custom themes. While we recommend using these themes from now on, your existing Shopware 4 templates are still supported by Shopware 5.

<div class="alert alert-info" role="alert">
<strong>Note:</strong> In Shopware 5 we improved the theme inheritance system to let themes extend each other. This gives you the possibility to create your own theme inheritance structure. If you want to use the new Shopware 5, themes you can find them in the <strong>themes</strong> directory. As mentioned before, we still support the Shopware 4 templates, so you can still find the older Shopware 4 templates in the <strong>templates</strong> directory. In the root of your Shopware 5 installation you can find both directories:
    <ul>
    <li><code>themes</code> directory: Contains the new Shopware 5 themes</li>
    <li><code>templates</code> directory: Contains the older Shopware 4 and below templates</li>
    </ul>
</div>

Keep in mind that your custom themes still need to be tested with Shopware 5 in a test environment, before being deployed into a production environment. Please read the [Shopware 5 Upgrade guide for developers](/developers-guide/shopware-5-upgrade-guide-for-developers/) to know more about what changed in Shopware 5.

Additionally, although they are still supported, **Shopware 4 templates support in Shopware 5 is a deprecated feature, and will be removed in Shopware 5.2.**

## Shopware 4 template installation and configuration

As before, there are two ways to add templates to Shopware:

* As part of a plugin
* Placing them in the `templates` directory on the root of your Shopware installation

If you don't have a `templates` directory in your clean Shopware 5 installation you can download a newer version of Shopware 5 (at least RC 3) where the Shopware 4 template is included.

As the new Shopware 5 Responsive theme has different requirements when compared with Shopware 4 templates, you might need to reconfigure some of your shop's settings in order for your template to work properly. This is applicable only if:

* You are using a Shopware 4 template on a clean Shopware 5 installation (no upgrade from Shopware 4)
* You upgraded from Shopware 4 to 5 and changed your shop settings to support the new Responsive theme, like described in the [Shopware 5 update guide](/developers-guide/shopware-5-upgrade-guide-for-developers//)

## Shopware 4 template settings

In order for your Shopware 4 templates to work properly in Shopware 5, you will need to perform the following changes:

- For each of the following albums, set the following thumbnail sizes. Keep in mind that any other custom thumbnail sizes you might want to have should appear **after** these, not before:

Album          | Thumbnail sizes
-------------- | ---------------------------------------------
Artikel        | 30x30 57x57 105x105 140x140 285x255 720x600
Blog           | 57x57 140x140 285x255 720x600

- Regenerate the thumbnails. We recommend using the `sw:thumbnail:generate` CLI command for this.
- Check your emotion world pages, particularly those depending on thumbnails from the above albums.

As you changed the article thumbnails settings, other parts of your system might also need to be reconfigured to function properly:
- Product export feeds
- Email templates, header and footer

Notice that these changes will cause the Shopware 5 Responsive theme (and themes based on it or on the Base theme) to incorrectly display images. You need to revert the above changes if you wish to change to the Shopware 5 Responsive theme.
