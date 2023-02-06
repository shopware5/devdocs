---
title: Quick Tip - Custom fonts in the TinyMCE editor
tags:
- tinymce
- custom-fonts
- shopware-plugin

categories:
- dev

authors: [stp]
github_link: blog/_posts/2017-08-09-shopping-custom-fonts-tinymce.md

---

![Screenshot Storefront](/blog/img/custom-font-example.png)

*Custom font example in the Shopware storefront*

Lately we're getting more & more requests on how to register / add custom fonts to the TinyMCE WYSIWYG editor in the Shopware administration.
The editor is used throughout every Shopware module where you can insert HTML text, therefore it would be handy to have your own fonts in there.

We've created an open source Shopware plugin called `SwagTinyMceCustomFont` which covers your needs.
It allows you to integrate custom fonts from [Google Fonts](https://fonts.google.com/).
The plugin automatically loads the font in the administration interface as well as in the storefront of your shop.

You can find the plugin as well as the feature overview, installation guide and usage example [on GitHub](https://github.com/shopware5/SwagTinyMceCustomFont).
