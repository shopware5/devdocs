---
layout: default
title: Legacy template development in Shopware 5
github_link: designers-guide/legacy-template-development-in-shopware-5/index.md
indexed: true
---
## Introduction

Shopware 5 includes the new, ready to use Responsive theme, as well as the Bare theme that you can use as a starting point for your custom themes. While we recommend using these themes from now on, your existing Shopware 4 templates are still supported by Shopware 5.

Keep in mind that your custom themes still need to be tested with Shopware 5 in a test environment, before being deployed into a production environment. Please read the [Shopware 5 Upgrade guide for developers](/developers-guide/shopware-5-upgrade-guide-for-developers/) to know more about what changed in Shopware 5.

Additionally, although they are still supported, **Shopware 4 templates support in Shopware 5 is a deprecated feature**, and may be removed in future updates.

## Shopware 4 template installation and configuration

As before, there are two ways to add templates to Shopware:

* As part of a plugin
* Placing them in the `templates` folder on the root of your Shopware installation

If you are using a clean installation of Shopware 5, you need to manually create the `templates` folder if you want to use the second option. The plugin approach works exactly like before.

As the new Shopware 5 Responsive theme has different requirements when compared with Shopware 4 templates, you might need to reconfigure some of your shop's settings in order for your template to work properly. This is applicable only if:

* You are installing a Shopware 4 template on a clean Shopware 5 installation (no upgrade from Shopware 4)
* You upgraded from Shopware 4 to 5 and changed your shop settings to support the new Responsive theme, like described in the [Shopware 5 update guide](/update-guide/)

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
