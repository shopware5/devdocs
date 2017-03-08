---
layout: default
title: Setting up PhpStorm
github_link: developers-guide/phpstorm/index.md
tags:
  - phpstorm
  - tools
indexed: true
group: Developer Guides
subgroup: General Resources
menu_title: Setting up PhpStorm
menu_order: 5
---

<div class="toc-list"></div>

## Introduction

PhpStorm is a PHP IDE that many of the Shopware Developers use on a daily basis.
You can find installation instructions on the official [PhpStorm Website](https://www.jetbrains.com/phpstorm/).

### Requirements for developing

First of all you need a working Shopware installation. Just visit our [Github Repository](https://github.com/shopware/shopware) 
and follow the `README.md` installation instructions. You do not want to worry about a local webserver or database server?
Then take a look at our [Vagrant and PHPStorm](/developers-guide/vagrant-phpstorm/) guide to set up a virtual
machine, ready to develop with Shopware. If you want to contribute to the Shopware repository also check out our
[Contributing](/contributing/) guides.


### Benefits of using PhpStorm

- Intelligent PHP editor
  - PHP code completion
  - Integrated refactoring
  - Smarty and PHPDoc support
- Easy installation
  - Cross platform
  - Individual project settings
- Visual PHPUnit test runner
- VCS support
  - SVN
  - Git
  - Mercurial
  - Local history
- FTP and remote synchronization
- Visual debugging inside the IDE
- HTML5 and CSS editor including zen coding

## Shopware PhpStorm Plugin

The [Symfony Plugin for PhpStorm](https://plugins.jetbrains.com/plugin/7219) adds Shopware specific features like code completion, quick fixes and navigations.
For an complete guide how to install, configure and use the plugin please read the following article: [Shopware development with PhpStorm](https://confluence.jetbrains.com/display/PhpStorm/Shopware+development+with+PhpStorm).

Special thanks go to [Daniel Espendiller](https://github.com/haehnchen/) ([@BigHaehnchen on Twitter](https://twitter.com/bighaehnchen)) who wrote and maintains this plugin.

## Set PHP Code Style

As mentioned in our [coding standards](/developers-guide/coding-standards/), you should follow the [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md) and [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding standards. 

PhpStorm provides you with predefined settings:

- On the main menu, choose `File | Settings` for Windows and Linux or `PhpStorm | Preferences` for OS X
- In the settings tree choose `Editor | Code Style | PHP`
- On the upper right hand corner click `Set from...`
- Choose `Predefined Style | PSR1/PSR2`

## Configure Project Directories

### Exclude Directories from Indexing

PhpStorm provides you with a variant of automatic code completions. First, PhpStorm needs to index all files inside your Project. This might take a while, depending on the number and size of files in your project.

To exclude directories from indexing, open the Settings and select **Directories** in the left sidebar.
 You can now select directories on the right side and mark them as excluded by clicking on **Excluded** above.

From the context menu `Mark directory as` -> `Excluded`

- `files`
- `media`
- `recovery`
- `snippets`
- `var/` (Do not exclude directory `var/cache/production_DATE/doctrine` to allow PhpStorm to autocomplete getter and setter of attributes. E.g. $article->getAttribute()->getAttr4())
- `web/cache/`

### Configure Source Directories

`Mark directory as` -> `Sources Root`

- `engine/Shopware/`
- `engine/Library/Enlight/`

### Configure Test directories 

`Mark directory as` -> `Test Sources Root`

- `tests/Unit/`
- `tests/Functional/`

