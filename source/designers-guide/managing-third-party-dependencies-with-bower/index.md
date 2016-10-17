---
layout: default
title: Managing third-party dependencies with Bower
github_link: designers-guide/managing-third-party-dependencies-with-bower/index.md
indexed: true
group: Frontend Guides
subgroup: General Resources
menu_title: Managing dependencies with Bower
menu_order: 90
---

<div class="toc-list"></div>

## Introduction

This is a small introduction on how to use the frontend package manager [Bower](https://www.bower.io) to manage the library and framework dependencies of custom themes in Shopware 5. A package manager, like Bower or npm, is a tool that automates the installation, upgrade, configuration and removal of software dependencies and prevents all third-party requirements from having to be shipped with the software itself. Bower is focused on frontend solutions like jQuery or Bootstrap and contains over 40.000 packages from which to choose. The default Shopware 5 Responsive theme in combination with Grunt also use Bower to install its third-party libraries.

## Setup Bower

In order to be able to use Bower inside your theme, you need to install it locally by using `npm`. It requires a `package.json` file in the root of your theme, which provides information about the packages and possible additional options. You have the option to either manually create the `package.json` file with the required information (as you can see in the example below) or to use the `npm init` CLI command that launches an initialization assistant. This is an example of how a `package.json` file should look like:

```json
{
  "name": "MyAwesomeTheme",
  "version": "1.0.0",
  "description": "Example description",
  "author": "test@example.com",
  "dependencies": {
    "bower": "^1.5.3"
  }
}
```

After creating the `package.json` file (if you haven't added Bower to the dependencies yet) you can use the npm CLI command `npm install bower --save` to automatically install Bower and add it to the dependencies object. To install all dependencies that are defined in the `package.json` file, simply run the `npm install` CLI command and the packages are installed into the `node_modules` directory, which is the default installation directory of npm. When the installation is completed and Bower is installed correctly, you are ready to configure Bower and add components to your theme.
This is an example of how a theme directory structure could look like:

```
MyAwesomeTheme
├── frontend
│   └── ...
├── node_modules
│   └── Bower
├── package.json
└── Theme.php
```

[Official npm documentation](https://docs.npmjs.com/)

## Bower configuration

The main configuration of Bower is handled inside a `.bowerrc` file that has to be located in the root of the theme, the same level as the `node_modules` directory. The `.bowerrc` file can contain settings for timeouts, proxies and custom shorthand resolvers, but the most popular settings is the `directory` option which specifies the output path of the installed packages. The default directory is `bower_components`.

**Important:** We recommend using the `frontend/_public/vendor/` directory as the default installation directory for Bower components, as the `_public` directory is the intended directory for theme assets.

```json
{
    "directory": "frontend/_public/vendor/"
}
```

[Bower config documentation](http://bower.io/docs/config/)

## Adding components
In order for Bower to be able to install components, you need to specify a `bower.json` file that contains required information, similar to the `package.json` file of npm. Once again you can either create a `bower.json` file manually or use the `bower init` CLI command to use the initialization service of Bower. This is an example of how a `bower.json` file could look like:

```json
{
  "name": "MyAwesomeTheme",
  "version": "1.0.0",
  "authors": [
    "test@example.com"
  ],
  "license": "MIT",
  "ignore": [
    "**/.*",
    "node_modules",
    "bower_components",
    "test",
    "tests"
  ],
  "dependencies": {
    "jquery": "~2.1.4"
  }
}
```

Both npm and Bower have two different types of package specifications that you can use. The `dependencies` list describes the required packages in order to get the project running from a user perspective and the `dev-dependencies` are the packages that a developer would need to start working on the project. Transferred to Shopware 5 themes, the `dependencies` should be packages that directly affect the theme and need to be installed for it to work correctly.

You can add new components by using the `bower install packageName --save` CLI command. Bower allows the installation of registered packages, URL's and also Git repositories. All Bower packages that are registered in the official Bower registry can be searched by using the `bower search packageName` CLI command or the [official Bower website](http://bower.io/search/). Git repositories can be installed with the `username/repository` shorthand. All defined dependencies can be installed by using the `bower install` CLI command. After the installation is finished and the packages are installed, [this guide](/designers-guide/css-and-js-files-usage/) explains how you can add custom CSS and JavaScript files to your theme. This is how a theme directory structure with installed Bower components could look like:
```
MyAwesomeTheme
├── frontend
│   └── _public
│       └── vendor
│           └── jQuery
│           └── AngularJS
│           └── Bootstrap
├── node_modules
│   └── Bower
├── package.json
├── bower.json
└── Theme.php
```

[Bower API documentation](http://bower.io/docs/api/)