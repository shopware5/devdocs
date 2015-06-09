---
layout: default
title: Creating themes with Shopware CLI tools
github_link: designers-guide/creating-themes-with-shopware-cli/index.md
indexed: true
---

You can easily create a new theme from your console by using the Shopware CLI tools. If you would like to have a general introduction in Shopware CLI you can take a look at our [wiki article](http://en.wiki.shopware.com/Shopware-CLI_detail_1653.html). To create a theme with the console you will have to use the `sw:theme:create` command followed by a few necessary parameters:

-    **--description:** Theme description text shown in the backend
-    **--author:** Theme author shown in the backend
-    **--license:** Theme license shown in the backend
-    **parent:** Name of the theme from which you want to inherit
-    **theme:** Name of the server theme directory
-    **name:** Theme name shown in the backend

#### Example ####
With the following command we will create a new theme called "ExampleTheme" which inherits from the Responsive Theme:
```
sw:theme:create --description="Text" --author="shopware AG" --license="MIT" Responsive ExampleThemeFolder ExampleTheme
```
If all parameters are correct you should see this message:
```
Theme "ExampleTheme" has been created successfully.
```
You can find the new theme inside the `themes` directory.