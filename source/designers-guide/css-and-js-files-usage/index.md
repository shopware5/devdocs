---
layout: default
title: Using CSS and JavaScript files in themes.
github_link: designers-guide/css-and-js-files-usage/index.md
indexed: true
group: Frontend Guides
subgroup: Developing Themes
menu_title: Using CSS and JavaScript files
menu_order: 50
---

This quick tip shows off the best way on how to use CSS and JavaScript files for your custom themes, in order to enable them to be automatically compressed by the Shopware theme compiler. To use this feature you have to place your CSS and JavaScript files inside your theme directory under the subdirectories `frontend/_public`. This would be an example directory structure:

```
ExampleTheme
└── frontend
    └── _public
        └── src
            ├── css
            │   └── example.css
            └── js
                └── example.js
```

As the second step you will have to define the CSS or JavaScript files you would like to use inside your custom theme. This can be done by adding an array to your `Theme.php` file that contains the specific file paths, as the following examples shows:

#### Add CSS files: ####
```php
protected $css = array(
    'src/css/example.css'
);
```

#### Add JavaScript files: ####
```php
protected $javascript = array(
    'src/js/example.js'
);
```
<div class="alert alert-info" role="alert">
    <strong>Note:</strong> When you add JavaScript with the <code>$javascript</code> array you will also have access to jQuery.
    If you'd like further information about creating your own jQuery plugins you can take a look at our Guide: <a title="Getting started with the statemanager and the jQuery plugin base" href="https://devdocs.shopware.com/designers-guide/javascript-statemanager-and-pluginbase/">Getting started with the statemanager and the jQuery plugin base</a>
</div>

After clearing the theme cache the changes should be displayed in the storefront.
