---
layout: default
title: Contribute
github_link: contribute/index.html
indexed: true
---

## Contribute to Shopware

Shopware is available under dual license (AGPL v3 and proprietary license). If you want to contribute code (features or bugfixes), you have to create a pull request and include valid license information. You can either contribute your code under New BSD or MIT license.

If you want to contribute to the backend part of Shopware, and your changes affect or are based on ExtJS code, they must be licensed under GPL V3, as per license requirements from Sencha Inc.

If you are not sure which license to use, or want more details about available licensing or the contribution agreements we offer, you can contact us at <contact@shopware.com>.

- **Contribution guide**: <https://github.com/shopware/shopware/blob/master/CONTRIBUTING.md>
- **License**: Dual license AGPL v3 / Proprietary
- **Github Repository**: <https://github.com/shopware/shopware>
- **Issue Tracker**: <http://jira.shopware.de/jira>

## Contribute to the Dev Docs

These doc pages are also available on Github and open to contribution from the community. 

- **Github Repository**: <https://github.com/shopware/devdocs>
- **License**: Proprietary

Contributions to this project should aim at improving the overall coverage / quality of the Shopware 5 documentation page.
 
All contributions should respect the following styling guidelines.

### Language

All documents must be written in English. Usage of American English is highly recommended.

### Naming conventions

Products/libraries/technologies should start with capital letters, unless explicitly specified so by the author/owner. Acronyms should be full upper case. Examples of correct usage:

- Shopware (the product)
- Shopware Community Store (as well as other shopware AG products or services)
- Less
- CSS
- JavaScript
- ID
- HTML
- HTTP/HTTPS
- SSL
- Responsive/Base (for themes names)
- shopware AG (company name)

When referring to specific parts of Shopware's interface, like buttons or modules, the naming should be an exact match to what users expect to find in the actual code/interface. For example, where referring to the "Basic Settings" module, please use this exact naming, and not similar names like "Settings Module" or "Basic Configurations", as this might be misleading for less experienced readers. 

Some naming conventions are not enforced, but are nonetheless recommended, to ensure consistency with already existing docs. Examples:
- Using "Frontend" is recommended over "Storefront" and other similar namings
- Using "Article" is recommended over "Product" and other similar namings

### Visual styling

File and folders paths, class and method names and interface elements should be always wrapped in single quotes. Examples:

- `sArticle`
- `sArticle.php`
- `sArticle::sGetArticleById()`
- `sArticle::sSYSTEM`
- `/engine/Shopware/Core/sArticles.php`
- `Theme manager` window
- `Create theme` button
- `Responsive` theme

### Screenshots

Screenshots should follow the language rule, ie. be taken from content displayed in English. Exceptions for this rule will be accepted for custom content (ie. user data, 3rd party plugins).

### Markdown code structure

Markdown syntax should be used whenever possible. Raw HTML should be used only as a last resort

### Precedence

Should you have any question regarding styling that is not covered in this guide, look for similar scenarios in other pages of this document set.
