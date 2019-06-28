---
layout: default
title: Shopware 5 upgrade guide
github_link: developers-guide/shopware-5-upgrade-guide-for-developers/index.md
indexed: true
group: Developer Guides
subgroup: General Resources
menu_title: Upgrade Guide
menu_order: 10
---
## General

In this document, developers will find details about changes made in the different Shopware 5 minor releases.

This document only covers the main changes done in each version. For a comprehensive change list of all Shopware versions,
including minor and bugfix releases, refer to the `UPGRADE.md` file found in your Shopware installation.

<div class="toc-list"></div>

## Shopware 5.6

### System requirements changes

The **minimum PHP version** has been increased to **PHP 7.2 or higher**. We’ve also added support for **PHP 7.3** and
encourage you to use the latest version. Due to this change, Shopware will start using real types instead of typehints on new
services or interfaces, as well as on private methods.

Existing services available for decoration, as well as hookable public or protected methods won't be strongly typed though,
to not break compatibility in plugins. 

The **minimum MySQL version is MySQL 5.7**, support for MySQL 5.5 and MySQL 5.6 has been dropped.

The **minimum Elasticsearch version is 6.6**, support for Elasticsearch 2.0 and 5.0 has been dropped due to the 
underlying library being used. Support for **Elasticsearch 7.0** has been added.

### Library updates

* Updated `Symfony` to 3.4.29
* Updated `jQuery` to 3.4.1
* Updated `doctrine/dbal` and `doctrine/orm` to 2.6.3 and `doctrine/common` to 2.10.0 
* Updated `mpdf/mpdf` to 7.1.9
* Updated `league/flysystem` to 1.0.46

### Content Types

Content Types are something similar to attributes, but for complete entities. You can create your own custom entities
with all necessary fields using an XML file (provided by a plugin) or all by yourself in the backend.
The main idea of this feature is to provide a possibility to easily create custom entities like recipes, store lists or
job listings without having to write any code.

A new entity can be defined by a list of fields. Some of them are meta fields (like `name`, `description` or an icon), others
describe the essence of a content type, e.g. `Ingredients`, `Directions`, `Nutrition Facts`, `Preparation Time` and
an image for a recipe.

Each defined entity comes with the following capabilities:

- a table with `s_custom_` prefix
- a backend menu and controller for managing the entries (e.g. creating new or modifying existing recipes)
- ACL resources for this backend menu
- a repository service with `shopware.bundle.content_type.`**type_name**
- an API controller for all CRUD operations (Custom**type_name** e.g. `CustomRecipe`)
- (if explicitly enabled) a frontend controller with listing and detail views

All custom entities are also accessible in templates using a new smarty function `fetchContent`

Example
```html
{fetchContent type=recipe assign=recipes filter=[['property' => 'name', 'value' => 'Spaghetti Bolognese']]}

{foreach $recipes as $recipe}
    {$recipe.name}
{/foreach}
```

The backend fields and titles can be translated using snippet namespace `backend/customYOURTYPE/main`.

Plugins can provide their own entities using an XML schema at `Resources/contenttypes.xml`:

```xml
<?xml version="1.0" encoding="utf-8"?>
<contentTypes xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:noNamespaceSchemaLocation="../../../engine/Shopware/Bundle/ContentTypeBundle/Resources/contenttypes.xsd">
    <types>
        <type>
            <typeName>store</typeName>
            <name>Stores</name>
            <fieldSets>
                <fieldSet>
                    <field name="name" type="text">
                        <label>Name</label>
                        <showListing>true</showListing>
                    </field>
                    <field name="address" type="text">
                        <label>Address</label>
                        <showListing>false</showListing>
                    </field>
                    <field name="country" type="text">
                        <label>Country</label>
                        <showListing>false</showListing>
                    </field>
                </fieldSet>
            </fieldSets>
        </type>
    </types>
</contentTypes>
```

You can find more information and details (e.g. regarding available field types) in the [Developer Docs](https://developers.shopware.com/developers-guide/content-types/).

### The request and response instances in Shopware now extend from Symfony Request / Response.

`Enlight_Controller_Request_RequestHttp` is now extending `Symfony\Component\HttpFoundation\Request` and
`Enlight_Controller_Response_ResponseHttp` extends `Symfony\Component\HttpFoundation\Response`.

This allows you to use the Symfony Request properties you might be more familiar with, like the `ParameterBag`s
`\Symfony\Component\HttpFoundation\Request::$attributes`, `\Symfony\Component\HttpFoundation\Request::$query`, 
`\Symfony\Component\HttpFoundation\Request::$cookies`, `\Symfony\Component\HttpFoundation\Request::$headers` and many more.

#### Updating your code using Rector

[Rector](https://getrector.org/) is a reconstructor tool which does instant upgrades and instant refactoring of your code.
We have contributed some packages to allow you to update your plugin automatically to new versions of Shopware, at least
partially automatic. To see which Rectors are available at this point in time, see the [Rectors Overview](https://github.com/rectorphp/rector/blob/master/docs/AllRectorsOverview.md#shopware),
this list will grow over time.

To upgrade your plugin code, just run the following command after installing Rector:

```bash
php bin/rector process --level=shopware56 custom/plugins/MyPlugin -a autoload.php
```

### Controller Registration using DI-Tag

To allow for easier testing of controllers, Shopware now supports controllers as a service: they can be defined like any
other service in the DIC and registered as a controller using the DI tag `shopware.controller`. This DI tag needs the
attributes `module` and `controller` to map them into Shopware's routing infrastructure. These controllers are also lazy-loaded.

#### Example DI

```xml
<service id="swag_example.controller.frontend.test" class="SwagExample\Controller\Frontend\Test">
    <argument type="service" id="dbal_connection"/>
    
    <tag name="shopware.controller" module="frontend" controller="test"/>
</service>
```

#### Example Controller

```php
<?php

namespace SwagExample\Controller\Frontend;

use Doctrine\DBAL\Connection;

class Test extends \Enlight_Controller_Action
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        parent::__construct();
    }

    public function indexAction()
    {
        // Do something with $this->connection
    }
    
    public function detailAction(int $productNumber = null, ListProductServiceInterface $listProductService, ContextServiceInterface $contextService)
    {
        if (!$productNumber) {
            throw new \RuntimeException('No product number provided');
        }
        
        $this->View()->assign('product', $listProductService->getList([$productNumber], $contextService->getShopContext()));
    }
}
```
### Autowiring of controller actions parameters

The new controllers tagged with `shopware.controller` tag, can now have parameters in action methods. Possible parameters are

* Services (e.g `ListProductService $listProductService`)
* $request (e.g `Request $request`)
* Request parameters, e.g `myAction(int $limit = 0)`, filled by requesting `/myaction?limit=5`


### Custom validation of order numbers (SKU)

Up to now, the validation of order numbers (or SKUs) was done in form of a Regex-Assertion in the Doctrine model at
`Shopware\Models\Article\Detail::$number`. That solution was not flexible and didn't allow any modifications of said
regex, let alone a complete custom implementation of a validation.
 
Now, a new constraint `Shopware\Components\Model\DBAL\Constraints\OrderNumber` is used instead, which is a wrapper
around `\Shopware\Components\OrderNumberValidator\RegexOrderNumberValidator`.

This way you can either change the regex which is being used for validation by defining one yourself in the `config.php`:
```php
<?php
return [
    'product' => [
        'orderNumberRegex' => '/^[a-zA-Z0-9-_.]+$/' // This is the default
    ],
    'db' => [...],
]
``` 
Or you can create your own implementation of the underlying interface
`Shopware\Components\OrderNumberValidator\OrderNumberValidatorInterface` and use it for the validation by simply
decorating the current service with id `shopware.components.ordernumber_validator` and e.g. query some API.

### Definition of MySQL version in config

It is now possible to define the MySQL version being used in the `config.php` as part of the Doctrine default configuration.
The version can be determined by running the SQL query `SELECT version()`, the result needs to be provided in the `db.serverVersion` config:

```php
<?php
return [
     ...
     'db' => [
         ...
         'serverVersion' => '5.7.24',
     ],
];
```
Providing this value via config makes it unnecessary for Doctrine to figure the version out by itself,
thus reducing the number of database calls Shopware makes per request by one.

If you are running a MariaDB database, you should prefix the `serverVersion` with `mariadb`- (e.g.: `mariadb-10.2.12`).

### Payment Token

Some internet security software packages recognize requests to domains of payment providers and open a new clean browser
without cookies out of security concerns. After returning from the payment provider, the customer then will be
redirected to the home page, because this new browser instance does not contain the previous session.

For this reason there is now a service to generate a token, which can be added to the returning url
(e.g `/payment_paypal/return?paymentId=test123&swPaymentToken=abc123def`). This parameter will be resolved in a
PreDispatch-event by the `\Shopware\Components\Cart\PaymentTokenSubscriber`: If the user is not logged in, but the URL
contains a valid token, the user will get back his former session and will be redirected to the original URL,
but without the token

Example implementation:

```php
<?php

use \Shopware\Components\Cart\PaymentTokenService;

class MyPaymentController extends Controller {

    public function gatewayAction()
    {
        // Do some payment things
        $token = $this->get('shopware.components.cart.payment_token')->generate();
        
        $returnParameters = [
            'controller' => 'payment_paypal',
            'action' => 'return',
            PaymentTokenService::TYPE_PAYMENT_TOKEN => $token
        ];
        $returnLink = $this->router->assemble($returnParameters);
        
        $redirectUrl = $this->paymentProviderApi->createPayment($this->getCart(), $returnLink);
        
        $this->redirect($redirectUrl);
    }
}
```

### Replaced Codemirror with Ace-Editor

Codemirror has been replaced with the [Ace-Editor](https://ace.c9.io/). For compatibility reason, Ace-Editor supports all xtypes / classes from Codemirror.

The following modes are available:
- css
- html
- javascript
- json
- less
- mysql
- php
- sass
- scss
- smarty
- sql
- text
- xml
- xquery

The Ace-Editor has some advantages over the previous editor:
- It provides syntax validation (see [Improved ExtJs Error Reporter in Backend](#improved-extjs-error-reporter-in-backend))
- It supports autocompletion
- It is faster

You can see the autocompletion in action in the mail templates of Shopware 5.6: It autompletes the available Smarty
variables and tags like `if` or `foreach`. This might be extended to other areas as well in future releases.

If you are interested in implementing this functionality in your own plugin, you'll first have to
[register a `completer`-callback](https://github.com/shopware/shopware/blob/83b7f50837b134050a8d882cde1dbb3e66c61df9/themes/Backend/ExtJs/backend/mail/view/main/content_editor.js#L107) function.
This [`callback`](https://github.com/shopware/shopware/blob/83b7f50837b134050a8d882cde1dbb3e66c61df9/themes/Backend/ExtJs/backend/mail/view/main/content_editor.js#L193) determines if an autocompletion could be possible (by performing some [sanity checks](https://github.com/shopware/shopware/blob/83b7f50837b134050a8d882cde1dbb3e66c61df9/themes/Backend/ExtJs/backend/mail/view/main/content_editor.js#L197)) and doing an AJAX-request containing the relevant text portion of the editor. 

The backend now can respond with a data structure containing the relevant autocompletion suggestions. How these are
to be determined depends on your use case and underlying data structures. The `MailBundle` uses an
`\Shopware\Bundle\MailBundle\AutoCompleteResolver` that pipes the given text through multiple
`\Shopware\Bundle\MailBundle\AutocompleteResolver\Resolver`s, each checking for specific possible completions.

### Improved ExtJs Error Reporter in Backend

When an error occurred in the JavaScript of the Backend, the Error Reporter that pops up in these cases often wasn't
very helpful since the stacktrace being shown in such cases can be overwhelming.

Thanks to the new [Ace-Editor](#replaced-codemirror-with-ace-editor), the Error Reporter can now show you the exact
position where the error occurred in the code and give you a hint what you might be able to do about it.

### ExtJs Developer-Mode

ExtJs developer mode loads a developer-version file of ExtJs to provide code documentation, warnings and better
error messages. This mode can be enabled using this snippet in the `config.php`:

```php
'extjs' => [
    'developer_mode' => true
]
```

### Improved Robots.txt

The `robots.txt` generation has been reworked and now shows all links from all language shops.
To remove or add entries overwrite the blocks `frontend_robots_txt_disallows_output`, `frontend_robots_txt_allows_output` and call methods `setAllow`, `setDisallow`, `removeAllow`, `removeDisallow` on the `$robotsTxt` service.

Example:

```smarty
{block name="frontend_robots_txt_disallows_output"}
    {$robotsTxt->removeDisallow('/ticket')}
    {$smarty.block.parent}
{/block}
```

### Plugin specific logger

There is a new logger service for each plugin.
The service ID of the plugin specific logger is a combination of the plugin's service prefix (lower case plugin name) and `.logger`.
For example: when a plugin's name is `SwagPlugin` the specific logger can be accessed via `swag_plugin.logger`.

This logger will now write into the logs directory (`var/logs`) using a rotating file pattern like the other logger services.
The settings for the logger can be configured using the DI parameters `swag_plugin.logger.level`(defaults to shopware
default logging level) and `swag_plugin.logger.max_files` (defaults to 14 like other Shopware loggers).

In our example, the logger would write into a file like `var/log/swag_plugin_production-2019-03-06.log`.

Support for easier log message writing is enabled, so `key` => `value` arrays can be used like this:

```php
<?php

$logger->fatal("An error is occured while requesting {module}/{controller}/{action}", $controller->Request()->getParams());
```

### Custom Sorting of products in categories

Products in a category can now be sorted "by hand". This specific sorting can also be created using the categories API resource.

They will be applied when the associated sorting has been selected in the storefront. Not manually sorted products will
use the configured normal fallback sorting.

To create a custom sorting, find your category in the Category backend module and click the new tab `Custom Sorting`.

Two display modes are available: a normal listview and a more frontend-like grid view.

### HTTP2 Server Push Support

HTTP2 Server Push allows Shopware to push certain resources to the browser without it even requesting them. To do so,
Shopware creates `Link`-headers for specified resources, informing Apache or Nginx to push these files to the browser.
Server Push is supported since
[Apache 2.4.18](https://httpd.apache.org/docs/2.4/mod/mod_http2.html#h2push) and
[nginx 1.13.9](https://www.nginx.com/blog/nginx-1-13-9-http2-server-push/#http2_push).

These resources are only pushed on the very first request of a client. After that, the files should be cached in the
browser and don't need to be transmitted anymore. The presence of a `session`-cookie is used to determine if a push is necessary.

The Smarty function `{preload}` is used to define in the template which resource are to be pushed and as what.

Example for CSS:
```html
<link href="{preload file={$stylesheetPath} as="style"}" media="all" rel="stylesheet" type="text/css" />
```

Example for Javascript:
```html
<script src="{preload file={link file='somefile.js'} as="script"}"></script>
```

Server Push can be enabled in the `Various` section of the `Cache/Performance` settings. Please do not enable
Server Push support if you are using Google's Pagespeed module: It creates custom CSS and Javascript files for the browser,
replacing the ones Shopware contains in the HTML. So pushing the original files to the browser leads to an unnecessary overhead.

## Shopware 5.5

### System requirements changes

<div class="alert alert-info">

The IonCube Loader requirement has been dropped! Starting with Shopware 5.5, only unencrypted plugins are supported.
Before removing the IonCube Loader from your server environment, please make sure no encrypted plugins are installed anymore.

</div>

The minimum PHP version still is **PHP 5.6.4 or higher**, though **PHP 7.x is highly encouraged**. Please be aware that
PHP 5.6 and PHP 7.0 reach their "end of life" (the end of the official support by the PHP Group) by the end of the year.
Shopware will drop support for those versions of PHP with the next minor version.

For performance and compatibility reasons, we recommend using **PHP 7.2**. Since encrypted plugins are no longer supported
and the IonCube Loader therefore is no longer a requirement, an update to the latest version of PHP is now possible.

The minimum MySQL version still is MySQL 5.5, but **MySQL 8.0** is now supported as well. Since the extended support of
MySQL 5.5 ends in December 2018, Shopware will drop the support for this version soon thereafter.

### Unencrypted Plugins

Starting with Shopware 5.5, IonCube encryption of plugins is being discontinued and only unencrypted plugins are
supported in Shopware 5.5.

The Shopware LicenseManager plugin still is compatible with Shopware 5.5 to not break any existing installations, it is
nevertheless being discontinued together with the encryption of plugins. So, if you are currently using the
Shopware LicenseManager in your plugin, you have to remove the license check in order to upload a Shopware 5.5
compatible version of your plugin to the Shopware Store.

### Removal of deprecated code

Shopware 5.5 removes a lot of old, deprecated code. If you're updating from Shopware < v5.4.5, you might want to consider
updating on the latest version of 5.4 first: versions 5.4.5 onwards show deprecation warnings in development mode if a
deprecated function is being called. Alternatively you can change the loglevel in your `config.php` by configuring:

```php
...
'logger' => [
    'level' => \Shopware\Components\Logger::DEBUG
],
...
```

The most relevant changes are:

- The methods `ModelManager::addAttribute` and `ModelManager::removeAttribute` were removed. Use `\Shopware\Bundle\AttributeBundle\Service\CrudService::update` instead.
- The legacy models `Shopware\Models\Customer\Billing` and `Shopware\Models\Customer\Shipping` aren't available anymore.
The same is true for their references in the `Customer` model (customer.billing, customer.shipping) and the repository
`Shopware\Models\Customer\BillingRepository`. The matching tables `s_user_billingaddress`, `s_user_shippingaddress`
and their attribute tables `s_user_billingaddress_attributes` and `s_user_shippingaddress_attributes` won't be removed
on upgrade until Shopware 5.6, but they won't be kept in sync anymore.
- Old conversion classes got removed: `Shopware_Components_Convert_Csv`, `Shopware_Components_Convert_Excel` and `Shopware_Components_Convert_Xml`
- The action `\Shopware_Controllers_Widgets_Listing::ajaxListingAction` was removed. Use `Shopware_Controllers_Widgets_Listing::listingCountAction` instead.

For a complete overview check the **Removals** part of the <a href="https://github.com/shopware/shopware/blob/5.5/UPGRADE-5.5.md#removals">Upgrade.md</a>. 

### MySQL 8 workaround

Due to a mixture of MySQL 8 and Doctrine constraints, the column `s_core_documents.ID` will be renamed to
`s_core_documents.id` on the fly if MySQL 8 is being used. To be able to do that, the service `\Shopware\Components\Compatibility\LegacyDocumentIdConverter`
was introduced, which is checked in the file `engine/Shopware/Models/Order/Document/Document.php` to determine if
a Doctrine model with uppercase or lowercase `id` needs to be used.

If you need to reference this column in your own model, we recommend to use the same workaround there. You can use the
same service (see above) with id `legacy_documentid_converter` for that.

The reason for this workaround is that MySQL 8 forces ids in foreign key constraints to be lower case.

This is a problem in current systems since we have an uppercase `ID` in table `s_order_documents`.
MySQL doesn't care if we use `ID` in the table and `id` in the constraint, but Doctrine needs both to be written 
in the same way. On new installations of Shopware 5.5 this is already the case, both are lowercase there.

So in order to support MySQL 8 on updates from older Shopware versions we need to change the case of the `id` column
in `s_order_documents`, which breaks support of blue/green deployments as older versions of Shopware (< 5.5) need
that column to be uppercase.

Since this change is only really necessary if you are using MySQL 8, it is only enforced when a MySQL 8 server is
detected. A downgrade to an older Shopware installation wouldn't be possible anyway in that case, as Shopware 5.4
does not support MySQL 8 yet.

If you want to make this migration offline, there is the command `sw:migrate:mysql8` to check if the migration was
executed and do so if you want.

The column `s_core_documents.id` will be lowercase from Shopware 5.6 forward.

### Library updates

- Updated `Symfony` to version 3.4.15 LTS. Some nice, new features are <a href="https://symfony.com/blog/new-in-symfony-3-4-simpler-injection-of-tagged-services" target="_blank">Simpler injection of tagged services</a> or <a href="https://symfony.com/blog/new-in-symfony-3-4-lazy-commands" target="_blank">Command Lazyloading</a>.
- Updated `jQuery` to 3.3.1.  You can see the <a href="https://jquery.com/upgrade-guide/3.0/" target="_blank">jQuery update guide</a>
    for a list of important and breaking changes and links to the migration plugin.
    In Shopware, the relevant changes were:
    - Changing `.delegate()` to `.on()`
    - Using `.prop()` instead of `.addAttribute()` or `.removeAttribute()`
    - Using `JSON.parse()` instead of `$.parseJSON()`
    - `$.ajax` is returning a `Deferred` object from now on and the callback method property for the failure behaviour is now called `error` instead of `failure`
- Updated `league/flysystem` to 1.0.45
- Updated `Mpdf` to 7.0.3
- Updated `doctrine/common` to 2.7.3
- Updated `beberlei/assert` to 2.9.2

### Basket refactoring

To improve basket performance and ease of use for plugin developers, the `sBasket` class was refactored slightly
while still being compatible with existing plugins as much as possible.

The most relevant change is in regards to the following blocks in `themes/Frontend/Bare/frontend/checkout/cart_item.tpl`
which were moved to their own files:
* `frontend_checkout_cart_item_product`
* `frontend_checkout_cart_item_premium_product`
* `frontend_checkout_cart_item_voucher`
* `frontend_checkout_cart_item_rebate`
* `frontend_checkout_cart_item_surcharge_discount`

These five blocks were moved to their own template files in `themes/Frontend/Bare/frontend/checkout/` to optimize the include process:
* `cart_item_product.tpl`
* `cart_item_premium_product.tpl`
* `cart_item_rebate.tpl`
* `cart_item_voucher.tpl`
* `cart_item_surcharge_discount.tpl`

Also, the following templates no longer extend `cart_item.tpl` but include the logic themselves and have their own subtemplates:
* `confirm_item_premium_product.tpl`
* `confirm_item_product.tpl`
* `confirm_item_rebate.tpl`
* `confirm_item_surcharge_discount.tpl`
* `confirm_item_voucher.tpl`
* `finish_item_premium_product.tpl`
* `finish_item_product.tpl`
* `finish_item_voucher.tpl`

If your theme extends one of the contained blocks, you'll have to change the filename it extends from.

The following classes were added to simplify changes to the checkout process by plugins:
* Added struct `Shopware\Components\Cart\Struct\CartItemStruct` to represent items in the cart during calculation
* Added public function `sBasket::updateCartItems` to provide a new way of interacting with cart updates

### Proportional tax calculation

The proportional tax calculation allows to calculate multiple taxes for all fees, vouchers, discounts etc in baskets
containing items with e.g. 19% and 7% tax. This feature needs to be activated in *Settings*, *Checkout*, *Proportional calculation of tax positions*.
For the proportional tax calculation to work with vouchers and modes of dispatch, be sure to set the mode of tax calculation to "auto detection" in their settings

The following changes were made to implement this feature:

* Added `Shopware\Components\Cart\ProportionalTaxCalculator` to calculate proportional taxes for the cart items
* Added `Shopware\Components\Cart\BasketHelper` to to add items to the cart that need to be calculation in a proportional way
* Added `Shopware\Components\Cart\ProportionalCartMerger` to merge proportional cart items into one cart item
* Added new filter event to modify proportional vouchers `Shopware_Modules_Basket_AddVoucher_VoucherPrices`
* Added new column `invoice_shipping_tax_rate` to `s_order`, to save exact dispatch shipping tax rate. If the proportional
calculation is disabled, this field is `null` and shipping tax rate will be calculated like before
* Added new column `is_proportional_calculation` to `s_order`, which defines that the order is made with proportional items

### Elasticsearch

Shopware 5.5 supports Elasticsearch versions 2.x, 5.x and 6.x.

<div class="alert alert-info">

Important: After the update to 5.5 you have to reindex your Elasticsearch indices.

</div>

Shopware works with index aliases to allow multiple indices to exist in parallel and switch between them if necessary.
Should an index with the name of an alias (e.g. `shop_1`) already exist, it will now be deleted automatically so that
the new alias can be created without error.

To support Elasticsearch 6 it was necessary to split the existing index into multiple indices for different document types
(product, property). If you're using `sw:es:analyze` and `sw:es:switch:alias`, you now need to also provide the parameter
document type that you want to analyze or switch the index of.

### Elasticsearch backend

A new `EsBackendBundle` was added to index and search for products, customers and orders in the backend. New `config.php`
parameters where added for that, the `es` array of parameters now contains a new key `backend`:

```
'es' => [
    ...
    'backend' => [
        'write_backlog' => false,
        'enabled' => false,
    ],
    ...
],
```

To activate Elasticsearch in the backend, simply change `backend.enabled` to `true` and use the `sw:es:backend:index:populate`
command to populate the necessary indices. If you also enabled the `backend.write_log`, you can use `sw:es:backend:sync`
periodically after that to keep the index in sync.

### Changed execution model of `replace` hooks

When multiple `replace` hooks exist for one method, up to Shopware 5.4 (incl.) each of these hooks were called
sequentially with every hook having the opportunity to call `executeParent()`. Hence, if this happened, the original (parent)
implementation got called multiple times, leading to unexpected behaviours.

In Shopware 5.5 the execution model was changed to a decorative type of implementation: If more than one `replace` hook
exists for a function, calling `executeParent()` inside the hook will execute the next `replace` hook of said function. Only
the last `replace` hook will then call the original implementation on `executeParent()`.
 
### Filesystem abstraction layer

As of Shopware 5.5, the use of direct file system access methods (like e.g. `fopen()`, `file()`, `is_readable()` and many more)
is being discouraged. Instead, we rely on the library <a href="https://github.com/thephpleague/flysystem" target="_blank">FlySystem</a>
as a generic file system abstraction layer. This allows a lot of flexibility in regards to where files are being stored.
E.g. Documents and Sitemap files can now also served from S3 or Google Cloud. We added support for Amazon Web Services
and Google Cloud Platform for the moment but this layer will help us in the future to support more cloud services.

Multiple instances of this abstraction classes are available to plugins. The services with the ids `shopware.filesystem.public`
and `shopware.filesystem.private` are available to Shopware itself and to all plugins that are active. These services are
used to access a shared "folder" in which files can be stored that should be shared between Shopware and plugins.
The service `shopware.filesystem.public.url_generator` can be used for generating a URL to a file in the public filesystem.

The `shopware.filesystem.public` file system is intended for files that need to be accessible to clients directly, e.g.
images, product manuals etc. while `shopware.filesystem.private` is for files that need to be available throughout Shopware
(e.g. invoice pdfs, ESDs like software), but are not to be accessed by a browser directly without any authentication.

This is a simple example of how a file can be written using the new system:

```php
$fileName = 'HelloWorld.txt';
$contents = 'Uploaded by Shopware using FlySystem';

$filesystem = $this->container->get('shopware.filesystem.private');
if ($filesystem->has($fileName)) {
    $filesystem->delete($fileName);
}

$filesystem->write($fileName, $contents);
```

Plugins can access their own filesystems which are instantiated and made available automatically by retrieving them from
the DIC:
    * `plugin_name.filesystem.public`
    * `plugin_name.filesystem.private`

### Routing

SEO support for some AJAX routes defined in the template `themes/Frontend/Bare/frontend/index/index.tpl` has been removed for performance reasons. If you need SEO URLs for the following routes, you can override the block 
`frontend_index_header_javascript` and re-enable them by removing the `_seo=false`-attribute from the `{url controller=...}`-call.

The affected routes are:

- `/checkout/ajaxCart`
- `/register/index`
- `/checkout/addArticle`
- `/widgets/Listing/ajaxListing`
- `/checkout/ajaxAmount`
- `/address/ajaxSelection`
- `/address/ajaxEditor`

### Sitemap

<div class="alert alert-info">

Important: Large shops with many entities should consider switching to cronjob generation instead of live generation after the upgrade

</div>

To support more than 50.000 URLs that are allowed in one `sitemap.xml` file, Shopware now adds a `sitemap_index.xml`,
which itself contains links to one or more `sitemap.xml`. The sitemap files can be created by cronjob, live or using
the command `sw:generate:sitemap`.

If you need to add some links to the sitemap coming from a plugin, you can simply create a service implementing the interface
`\Shopware\Bundle\SitemapBundle\UrlProviderInterface` and give it the DIC tag `sitemap_url_provider`. The sitemap exporter
will collect your service using the tag and export the URLs it provides along with the others.

### Dynamic cache invalidation

The cache duration of the HTTP cache for shopping worlds, product detail pages, categories or blog pages is now being
calculated dynamically, should an entity be changed automatically at a certain point in time. An example would be a 
new shopping world on the frontpage or a category that goes live at noon. Normally it wouldn't be visible till the 
HTTP cache expires or is cleaned manually.

Now, the cache time is calculated dynamically so that it expires at the correct point in time.

If you want to implement this feature for your own element, you only need to provide a service that implements the 
interface `Shopware\Components\HttpCache\CacheTimeServiceInterface` and then tag this service in the `services.xml` with
the tag `invalidation_date_provider`. It will then be picked up and called automatically.

### Cache warmer

Up to now, the cache warmer relied on seo urls, but that did only cover a small amount of possible urls which can be found
in shopware. With these changes, developers doesn't have to add unnecessary new seo urls to warm them for the cache.
In addition, the performance and amount of urls were greatly improved to cover the most content of shopware by itself.

By implementing `HttpCache\UrlProvider\UrlProviderInterface` to a new service with the tag `cache_warmer.url_provider`
developers can now easily add their own url providers for the cache warmer. Note that CLI commands can't be extended by
Plugins, so adding UrlProviders can only be used by the `--extensions` parameter to only warm all non-Shopware extensions
or warming the full cache. To add these functions to the backend module, you have to extend the `httpCache` property like this:

```
//{block name="backend/performance/view/main/multi_request_tasks"}
//{$smarty.block.parent}
Ext.override(Shopware.apps.Performance.view.main.MultiRequestTasks, {
    initComponent: function () {
        this.httpCache.myNewProvider = {
            providerLabel: 'myNewProvider',
            requestUrl: '{url controller="Performance" action="warmUpCache" resource=myNewProvider}',
        };

        this.callParent(arguments);
    }
});
//{/block}
```

As mentioned in the change log, the CLI command also offers new parameters. They can be used combined to call the providers
independently. However, you still don't have to add any parameters to warm up everything.

| Parameter             | Short | Description                                   |
| --------------------- | ----- | --------------------------------------------- |
| --category            | -k    | Warm up categories                            |
| --emotion             | -o    | Warm up emotions                              |
| --blog                | -g    | Warm up blog                                  |
| --manufacturer        | -m    | Warm up manufacturer pages                    |
| --static              | -t    | Warm up static pages                          |
| --product             | -p    | Warm up products                              |
| --variantswitch       | -d    | Warm up variant switch of configurators       |
| --productwithnumber   | -z    | Warm up products with number parameter        |
| --productwithcategory | -y    | Warm up producss with category parameter      |
| --extensions          | -x    | Warm up all URLs provided by other extensions | 
 

## Shopware 5.4

<div class="alert alert-info">

### SSL Encryption

The mixed SSL encryption mode in the shop configuration has been removed. As of 5.4, the SSL encryption can only be
enabled or disabled globally. Shops using the mixed SSL encryption setting will automatically be upgraded to full
SSL encryption. Deprecated table columns and methods have been removed.

To learn more about the server configuration changes to switch to the full SSL encryption, please refer to [Redirect all requests to equivalent HTTPS domain](http://en.community.shopware.com/_detail_1864.html).

</div>

### System requirements changes

The minimum PHP version still is **PHP 5.6.4 or higher**, though **PHP 7.x is highly encouraged**.

### Removal of JSONP requests

Shopware 5.4 removes all JSONP requests and replaces them with regular AJAX requests. The response type was changed to
standard HTML mostly (with one exception being JSON without the JSONP-callback, see below).

These actions now return HTML directly:
- Shopware/Controllers/Frontend/AjaxSearch.php
    - `indexAction`
- Shopware/Controllers/Frontend/Checkout.php
    - `ajaxCartAction`
- Shopware/Controllers/Frontend/Compare.php
    - `addArticleAction`
    - `deleteArticleAction`
    - `deleteAllAction`
    - `getListAction`
    - `indexAction`
    - `overlayAction`
- Shopware/Controllers/Frontend/Note.php
    - `ajaxAddAction`
- Shopware/Controllers/Widgets/Listing.php 
    - `ajaxListingAction`
    
This action still returns JSON but doesn't use the JSONP-type function call.
- Shopware/Controllers/Frontend/Checkout.php
    - `ajaxAmountAction`

### POST on data modification

Also, to be more HTTP compliant, all request that change any data on the server were changed to be made using the HTTP POST verb.
These methods are:

* Basket actions
    - `addArticle`
    - `addAccessories`
    - `addPremium`
    - `changeQuantity`
    - `deleteArticle`
    - `setAddress`
    - `ajaxAddArticle`
    - `ajaxAddArticleCart`
    - `ajaxDeleteArticle`
    - `ajaxDeleteArticleCart`

* Checkout actions:
    - `finish`

### Variants in listing

It is now possible to allow displaying and filtering of variants in the listing. This new filter can be activated in the
backend filter settings and defines which groups are filterable and which of those will be expanded in the listing.

The basic product box size is increased by 45px (from 231px to 276px) to allow the variants to display which of the
options filtered match for the current variant. If the customer e.g. filters for the colors red and blue and the
expanding of colors is active, each variant listed shows a little "**Color: red**" or "**Color: blue**" tag to identify
what variant this is.     

A new block `frontend_listing_box_variant_description` was added in file `themes/Frontend/Bare/frontend/listing/product-box/box-basic.tpl`
to allow modifications of the default way the options of the variant shown are displayed. 

### Sold out variants

Shop owners can now mark individual variants as “sold out”. The flag `laststock` in table `s_articles` is still 
available but a new column `laststock int(1) NOT NULL DEFAULT '0'` in table `s_articles_details` has been introduced
to allow selling off of variants.

The current behaviour for main products stays the same, the new flag is being checked when variants are shown in listings 
and on detail pages.

This new flag is part of the configurator templates that are applied to variants, so regeneration of variants use the
default setting for this flag from the configurator template that is responsible for this product.

Please make sure to also check for this new flag if you handle variants in your plugins or happen to read or write from 
the `s_articles_details` table. Also take the flag into account when checking for stock quantity.  

### Smarty

#### Security mode

The `config.php` option `['template_security']['enabled'] => false` for disabling smarty security got removed.

#### Link flags

The flags `forceSecure` and `sUseSSL` for forcing the smarty `{url controller=...}`-helper to use SSL are deprecated.
They are now without function, whether the link uses SSL or not depends on the global "Use SSL" setting.

### Routing

Some AJAX routes generated in the template `themes/Frontend/Bare/frontend/index/index.tpl` check for existing SEO URLs. This
behaviour has been deprecated for performance reasons and SEO support for the routes defined in the template will be 
removed in 5.5. If you want to disable SEO support for this routes, you can override the block 
`frontend_index_ajax_seo_optimized` and set the variable `$ajaxSeoSupport` to `false`.

The affected routes are:

- `/checkout/ajaxCart`
- `/register/index`
- `/checkout/addArticle`
- `/widgets/Listing/ajaxListing`
- `/checkout/ajaxAmount`
- `/address/ajaxSelection`
- `/address/ajaxEditor`

### DIC

There have been some changes to underlying constants to be able to support Shopware as a [Composer](https://getcomposer.org/)
dependency. If you are interested in developing Shopware using Composer, have a look at the <a href="{{ site.url }}/developers-guide/shopware-composer/">documentation</a>
 and the Shopware [Composer project](https://github.com/shopware/composer-project).

#### Shopware Version

The usage of the constants `Shopware::VERSION`, `Shopware::VERSION_TEXT` and `Shopware::REVISION` has been deprecated. 
They have been replaced with the following parameters in the DIC:

- `shopware.release.version`
    The version of the Shopware installation (e.g. '5.4.0')
- `shopware.release.version_text`
    The version_text of the Shopware installation (e.g. 'RC1')
- `shopware.release.revision`
    The revision of the Shopware installation (e.g. '20180081547')

A new service was added in the DIC containing all parameters above 
- `shopware.release`
    A new struct of type `\Shopware\Components\ShopwareReleaseStruct` containing all parameters above

To be compatible with most versions of Shopware, please use the `config` service from the DIC for the time being:
```
    $this->container->get('config')->get('version') === Shopware::VERSION; # => true
    $this->container->get('config')->get('version_text') === Shopware::VERSION_TEXT; # => true
    $this->container->get('config')->get('revision') === Shopware::REVISION; # => true
```

#### New paths

Several paths have been added to the DIC:

- `shopware.plugin_directories.projectplugins` 
    Path to project specific plugins, see [Composer project](https://github.com/shopware/composer-project)
- `shopware.template.templatedir`
    Path to the themes folder
- `shopware.app.rootdir`
    Path to the root of your project
- `shopware.app.downloadsdir`
    Path to the downloads folder
- `shopware.app.documentsdir`
    Path to the generated documents folder
- `shopware.web.webdir`
    Path to the web folder
- `shopware.web.cachedir`
    Path to the web-cache folder 

These paths are configurable in the `config.php`, see `engine/Shopware/Configs/Default.php` for defaults

### Mpdf

Mpdf has been updated to v6.1.4 and it's namespace has been registered in the Composer autoloader. You don't need to 
include the `mpdf.php` library as you used to in previous versions, you can just use `new mPDF();` to create a new instance.

### Discard JavaScript/CSS from other Themes

Since Shopware 5.4, it's possible to manipulate the chain of inheritance by discarding Less/JavaScript, defined by another theme.
Find out more at <a href="{{ site.url }}/designers-guide/configuration-using-theme-php/#discard-javascript/css-from-other-themes">Custom theme configuration</a>.

## Shopware 5.3

<div class="alert alert-info">

### SSL Encryption

The mixed SSL encryption mode in the shop configuration has been deprecated in 5.3 in favour of a stronger 
security policy.

As of 5.4, the SSL encryption can only be enabled or disabled globally. Shops using the mixed SSL encryption
setting will automatically be upgraded to full SSL encryption. We advise you to enable the full SSL encryption
for your shops as soon as possible to prevent negative future side-effects.

To learn more about the server configuration changes to switch to the full SSL encryption, please refer to [Redirect all requests to equivalent HTTPS domain](http://en.community.shopware.com/_detail_1864.html).

</div>

### System requirements changes

The minimum PHP version still is **PHP 5.6.4 or higher**.

### Internet Explorer 10 support

Version 5.3 does not support IE10 anymore.

### Smarty

#### Security mode

We have activated the Smarty security mode globally with 5.3:
[https://github.com/shopware/shopware/blob/5.3/engine/Shopware/Components/DependencyInjection/Bridge/Template.php#L57](https://github.com/shopware/shopware/blob/5.3/engine/Shopware/Components/DependencyInjection/Bridge/Template.php#L57)

This means that certain PHP functions can no longer be used in Smarty. The available Smarty functions are stored in the following configuration file:
[https://github.com/shopware/shopware/blob/5.3/engine/Shopware/Configs/smarty_functions.php](https://github.com/shopware/shopware/blob/5.3/engine/Shopware/Configs/smarty_functions.php)

This can be extended via the config.php as follows:

```
<?php
return [
    'db' => [
        //....
    ],
    'template_security' => [
        'php_modifiers' => ['dirname'],
        'php_functions' => ['dirname', 'shell_exec'],
    ]
];
```

##### Disable template loading

To disable the automatic template loading, the loadTemplate function was often used without parameters. 
This does not work with version 5.3 anymore. To disable the automatic loading of templates, only the setNoRender function can be used:

```
<?php

class Shopware_Controllers_Frontend_Test extends Enlight_Controller_Action
{
    public function indexAction()
    {
        //wrong way: exception with shopware 5.3
        $this->View()->loadTemplate('');

        //right way: no template loaded
        $this->container->get('front')->Plugins()->ViewRenderer()->setNoRender();
    }
}
```

##### Load templates from non-registered directories

In security mode, it is only possible to load templates from registered directories

```
<?php

class Shopware_Controllers_Frontend_Test extends Enlight_Controller_Action
{
    public function indexAction()
    {
        //wrong way: exception with shopware 5.3
        $this->View()->loadTemplate(__DIR__ . '/../Views/frontend/test.tpl');

        //right way
        $this->View()->addTemplateDir(__DIR__ . '/../Views/')
        $this->View()->loadTemplate('frontend/test.tpl');
    }
}
```

###### Unknown {s} tag error
In the past, this error has occurred on some systems and results to an 500 internal server error. This is based on the problem that template files included, which are not inside a registered directory. In a production environment shopware prevents the exceptions to be displayed (config.php), which results that the rendering process are not aborted.
The following scenarios can occur:

- Default config 
No exceptions no errors should be displayed. This results to an apache error log entry with the unknown {s} tag error
- phpsettings.display_errors = 1
The {s} tag error will be displayed in the store front
- front.throwExceptions = true
The original exception with the `unsecure template directory` will be displayed:
```
Uncaught SmartyException: directory 'custom/plugins/.../Views/test_index.tpl' not allowed by security setting
```

To see all configurable options see [config.php settings documentation](https://developers.shopware.com/developers-guide/shopware-config/) 

#### Rendering

##### Form module in the backend

Smarty functions in form templates have been disabled. Also no new variables can be added to the template.

**Example**

```
{sElement.name} // works

{sElement.name|currency} // works, but does not execute the currency function

{sElement.value[$key]|currency} // does not work
```

##### Tracking Code

Smarty rendering has been disabled for this section. All variables have been removed with one exception. The variable `{$offerPosition.trackingcode}` is a placeholder now. To generate tracking urls, use the following pattern:

```
https://gls-group.eu/DE/de/paketverfolgung?match={$offerPosition.trackingcode}

<a href="https://gls-group.eu/DE/de/paketverfolgung?match={$offerPosition.trackingcode}" onclick="return !window.open(this.href, 'popup', 'width=500,height=600,left=20,top=20');" target="_blank">{$offerPosition.trackingcode}</a>
```

##### Extending listing templates

We've changed the way when product listing templates will be loaded. In order to respond with a JSON object, the template must be rendered even before the response will be sent. Therefore you have to subscribe to the `PreDispatch` event to register your templates in time. In case you are used to `extendsTemplate`, you have to update your plugin as this won't work anymore. Learn more on how to extends templates <a href="{{site.url}}/developers-guide/shopware-5-plugin-update-guide/#template-extensions">here</a>.

**Example Plugin**

```php
<?php
public static function getSubscribedEvents()
{
    return [
        'Enlight_Controller_Action_PreDispatch_Frontend' => 'onListing',
        'Enlight_Controller_Action_PreDispatch_Widgets' => 'onListing',
    ];
}

public function onListing(\Enlight_Event_EventArgs $args)
{
    $this->container->get('template')->addTemplateDir(__DIR__ . '/Resources/views');
}
```

### New basket signature

Improvements in basket on security and query manipulation as described in [5.3 signature](/developers-guide/payment-plugin/#new-signature-in-shopware-5.3-and-later).

### Product votes

Added opportunity to display product votes only in sub shop where they posted. This behavior can be configured over the backend configuration module.

### Attribute label translations

Translations for different fields (help, support, label) may be configured via snippets.

**Example: `s_articles_attributes.attr1`**

| Field | Snippet name |
|-------|--------------|
| Snippet namespace         |  backend/attribute_columns |
| Snippet name label        |  s_articles_attributes_attr1_label |
| Snippet name support text |  s_articles_attributes_attr1_supportText |
| Snippet name help text    |  s_articles_attributes_attr1_helpText |

### Backend Components

You may now define the expression for the comparison in SQL. For example `>=` can be defined like seen below:

```javascript
Ext.define('Shopware.apps.Vote.view.list.extensions.Filter', {
    extend: 'Shopware.listing.FilterPanel',
    alias:  'widget.vote-listing-filter-panel',
    configure: function() {
        return {
            controller: 'Vote',
            model: 'Shopware.apps.Vote.model.Vote',
            fields: {
                points: {
                    expression: '>=',
                }
            }
        };
    }
});
```

### Captcha

Captchas are now configurable via backend and can be added using the `shopware.captcha` dependency injection container tag.

```xml
<service id="shopware.captcha.recaptcha" class="SwagReCaptcha\ReCaptcha">
    <argument type="service" id="guzzle_http_client_factory"/>
    <argument type="service" id="config"/>
    <tag name="shopware.captcha"/>
</service>
```

For more information, please refer to our [Captcha Documentation](https://developers.shopware.com/developers-guide/implementing-your-own-captcha/).

### Redis backend and doctrine cache
Redis may now be used as a cache provider for the backend and model caches. Here is an example:

```
    'model' => [
        'redisHost' => '127.0.0.1',
        'redisPort' => 6379,
        'redisDbIndex' => 0,
        'cacheProvider' => 'redis',
    ],

    'cache' => [
        'backend' => 'redis',
        'backendOptions' => [
            'servers' => array(
                array(
                    'host' => '127.0.0.1',
                    'port' => 6379,
                    'dbindex' => 0,
                ),
            ),
        ],
    ],
```

### Asynchronous JavaScript
The concatenated main JavaScript file is now loaded asynchronously. This improves the first rendering of the page also known as page speed. If you are adding your files via the theme compiler you should not worry about a thing. Your script is loaded together with all other Shopware scripts.

If there is a reason for you to implement your script in a different way, please be aware of possible race conditions that could occur. When you need some parts from the main script as a dependency (for example jQuery) there is a new callback method which you can use to wait for the main script to load.

```javascript
document.asyncReady(function() {
    // do your magic here  
});
```

### Select field replacement

The replacement of the select field elements via JavaScript is deprecated and will be removed in a future release. You may create a styled select field with a simple CSS-only solution by adding a wrapper element.

```
<div class="select-field">
    <select>
        <option></option>
        <option></option>
    </select>
</div>
```

### Batch Product Search

The Batch Product Search service works with request and results. You can add multiple criteria and/or product numbers to a request and resolve them in an optimized way. An optimizer groups multiple equal criteria into one and performs the search.

```php
$criteria = new Criteria();
$criteria->addCondition(new CategoryCondition([3]));
$criteria->limit(3);

$anotherCriteria = new Criteria();
$anotherCriteria->addCondition(new CategoryCondition([3]));
$anotherCriteria->limit(5);

$request = new BatchProductNumberSearchRequest();
$request->setProductNumbers('numbers-1', ['SW10004', 'SW10006']);
$request->setCriteria('criteria-1', $criteria);
$request->setCriteria('criteria-2', $anotherCriteria);

$result = $this->container->get('shopware_search.batch_product_search')->search($request, $context);

$result->get('numbers-1'); // ['SW10004' => ListProduct, 'SW10006' => ListProduct]
$result->get('criteria-1'); // ['SW10006' => ListProduct, 'SW10007' => ListProduct, 'SW10008' => ListProduct]
$result->get('criteria-2'); // ['SW10009' => ListProduct, 'SW10010' => ListProduct, 'SW10011' => ListProduct, 'SW10012' => ListProduct, 'SW10013' => ListProduct]
```

### Partial facets

`\Shopware\Bundle\SearchBundleDBAL\FacetHandlerInterface` is marked as deprecated and replaced by `\Shopware\Bundle\SearchBundleDBAL\PartialFacetHandlerInterface`.
Each facet handler had to revert the provided criteria on their own to remove customer conditions. This behaviour is now handled in the `\Shopware\Bundle\SearchBundleDBAL\ProductNumberSearch::createFacets`

Old implementation:
```
/**
 * @param FacetInterface $facet
 * @param Criteria $criteria
 * @param ShopContextInterface $context
 * @return BooleanFacetResult
 */
public function generateFacet(
    FacetInterface $facet,
    Criteria $criteria,
    ShopContextInterface $context
) {
    $reverted = clone $criteria;
    $reverted->resetConditions();
    $reverted->resetSorting();

    $query = $this->queryBuilderFactory->createQuery($reverted, $context);
    //...
}
```

New implementation:
```
public function generatePartialFacet(
    FacetInterface $facet,
    Criteria $reverted,
    Criteria $criteria,
    ShopContextInterface $context
) {
    $query = $this->queryBuilderFactory->createQuery($reverted, $context);
    //...
```

#### Elasticsearch

In the elastic search implementation the current filter behavior is controlled by the condition handlers. By adding a query as `post filter`, facets are not affected by this filter.
This behavior is checked using the the `Criteria->hasBaseCondition` statement:
```
/**
 * @inheritdoc
 */
public function handle(
    CriteriaPartInterface $criteriaPart,
    Criteria $criteria,
    Search $search,
    ShopContextInterface $context
) {
    if ($criteria->hasBaseCondition($criteriaPart->getName())) {
        $search->addFilter(new TermQuery('active', 1));
    } else {
        $search->addPostFilter(new TermQuery('active', 1));
    }
}

```
This behavior is now controlled in the `\Shopware\Bundle\SearchBundleES\ProductNumberSearch`. To support the new filter mode, each condition handler has to implement the `\Shopware\Bundle\SearchBundleES\PartialConditionHandlerInterface`.
It is possible to implement this interface beside the original `\Shopware\Bundle\SearchBundleES\HandlerInterface`.
```
namespace Shopware\Bundle\SearchBundleES;
if (!interface_exists('\Shopware\Bundle\SearchBundleES\PartialConditionHandlerInterface')) {
    interface PartialConditionHandlerInterface { }
}

namespace Shopware\SwagBonusSystem\Bundle\SearchBundleES;

class BonusConditionHandler implements HandlerInterface, PartialConditionHandlerInterface
{
    const ES_FIELD = 'attributes.bonus_system.has_bonus';

    public function supports(CriteriaPartInterface $criteriaPart)
    {
        return ($criteriaPart instanceof BonusCondition);
    }

    public function handleFilter(
        CriteriaPartInterface $criteriaPart,
        Criteria $criteria,
        Search $search,
        ShopContextInterface $context
    ) {
        $search->addFilter(
            new TermQuery(self::ES_FIELD, 1)
        );
    }


    public function handlePostFilter(
        CriteriaPartInterface $criteriaPart,
        Criteria $criteria,
        Search $search,
        ShopContextInterface $context
    ) {
        $search->addPostFilter(new TermQuery(self::ES_FIELD, 1));
    }

    public function handle(
        CriteriaPartInterface $criteriaPart,
        Criteria $criteria,
        Search $search,
        ShopContextInterface $context
    ) {
        if ($criteria->hasBaseCondition($criteriaPart->getName())) {
            $this->handleFilter($criteriaPart, $criteria, $search, $context);
        } else {
            $this->handlePostFilter($criteriaPart, $criteria, $search, $context);
        }
    }
}
```

### CookiePermission

Cookie permissions is now a part of shopware and you can configure it in the shop settings.

We implement a basic cookie permission hint ***(see migration 910)***. If you want to change the decision whether the item is displayed or not, overwrite the jQuery plugin in the `jquery.cookie-permission.js`.

### Shopping worlds

Shopping worlds have been technically refactored from the ground up to improve the overall performance when adding several elements to a shopping world. It is now possible to export and import shopping worlds via the backend.
You can also convert shopping worlds to presets for reusability of configured shopping worlds. Please look at the "[Create custom emotion preset plugin](/developers-guide/emotion-preset-plugin/)" article for further information.

#### Removed escaped_fragments

In previous versions it was possible to request shopping worlds with parameter ```?_escaped_fragment_=1```. This provided direct loading of shopping worlds instead of ajax loading. This
parameter does not work anymore.

#### ComponentHandler

The processing of elements has been changed from events to component handler classes.

**Before: Subscribe to an event and process element data in the callback method**

```php
public static function getSubscribedEvents()
{
    return ['Shopware_Controllers_Widgets_Emotion_AddElement' => 'handleSideviewElement'];
}
```

**After: Create a new class and tag it as `shopware_emotion.component_handler` in your `services.xml`**

```php
class SideviewComponentHandler implements ComponentHandlerInterface
{
    public function supports(Element $element)
    {
        return $element->getComponent()->getType() === 'emotion-component-sideview';
    }

    public function prepare(PrepareDataCollection $collection, Element $element, ShopContextInterface $context)
    {
        // do some prepare logic
    }

    public function handle(ResolvedDataCollection $collection, Element $element, ShopContextInterface $context)
    {
        // do some handle logic and fill data
        $element->getData()->set('key', 'value');
    }
}
```

#### Requesting items in ComponentHandler

To make use of the performance improvement, you have to split your logic into a prepare step and handle step. The prepare step collects product numbers or criteria objects which will be resolved across all elements at once. The handle step provides a collection with resolved products and can be merged into your element.

```php
public function prepare(PrepareDataCollection $collection, Element $element, ShopContextInterface $context)
{
    $productNumber = $element->getConfig()->get('selected_product_number');
    $collection->getBatchRequest()->setProductNumbers('my-unique-request', [$productNumber]);
}

public function handle(ResolvedDataCollection $collection, Element $element, ShopContextInterface $context)
{
    $product = current($collection->getBatchResult()->get('my-unique-request'));
    $element->getData()->set('product', $product);
}
```

Keep in mind to use a unique key for requesting and getting products. For best practise, use the element's id in your key (`$element->getId()`).

### Grunt LiveReload mode & Modularized Grunt tasks
We worked on our Grunt integration and added two new features. The first one is a LiveReload mode which speeds up your theme development. The next big step forward is modularizing our Grunt tasks into separate files. Learn more on how to use these new features in our article on ["Using Grunt for theme development"](/designers-guide/best-practice-theme-development/).

#### Import & Export of shopping worlds
It is now possible to import and export shopping worlds. To realize export of media for your own custom shopping world element, you can register custom handlers.
How to create such a handler is described in "[Adding a custom component handler for export](/developers-guide/custom-shopping-world-elements/#adding-a-custom-component-handler-for-export)" article.

### Import / export module
The core import & export module got removed. It is replaced by the free [SwagImportExport](http://store.shopware.com/swagef36a3f0ee25/shopware-import/export.html) plugin which is is available in our community store. Installation
is also integrated via the backend.

### New table for saving user settings

A new table ```s_core_auth_config``` is added for storing MediaManager settings of an individual user. This table can also be used by third party plugins for storing other user related module configurations.

### Library updates

* Updated `FPDF` to 1.8.1
* Updated `FPDI` to 1.6.1
* Updated `flatpickr` to 2.4.7
* Updated `jquery` to 2.2.4
* Updated `grunt` to 1.0.1
* Updated `grunt-contrib-clean` to 1.1.0
* Updated `grunt-contrib-copy` to 1.0.0
* Updated `Modernizr` to 3.5.0

### Deprecations

* Deprecated `Shopware_Components_Convert_Csv` without replacement, to be removed with 5.4
* Deprecated `Shopware_Components_Convert_Xml` without replacement, to be removed with 5.4
* Deprecated `Shopware_Components_Convert_Excel` without replacement, to be removed with 5.4
* Deprecated `\Shopware_Controllers_Widgets_Listing::ajaxListingAction`, use `\Shopware_Controllers_Widgets_Listing::listingCountAction` instead
* Deprecated method `sArticles::sGetAffectedSuppliers()` without replacement, to be removed with 5.5
* Deprecated `Shopware\Models\Article\Element`, to be removed with 6.0

### Removals

<div class="alert alert-info" role="alert">
    <strong>Note:</strong> This section covers only the most relevant removals. Please refer to the UPGRADE.MD file in your Shopware installation for a complete, detailed list of removed elements
</div>

#### Article related
* Default plugin `LastArticle`, use `shopware.components.last_articles_subscriber` instead
* Session key `sLastArticle`
* View variable `sLastActiveArticle` from basket
* Join on `s_core_tax` in `Shopware\Bundle\SearchBundleDBAL\ProductNumberSearch`

#### Methods
The following methods have been removed:

* `Shopware\Models\Order\Repository::getBackendOrdersQueryBuilder()`
* `Shopware\Models\Order\Repository::getBackendOrdersQuery()`
* `Shopware\Models\Order\Repository::getBackendAdditionalOrderDataQuery()`
* `Shopware\Components\Model\ModelManager::__call()`
* `Shopware_Controllers_Widgets_Emotion::getEmotion()`
* `Shopware_Controllers_Widgets_Emotion::handleElement()`, use `Shopware\Bundle\EmotionBundle\ComponentHandler\ComponentHandlerInterface` instead
* `Shopware_Controllers_Widgets_Emotion::getRandomBlogEntry()`
* `Shopware_Controllers_Widgets_Emotion::getBlogEntry()`, has been replaced by `Shopware\Bundle\EmotionBundle\ComponentHandler\BlogComponentHandler`
* `Shopware_Controllers_Widgets_Emotion::getCategoryTeaser()`, has been replaced by `Shopware\Bundle\EmotionBundle\ComponentHandler\CategoryTeaserComponentHandler`
* `Shopware_Controllers_Widgets_Emotion::getBannerMappingLinks()`, has been replaced by `Shopware\Bundle\EmotionBundle\ComponentHandler\BannerComponentHandler`
* `Shopware_Controllers_Widgets_Emotion::getManufacturerSlider()`, has been replaced by `Shopware\Bundle\EmotionBundle\ComponentHandler\ManufacturerSliderComponentHandler`
* `Shopware_Controllers_Widgets_Emotion::getBannerSlider()`, has been replaced by `Shopware\Bundle\EmotionBundle\ComponentHandler\BannerSliderComponentHandler`
* `Shopware_Controllers_Widgets_Emotion::getArticleSlider()`, has been replaced by `Shopware\Bundle\EmotionBundle\ComponentHandler\ArticleSliderComponentHandler`
* `Shopware_Controllers_Widgets_Emotion::getHtml5Video()`, has been replaced by `Shopware\Bundle\EmotionBundle\ComponentHandler\Html5VideoComponentHandler`
* `Shopware\Models\Customer\Repository::getListQueryBuilder`
* `Shopware\Models\Customer\Repository::getListQuery`
* `Shopware\Models\Customer\Repository::getBackendListCountedBuilder`
* `hasLocalStorageSupport` from `jquery.storage-manager.js`. Use Modernizr to detect the feature
* `hasSessionStorageSupport` from `jquery.storage-manager.js`. Use Modernizr to detect the feature
* `Shopware\Bundle\StoreFrontBundle\Struct\Country::setShippingFree` and `Shopware\Bundle\StoreFrontBundle\Struct\Country::isShippingFree`
* `Shopware\Models\Country\Country::$shippingFree`, `Shopware\Models\Country\Country::setShippingFree` and `Shopware\Models\Country\Country::getShippingFree`

#### Methods signature changes
* Removed parameter `$checkProxy` from `Enlight_Controller_Request_Request::getClientIp()`
* Removed parameter `sCategory` from search controller `listing/ajaxCount` requests

#### Removed ExtJS components 

* Files and components
    * `themes/Backend/ExtJs/backend/vote/view/vote/detail.js`
    * `themes/Backend/ExtJs/backend/vote/view/vote/edit.js`
    * `themes/Backend/ExtJs/backend/vote/view/vote/infopanel.js`
    * `themes/Backend/ExtJs/backend/vote/view/vote/list.js`
    * `themes/Backend/ExtJs/backend/vote/view/vote/toolbar.js`
    * `themes/Backend/ExtJs/backend/vote/view/vote/window.js`
    * `themes/Backend/ExtJs/backend/vote/controller/vote.js`
    * `themes/Backend/ExtJs/backend/vote/controller/vote.js`
    * `Shopware.apps.Customer.model.List`
    * `Shopware.apps.Customer.store.List`
    * `Shopware.apps.CanceledOrder.model.Customer`

#### Database queries
* Field `attributes.search.cheapest_price` from DBAL search query
* Field `attributes.search.average` from DBAL search query
* `__country_shippingfree` field in `Shopware\Bundle\StoreFrontBundle\Gateway\DBAL\FieldHelper::getCountryFields`

#### Database tables
* `s_core_auth_config`
    * `user_id`
    * `name`
    * `config`    

#### Database columns
* `s_emarketing_lastarticles`
    * `articleName`
* `s_emarketing_lastarticles`
    * `img`
* `s_core_countries`
    * `shippingfree`
    
#### Classes
* `Enlight_Bootstrap`

#### Smarty template files, blocks and their snippets
* Files
    * `themes/Frontend/Bare/frontend/search/category-filter.tpl`

* Snippets
    * `frontend/checkout/actions/CheckoutActionsLinkLast`

* Blocks    
    * `frontend_listing_filter_facet_media_list_flyout`
    * `frontend_listing_filter_facet_media_list_title`
    * `frontend_listing_filter_facet_media_list_icon`
    * `frontend_listing_filter_facet_media_list_content`
    * `frontend_listing_filter_facet_media_list_list`
    * `frontend_listing_filter_facet_media_list_option`
    * `frontend_listing_filter_facet_media_list_option_container`
    * `frontend_listing_filter_facet_media_list_input`
    * `frontend_listing_filter_facet_media_list_label`
    * `frontend_listing_filter_facet_radio_flyout`
    * `frontend_listing_filter_facet_radio_title`
    * `frontend_listing_filter_facet_radio_icon`
    * `frontend_listing_filter_facet_radio_content`
    * `frontend_listing_filter_facet_radio_list`
    * `frontend_listing_filter_facet_radio_option`
    * `frontend_listing_filter_facet_radio_option_container`
    * `frontend_listing_filter_facet_radio_input`
    * `frontend_listing_filter_facet_radio_label`
    * `frontend_listing_filter_facet_value_list_flyout`
    * `frontend_listing_filter_facet_value_list_title`
    * `frontend_listing_filter_facet_value_list_icon`
    * `frontend_listing_filter_facet_value_list_content`
    * `frontend_listing_filter_facet_value_list_list`
    * `frontend_listing_filter_facet_value_list_option`
    * `frontend_listing_filter_facet_value_list_option_container`
    * `frontend_listing_filter_facet_value_list_input`
    * `frontend_listing_filter_facet_value_list_label`
    * `frontend_listing_actions_sort_field_relevance`
    * `frontend_listing_actions_sort_field_release`
    * `frontend_listing_actions_sort_field_rating`
    * `frontend_listing_actions_sort_field_price_asc`
    * `frontend_listing_actions_sort_field_price_desc`
    * `frontend_listing_actions_sort_field_name`
    * `frontend_search_category_filter`
    * `frontend_listing_swf_banner`

#### jQuery plugins
* Complete
    * `src/js/jquery.selectbox-replacement.js`
    * jQuery UI date picker integration in favour of a new global component
* Methods
    * `showFallbackContent` in `jquery.emotion.js`
    * `hideFallbackContent` in `jquery.emotion.js`
    
* Events
    * `plugin/swEmotionLoader/onShowFallbackContent` in `jquery.emotion.js`
    * `plugin/swEmotionLoader/onHideFallbackContent`in `jquery.emotion.js`
    
#### View variables
* Global
    * `hasEscapedFragment`
* Forms
    * `{$sShopname}`, use `{sShopname}` instead    
    
#### Frontend
* Support for `Internet Explorer < 11`
* Unneeded `css` classes
* Several `modernizr` options
* Meta tag `fragment`   
* LESS variable `@zindex-fancy-select`
* LESS variable `@font-face` for `extra-bold` and `light` of the Open Sans font type
* LESS file `ie.less` from the Responsive Theme
* File `jquery.ie-fixes.js` from the Responsive Theme
* Several polyfills
* `html5shiv`
* Smarty modifier `rewrite`
* Vendor prefixes `-ms` and  `-o` from all mixins in the Bare and Responsive Theme
* Scrollbar styling on filter-panels (Selector: `.filter-panel--content`)
* Support for `.swf` file type in the banner module
* `max-width` rule for `.filter--active` in `themes/Frontend/Responsive/frontend/_public/src/less/_components/filter-panel.less`
   
### Other changes
* Added config element `displayOnlySubShopVotes` to display only shop assigned article votes
* Added parameter `displayProgressOnSingleDelete` to `Shopware.grid.Panel` to hide progress window on single delete action
* Added parameter `expression` in `Shopware.listing.FilterPanel` to allow definition of own query expressions
* Added parameter `splitFields` to `Shopware.model.Container` to configure fieldset column layout
* Added interface `Shopware\Components\Captcha\CaptchaInterface`
* Added method `Shopware\Models\Order\Repository::getList()`
* Added method `Shopware\Models\Order\Repository::search()`
* Added method `Shopware\Models\Order\Repository::getDocuments()`
* Added method `Shopware\Models\Order\Repository::getDetails()`
* Added method `Shopware\Models\Order\Repository::getPayments()`
* Added responsive helper css/less classes in `_mixins/visibility-helper.less`
* Added method `Shopware\Bundle\MediaBundle\MediaServiceInterface::getFilesystem()` for direct access to the media filesystem
* Added config element `liveMigration` to enable or disable the media live migration
* Added config element `displayListingBuyButton` to display listing buy button
* Added service `shopware_search.batch_product_search` and `shopware_search.batch_product_number_search` for optimized product queries
* Added support for callback methods and jQuery promises in `jQuery.overlay` and `jQuery.loadingIndicators`
* Added jQuery method `setLoading()` to apply a loading indicator to an element `$('selector').setLoading()`
* Added required attribute `data-facet-name` for filter elements
* Added type for the filter panels `value-list-single`
* Added smarty blocks for `frontend_listing_filter_facet_multi_selection` for unified filter panel
* Added service `Shopware\Bundle\StoreFrontBundle\Service\Core\CategoryDepthService` to select categories by the given depth
* Added jQuery event `plugin/swListing/fetchListing` which allows to load listings, facet data or listing counts
* Added config element `listingMode` to switch listing reload behavior
* Added jQuery event `action/fetchListing` which allows to load listings, facet data or listing counts
* Added property `path` to `Shopware\Bundle\StoreFrontBundle\Struct\Media` which reflects the virtual path
* Added service `Shopware\Bundle\StoreFrontBundle\Service\Core\BlogService` to fetch blog entries by id
* Added template `themes/Frontend/Bare/frontend/detail/content.tpl`
* Added template `themes/Frontend/Bare/frontend/detail/content/header.tpl`
* Added template `themes/Frontend/Bare/frontend/detail/content/buy_container.tpl`
* Added template `themes/Frontend/Bare/frontend/detail/content/tab_navigation.tpl`
* Added template `themes/Frontend/Bare/frontend/detail/content/tab_container.tpl`
* Added option to select variants in `Shopware.apps.Emotion.view.components.Article` and `Shopware.apps.Emotion.view.components.ArticleSlider`
* Added local path to `@font-face` integration of the Open Sans font
* Added smarty block `frontend_register_billing_fieldset_company_panel` for registration
* Added smarty block `frontend_register_billing_fieldset_company_title` for registration
* Added smarty block `frontend_register_billing_fieldset_company_body` for registration
* Added smarty block `frontend_register_billing_fieldset_panel` for registration
* Added smarty block `frontend_register_billing_fieldset_title` for registration
* Added smarty block `frontend_register_billing_fieldset_body` for registration
* Added smarty block `frontend_register_index_cgroup_header_title` for registration
* Added smarty block `frontend_register_index_cgroup_header_body` for registration
* Added smarty block `frontend_register_index_advantages_title` for registration
* Added smarty block `frontend_register_login_customer_title` for registration
* Added smarty block `frontend_register_personal_fieldset_panel` for registration
* Added smarty block `frontend_register_personal_fieldset_title` for registration
* Added smarty block `frontend_register_personal_fieldset_body` for registration
* Added smarty block `frontend_register_shipping_fieldset_panel` for registration
* Added smarty block `frontend_register_shipping_fieldset_title` for registration
* Added smarty block `frontend_register_shipping_fieldset_body` for registration
* Added global date picker component `frontend/_public/src/js/jquery.datepicker.js` to Responsive theme
* Added filter facets for date and datetime fields
* Added template `themes/Frontend/Bare/frontend/listing/filter/facet-date.tpl` for date and datetime facets
* Added template `themes/Frontend/Bare/frontend/listing/filter/facet-date-multi.tpl` for date and datetime facets
* Added template `themes/Frontend/Bare/frontend/listing/filter/facet-date-range.tpl` for date and datetime facets
* Added template `themes/Frontend/Bare/frontend/listing/filter/facet-datetime.tpl` for date and datetime facets
* Added template `themes/Frontend/Bare/frontend/listing/filter/facet-datetime-multi.tpl` for date and datetime facets
* Added template `themes/Frontend/Bare/frontend/listing/filter/facet-datetime-range.tpl` for date and datetime facets
* Added class `.filter-panel--radio` to `themes/Frontend/Responsive/frontend/_public/src/less/_components/filter-panel.less`
* Added class `.filter-panel--checkbox` to `themes/Frontend/Responsive/frontend/_public/src/less/_components/filter-panel.less`
* Added class `.radio--state` to `themes/Frontend/Responsive/frontend/_public/src/less/_components/filter-panel.less`
* Added class `.checkbox--state` to `themes/Frontend/Responsive/frontend/_public/src/less/_components/filter-panel.less`
* Added JavaScript method `document.asyncReady()` to register callbacks which fire after the main script was loaded asynchronously.
* Added missing dependency `jquery.event.move` to the `package.json` file.
* Added template switch for `listing/index.tpl` to `listing/customer_stream.tpl` in case that the category contains a shopping world which is restricted to customer streams
* Added database column `s_emarketing_vouchers.customer_stream_ids` to restrict vouchers to customer streams.
* Added database column `s_emotion.customer_stream_ids` to restrict shopping worlds to customer streams.
* Added database table `s_customer_streams` for a list of all existing streams (`Shopware\Models\Customer\CustomerStream`)
* Added database table `s_customer_search_index` for a fast customer search
* Added database table `s_customer_streams_mapping` for mappings between customer and assigned streams
* Added bundle `Shopware\Bundle\CustomerSearchBundle` which defines how customers can be searched
* Added bundle `Shopware\Bundle\CustomerSearchBundleDBAL` which allows to search for customers using DBAL
* Added console command `sw:customer:search:index:populate` to generate customer stream search index
* Added console command `sw:customer:stream:index:populate` to generate customer stream mapping table
* Added flag `$hasCustomerStreamEmotion` in `frontend/home/index.tpl` to switch between emotions restricted to customer streams and those which are unrestricted
* Added route `/frontend/listing/layout` which loads the category page layout for customer streams. This route is called using `{action ...}` in case that the category contains an emotion with customer streams
* Added route `/frontend/listing/listing` which loads the category product listing. This route is called using `{action ...}` in case that the category contains an emotion with customer streams
* Added entity `Shopware\Models\Customer\CustomerStream` for attribute single and multi selection.
* Added translations for attribute labels. See below for more information.
* Added database structure for new emotion preset feature:
    * `s_emotion_presets` - contains all installed presets
    * `s_emotion_preset_translations` - contains presets translations
* Added models for presets
    * `Shopware\Models\Emotion\Preset`
    * `Shopware\Models\Emotion\PresetTranslation`
* Added classes for handling emotion preset feature
    * `Shopware\Components\Emotion\EmotionImporter` - handle emotion imports
    * `Shopware\Components\Emotion\EmotionExporter` - handle emotion exports
    * `Shopware\Components\Emotion\Preset\EmotionToPresetDataTransformer` - transform emotion to preset
    * `Shopware\Components\Emotion\Preset\PresetDataSynchronizer` - uses component handlers to support import / export of emotions  
    * `Shopware\Components\Emotion\Preset\PresetInstaller` - installer for preset plugins
    * `Shopware\Components\Emotion\Preset\PresetLoader` - loads presets and refreshes preset data to match current database
    * `Shopware\Components\Emotion\Preset\PresetMetaDataInterface` - interface to use for preset plugin development
* Added API Resource for emotion presets `Shopware\Components\Api\Resource\EmotionPreset`
* Added backend controller for emotion presets `Shopware\Controllers\Backend\EmotionPresets`
* Added compiler pass to register emotion component handlers `Shopware\Components\DependencyInjection\Compiler\EmotionPresetCompilerPass`
* Added component handlers for asset import and export of shopping world elements
    * `Shopware\Components\Emotion\Preset\ComponentHandler\BannderComponentHandler`
    * `Shopware\Components\Emotion\Preset\ComponentHandler\BannerSliderComponentHandler`
    * `Shopware\Components\Emotion\Preset\ComponentHandler\CategoryTeaserComponentHandler`
    * `Shopware\Components\Emotion\Preset\ComponentHandler\Html5VideoComponentHandler`
* Added new ExtJs views for emotion presets under `themes\backend\emotion\view\preset`
* Added new service tag for registering emotion preset component handlers `shopware.emotion.preset_component_handler`
* Added actions to import and export shopping worlds in `Shopware_Controllers_Backend_Emotion`
* Added condition class `Shopware\Bundle\SearchBundle\Condition\WidthCondition`
* Added condition class `Shopware\Bundle\SearchBundle\Condition\HeightCondition`
* Added condition class `Shopware\Bundle\SearchBundle\Condition\LengthCondition`
* Added condition class `Shopware\Bundle\SearchBundle\Condition\WeightCondition`
* Added facet class `Shopware\Bundle\SearchBundle\Facet\CombinedConditionFacet`
* Added facet class `Shopware\Bundle\SearchBundle\Facet\WidthFacet`
* Added facet class `Shopware\Bundle\SearchBundle\Facet\HeightFacet`
* Added facet class `Shopware\Bundle\SearchBundle\Facet\LengthFacet`
* Added facet class `Shopware\Bundle\SearchBundle\Facet\WeightFacet`
* Added `Shopware\Bundle\SearchBundleDBAL\VariantHelper` which joins all variants for dbal search
* Added smarty blocks `frontend_checkout_shipping_payment_core_button_top` and `frontend_checkout_shipping_payment_core_button_top` for shipping
* Added new Interface for facet result template switch `Shopware\Bundle\SearchBundle\TemplateSwitchable`
* Added `selecttree` and `combotree` config elements for plugins
* Added backend configuration option for the newsletter to configure if a captcha is required to subscribe to the newsletter
* Added two new Smarty blocks for menu and menu item overwrite possibility to the account sidebar
* Added LiveReload mode for the default grunt which reloads your browser window automatically after the grunt compilation was successful
* Added `nofollow` attribute to all links in the block `frontend_account_menu` since these links are now visible in the frontend if the account dropdown menu is activated
* Added `type` parameter to `Shopware_Controllers_Widgets_Listing::productSliderAction` and `Shopware_Controllers_Widgets_Listing::productsAction` which allows to load product sliders or product boxes.
* Added new search builder class `Shopware\Components\Model\SearchBuilder`
* Added new search builder as `__construct` parameter in `Shopware\Bundle\AttributeBundle\Repository\Searcher\GenericSearcher`
* Added new `FunctionNode` for IF-ELSE statements in ORM query builder
* Added `/address` to robots.txt 
* Added snippet `DetailBuyActionAddName` in `snippets/frontend/detail/buy.ini`
* Added `Shopware\Components\Template\Security` class for all requests.
* Added whitelist for allowed php functions and php modifiers in smarty
    * `template_security.php_modifiers`
    * `template_security.php_functions`
* Added new column `do_not_split` to table `s_search_fields`. Activate to store the values of this field as given into the search index. If not active, the default behaviour is used
* Added new service `shopware_storefront.price_calculator` which calculates the product price. Was formerly a private method in `shopware_storefront.price_calculation_service`
* Added service `shopware_media.extension_mapping` to provide a customizable whitelist for media file extensions and their type mapping
* Changed theme path for new plugins from `/resources` into `/Resources`
* Changed sorting of `Shopware.listing.FilterPanel` fields
* Changed database column `s_articles_vote`.`answer_date` to allow `NULL` values
* Changed `LastArticle` plugin config elements `show`, `controller` and `time` to be prefixed with `lastarticles_`
* Changed product listings in shopping worlds to only be loaded if `showListing` is true
* Changed sql query
 in `sAdmin` queries which uses a sub query for address compatibility, following functions affected:
    * `sAdmin::sGetDispatchBasket`
    * `sAdmin::sGetPremiumDispatches`
    * `sAdmin::sGetPremiumDispatchSurcharge`
* Changed attribute type `string` mapping to mysql `TEXT` type. String and single selection data type supports no longer a sql default value.
* Changed `roundPretty` value for currency range filter
* Changed `CategoryFacet` behavior to generate each time a tree based on the system category with a configured category depth
* Changed facet templates `facet-radio`, `facet-media-list` and `facet-value-list` into one template
* Renamed parameter `data-count-ctrl` on `#filter` form to `data-listing-url`
* Changed removal version of method `Shopware\Components\Model\ModelManager::addAttribute` to 5.4
* Changed removal version of method `Shopware\Components\Model\ModelManager::removeAttribute` to 5.4
* Changed template `component_article_slider.tpl` to show provided products instead of always fetching them via ajax
* Changed emotion preview to not save the current state before showing preview
* Changed command `sw:thumbnail:cleanup` to search the filesystem to remove orphaned thumbnails
* Changed configuration `defaultListingSorting` from the performance module to basic settings in `categories / listings`
* Changed the jQuery plugin `src/js/jquery.selectbox-replacement.js` to be used only as a polyfill. Use the CSS-only version for select fields instead.
* Changed template filename from `frontend/forms/elements.tpl` to `frontend/forms/form-elements.tpl`
* Changed smarty block from `frontend_forms_index_elements` to `frontend_forms_index_form_elements`
* Changed smarty blocks from `frontend_forms_elements*` to `frontend_forms_form_elements*`
* Changed template file `themes/Frontend/Bare/frontend/detail/index.tpl` to split it into separated files
* Changed property `linkDetails` of `$sArticle`
* Changed the article url to also contain the order number of the product
* Changed the product selection to variant selection in `Shopware.apps.Emotion.view.components.BannerMapping`
* Changed the integration of `modernizr.js` and added it to the compressed main JavaScript files
* Changed the script tag for the generated JavaScript file for asynchronous loading, can be changed in theme configuration
* Changed the inline script for the statistics update to vanilla JavaScript
* Changed event name from `plugin/swAjaxProductNavigation/onSetProductState` to `plugin/swAjaxProductNavigation/onGetProductState`
* Changed behavior of the smarty rendering in forms fields comment. See below for more information
* Changed behavior of the tracking url rendering. See below for more information
* Changed text color and height of `.filter--active` in `themes/Frontend/Responsive/frontend/_public/src/less/_components/filter-panel.less`
* Changed database column `s_articles_details.instock` to allow `NULL` values and default to `0`
* Changed return values so the array keys are now the respective country/state IDs in `\Shopware\Bundle\StoreFrontBundle\Service\Core\LocationService::getCountries`
* Moved the removal of the whole cache folder after the removal of the `.js` and `.css` files for better handling of huge caches in the `clear_cache.sh` script
* Changed `Shopware_Controllers_Widgets_Listing::streamSliderAction` to `Shopware_Controllers_Widgets_Listing::streamAction`
* Changed `Shopware_Controllers_Widgets_Listing::productSliderAction` to `Shopware_Controllers_Widgets_Listing::productsAction`
* Changed snippet `DetailBuyActionAdd` in `snippets/frontend/detail/buy.ini`, it now contains <span> tags
* Changed snippet `ListingBuyActionAdd` in `snippets/frontend/listing/box_article.ini`, it now contains another <span> tag
* Merged `account/sidebar.tpl` and `account/sidebar_personal.tpl`
* Moved snippets from `account/sidebar_personal.ini` to `account/sidebar.ini`
* Changed `Enlight_Hook_ProxyFactory` to use [ocramius/proxy-manager](https://github.com/Ocramius/ProxyManager) for generating proxy classes
* Backend customer listing is now loaded in `Shopware_Controllers_Backend_CustomerQuickView`
* Refactored backend customer module. Please take a look into the different template files to see what has changed.
* Removed import / export module
* Removed unused `Zend Framework Components`
* Removed alias support from `Enlight_Controller_Request_Request` (`getAlias`, `getAliases`, `setAlias`)
* Removed configuration option `sCOUNTRYSHIPPING`
* Removed constants of `\Shopware\Bundle\SearchBundle\CriteriaRequestHandler\CoreCriteriaRequestHandler` and `Shopware\Bundle\SearchBundle\StoreFrontCriteriaFactory`:
    * `SORTING_RELEASE_DATE`
    * `SORTING_POPULARITY`
    * `SORTING_CHEAPEST_PRICE`
    * `SORTING_HIGHEST_PRICE`
    * `SORTING_PRODUCT_NAME_ASC`
    * `SORTING_PRODUCT_NAME_DESC`
    * `SORTING_SEARCH_RANKING`
* Removed route `/backend/performance/listingSortings`
* Removed route `/backend/customer/getList`
* Removed event `Shopware_Plugins_HttpCache_ShouldNotCache`
* Removed `eval` from block `frontend_forms_index_headline` in `index.tpl` of `themes\Frontend\Bare\frontend\forms` for `$sSupport.text`
* Removed cleanupPlugins from `Shopware\Bundle\PluginInstallerBundle\Service`

## Shopware 5.2

### System requirements changes

The required PHP version is now **PHP 5.6.4 or higher**. Please check your system configuration and update your PHP version if necessary. If you are using a PHP version prior to 5.6.4 there will be errors.

The required ionCube Loader version was bumped to 5.0 or higher.

### Shopping worlds

<div class="alert alert-info">
<strong>Important tasks after updating to Shopware 5.2</strong><br/>
Due the removal of the <code>masonry</code> mode, it is necessary to update the plugins <strong>Emotion Advanced</strong> and <strong>Digital Publishing</strong> to the latest version. You won't be able to open any shopping world unless you've updated the plugins. After these steps, please verify that your shopping worlds are still working and look like they should.
</div>

The shopping worlds have been refactored which results in important changes for plugin developers.

* The `masonry` mode has been replaced with a new mode called `fluid`. Both modes automatically create a pleasant user experience when viewing
a shopping world on smaller viewports than intended by the creator. Since the `masonry` jQuery plugin is no longer needed, it has been removed from the library.

* The fields and the corresponding SEO URL are now translatable.

* Landing pages are now independent from categories since they don't always have to belong to them. Instead, they can now be assigned to multiple subshop's or language shop's, which makes it possible to generate individual SEO urls per shop. Therefore, category teaser images are no longer necessary and have been removed.

* To enable your widgets to be translatable, make sure to set the value in `s_library_component_field`.`translatable` to `1`.

* Every element now has its own viewport settings which are saved in `s_emotion_element_viewports`. These settings include position and visibility for each viewport.

* You can define a default config for each custom widget. This can include settings like maximal height, maximal width or an icon. For more information, refer to the base file in `themes/Backend/ExtJs/backend/emotion/view/detail/elements/base.js`.

To make overriding the widget configuration easy, there are new blocks in `widgets/emotion/index.tpl`:
* `widgets/emotion/index/attributes`
* `widgets/emotion/index/config`
* `widgets/emotion/index/element/config`


### CSRF protection

Shopware now includes CSRF protection which is enabled by default. Every ajax and form request
has to provide a CSRF token and will fail if it doesn't.

The header `X-CSRF-Token` is automatically added to every ajax request in the back- and frontend. In addition, the field `__csrf_token` will automatically be added to every frontend html form.

If you decide to disable the CSRF protection for specific controller actions, you have to implement the interface `Shopware\Components\CSRFWhitelistAware` and provide a list of actions you want to disable the protection for.
In case you want to disable the CSRF protection entirely, you can change the option `csrfProtection` in your `config.php` file. Please keep in mind that this is a potential security risk and we do not recommend to disable the CSRF protection.

To learn more about the new CSRF protection, refer to the [CSRF Protection Guide](/developers-guide/csrf-protection/).

### Display errors

The PHP configuration [`display_errors`](http://php.net/manual/en/errorfunc.configuration.php#ini.display-errors) defaults to `0` / `off` now.
This means for errors that happen early in the shopware stack no errors are shown to the user.
To show these early errors the `display_errors` configuration can be set back to `1` / `on` by adding the following to your `config.php` file:

```
'phpsettings' => [
    'display_errors' => 1,
],
```

This was changed to prevent information leakage of security sensitive data like usernames or directories in the error messages.

### Account / Registration

The account section and registration have been refactored to continue the refactoring of core classes.

* Changes to model `\Shopware\Models\Customer\Customer`
    * Added `title`, `salutation`, `firstname`, `lastname`, `defaultBillingAddress` and `defaultShippingAddress`
    * Moved `customernumber` and `birthday` from `Billing` to the `Customer`
    * Deprecated `billing` and `shipping`
        * Use `defaultBillingAddress` and `defaultShippingAddress` instead
* The register controller has been completely rewritten.
    * Uses the new service `shopware_account.register_service`
    * Methods of core class `\sAdmin` regarding the registration have been removed without substitution.
    * Templates may have been rewritten
        * For a complete list of template and event changes, refer to the [UPGRADE.md](https://github.com/shopware/shopware/blob/5.3/UPGRADE-5.2.md).

### Address management

<div class="alert alert-info">
<strong>Important tasks after updating to Shopware 5.2</strong><br/>
Existing adresses including their attributes have been migrated into <code>s_user_addresses</code>. Please verify that all addresses have been merged completely. Addresses have been read from <code>s_user_billingaddress</code>, <code>s_user_shippingaddress</code>, <code>s_order_billingaddress</code>, <code>s_order_shippingaddress</code>.
</div>

The address management allows a customer to manage more than only one address which gets changed with every order. The customer is now able to create more address, e.g. for home and work, and use them later on in an order without loosing all existing address data. He can just change the reference to the default billing address, instead of changing it entirely.

* Shopware versions prior to 5.2 were using the tables `s_user_billingaddress` and `s_user_shippingaddress` which have now been marked as deprecated as well as their associated models `\Shopware\Models\Customer\Billing` and `\Shopware\Models\Customer\Shipping`. Their association in `\Shopware\Models\Customer\Customer` will be removed with Shopware version 5.3. Please use `s_user_addresses` and it's model `\Shopware\Models\Customer\Address` instead.
    * Changes to the new model will automatically be synchronised with the old models.
    * Changes to the old models or tables **won't** be synchronised to the new model and will be overwritten in case that the default billing or shipping address model changes.
* The new model is associated with the customer using the `$defaultBillingAddress` and `$defaultShippingAddress` properties. The associations are no longer managed by the customer which implies, that you have to use the new `shopware_account.address_service` to make changes to addresses.
* Selecting another address in the checkout results in a change of the session key `checkoutBillingAddressId` or `checkoutShippingAddressId` with the corresponding address id. After the order has been saved, the session keys will be reset.
* The customer api endpoint now uses the structure of the address model, instead of the billing or shipping model
* The checkout templates have been rewritten which results in changed and removed blocks.
    * For a complete list of template changes, refer to the [UPGRADE.md](https://github.com/shopware/shopware/blob/5.3/UPGRADE-5.2.md).

To learn more about the new address service, refer to the [Address Management Guide](/developers-guide/address-management-guide).

### Attribute management

A new attribute management has been created to enable shop owners to create attributes in the backend without writing an own plugin or touching the database itself.

* 39 different attribute tables are now manageable using the new UI.
* Create, update and delete fields for every attribute using a simple UI.
    * Updating the attribute structure of an entity will automatically regenerate the appropriate attribute entities. You may have to reload the module to see your changes.
    * Attributes are now translatable, if defined so.
    * Attributes are now typed. Custom types can be created too.
* Nearly every module in Shopware now contains an attribute field set by default.

Please note, that the table `s_core_engine_elements` is still present, but will be ignored by the attribute system.

#### For frontend developers

New services were introduced to read and write attributes in the frontend. The most common services are `shopware_attribute.data_loader` and `shopware_attribute.data_persister`.

##### Loading attributes

To get all attributes for a specific item, you have to use the `shopware_attribute.data_loader` and provide the attribute table and the foreign key.

This example will fetch all attributes for the order with id 15.

```php
/** @var array $attributes */
$attributes = $this->get('shopware_attribute.data_loader')->load('s_order_attributes', 15);
```

If there is no attribute entry for the provided foreign key, `$attributes` will be false.

##### Saving attributes

To save the attributes back into the database, you have to use the `shopware_attribute.data_persister` and provide the data, the attribute table and the foreign key.
Non-existent attributes will be created automatically.
You don't have to strip array keys like `id` or foreign keys like `orderID` from the data as they will be removed by the service.

This example will save the fetched attribute data in `$attributes` from above back to the database.

```php
$this->get('shopware_attribute.data_persister')->persist($attributes, 's_order_attributes', 152);
```

In case there went something wrong, you'll get an exception.

#### For backend developers

The most common implementation is the `Shopware.attribute.Form` component that is loaded in all backend modules in order to display attributes.
There are also components for a grid or button. All components regarding attributes can be found in `themes/backend/base/attribute`.

##### Implement the Shopware.attribute.Form component

In order to implement the form component, you have to create a new instance of `Shopware.attribute.Form` and provide at least the attribute table.

This example will implement the attribute form for orders.

```js
var attributeForm = Ext.create('Shopware.attribute.Form', {
    table: 's_order_attributes'
});
```

You now have to add the `attributeForm` to your window or another existing component. To enable translation for the fields, you have to set `translatable` to  `true`. Refer to the readme file below to see all available options.

After adding the component, you are ready to load the data.

##### Loading attributes

To load the attributes into an attribute component, you have to call `loadAttribute()` and provide the foreign key.

```js
// loading attributes with a static foreign key
attributeForm.loadAttribute(5);

// or using a record
attributeForm.loadAttribute(record.get('id'));
```

This call will send a new request which loads all attributes for this item.

You can also provide a callback method which will be called after the request has been completed.

```js
attributeForm.loadAttribute(record.get('id'), function () {
    alert('attributes have been loaded!');
});
```

##### Saving attributes

Saving the attributes back to the database is almost the same as reading them. You just have to call `saveAttribute()` on the form component and provide the foreign key.

```js
// loading attributes with a static foreign key
attributeForm.saveAttribute(5);

// or using a record
attributeForm.saveAttribute(record.get('id'));
```

You can also provide a callback method which will be called after the request has been completed.

```js
attributeForm.saveAttribute(record.get('id'), function (successful) {
    if (successful) {
        alert('attributes were save successfully!');
    } else {
        alert('attributes were not saved');
    }
});
```

This call will send a new request which saves all attributes for this item.

To learn more about the new attribute management, refer to the [README.md](https://github.com/shopware/shopware/blob/5.3/engine/Shopware/Bundle/AttributeBundle/README.md) file in the source code.

### Library updates

* Updated Symfony Components to version 2.8 LTS
* Updated `monolog/monolog` to version 1.17.2
* Updated `ongr/elasticsearch-dsl` to v2.0.0, see [ElasticsearchDSL changelog](https://github.com/ongr-io/ElasticsearchDSL/blob/master/CHANGELOG.md#v200-2016-03-03) for backwards compatibility breaks.
* Updated `elasticsearch/elasticsearch` to v2.2.0

### Replacements

* Replaced `bower` with `npm` to manage the frontend dependencies
    * Removed the file `vendors/less/open-sans-fontface/open-sans.less`. It's now located under `public/src/less/_components/fonts.less`
    * The dependencies can now be installed using the command: `npm install && npm run build`
* Replaced all occurrences with one unified product slider
    * Created template files
        * `themes/Frontend/Bare/frontend/_includes/product_slider.tpl`
        * `themes/Frontend/Bare/frontend/_includes/product_slider_item.tpl`
        * `themes/Frontend/Bare/frontend/_includes/product_slider_items.tpl`
    * Created template blocks
        * `frontend_common_product_slider_config`
        * `frontend_common_product_slider_component`
        * `frontend_common_product_slider_container`
        * `frontend_common_product_slider_items`
        * `frontend_common_product_slider_item_config`
        * `frontend_common_product_slider_item`
    * Removed template blocks
        * `checkout_ajax_add_cross_slider_item`
        * `frontend_detail_index_streams_slider_container`
        * `frontend_detail_index_similar_slider_item`
        * `widget_emotion_component_product_slider`
        * `widgets_listing_top_seller_slider_container`
        * `widgets_listing_top_seller_slider_container_inner`
        * `widgets_listing_top_seller_slider_container_include`
        * `frontend_detail_index_also_bought_slider_inner`
        * `frontend_detail_index_similar_viewed_slider_inner`
        * `frontend_widgets_slide_articles_item`
    * Removed template files
        * `themes/Frontend/Bare/widgets/emotion/slide_articles.tpl`

### Deprecations

<div class="alert alert-info" role="alert">
<strong>Note:</strong> Deprecated methods now use <code>trigger_error</code> of type <code>E_USER_DEPRECATED</code>.
</div>

* `Enlight_Application::ComponentsPath()` / `Shopware()->ComponentsPath()`.
* `Enlight_Application::CorePath()` / `Shopware()->CorePath()`.
* `Enlight_Application::DS()`.
* `Enlight_Application::Instance()` and `Enlight()`, use `Shopware()` instead.
* `Enlight_Application::Path()` / `Shopware()->Path()`.
* `Enlight_Application`.
* `initMasonryGrid` method and `plugin/swEmotion/onInitMasonryGrid` event in `jquery.emotion.js`.
* `Shopware()->Models()->__call()`.
* `Shopware::App()` / `Shopware()->App()`.
* `Shopware::Environment()` / `Shopware()->Environment()`.
* `Shopware::OldPath()` / `Shopware()->OldPath()`.
* `Shopware::setEventManager()` / `Shopware()->setEventManager()`.
* `Shopware\Kernel::getShopware()`.
* `Shopware_Bootstrap` and `Enlight_Bootstrap`, commonly accessed by `Shopware()->Bootstrap()`.
* `Shopware\Models\Article\Element`.
* `Shopware\Models\Customer\Billing`.
* `Shopware\Models\Customer\Shipping`.
* `Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface::getContext`
* `Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface::getProductContext`
* `Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface::getLocationContext`
* `Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface::initializeContext`
* `Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface::initializeLocationContext`
* `Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface::initializeProductContext`
* `Shopware\Bundle\StoreFrontBundle\Struct\LocationContext`
* `Shopware\Bundle\StoreFrontBundle\Struct\ProductContext`
* `Shopware\Bundle\StoreFrontBundle\Struct\LocationContextInterface`
* `Shopware\Bundle\StoreFrontBundle\Struct\ProductContextInterface`
* Database field `s_articles_prices.baseprice`. All data is left intact but this field is not used in shopware anymore and will be dropped in a future version.

### Removals

<div class="alert alert-info" role="alert">
    <strong>Note:</strong> This section covers only the most relevant removals. Please refer to the UPGRADE.MD file in your Shopware installation for a complete, detailed list of removed elements
</div>

#### Article related

* Database field `s_article_configurator_template_prices.baseprice`.
* Methods `Shopware\Models\Article\Price::getBasePrice()` and `Shopware\Models\Article\Price::setBasePrice()`.
* Property `basePrice` of `Shopware\Models\Article\Configurator\Template\Price`.
* Property `basePrice` of `Shopware\Models\Article\Price`.

#### Removed attribute associations

* Backend components
    * `Shopware.apps.Banner.model.BannerDetail`
    * `Shopware.apps.Blog.model.Detail`
    * `Shopware.apps.Config.model.form.Country`
    * `Shopware.apps.Customer.model.Customer`
    * `Shopware.apps.Emotion.model.Emotion`
    * `Shopware.apps.Form.model.Form`
    * `Shopware.apps.MediaManager.model.Media`
    * `Shopware.apps.Order.model.Order`
    * `Shopware.apps.Order.model.Position`
    * `Shopware.apps.Order.model.Receipt`
    * `Shopware.apps.Property.model.Set`
    * `Shopware.apps.Supplier.model.Supplier`
    * `Shopware.apps.Voucher.model.Detail`

* Components and files
    * `Shopware.apps.Banner.model.Attribute`
    * `Shopware.apps.Blog.model.Attribute`
    * `Shopware.apps.Config.model.form.Attribute`
    * `Shopware.apps.Customer.model.Attribute`
    * `Shopware.apps.Customer.model.BillingAttributes`
    * `Shopware.apps.Customer.model.ShippingAttributes`
    * `Shopware.apps.Emotion.model.Attribute`
    * `Shopware.apps.Form.model.Attribute`
    * `Shopware.apps.MediaManager.model.Attribute`
    * `Shopware.apps.Order.model.Attribute`
    * `Shopware.apps.Order.model.BillingAttribute`
    * `Shopware.apps.Order.model.PositionAttribute`
    * `Shopware.apps.Order.model.ReceiptAttribute`
    * `Shopware.apps.Order.model.ShippingAttribute`
    * `Shopware.apps.Property.model.Attribute`
    * `Shopware.apps.Supplier.model.Attribute`
    * `Shopware.apps.Voucher.model.Attribute`
    * `themes/Backend/ExtJs/backend/blog/view/blog/detail/sidebar/attributes.js`
    * `themes/Backend/ExtJs/backend/config/model/form/attribute.js`
    * `themes/Backend/ExtJs/backend/config/store/form/attribute.js`
    * `themes/Backend/ExtJs/backend/config/view/form/attribute.js`

#### Database tables

* `s_user_debit`

#### Database columns

* `s_filter_values`
    * `value_numeric`
* `s_filter_options`
    * `default`
* `s_user_billingaddress`
    * `fax`
    * `customernumber`
* `s_order_billingaddress`
    * `fax`
* `s_core_menu`
    * `hyperlink`
    * `style`
    * `resourceID`

#### Models

* Entire files
    * `Shopware.apps.Customer.view.detail.Billing`
    * `Shopware.apps.Customer.view.detail.Shipping`
* Properties
    * `Shopware\Models\Customer\Customer`
        * `$debit`
    * `Shopware\Models\Customer\Billing`
        * `$number`
        * `$birthday`

#### Classes

* Unused
    * `Shopware\Bundle\StoreFrontBundle\Struct\Context`
    * `Shopware_Components_Menu_Item`
    * `Shopware_Components_Menu_SaveHandler_DbTable`
    * `Shopware_Models_Payment`
    * `Shopware_Models_PaymentManager`
    * `Shopware_Plugins_Frontend_Payment_Bootstrap` known as `Shopware()->Payments()`

#### Events & Hooks

* Hooks
    * `sArticles::calculateCheapestBasePriceData::after`
    * `sArticles::calculateCheapestBasePriceData::replace`
    * `sArticles::getArticleListingCover::after`
    * `sArticles::getArticleListingCover::replace`
    * `sArticles::sCalculatingPrice::replace`
    * `sArticles::sCalculatingPrice::replace`
    * `sArticles::sGetArticlePictures::after`
    * `sArticles::sGetArticlePictures::replace`
    * `sArticles::sGetArticleProperties::after`
    * `sArticles::sGetArticleProperties::replace`
    * `sArticles::sGetArticlesAverangeVote::after`
    * `sArticles::sGetArticlesAverangeVote::replace`
    * `sArticles::sGetArticlesVotes::after`
    * `sArticles::sGetArticlesVotes::replace`
    * `sArticles::sGetCheapestPrice::after`
    * `sArticles::sGetCheapestPrice::replace`
    * `sArticles::sGetPricegroupDiscount::after`
    * `sArticles::sGetPricegroupDiscount::replace`
    * `sArticles::sGetUnit::after`
    * `sArticles::sGetUnit::replace`

* Events
    * `Shopware_Modules_Articles_GetArticleById_FilterArticle`
    * `Shopware_Modules_Articles_GetPromotionById_FilterResult`
    * `Shopware_Modules_Admin_SaveRegisterMainData_FilterSql`
    * `Shopware_Modules_Admin_SaveRegisterMainData_Return`
    * `Shopware_Modules_Admin_SaveRegisterMainData_Return`
    * `Shopware_Modules_Admin_SaveRegisterBilling_FilterSql`
    * `Shopware_Modules_Admin_SaveRegisterBilling_Return`
    * `Shopware_Modules_Admin_SaveRegisterShipping_FilterSql`
    * `Shopware_Modules_Admin_SaveRegisterShipping_Return`
    * `Shopware_Modules_Admin_SaveRegister_Start`
    * `Shopware_Modules_Admin_SaveRegister_GetCustomerNumber`
    * `Shopware_Modules_Admin_SaveRegister_FilterNeededFields`
    * `Shopware_Modules_Admin_SaveRegister_FilterErrors`

#### Smarty template files, blocks and their snippets

* Files
    * `frontend/account/billing.tpl`
    * `frontend/account/billing_checkout.tpl`
    * `frontend/account/content_right.tpl`
    * `frontend/account/select_address.tpl`
    * `frontend/account/select_billing.tpl`
    * `frontend/account/select_billing_checkout.tpl`
    * `frontend/account/select_shipping.tpl`
    * `frontend/account/select_shipping_checkout.tpl`
    * `frontend/account/shipping.tpl`
    * `frontend/account/shipping_checkout.tpl`
    * `frontend/checkout/cart_left.tpl`
    * `frontend/checkout/confirm_left.tpl`
    * `frontend/campaign/box.tpl`

* Blocks
    * `frontend_blog_index_campaign_bottom`
    * `frontend_blog_index_campaign_middle`
    * `frontend_blog_index_campaign_top`
    * `frontend_checkout_finish_info`
    * `frontend_index_left_campaigns_bottom`
    * `frontend_index_left_campaigns_middle`
    * `frontend_index_left_campaigns_top`

#### jQuery Plugins

* masonry

#### Methods & Actions

* Methods
    * `Enlight_Application::getOption()`
    * `Enlight_Application::getOptions()`
    * `Enlight_Application::setIncludePaths()`
    * `Enlight_Application::setOptions()`
    * `Enlight_Application::setPhpSettings()`
    * `Enlight_Application::__callStatic()`
    * `sAdmin::sGetPreviousAddresses()`
    * `sAdmin::sUpdateAccount()`
    * `sAdmin::sUpdateBilling()`
    * `sAdmin::sUpdateShipping()`
    * `sAdmin::sValidateStep1()`
    * `sAdmin::sValidateStep2()`
    * `sAdmin::sValidateStep2ShippingAddress()`
    * `sAdmin::sSaveRegisterMainData()`
    * `sAdmin::sSaveRegisterNewsletter()`
    * `sAdmin::sSaveRegisterBilling()`
    * `sAdmin::sSaveRegisterShipping()`
    * `sAdmin::sSaveRegister()`
    * `sAdmin::validateRegistrationFields()`
    * `sAdmin::assignCustomerNumber()`
    * `sAdmin::logRegistrationMailException()`
    * `sOrder::sManageEsdOrder()`
    * `Shopware\Models\Menu\Repository::addItem()`
    * `Shopware\Models\Menu\Repository::save()`
    * `Shopware\Models\Emotion\Repository::getCampaignByCategoryQuery()`
    * `Shopware\Bundle\PluginInstallerBundle\Service\InstallerService::getPluginBootstrap()`
    * `Shopware\Models\Menu\Menu::setStyle()`
    * `Shopware\Models\Menu\Menu::getStyle()`
    * `Shopware_Controllers_Frontend_Account::validatePasswordResetForm()`
    * `Shopware_Controllers_Frontend_Account::resetPassword()`
    * `Shopware_Controllers_Backend_ImportExport::getCustomerRepository()`
    * `Shopware_Controllers_Backend_ImportExport::exportCustomersAction()`
    * `Shopware_Controllers_Backend_ImportExport::importCustomers()`
    * `Shopware_Controllers_Backend_ImportExport::saveCustomer()`
    * `Shopware_Controllers_Backend_ImportExport::prepareCustomerData()`
    * The following repository methods no longer select attributes or have been removed entirely
        * `\Shopware\Models\Article\Repository::getSupplierQueryBuilder()`
        * `\Shopware\Models\Banner\Repository::getBannerMainQuery()`
        * `\Shopware\Models\Blog\Repository::getBackedDetailQueryBuilder()`
        * `\Shopware\Models\Customer\Repository::getAttributesQuery()`
        * `\Shopware\Models\Customer\Repository::getAttributesQueryBuilder()`
        * `\Shopware\Models\Customer\Repository::getBillingAttributesQuery()`
        * `\Shopware\Models\Customer\Repository::getBillingAttributesQueryBuilder()`
        * `\Shopware\Models\Customer\Repository::getCustomerDetailQueryBuilder()`
        * `\Shopware\Models\Customer\Repository::getShippingAttributesQuery()`
        * `\Shopware\Models\Customer\Repository::getShippingAttributesQueryBuilder()`
        * `\Shopware\Models\Emotion\Repository::getEmotionDetailQueryBuilder()`
        * `\Shopware\Models\Order\Repository::getBackendAdditionalOrderDataQuery()`
        * `\Shopware\Models\Order\Repository::getBackendOrdersQueryBuilder()`
        * `\Shopware\Models\ProductFeed\Repository::getDetailQueryBuilder()`

* Actions
    * `Shopware_Controllers_Frontend_Account::billingAction()`
    * `Shopware_Controllers_Frontend_Account::shippingAction()`
    * `Shopware_Controllers_Frontend_Account::saveAccount()`
    * `Shopware_Controllers_Frontend_Account::saveBillingAction()`
    * `Shopware_Controllers_Frontend_Account::saveShippingAction()`
    * `Shopware_Controllers_Frontend_Account::selectBillingAction()`
    * `Shopware_Controllers_Frontend_Account::selectShippingAction()`
    * `Shopware_Controllers_Frontend_Register::saveRegister()`
    * `Shopware_Controllers_Frontend_Register::personalAction()`
    * `Shopware_Controllers_Frontend_Register::savePersonalAction()`
    * `Shopware_Controllers_Frontend_Register::billingAction()`
    * `Shopware_Controllers_Frontend_Register::saveBillingAction()`
    * `Shopware_Controllers_Frontend_Register::shippingAction()`
    * `Shopware_Controllers_Frontend_Register::saveShippingAction()`
    * `Shopware_Controllers_Frontend_Register::paymentAction()`
    * `Shopware_Controllers_Frontend_Register::savePaymentAction()`
    * `Shopware_Controllers_Frontend_Register::validatePersonal()`
    * `Shopware_Controllers_Frontend_Register::setRegisterData()`
    * `Shopware_Controllers_Frontend_Register::validateBilling()`
    * `Shopware_Controllers_Frontend_Register::validateShipping()`
    * `Shopware_Controllers_Frontend_Register::validatePayment()`

#### View variables

* Global
    * `client_check`
    * `referer_check`
    * jQuery controller endpoints
        * `ajax_login`
        * `ajax_logout`
* Blog
    * `media.path`
* Banner mappings
    * `file`
* Shopping worlds
    * `landingPageTeaser`
    * `landingPageBlock`
* Product listing
    * `sArticle.sVoteAverange`
    * `sBanner.img`
    * `sCategoryInfo`
* Note listing
    * `sNote.sVoteAverange`

### Method signature changes

* Constructor of `\Shopware\Bundle\PluginInstallerBundle\Service\DownloadService`
    * Now expects an `array` of strings representing plugin directories as second parameter (additionally)
* Constructor of `\Shopware\Components\Theme\PathResolver`
    * Now expects an `array` of strings representing plugin directories as second parameter (additionally)
* Constructor of `\Shopware_Components_Snippet_Manager`
    * Now expects an `array` of strings representing plugin directories as second parameter (additionally)
* `Shopware\Bundle\SearchBundleDBAL\PriceHelper::getSelection`
    * Now expects `ProductContextInterface` instead of `ShopContextInterface`
* `Shopware\Bundle\SearchBundleDBAL\PriceHelperInterface::getSelection`
    * Now expects `ProductContextInterface` instead of `ShopContextInterface`
* `Shopware\Bundle\StoreFrontBundle\Gateway\GraduatedPricesGatewayInterface`
    * Requires now a provided `ShopContextInterface`
* `Shopware\Bundle\StoreFrontBundle\Service\CheapestPriceServiceInterface::getList`
    * Now expects `ProductContextInterface` instead of `ShopContextInterface`
* `Shopware\Bundle\StoreFrontBundle\Service\CheapestPriceServiceInterface::get`
    * Now expects `ProductContextInterface` instead of `ShopContextInterface` and `ListProduct` instead of `BaseProduct`
* `Shopware\Bundle\StoreFrontBundle\Service\Core\CheapestPriceService::getList`
    * Now expects `ProductContextInterface` instead of `ShopContextInterface`
* `Shopware\Bundle\StoreFrontBundle\Service\Core\CheapestPriceService::get`
    * Now expects `ProductContextInterface` instead of `ShopContextInterface` and `ListProduct` instead of `BaseProduct`
* `Shopware\Bundle\SearchBundleES\ConditionHandler\ProductAttributeConditionHandler`
    * Now expects `\Shopware\Bundle\AttributeBundle\Service\CrudService` as constructor parameter
* `\sAdmin::sManageRisks`
    * Removed `$basket` from parameters

### Other changes

* Removed Session variables `__SW_REFERER` and `__SW_CLIENT`.
* Added AdvancedMenu feature to configure menu opening delay on mouse hover
* Added composer dependency for Symfony Form and implemented FormBundle
* Added creation of custom `__construct()` method to `Shopware\Components\Model\Generator`, which initializes any default values of properties when generating attribute models
* Added HTML code widget for the shopping worlds which lets the user enter actual Smarty & JavaScript code which will be included like it is
    * The Smarty code has access to all globally available Smarty variables
* Added new blocks to `widgets/emotion/index.tpl` for better overriding of the configuration.
    * `widgets/emotion/index/attributes`
    * `widgets/emotion/index/config`
    * `widgets/emotion/index/element/config`
* Added new configuration field to the emotion banner widget for link target.
* Added new database field `s_articles_details.purchaseprice`.
* Added polyfill for `random_bytes()` and `random_int()` via `paragonie/random_compat`
* Added property `purchasePrice` to `Shopware\Models\Article\Detail`.
* Added service `shopware.number_range_manager` for safely retrieving the next number of a number range (`s_order_number`)
* Added the ability to add custom CSS classes to emotion elements in the backend.
    * Added new `css_class` column to the `s_emotion_elements` table.
    * Multiple classnames can be added by separating them with whitespaces.
* Added the following fields to status emails:
    * `billing_additional_address_line1`
    * `billing_additional_address_line2`
    * `shipping_additional_address_line1`
    * `shipping_additional_address_line2`
* Added validation of order number to `Shopware\Components\Api\Resource\Variant::prepareData()` to respond with meaningful error message for duplicate order numbers
* Categories of `Shopware\Components\Api\Resource\Article::getArticleCategories($articleId)` are no longer indexed by category id
* Changed default error_reporting to `E_ALL & ~E_USER_DEPRECATED`
* Changed markup and styling on checkout confirm and finish page
* Changed position of `Shopware.apps.Customer.view.detail.Billing` fields
* Changed position of `Shopware.apps.Customer.view.detail.Shipping` fields
* Changed the following methods to use the `shopware.number_range_manager` service for retrieving the next number of a range:
    * `sAdmin::assignCustomerNumber()`
    * `Shopware_Components_Document::saveDocument()`
    * `sOrder::sGetOrderNumber()`
* Fixed Shopware.form.plugin.Translation, the plugin can now be used in multiple forms at the same time.
    * Removed `clear`, `onOpenTranslationWindow`, `getFieldValues` and `onGetTranslatableFields` function
* HttpCache: Added possibility to add multiple, comma separated proxy URLs
* Moved block `frontend_checkout_confirm_left_billing_address` outside panel body
* Moved block `frontend_checkout_confirm_left_shipping_address` outside panel body
* Moved `<form>` element in checkout confirm outside the agreement box to wrap around address and payment boxes
* Moved `s_articles_prices.baseprice` to `s_articles_details.purchaseprice`
* Renamed block 'frontend_blog_bookmarks_deliciosus' to 'frontend_blog_bookmarks_delicious'
* Replaced old LESS mixin `createColumnSizes` for new grid mixins `createGrid` and `createColumns` in `_components/emotion.less`.
* Support arbitrary namespaces for doctrine entities instead of the `Shopware\CustomModels` namespace.
* The filter event `Shopware_Modules_Order_SaveBilling_FilterArray` now contains an associative array instead of one with numeric keys.
* The filter event `Shopware_Modules_Order_SaveBilling_FilterSQL` now uses named parameters in the query instead of question marks.
* The filter event `Shopware_Modules_Order_SaveShipping_FilterArray` now contains an associative array instead of one with numeric keys.
* The filter event `Shopware_Modules_Order_SaveShipping_FilterSQL` now uses named parameters in the query instead of question marks.
* The following article arrays are now indexed by their order number
    * emotion slider data
    * recommendation data (also bought and also viewed)
    * similar and related articles
    * top seller
* Remove `subject` from event `Shopware_Modules_Admin_SaveRegister_Successful`
* Changed following registration templates
    * frontend/register/index.tpl
    * frontend/register/shipping_fieldset.tpl
    * frontend/register/personal_fieldset.tpl
    * frontend/register/error_messages.tpl
    * frontend/register/billing_fieldset.tpl

## Shopware 5.1

### System requirements changes

Shopware 5.1 is the last Shopware minor release to support PHP 5.4. The following minor release of Shopware will require PHP 5.5+. Furthermore, as of 15/09/2015, PHP 5.4 will no longer receive security updates. For these reasons, we highly recommend that you update your PHP version to the latest stable version.

The full system requirement list for Shopware 5.1 is the same as in Shopware 5.0, and can be found [here](/sysadmins-guide/system-requirements/ "Shopware system requirements").

### PHP 7 compatibility

Shopware 5.1 also introduces PHP 7.0 compatibility. This new PHP version introduces several internal changes to PHP itself, which result in greatly improved performance when compared to PHP 5.x. However, at the time of Shopware 5.1 release, PHP 7.0 was still in Release Candidate phase. As with all software major version release, we highly recommend that you do not upgrade your production environment until a final, stable version is released.

As PHP 7 introduces significant performance improvements, we expect it to be quickly adopted by our community. Therefore, we recommend that you ensure that your plugins are compatible with PHP 7. In [this link](http://php.net/manual/en/migration70.php), you will find the official migration manual, detailing all changes made to PHP 7. In particular, we expect the [new error handling](http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.error-handling) to require changes in a significant number of plugins in order to ensure compatibility. If you are using PHP 5.x, you can emulate the new error handling behaviour in Shopware by changing your `config.php` file:

```
<?php
return array (
    // other config values
    'errorHandler' => [
        'throwOnRecoverableError' => true,
    ]
);
```

Please keep in mind that changing this setting might cause PHP to throw exceptions in processes that would otherwise work flawlessly. We strongly advice that you do not enable this setting in production environments without prior thorough testing in a development environment.

Also, using this setting in PHP 5.x only emulates one of the many changes done in PHP 7. Be sure to fully test your plugins in a native PHP 7 environment to ensure their full compatibility.


### JavaScript events

* All JavaScript plugins now have `sw` prepended to their name.
* All event names have `on` prepended to them

As such, old calls, like the following:

```
$.subscribe('plugin/lightbox/init', function(arg1, arg2) {...});
```

should now be implemented like so:

```
$.subscribe('plugin/swLightbox/onInit', function(argArray) {...});
```

If your plugin publishes events, you now have the possibility to pass multiple arguments in your events. So the old syntax

```
$.publish('plugin/swPluginname/onEventname', [ me ]);
```

can now be extended to use more arguments:

```
$.publish('plugin/swPluginname/onEventname', [ me, example, test ]);
```

### AJAX Variants

Detail pages for variant articles now load new variant data via AJAX, instead of the previous full page reload. This feature is optional and enabled by default. You can disable it in your theme settings, in which case existing plugins should work as before.

The AJAX call uses the same backend controller and action as the normal HTTP call. It also renders the same template file, so most plugins should remain compatible with the new feature. However, some JavaScript customizations might require that you use the new event, which is triggered once the AJAX loading for a variant returns:

```
$.subscribe('plugin/swAjaxVariant/onRequestData', function() {
    StateManager.addPlugin('*[data-image-slider="true"]', 'swImageSlider', { touchControls: true })
});
```

Moreover, if your custom JavaScript plugin is initialized outside of the `product--detail-upper` element, it will not be automatically reinitialized after the new content is loaded. Also, due to the way in which our plugin handler is built, you cannot reinitialize plugins multiple times on the same element. As such, you need to explicitly destroy the plugin instance prior to the AJAX call, and reinitialize it after the loading process is finished.

```
$.subscribe('plugin/swAjaxVariant/onBeforeRequestData', function() {
    $('body').data('plugin_swagCustomPlugin').destroy()
});

$.subscribe('plugin/swAjaxVariant/onRequestData', function() {
    $('body').swagCustomPlugin();
});
```

### Media Service

Shopware 5.1 includes a new media management layer, which abstracts the location of your media files. Existing file paths like `media/image/my-item.png` should now be considered virtual paths. This applies to both new and existing installations, and there is no possibility to revert to the old behaviour.

If you are upgrading from Shopware 5.0 or previous, the actual media files will be automatically moved by Shopware from the old to the new path when they are first handled by the media service (i.e. displayed in the frontend, after the upgrade). This process will move the files from their current location (i.e. `media/image/my-item.png`) to a new, semi-random location (i.e. `/media/image/5c/d1/af/my-item.png`). Reverting this process is not supported, as Shopware will always move files to the new locations when needed (even if you manually move them back). If you wish, you can also use the `sw:media:migrate` CLI command to migrate all files at once, in order to avoid the migration performance penalty in the frontend.

#### For backend developers

Like mentioned before, if your server-side code manipulates media files, you will probably need to do a few changes to it. The `shopware_media.media_service` is responsible for retrieving the real path of a file based on its virtual path. You should also use it if you need to perform any other CRUD operation on media files in your custom code. The service implements methods that will allow you to perform these operations.

Suppose your custom controller has the following code:

```
$media = $this->getMedia($id);
$mediaPath = $media->getPath();
$this->View()->assign(array(
    'mediaPath' => $mediaPath
));
```

You now need to use the `shopware_media.media_service` to retrieve the actual file path for your media file:

```
$media = $this->getMedia($id);
$mediaPath = $media->getPath();

// You can access the container outside of a controller using Shopware()->Container();
$mediaService = $this->get('shopware_media.media_service');
$mediaPath = $mediaService->getUrl($mediaPath);
// the new path might look like this: "http://www.shop.com/media/image/5c/d1/af/my-fancy-image.png";

$this->View()->assign(array(
    'mediaPath' => $mediaPath
));
```

As you can see, the previous media file path is still useful. However, instead of directly, it is used internally by the `shopware_media.media_service` to retrieve the actual file path. You should adapt your custom code to fit this new logic.

The media management API also includes a path normalizer that can be used, for example, when handling media file upload to the server. In this scenario, you need to get a virtual and normalized path to the file you just uploaded. You can do so like this:

```
$fullMediaPath = 'http//www.shop.com/media/image/my-fancy-image.png'
$mediaService = $container->get('shopware_media.media_service');
$normalizedPath = $mediaService->normalize($fullMediaPath); // media/image/my-fancy-image.png
```

#### For frontend developers

Should your template or JavaScript files somehow manipulate the file path of a media entity, you should refactor your code so that this kind of logic is handled by the `shopware_media.media_service`, during server logic execution. The media service should be the exclusive responsible for determining the real path to a media file, and any external change to it might result in broken paths to files. Ensure that your code meets this standard to prevent issues when handling media files, now and in the future.

##### New Smarty Tag

In addition to the PHP functionality, we have created a new Smarty tag for generating the real path to the media file. For example, you can use it as a value for the `src` attribute of an `<img />` like seen below:

```php
<img src="{media path='media/image/my-fancy-image.png'}" />
```

#### Garbage Collector

To find unused media files, we created the `GarbageCollector` which searches through all Shopware core tables to identify unused media files.

As a plugin developer, you may have created new tables and established a relation to a media file. In case of that, you have to register your tables to the `GarbageCollector`. First, subscribe to the `Shopware_Collect_MediaPositions` event and add your tables to an `ArrayCollection`.

```php
public function install()
{
    [...]
    $this->subscribeEvent('Shopware_Collect_MediaPositions', 'onCollect');
    [...]
}

public function onCollect() {
    return new ArrayCollection([
        new \Shopware\Bundle\MediaBundle\Struct\MediaPosition('my_plugin_table', 'mediaID'),
        new \Shopware\Bundle\MediaBundle\Struct\MediaPosition('my_other_plugin_table', 'mediaPath', 'path'),
        new \Shopware\Bundle\MediaBundle\Struct\MediaPosition('s_core_templates_config_values', 'value', 'path', MediaPosition::TYPE_SERIALIZE),
    ]);
}

```

The `MediaPosition` requires 2 parameters - a database table and the column which holds the relation to the `s_media` table. The third and fourth parameters are optional.

The **third** parameter selects the `s_media` column you are referencing to. The default value is `id` and can be replaced to any column in the `s_media` table like seen above. The **fourth** parameter sets the type of the value. e.g. json string. Available types are:


| Constant | Description |
|----------|----------|
| TYPE_PLAIN  |  *(Default)* Uses the plain value |
| TYPE_JSON | Decodes the value, parses json and iterates through an object or an array of objects |
| TYPE_HTML | Searches for `<img />` tags then parses and normalizes the `src` attribute |
| TYPE_SERIALIZE | Unserializes the value |


### Library updates
* Symfony components: 2.6.9 to 2.7.1
* Doctrine: 2.4.2 to 2.5.0
* Added:
    * `beberlei/assert`
    * `zendframework/zend-escaper`
    * `elasticsearch/elasticsearch`

### New CLI commands
* `sw:clone:category:tree`
* `sw:plugin:reinstall`
* `sw:media:cleanup`
* `sw:media:migrate`

### Removals

<div class="alert alert-info" role="alert">
    <strong>Note:</strong> This section covers only the most relevant removals. Please refer to the UPGRADE.MD file in your Shopware installation for a complete, detailed list of removed elements
</div>

* `Shopware()->Api()`
* `Shopware()->Adodb()` and `sSystem::$sDB_CONNECTION`
* `s_core_multilanguage` database table

### Other changes
* Added library [beberlei/assert](https://github.com/beberlei/assert) for low-level validation.
* Added Escaper component to escape output data, dependent on the context in which the data will be used
    * Added library [zendframework/zend-escaper](https://github.com/zendframework/zend-escaper)
    * New interface: `\Shopware\Components\Escaper\EscaperInterface`
    * Default implementation: `\Shopware\Components\Escaper\Escaper`, uses `Zend\Escaper`
    * Available in DI-Container: `shopware.escaper`
    * Smarty Modifiers:
        * escapeHtml
        * escapeHtmlAttr
        * escapeJs
        * escapeUrl
        * escapeCss
* Deprecated pre-installed import / export module in favor of the new import / export plugin, which is for free now
* Move directory `logs/` to `var/log/` and `cache/` to `var/cache`

## Shopware 5.0

### Checking the Shopware version
Important: Please try to modify your plugin so that it is compatible with both 4.3 and 5
You can use the following code in your plugin:
```
if ($this->assertMinimumVersion('5')) {
    // new code
} else {
    // old code
}
```

### End of support and system requirements
We changed some of the system requirements for the new version.
Beside the known requirements which you can find [here](http://www.shopware.com/software/overview/system-requirements "Shopware system requirements") there are the following new ones:

#### PHP 5.3
PHP 5.3 is no longer supported in Shopware 5. It's recommend to use the latest stable version of PHP. Please keep in mind that PHP 5.4 will soon reach end of life, and support for it may end before the release of the next major version. For performance and compatibility reasons, we recommend using PHP 5.6.

#### Internet Explorer 8
The new responsive template is not supported in Internet Explorer 8 and below. The old emotion template still supports Internet Explorer 7 and above.

#### MySQL 5.1
MySQL 5.1 is no longer supported in Shopware 5. The required Version of MySQL for Shopware 5 is 5.5 or above.

#### ionCube Loader
ionCube Loader requirement has been upped to version 4.6.0. Notice that you only need the ionCube Loader if you are using plugins from the Shopware Store.

### Major Breaks
* `Street number` data was moved into the `Street`field
    * The existing `Street` fields were enlarged and the existing information of the `Street number` fields was appended to them.
    * The `Street number` field was removed from all template files
    * The API will still accept writes to `Street number` field, but will internally append it to the `Street` field. Read operations will no longer have `Street number` values.
* The `default` template was merged into the `emotion` template. The `default` template is was deleted.
* The Filter in the listings are now using the new core classes and the new struct objects.
* The structure of the thumbnail images has been changed.
If you are installing the new Shopware version or if you update and still want to use the  `emotion` template you don't have to do anything.
But if you are updating to Shopware 5 and you want to use the new template you have to define and generate the following new thumbnails sizes in the media manager:

    Album          | Thumbnail sizes
    -------------- | ---------------------------------------------
    Einkaufswelten | 800x800 1280x1280 1920x1920
    Banner         | 800x800 1280x1280 1920x1920
    Artikel        | 200x200 600x600 1280x1280
    Blog           | 200x200 600x600 1280x1280

For this operation we recommend the console command `sw:thumbnail:generate` to avoid timeout problems.

* The former `MultiEdit` plugin was merged into the Shopware core.
    * Plugins hooking the `ArticleList` controller or extending the `ArticleList` backend module should be reviewed
* Refactored price surcharge for variants
    * `s_article_configurator_price_surcharges` database table was fully restructured and renamed to `s_article_configurator_price_variations`. Existing data is migrated on update
    * Existing related ExtJs classes and events removed
    * Existing price variation backend controller actions and methods removed
    * `Shopware\Models\Article\Configurator\PriceSurcharged` replaced by `Shopware\Models\Article\Configurator\PriceVariation`
* The new Shopware core selects all required data for `sGetArticleById`, `sGetPromotionById` and `sGetArticlesByCategory`. Several internal methods and events are no longer used by those functions.
* Moved `engine/core/class/*` to `engine/Shopware/Core/*`
* Renamed `ENV` to `SHOPWARE_ENV` to avoid accidentally set `ENV` variable, please update your .htaccess if you use a custom environment or you are using the staging plugin
* All downloaded dummy plugins are now installed in the engine/Shopware/Plugins/Community directory.
* Replaced `orderbydefault` configuration by `defaultListingSorting`. The `orderbydefault` configuration worked with a plain sql input which is no longer possible. The `defaultListingSorting` contains now one of the default `sSort` parameters of a listing. If you want to reintegrate your old statement you can simple create a small plugin. You can find more information [here.](/developers-guide/shopware-5-search-bundle/)

### Deprecations
* The `sBasket::sGetNotes` function is refactored with the new Shopware service classes and calls no more the sGetPromotionById function.
* `Shopware_Controllers_Frontend_Account::ajaxLoginAction` is deprecated
* `Shopware_Controllers_Frontend_Account::loginAction` usage to load a login page is deprecated. Use `Shopware_Controllers_Frontend_Register::indexAction` instead for both registration and login
* `sSystem::sSubShop` is deprecated
* Deprecate Legacy API `Shopware->Api()`, will be removed in SW 5.1
* Deprecated Zend Framework components `Zend_Rest` and `Zend_Http`.
    * Will be removed in the next minor release.
    * Use `http_client` from container instead.
* `Shopware_Controllers_Widgets_Emotion::emotionTopSellerAction` and `Shopware_Controllers_Widgets_Emotion::emotionNewcomerAction` are now deprecated and should be replaced by `Shopware_Controllers_Widgets_Emotion::emotionArticleSliderAction`
* `Enlight_Components_Adodb` (also accessed as `Shopware()->Adodb()` or `$system->sDB_CONNECTION`) will be removed in SW 5.1
* Removed `table` and `table_factory` from container.
* The old table configurator was removed and replaced by the new image configurator in the emotion and responsive template.
* Template inheritance using `{extends file="[default]backend/..."}` is no longer supported and should be replaced by `{extends file="parent:backend/..."}`
* Shopware_Components_Search_Adapter_Default is now deprecated, use \Shopware\Bundle\SearchBundle\ProductNumberSearch.
* Running cronjobs using `php shopware.php backend/cron` is not recommended and should be seen as deprecated
* `sSelfCanonical` is deprecated. Use the `canonicalParams` array instead
* Change array structure of thumbnail images in emotions, product detail pages, product listings, blog pages.

* Deprecated classes:
    * `Zend_Rest`
    * `Zend_Http`
    * `Enlight_Components_Adodb` (also accessed as `Shopware()->Adodb()` or `$system->sDB_CONNECTION`) will be removed in SW 5.1
    * `Shopware_Components_Search_Adapter_Default` is now deprecated, use `\Shopware\Bundle\SearchBundle\ProductNumberSearch`
    * `Zend_Validate_EmailAddress`
* Deprecated methods/variables:
    * `Shopware_Controllers_Frontend_Account::ajaxLoginAction()`
    * `Shopware_Controllers_Frontend_Account::loginAction()` usage to load a login page is deprecated. Use `Shopware_Controllers_Frontend_Register::indexAction()` instead for both registration and login
    * `sSystem::sSubShop`
    * `sExport::sGetMultishop()`
    * `sExport::sLanguage`
    * `sExport::sMultishop`
* Deprecated configuration variables from `Basic settings`:
    * `basketHeaderColor`
    * `basketHeaderFontColor`
    * `basketTableColor`
    * `detailModal`
    * `paymentEditingInCheckoutPage`
    * `showbundlemainarticle`
* Deprecated tables/columns:
    * `s_core_multilanguage`. Table will be removed in SW 5.1. Previously unused fields `mainID`, `flagstorefront`, `flagbackend`, `separate_numbers`, `scoped_registration` and `navigation` are no longer loaded from the database


### Removals
* Removed the template directory `_default` and all it's dependencies
* Removed unused `/backend/document` templates and several unused `Shopware_Controllers_Backend_Document` actions, methods and variables
* Removed `table` and `table_factory` from container.
* The old table configurator was removed and replaced by the new image configurator in the emotion and responsive template.
* Removed legacy `excuteParent` method alias from generated hook proxy files
* Removed the template files for the feed functionality, which was marked as deprecated in SW 3.5
* Template inheritance using `{extends file="[default]backend/..."}` is no longer supported and should be replaced by `{extends file="parent:backend/..."}`
* Removed smarty variable `$sArticle.sNavigation` in product detail page
* Removed support for flash banners. The associated template block `frontend_listing_swf_banner` is marked as deprecated
* Removed `Trusted Shops` from the basic settings. Functionality can now be found in `Trusted Shops Excellence` plugin
* Removed support for `engine/Shopware/Configs/Custom.php`
    * Use `config.php` or `config_$environment.php` e.g. `config_production.php`
* Generated listing links in the `sGetArticlesByCategory` function removed. The listing parameters are build now over an html form.
    * `sNumberPages` value removed
    * `categoryParams` value removed
    * `sPerPage` contains now the page limit
    * `sPages` value removed
* `sGetArticleById` result no longer contains the `sConfiguratorSelection` property. `sConfiguratorSelection` previously contained the selected variant data, which can now be accessed directly in the first level of the `sGetArticleById` result.
* `sConfigurator` class removed. The configurator data can now selected over the `Shopware\Bundle\StoreFrontBundle\Service\Core\ConfiguratorService.php`. To modify the configurator data you can use the `sGetArticleById` events.
* Removed `frontend/checkout/ajax_add_article_slider_item.tpl`
* Removed `frontend/listing/box_crossselling.tpl`
* Removed `widgets/recommendation/item.tpl`
* Moved `frontend/detail/similar.tpl` to `frontend/detail/tabs/similar.tpl`
* Removed Facebook Plugin from core (`Shopware_Plugins_Frontend_Facebook_Bootstrap`). Will be released as plugin on Github.
* Removed Google Plugin from core (`Shopware_Plugins_Frontend_Google_Bootstrap`). Will be released as plugin on Github.
* Removed `src` property of article images. Each images contains now a `thumbnails` property which all thumbnails.
    * `src` property still exists for old templates.
* Removed plugin `Shopware_Plugins_Frontend_RouterOld_Bootstrap`

* Removed classes:
    * `Enlight_Components_Currency`
    * `Enlight_Components_Form` and subclasses
    * `Enlight_Components_Locale`
    * `Enlight_Components_Menu` and subclasses
    * `Enlight_Components_Site` and subclasses
    * `Enlight_Components_Test_Constraint_ArrayCount`
    * `Enlight_Components_Test_Database_TestCase`
    * `Enlight_Components_Test_Selenium_TestCase`
    * `Enlight_Components_Test_TestSuite`
    * `Enlight_Extensions_Benchmark_Bootstrap`
    * `Enlight_Extensions_Debug_Bootstrap`
    * `Enlight_Extensions_ErrorHandler_Bootstrap`
    * `Enlight_Extensions_Log_Bootstrap`
    * `Enlight_Extensions_Router_Bootstrap`
    * `Enlight_Extensions_RouterSymfony_Bootstrap`
    * `Enlight_Extensions_Site_Bootstrap`
    * `Enlight_Components_Log` (also accessed as `Shopware->Log()`)
* Removed methods/variables:
    * `sArticles::sGetAllArticlesInCategory()`
    * `sSystem::sSubShops`
    * `sSystem::sLanguageData`. Please use `Shopware()->Shop()` instead
    * `sSystem::sLanguage`. Please use `Shopware()->Shop()->getId()` instead
    * `Shopware_Plugins_Core_ControllerBase_Bootstrap::getLanguages()`
    * `Shopware_Plugins_Core_ControllerBase_Bootstrap::getCurrencies()`
    * `sExport::sGetLanguage()`
    * `Shopware_Controllers_Backend_Article::getConfiguratorPriceSurchargeRepository()`
    * `Shopware_Controllers_Backend_Article::saveConfiguratorPriceSurchargeAction()`
    * `Shopware_Controllers_Backend_Article::deleteConfiguratorPriceSurchargeAction()`
    * `Shopware_Controllers_Backend_Article::getArticlePriceSurcharges()`
    * `Shopware_Controllers_Backend_Article::getSurchargeByOptionId()`
    * `sArticles::sGetArticlesAverangeVote`
    * `sArticles::getCategoryFilters`
    * `sArticles::getFilterSortMode`
    * `sArticles::addFilterTranslation`
    * `sArticles::sGetArticleConfigTranslation`
    * `sArticles::sGetArticlesByName`
    * `sArticles::sGetConfiguratorImage`
    * `sArticles::sCheckIfConfig`
    * `sArticles::getCheapestVariant`
    * `sArticles::calculateCheapestBasePriceData`
    * `sArticles::displayFiltersOnArticleDetailPage`
    * `sArticles::getFilterQuery`
    * `sArticles::addArticleCountSelect`
    * `sArticles::addActiveFilterCondition`
    * `sArticles::displayFilterArticleCount`
    * `sArticles::sGetLastArticles`
    * `sArticles::sGetCategoryProperties`
    * `sArticles::sGetArticlesVotes`
    * `Enlight_Controller_Front::returnResponse()`
    * `Shopware_Plugins_Core_Cron_Bootstrap::onAfterSendResponse()`
    * `\Shopware\Models\User\User::setAdmin()`
    * `\Shopware\Models\User\User::getAdmin()`
    * `\Shopware\Models\User\User::setSalted()`
    * `\Shopware\Models\User\User::getSalted()`
    * `\Shopware\Models\Banner\Banner::setLiveShoppingId()`
    * `\Shopware\Models\Banner\Banner::getLiveShoppingId()`
    * `sArticles::getPromotionNumberByMode('premium')`
    * `sArticles::sGetPromotions()`
    * `sMarketing::sCampaignsGetDetail()`
    * `sMarketing::sCampaignsGetList()`
    * `\Shopware\Models\Plugin\Plugin::isDummy()`
    * `\Shopware\Models\Plugin\Plugin::disableDummy()`
    * Removed `sArticles::getPromotionNumberByMode('image')` and `sArticles::getPromotionNumberByMode('gfx')` support
* Removed events:
    * `Shopware_Modules_Articles_GetFilterQuery`
    * `Shopware_Modules_Article_GetFilterSortMode`
    * `Shopware_Modules_Article_GetCategoryFilters`
    * `Enlight_Controller_Front_SendResponse`
    * `Enlight_Controller_Front_AfterSendResponse`
    * `Shopware_Modules_Articles_sGetProductByOrdernumber_FilterSql`
    * `Shopware_Modules_Articles_GetPromotions_FilterSQL`
* Removed Smarty vars:
    * `$sArticle.sNavigation` for product detail page
* Removed configuration variables from `Basic settings`:
    * `useDefaultControllerAlways`
    * `articlelimit`
    * `configcustomfields`
    * `configmaxcombinations`
    * `displayFilterArticleCount`
    * `ignoreshippingfreeforsurcharges`
    * `liveinstock`
    * `mailer_encoding`
    * `redirectDownload`
    * `redirectnotfound`
    * `seorelcanonical`
    * `seoremovewhitespaces`
    * `taxNumber`
    * `deactivateNoInstock`
* Removed database table/columns:
    * `s_core_rewrite`
    * `s_cms_groups`
    * `s_core_auth.admin`
    * `s_core_auth.salted`
    * `s_order_basket.liveshoppingID`
    * `s_emarketing_banners.liveshoppingID`
    * `s_core_sessions.expireref`
    * `s_core_sessions.created`
    * `s_core_sessions_backend.created`
    * `s_emarketing_promotions*`
    * `s_core_plugins.capability_dummy`
    * `s_articles_details.impressions`

### Additions
* New theme system
    * Added a new Theme Manager 2.0 to replace the existing theme manager, which was removed.
    * It's now possible to create and configure custom themes directly from the backend
    * Shop configuration no longer contains the template selection.
* New jQuery plugin system
* Added several javascript libraries that enhance the supported features of the IE 8 and above
* Added `controller_action` and `controller_name` smarty functions that return the correspondent variable values
* Added `secureUninstall` method and capability for plugins.
    * (new) Bootstrap::secureUninstall() -> should be used to remove only non-user data
    * (old) Bootstrap::uninstall() -> existing logic, removes all plugins related data
* The registration and checkout workflows have been redesigned for the new template. Shopware 4 templates will behave as before
* Variant's `additional text` field is now automatically generated using the configurator group options. This can be optionally disabled
* Changed behavior of the `selection` configurator. Configurator options which have no available product variant disabled now in the select-tag. The new snippet `DetailConfigValueNotAvailable` can be used to append additional text after the value name.
* The article slider now supports sorting by price (asc and desc) and category filtering
    * `Shopware_Controllers_Widgets_Emotion::emotionTopSellerAction` and `Shopware_Controllers_Widgets_Emotion::emotionNewcomerAction` are now deprecated and should be replaced by `Shopware_Controllers_Widgets_Emotion::emotionArticleSliderAction`
* Added [Guzzle](https://github.com/guzzle/guzzle).
* Added HTTP client `Shopware\Components\HttpClient\HttpClientInterface`.
    * Can be fetched from the container using the key `http_client`.
* Add `isFamilyFriendly` core setting to enable or disable the correspondent meta tag.
* Added `Theme cache warm up` modal window and functionality
* Added `http cache warmer` modal window in the performance module and console command `sw:warm:http:cache`
* The MailTemplates now have global header and footer fields in configuration -> storefront -> email settings
    * Header for Plaintext
    * Header for HTML
    * Footer for Plaintext
    * Footer for HTML
* Added global JavaScript StateManager Singleton to handle different states based on registered breakpoints.
* Added `frontend/listing/product-box/box--product-slider.tpl`
    * This file should be used as an product slider item template
* Install, update, uninstall function of a plugin supports now a "message" return parameter which allows to display different messages.
* New commands: `sw:cron:list` and `sw:cron:run`
* Added VRRL Plugin to Core. Service articles can be identified by article attributes. The field can be configured by general settings
* Email validation is now done using the `egulias/email-validator` library.
* Added configuration `showEsd` to show/hide the ESD-Downloads in the customer accounts menu. (default = true)
* Article image album sizes have been changed to match the requirements of the new template (only new installations)
* \sArticles::sGetProductByOrdernumber result is equal with the \sArticles::sGetPromotionById result.

### 5.0.0 Beta 2 Changes
* Renamed the shopware_searchdbal.product_number_search to shopware_search.product_number_search. Use shopware_search.product_number_search service for number searches.
* Removed aliases from bundle services. Example: list_product_service is now directly set to the old list_product_service_core
* Extend ProductAttributeFacet with different FacetResult properties, to allow full FacetResult configuration over the facet.
* Out of stock articles and variants are now not included in the product feed if the `Do not show on sale products that are out of stock ` option is enabled
* Added a new config to improve the quality of the thumbnail generation
* implement a new seo router to increase the performance of the seo url rendering

### 5.0.0 RC1
* New orders will no longer set `s_order.transactionID` automatically from POST data. 3rd party plugins can still use this value as before.
* Fix translation API, rename all `localeId` references to `shopId`. Create / update / delete with `localeId` are still supported as legacy.
* `\Shopware\Models\Translation\Translation` now correctly relates to `Shop` model instead of `Locale`.
* widgets/recommendations - boughtAction & viewedAction calls no more the `sGetPromotionById` function.
* Added emotion positioning number for ordering emotions by position number if there are more emotions on one page
* Replaced `closeOverlay` with `openOverlay` option in the loading indicator to improve the simplicity.
* Removed overlay options in the modal box and loading indicator jQuery plugin.
* Overlay jQuery plugin now only provides the closeOnClick, onClick and onClose options. To style the overlay, use the corresponding less file.
* Removed several unused methods from `Shopware_Controllers_Backend_Config`
* Removed several unused JavaScript files from the backend
* Removed classes:
    * `ConfigIframe.php` backend controller
    * `Viewport.php` frontend controller
* Removed template files:
    * `backend\index\iframe.tpl`
* Removed commands `sw:store:download:update` and `sw:store:licenseplugin`.
* Added `sw:store:download` command to download, install and update plugins.
* Added `sw:store:list:integrated` command to list all Shopware 5 integrated plugins.
* `Shopware.model.Container` provides now the raw record value as id parameter to the `searchAssociationAction` to request the whole record on form load.
* Added way to early exit the dispatch.
    * After `Enlight_Controller_Front_RouteShutdown` a response containing a redirect will not enter the dispatch loop.
* `HttpCache` plugin is no longer handled by the Plugin manager. Use the `Performance` window to enable/configure the HTTP cache instead
* `\Shopware\Models\Emotion\Repository::getListQuery` function replaced by `getListingQuery`.


### 5.0.0 RC2
* SEO URL generation variable "statistic" has been translated and corrected to "static"
* Theme config elements can now define, over the attributes array, if they are less compatible. Example: `attributes => ['lessCompatible' => false]`, default is set to true.
* Implement plugin bootstrap helper functions: addHttpCacheRoute and removeHttpCacheRoute, to add and remove http cache routes.
* Refactor getRandomArticle function of sArticles. Shopware_Modules_Articles_GetPromotionById_FilterSqlRandom event removed.
* `Mark VAT ID number as required` moved to `Login / Registration` in `Basic Settings`. All other VAT ID validation options were removed. If you need VAT ID validation functionalities, please use the VAT ID Validation plugin available on the store.
    * `sAdmin::sValidateVat()` removed
* Removed supplier description on article detail page to prevent duplicated content for google remote crawling
* Fix duplicate name parameter for backend extjs stores inside the config module. Repository class name sent before as `name` parameter. Now the stores uses `_repositoryClass` as parameter.
* Removed shopware_storefront.product_gateway (\Shopware\Bundle\StoreFrontBundle\Gateway\ProductGatewayInterface).
* \Shopware\Bundle\StoreFrontBundle\Service\Core\ProductService uses now the ListProductService to load the product data and converts the product structs by loaded list products.
* Removed `\Shopware\Bundle\StoreFrontBundle\Gateway\DBAL\Hydrator\ProductHydrator::hydrateProduct` function.
* Removed \Shopware\Bundle\StoreFrontBundle\Struct\ListProduct::STATE_TRANSLATED constant.
* Removed Service `guzzle_http_client`, use `guzzle_http_client_factory` instead.
* Added support for Bundle of CA Root Certificates. See: http://curl.haxx.se/docs/caextract.html.
* Removed `setField` and `setMode` function in \Shopware\Bundle\SearchBundle\Facet\ProductAttributeFacet.
* Removed unnecessary theme variable prefix for less compiler. Each theme config variable prefixed with "theme" . ucfirst($key) which generates @themeBrandPrimary. This variables were remapped inside responsive theme.
* fixed pagination problem in the listing if JavaScript is deactivated
* improved the performance of the Advanced Menu, Advanced Emotion Worlds and Emotion Worlds
* fixed problem with loading an Emotion World on a category page
* added a time out if the Plugin Manager can't connect to the plugin store
* fixed a SSL bug in the ajax search
* optimized the LESS variable naming
* fixed issues with PHP 5.6.6
* the Product Service now uses the List Product Service
* update Symfony from 2.6.4 to 2.6.6
* fixed several design issues for the Off-Canvas cart
* added color code validation for the theme configuration
* fixed some design issues for the image slider
* fixed several caching issues
* fixed landing page master / slave issues
* fixed blog articles without images
* several rewriting fixes
* fixed media manager image upload
* added a confirmation message for deleting images in the media manager
* fixed several styles for the IE 11
* fixed several First Run Wizard bugs
* fixed several Emotion World bugs
* fixed several SEO tags
* fixed cache interaction between multiple Shopware installations
* fixed tap on search results in the drop down search
* fixed several bugs for the Surface
* added missing smarty blocks to the analytics module
* the configuration uses the array_replace_recursive method instead of array_merge
* several improvements of the visitors statics
* fixed a bug for the SEO URLs generating statistics bar
* improved the password description

### 5.0.0 RC3
* \Shopware\Bundle\SearchBundleDBAL\ConditionHandler\HasPriceConditionHandler now joins the prices as a 1:1 association for a performance improvement.
* sCategories::sGetCategoryContent function returns no more the category articleCount. Variable is unused.
* sCategories::sGetCategoryIdByArticleId function use now the s_articles_categories table.
* added legacy template, support ends with Shopware 5.2
* several Emotion template fixes
* Shopping world slider heading turncate fix
* fixed several upgrade issues
* inactive sub categories was displayed in the Off-Canvas menu
* tracked partner order was not displayed in the statistic
* plugins can now be downloaded if you ware using Windows
* fixed manual plugin upload for the Firefox
* fixed several typos
* fixed price group discount
* added \__redirect parameter in frontend language switcher. Each language switcher requires now an additionally post parameter to redirect to the new shop <input type="hidden" name="__redirect" value="1">

### Further changes
You can find a complete list of all changes in the release package in the file `upgrade.md`
