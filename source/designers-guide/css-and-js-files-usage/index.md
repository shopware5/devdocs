---
layout: default
title: Using CSS and JavaScript in themes
github_link: designers-guide/css-and-js-files-usage/index.md
indexed: true
group: Frontend Guides
subgroup: Developing Themes
menu_title: Using CSS and JavaScript
menu_order: 50
---

<div class="toc-list"></div>

## The theme compiler
Shopware uses a compiler to concatenate and minify resource files like CSS and JavaScript to single compressed files. This reduces the file size and the amount of server requests. It is highly recommended to add your files to the compiler, too. The generated files are added to the template automatically, so you don't have to worry about including your files at all.

Before you add your files to the theme compiler you have to place them in the correct directory structure inside your custom theme. Here is an example directory structure:
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

As the second step you will have to define the CSS or JavaScript files you would like to use inside your custom theme. This can be done by defining the corresponding configuration variables in the `Theme.php` file, where you add the specific file paths.

### Add CSS files
Add the file paths to the `$css` variable in your `Theme.php` file, relative to the `_public` directory.
```php
protected $css = [
    'src/css/example.css'
];
```

### Add JavaScript files
Add the file paths to the `$javascript` variable in your `Theme.php` file, relative to the `_public` directory.
```php
protected $javascript = [
    'src/js/example.js'
];
```
<div class="alert alert-info" role="alert">
    <strong>Note:</strong> When you add JavaScript with the <code>$javascript</code> array you will also have access to jQuery.
    If you'd like further information about creating your own jQuery plugins you can take a look at our Guide: <a title="Getting started with the statemanager and the jQuery plugin base" href="https://devdocs.shopware.com/designers-guide/javascript-statemanager-and-pluginbase/">Getting started with the statemanager and the jQuery plugin base</a>
</div>

### Compiling files
Every time you add changes to your LESS, CSS or JavaScript files the corresponding theme files have to be compiled. There are two ways of doing this. 

#### Production
During production mode you can start the compiler via the administration panel in the cache and performance module. Go to `Configuration` -> `Caches & Performance` -> `Caches` and check the `Compile themes` option.

#### Development
For development you have the option to disable the compiling in `Configuration` -> `Theme Manager` -> `Settings` -> `Disable compiler caching`.  
But the **best way for developing** is to use the [grunt file watcher](/designers-guide/best-practice-theme-development/).

## Asynchronous JavaScript

Since Shopware 5.3 we are loading the concatenated JavaScript file asynchronously. This improves the first rendering of the page also known as page speed. If you are using the compiler you should not worry about a thing, because your script is loaded together with all other Shopware scripts.

If there is a reason for you to implement your script in a different way, please be aware of possible race conditions that could occur. When you need some parts from the main script as a dependency (for example jQuery) there is a new callback method which you can use to wait for the main script to load.

```javascript
document.asyncReady(function() {
    // do your magic here  
});
```