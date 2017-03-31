---
layout: default
title: Coding Standards
github_link: developers-guide/coding-standards/index.md
indexed: true
menu_title: Coding Standards
menu_order: 30
group: Contributing
---

The shopware team uses the following coding standards. All contributions to Shopware as well as plugins and customizations should also use these standards. 

## General

If not stated otherwise we use the following coding styles for all source files:

- Unix-like newlines (Line Feed/LF, `\n`)
- 4 spaces for indenting, not tabs
- UTF-8 File encoding

## PHP Coding Standard
For PHP Code all contributions should use the [PSR-1: Basic Coding Standard](http://www.php-fig.org/psr/psr-1/) and [PSR-2: Coding Style Guide](http://www.php-fig.org/psr/psr-2/).

You can automatically check and fix the coding style with [php-cs-fixer](http://cs.sensiolabs.org/):

```bash
php-cs-fixer fix -v --level=psr2 /path/to/files
```

## JavaScript Coding Standard
Please see our dedicated page for the [JavaScript Coding Standard](/designers-guide/javascript-coding-style/).

## CSS Coding Guidelines
Please see the following blog post for the [CSS Coding Guidelines](/blog/2016/08/26/css-coding-guidelines/).

## Smarty / HTML Coding Guidelines
Please see our dedicated page for the [Smarty / HTML Coding Guidelines](/designers-guide/html-smarty-coding-guidelines/).
