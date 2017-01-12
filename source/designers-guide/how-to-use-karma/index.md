---
layout: default
title: How to use Karma for storefront tests
github_link: designers-guide/how-to-use-karma/index.md
shopware_version: 5.0.3
indexed: true
group: Frontend Guides
subgroup: General Resources
menu_title: Testing with Karma
menu_order: 130
---

<div class="toc-list"></div>

## Introduction
[Karma](http://karma-runner.github.io/) is a JavaScript test runner for Shopware's storefront. The tests themselves are written in [Jasmine](http://jasmine.github.io/).

The main goal of Karma is to create a productive testing environment for developers, where they don't have to set up multiple configurations, and can just write code and get instant feedback from their tests.

## Requirements 
Karma requires [Node.js](https://nodejs.org/) and [npm](https://www.npmjs.com/) to be installed on your system. Node.js is available on a majority of systems and distributions. If your system isn't listed below, please use the [official Node.js installation guide](https://github.com/nodesource/distributions).

## Installation
Now that all requirements are installed, we can install Karma and its dependencies. First of all, please switch to the directory ```themes/Frontend/Responsive``` and execute the following command:

```bash
npm install karma karma-phantomjs-launcher karma-jasmine jasmine-core karma-junit-reporter
```

## Running tests using the command line
Running the storefront tests is easy. Start up for favourite terminal application and switch to the `themes/Frontend/Responsive` directory of your Shopware installation. Now you can run the tests using the following command:

```bash
karma start karma.conf.js
```

### Using the build script
If you feel more comfortable using our build script, you can use it as well. Head over to your Shopware installation and go to the `build` directory  You can execute the tests using the following command:

```bash
ant karma-shopware-continuous
```

## Integrate Karma in PhpStorm
[PhpStorm](https://www.jetbrains.com/phpstorm/) is our recommended IDE. In the plugin repository, you can find a [Karma plugin](https://plugins.jetbrains.com/plugin/7287?pr=) which can run the storefront tests. Here's how you can install and use it: [Installing, Updating and Uninstalling Repository Plugins](https://www.jetbrains.com/phpstorm/help/installing-updating-and-uninstalling-repository-plugins.html)
