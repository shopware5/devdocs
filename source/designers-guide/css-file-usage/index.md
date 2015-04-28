---
layout: default
title: Using CSS files in themes.
github_link: designers-guide/css-file-usage/index.md
indexed: true
---

This quick tip explains the best way to use CSS files for your custom themes, in order to enable them to be automatically compressed by the Shopware theme compiler. To use this feature you have to place your css files inside your theme directory under the subfolders `frontend/_public`. This would be an example directory structure:

```
ExampleTheme
    frontend
        _public
            example.css
```

As the second step you will have to define the CSS files you would like to use inside your custom theme. This can be done by adding an array to your `Theme.php` file that contains the specific file paths, as the following example shows:

```
protected $css = array(
    'example.css',
    'example2.css'
);
```

As the last step you have to re-select the theme in the Shopware 5 theme manager inside the backend. After selecting the theme, the changes should be displayed in the storefront.