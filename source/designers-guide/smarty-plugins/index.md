---
layout: default
title: Smarty Plugins
github_link: designers-guide/smarty-plugins/index.md
indexed: true
group: Frontend Guides
subgroup: General Resources
menu_title: Smarty Plugins
menu_order: 55
---

<div class="toc-list"></div>

# Smarty plugins #

Several more Smarty plugins are available in the current Shopware version. This makes it even easier to deal with currencies, data (times), configurations, links and paths in the template. Below you will find all the information needed to use these plugins in your Smarty template.

## Currency plugin ##

The currency plugin allows you to control how prices are formatted and displayed in your shop. So you have the possibility to apply different predefined standard formats and to determine where, for example, the currency symbol will appear.

To activate the plugin, please use the following syntax:

Example: *currency syntax*
```
{* Syntax *}
{[Price]|currency:[FORMAT]:[CURRENCY POSITION}
 
{* Examplecall - Output: 49,95 EUR *}
{$sArticle.price|currency:use_shortname:right}
```

The following formats are available in the menu:

- no_symbol - price without specifying currency, e.g., 49.95. Here the position is ignored.
- use_symbol - price with currency symbol, e.g., $ 49.95
- use_shortname - price with indication of currency as short, e.g., 49.95 USD
- use_name - price with indication of currency as long as the form, e.g., 49.95 dollars

The following positions are available in the menu:

- left - displays the currency symbol to the left of the price as USD 49.95
- right - displays the currency symbol to the right of the price as 49.95 USD
- standard - displays the currency symbol in the default location, which is on the right e.g., 49,95 USD

<div class="alert alert-info">
    Note: Shopware, by default, formats prices in the frontend as follows:
    {$sArticle.price|currency:use_symbol:right}.
    If you wish to have certain areas of your shop in a different format, this plugin will allow you to change the format.
</div>

## Config plugin ##

The config plugin grants you the ability to access and read the various features in the backend.

To do so, use the following syntax:
 
Example: *config syntax*
```
{* Syntax *}
{config name=[NAME OF PROPERTY]}
 
{* Examplecall "Disable vote" *}
{config name=VoteDisable}
```
 
## Link Plugin ##

The link plugin helps you to specify file paths, for example, when loading images or style sheets. We'll assume that you've installed your shop in a subdirectory shopware on your server (domain = http://www.meinshop.de) and would like to load a style sheet my_styles.css from the directory frontend/_resources/styles into your template my_template.

Without the plugin, the call looks like this:

Example: *normal style sheet call*
```
<link rel="stylesheet" media="screen, projection" href="http://www.meinshop.de/shopware/templates/my_template/frontend/_resources/styles/my_styles.css" />
```


By using the link plugin you only have to define the path relative to your own theme directory.

Example: *Calling a style sheet via link plugin*
```
<link rel="stylesheet" media="screen, projection" href="{link file='frontend/_resources/styles/my_styles.css'}" />
```

The syntax of the plugin looks like this:

``` 
{* Syntax *}
{link file="[PATH TO FILE]"}
```

## Media plugin ##

With Shopware 5.2 the new media plugin was added.

In your Smarty templates you may use the {media path=...} expression to get the fully qualified URL.

Example: *Calling an image via media plugin* 
```
<img src="{media path="media/image/my-fancy-image.png"}">
``` 
 
{media} evaluates the given path at template's compile time, so you cannot use runtime variables for its path argument (generally you will use a constant path as in the example above). 
 
[Shopware 5 Media Service Documentation](https://developers.shopware.com/developers-guide/shopware-5-media-service/#url-generation)

## Url plugin ##

The url plugin gathers the URLS together from throughout the frontend and passes those specified on to the appropriate controller and action. Let's assume that you have a link that should connect to the wish list, then the call would look like this:

 
Example: *example of a call to the wish list*
``` 
{* A link to notes *}
<a href="{url controller='note'}" title="Show notes">
    Show notes
</a>
``` 
 
The index action is always the default action. If you wish to call, for example, instant downloads in the account area, then the action must be passed on accordingly.

Example: *example of a call to instant downloads*
``` 
{* A link to instant downloads *}
<a href="{url controller='account' action='downloads'}" title="Open instant downloads">
    Open instant downloads
</a>
``` 
 
You also have the option to pass on to the controller parameters. For this example, we'll extend the call to instant downloads with the parameter sParam with the value test.

Exmaple: *call to instant download with parameter*
``` 
{* A link to instant downloads *}
<a href="{url controller='account' action='downloads' sParam='test'}" title="Open instant downloads with parameter">
    Open instant downloads with parameter
</a>
``` 
 
The syntax of the url plugin is as follows:
 
 
Example: *Syntax url plugin*
``` 
{* Syntax *}
{url module='[FRONTEND/WIDGETS]' controller='[CONTROLLERNAME]' action='[ACTIONNAME]' [MORE PARAMETERS='PARAMETERVALUE']}
``` 
 
The plugin also automatically builds up the SEO links if the corresponding plugin is installed.

## Date plugin ##

The date plugin is used to format date and time information. To this end, you have the ability to use a variety of formats. The syntax of the date plugin is as follows:

Example: *date plugin syntax*
``` 
{* Syntax *}
{[VALUE]|date:[FORMAT]:[TYPE]}
``` 
 
The plugin has the following format types, which are based on the Zend Framework:

<div class="alert alert-info">
    Note: Due to localization, the following date formats may differ slightly from the examples given.
</div>

Date:

- DATE_FULL - full date e.g., "Thursday, November 4, 2010"'
- DATE_LONG - DATE_LONG - long date e.g., "4th of November 2010"
- DATE_MEDIUM - normal date e.g., "04/11/2010"
- DATE_SHORT - abbreviated date e.g., "11.04.10"'

Time:

- TIME_FULL - full time e.g., "1:55:52 pm Europe/Berlin"
- TIME_LONG - long time e.g., "1:55:52 pm CET"
- TIME_MEDIUM - normal time e.g., "1:55:52 pm"
- TIME_SHORT - abbreviated time e.g., "1:55 pm"

Date/time:

- DATETIME_FULL - full date with time e.g., "Thursday, November 4, 2010 1:55:52 pm Europe / Berlin"
- DATETIME_LONG - long date with time e.g., "November 4, 2010 1:55:52 pm CET"
- DATETIME_MEDIUM - normal date with time e.g., "11/04/2010 1:55:52 pm"
- DATETIME_SHORT - abbreviated date with time e.g., "11/04/10 1:55 pm"

Miscellaneous:

- ISO_8601 - date according to ISO 8601 e.g., "2010-11-04T13:55:52+01:00"
- RFC_2822 - date according to RFC 2822 e.g., "Thu, 04 Nov 2010 13:55:52 +0100"
- TIMESTAMP - UNIX time e.g., "1288875352"
- ATOM - date according to ATOM e.g., "2010-11-04T13:55:52+01:00"
- RSS - date for RSS feeds e.g., "Thu, 04 Nov 2010 13:55:52 +0100"
- COOKIE - date for cookies e.g., "Thursday, 04-Nov-10 1:55:52 p.m. Europe / Berlin"
- W3C - date for HTML or HTTP W3C e.g., "2010-11-04T13:55:52+01:00"

The following types can be defined for the plugin:

- ISO - ISO format for date formatting
- PHP - PHP's date() function for date formatting
 
## Action plugin ##

The plugin uses widgets to embed templates. Widgets are self-contained parts of the frontend, such as the shopping worlds.
 
``` smarty
{* Syntax *}
{action module=widgets controller=[CONTROLLERNAME] action=[ACTIONNAME] [[MORE PARAMETER]]}
 
{* Examplecall - Topseller *}
{action module=widgets controller=listing action=top_seller sCategory=$sCategoryContent.id}
``` 
 
In this case, an HTTP request is triggered internally within the system, resulting in widgets being completely dynamic elements which are not cached.

For further information read this blog: <a href="{{ site.url }}/blog/2016/07/11/on-action-tags/">On action tags</a>


## Custom Smarty plugins
To register custom smarty plugins please see <a href="{{ site.url }}/designers-guide/smarty/#register-custom-smarty-plugins">register custom smarty plugins</a>
