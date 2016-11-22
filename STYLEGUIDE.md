# Shopware Development Documentation Style Guide

This document defines some rules that should be used when editing content on this project. They aim at providing the end user with a consistent experience when reading different pages/version inside this project.

## Language

All documents should be written in American or UK English. Should it be necessary, disambiguation between the two should be done on a per occurrence basis, giving preference to the one most commonly used on similar contexts (other documentation sites, for example)

## Naming conventions

Products/libraries/technologies should start with capital letters, unless explicitly specified so by the author/owner. Acronyms should be full upper case. Examples of correct usage:
- Shopware (the product)
- Less
- CSS
- JavaScript
- ID
- HTML
- HTTP/HTTPS
- SSL
- Responsive/Base (for themes names)
- shopware AG (company name)

When referring to specific parts of Shopware's interface, like buttons or modules, the naming should be an exact match to what users expect to find in the actual code/interface. Examples:
- "Storefront" is no longer used, use "Frontend" instead
- "Product" is not used in Shopware, use "Article" instead

## Visual styling

File and folders paths, class and method names and interface elements should be always wrapped in single quotes. Examples:
- `sArticle`
- `sArticle.php`
- `sArticle::sGetArticleById()`
- `sArticle::sSYSTEM`
- `/engine/Shopware/Core/sArticles.php`
- `Theme manager` window
- `Create theme` button
- `Responsive` theme

## Screenshots

Screenshots should follow the language rule, ie. be taken from content displayed in English. Exceptions for this rule will be accepted for custom content (ie. user data, 3rd party plugins).

## Markdown code structure

Markdown syntax should be used whenever possible. Raw HTML should be used only as a last resort

## Precedence

Should you have any question regarding styling that is not covered in this guide, look for similar scenarios in other pages of this document set.

## General

