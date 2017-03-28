---
layout: default
title: Storefront extension
github_link: developers-guide/example-plugin/index.md
indexed: true
menu_title: Storefront extension
menu_order: 20
group: Developer Guides
subgroup: Developing plugins
---

<div class="toc-list"></div>

## Introduction
This guide shows how the full plugin system of Shopware 5 works. As part of this guide, we will create a small example plugin, which can be downloaded <a href="{{ site.url }}/exampleplugins/SwagPluginSystem.zip">here</a>.

The plugin modifies the following parts of Shopware:

* Extends the `ListProductService` to append additional product data in listings or sliders.
* Create a custom service, which is then injected into the DI container.
* Extends the Shopware responsive template with custom plugin templates.
* Implements a custom theme which overrides the plugin templates.

This example gives answers to the following questions:

* How to register a custom service inside the DI container?
* How to decorate existing Shopware services, to enrich products with additional data?
* How to structure my plugin templates, so that they can be overwritten by local themes or other plugins?
* How to override plugin templates inside my custom theme?

## Bootstrap

```php

<?php

use ShopwarePlugins\SwagPluginSystem\StoreFrontBundle\ListProductService;
use ShopwarePlugins\SwagPluginSystem\StoreFrontBundle\SeoCategoryService;

class Shopware_Plugins_Frontend_SwagPluginSystem_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{
    public function getLabel()
    {
        return 'Shopware 5 - Big picture of the plugin system';
    }

    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_InitResource_shopware_storefront.seo_category_service',
            'registerSeoCategoryService'
        );

        $this->subscribeEvent(
            'Enlight_Bootstrap_AfterInitResource_shopware_storefront.list_product_service',
            'registerListProductService'
        );

        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend',
            'addTemplateDir'
        );

        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Widgets',
            'addTemplateDir'
        );

        $this->subscribeEvent(
            'Theme_Compiler_Collect_Plugin_Less',
            'onCollectLessFiles'
        );

        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace(
            'ShopwarePlugins\SwagPluginSystem',
            $this->Path()
        );
    }

    /**
     * @return \Shopware\Components\Theme\LessDefinition
     */
    public function onCollectLessFiles()
    {
        return new \Shopware\Components\Theme\LessDefinition(
            [],
            [__DIR__ . '/Views/frontend/_public/src/less/all.less']
        );
    }

    public function addTemplateDir()
    {
        Shopware()->Container()->get('template')->addTemplateDir($this->Path() . 'Views/');
    }

    public function registerSeoCategoryService()
    {
        $seoCategoryService = new SeoCategoryService(
            Shopware()->Container()->get('dbal_connection'),
            Shopware()->Container()->get('shopware_storefront.category_service')
        );
        Shopware()->Container()->set('shopware_storefront.seo_category_service', $seoCategoryService);
    }

    public function registerListProductService()
    {
        Shopware()->Container()->set(
            'shopware_storefront.list_product_service',
            new ListProductService(
                Shopware()->Container()->get('shopware_storefront.list_product_service'),
                Shopware()->Container()->get('shopware_storefront.seo_category_service')
            )
        );
    }
}

```

## Events
The plugin bootstrap registers the following events:

* `Enlight_Bootstrap_InitResource_shopware_storefront.seo_category_service`
    * The `Enlight_Bootstrap_InitResource_*` event fired when the suffixed service has to be initialized by the DI container.
    * Because the `shopware_storefront.seo_category_service` is defined in this plugin, the plugin bootstrap has to handle the initialization of the service.
* `Enlight_Bootstrap_AfterInitResource_shopware_storefront.list_product_service`
    * The `Enlight_Bootstrap_AfterInitResource_*` event is fired after the suffixed service was initialized by the DI container.
    * The plugin wants to decorate the original `list_product_service`, so it needs to use the `AfterInitResource` event instead of the `InitResource` event, like in the `seo_category_service`.
* `Enlight_Controller_Action_PostDispatchSecure_Frontend`
* `Enlight_Controller_Action_PostDispatchSecure_Widgets`
    * The `PostDispatchSecure_Frontend` and `PostDispatchSecure_Widgets` events are used to register the plugin `views` directory as a frontend and widgets template directory.
* `Theme_Compiler_Collect_Plugin_Less`
    * The `Theme_Compiler_Collect_Plugin_Less` event is fired when Shopware compiles theme and plugin LESS files into one CSS file
    * This event is used to add the plugin's LESS files to the compilation process.

Notice: The usage of the `Enlight_Bootstrap_InitResource` and `Enlight_Bootstrap_AfterInitResource` events has many benefits over other events, like `PreDispatch` or `Enlight_Controller_Front_StartDispatch`:

* These events are only fired if the corresponding service is really required and used in the system.
* These events are also fired in console commands.


## The event listener
The event listener contains the following sources:

### Enlight_Bootstrap_InitResource_shopware_storefront.seo_category_service
```php
public function registerSeoCategoryService()
{
    $seoCategoryService = new SeoCategoryService(
        Shopware()->Container()->get('dbal_connection'),
        Shopware()->Container()->get('shopware_storefront.category_service')
    );
    Shopware()->Container()->set('shopware_storefront.seo_category_service', $seoCategoryService);
}
```
Creates a new instance of the `SeoCategoryService` class, which is defined inside the plugin. As constructor parameters, the DBAL connection and the category service of Shopware are provided to the new service instance. The category service is a central service that loads category data identified by its id. After the service is initialized, it is set into the DI container via `Shopware()->Container()->set()`.

### Enlight_Bootstrap_AfterInitResource_shopware_storefront.list_product_service
```php
public function registerListProductService()
{
    Shopware()->Container()->set(
        'shopware_storefront.list_product_service',
        new ListProductService(
            Shopware()->Container()->get('shopware_storefront.list_product_service'),
            Shopware()->Container()->get('shopware_storefront.seo_category_service')
        )
    );
}
```
This event listener also creates a new instance of the `ListProductService`, which is also defined inside the plugin. In contrast with the previous event listener, this event listener is called after the original initialisation of a service. This means that the original `shopware_storefront.list_product_service` is already initialized, and can be loaded via `Shopware()->Container()->get()`. The new service expects the original `shopware_storefront.list_product_service` service and the `shopware_storefront.seo_category_service` as constructor parameters.

### Enlight_Controller_Action_PostDispatchSecure_Frontend/Widgets
```php
public function addTemplateDir()
{
    Shopware()->Container()->get('template')->addTemplateDir($this->Path() . 'Views/');
}
```
Registers the plugin's `Views` directory as a template directory for Shopware.
Attention: This event listener listens to the global frontend and widgets post dispatch secure event. The plugin shouldn't do some performance sensitive tasks here, otherwise each post dispatch event in the store front will be slowed down.

### Theme_Compiler_Collect_Plugin_Less
```php
public function onCollectLessFiles()
{
    return new \Shopware\Components\Theme\LessDefinition(
        [],
        [__DIR__ . '/Views/frontend/_public/src/less/all.less']
    );
}
```
Adds the plugin `all.less` file to the Shopware LESS compiling step. This allows the plugin to implement custom frontend styling via LESS.

## Plugin ListProductService

```php
<?php

namespace ShopwarePlugins\SwagPluginSystem\StoreFrontBundle;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

class ListProductService implements ListProductServiceInterface
{
    /**
     * @var ListProductServiceInterface
     */
    private $service;

    /**
     * @var SeoCategoryService
     */
    private $seoCategoryService;

    /**
     * @param ListProductServiceInterface $service
     * @param SeoCategoryService $seoCategoryService
     */
    public function __construct(ListProductServiceInterface $service, SeoCategoryService $seoCategoryService)
    {
        $this->service = $service;
        $this->seoCategoryService = $seoCategoryService;
    }

    /**
     * @inheritdoc
     */
    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        $products = $this->service->getList($numbers, $context);

        $categories = $this->seoCategoryService->getList($products, $context);

        /**@var $product Struct\ListProduct*/
        foreach ($products as $product) {
            $productId = $product->getId();
            if (!isset($categories[$productId])) {
                continue;
            }

            $attribute = new Struct\Attribute(['category' => $categories[$productId]]);
            $product->addAttribute('swag_plugin_system', $attribute);
        }
        return $products;
    }

    /**
     * @inheritdoc
     */
    public function get($number, Struct\ProductContextInterface $context)
    {
        $products = $this->getList([$number], $context);
        return array_shift($products);
    }
}
```

The service has an internal reference to the original list product service of the Shopware core and a reference to the plugin SEO category service. First, the `getList` function of this service calls the `getList` function of the original service to load the product data over the Shopware core.

```php
$products = $this->service->getList($numbers, $context);
```

After the products are loaded by the original service, the service provides the loaded products to the SEO category service to load all SEO categories in one step.

```php
$categories = $this->seoCategoryService->getList($products, $context);
```

Finally, the function iterates over the products, assigning the SEO categories. The returned categories array of the SEO category service is indexed by the product id. This make it easy to assign the loaded categories to the products, by checking if the product ID is set in the category array:

```php
$productId = $product->getId();
if (!isset($categories[$productId])) {
    continue;
}
```

To assign the SEO categories to the products, the function begins by creating a new attribute struct:

```php
$attribute = new Struct\Attribute(['category' => $categories[$productId]]);
```
Inside a single attribute struct, it is possible to assign complex data structures, and not just a single value. So, if you want to assign multiple values to a single product, you can do so in a single `addAttribute` to the product struct.

```php
$product->addAttribute('swag_plugin_system', $attribute);
```

The `addAttribute` function expects as first parameter an unique key for the attribute. The easiest way is to use the plugin name to prevent collisions with other plugins.

## Plugin SeoCategoryService

```php
<?php

namespace ShopwarePlugins\SwagPluginSystem\StoreFrontBundle;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\StoreFrontBundle\Service\CategoryServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\Core\CategoryService;
use Shopware\Bundle\StoreFrontBundle\Struct\Category;
use Shopware\Bundle\StoreFrontBundle\Struct\ListProduct;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class SeoCategoryService
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var CategoryServiceInterface
     */
    private $categoryService;

    /**
     * @param Connection $connection
     * @param CategoryServiceInterface $categoryService
     */
    public function __construct(Connection $connection, CategoryServiceInterface $categoryService)
    {
        $this->connection = $connection;
        $this->categoryService = $categoryService;
    }

    /**
     * @param ListProduct[] $listProducts
     * @param ShopContextInterface $context
     * @return Category[] indexed by product id
     */
    public function getList($listProducts, ShopContextInterface $context)
    {
        $ids = array_map(function (ListProduct $product) {
            return $product->getId();
        }, $listProducts);

        //select all SEO category ids, indexed by product id
        $ids = $this->getCategoryIds($ids, $context);

        //now select all category data for the selected ids
        $categories = $this->categoryService->getList($ids, $context);

        $result = [];
        foreach ($ids as $productId => $categoryId) {
            if (!isset($categories[$categoryId])) {
                continue;
            }
            $result[$productId] = $categories[$categoryId];
        }

        return $result;
    }

    /**
     * @param $ids
     * @param $context
     */
    private function getCategoryIds($ids, ShopContextInterface $context)
    {
        $query = $this->connection->createQueryBuilder();
        $query->select(['seoCategories.article_id', 'seoCategories.category_id'])
            ->from('s_articles_categories_seo', 'seoCategories')
            ->andWhere('seoCategories.article_id IN (:productIds)')
            ->andWhere('seoCategories.shop_id = :shopId')
            ->setParameter(':shopId', $context->getShop()->getId())
            ->setParameter(':productIds', $ids, Connection::PARAM_INT_ARRAY);

        return $query->execute()->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
```

The `SeoCategoryService` is really simple. It has only one public method, `getList`, which can be called by other classes. Inside this method, the product IDs are collected by an `array_map` call. This is necessary, as the SEO categories are saved in the database by the product ID:

```php
$ids = array_map(function (ListProduct $product) {
    return $product->getId();
}, $listProducts);
```

After the IDs are collected, the method loads all associated SEO category IDs over the `getCategoryIds` function:

```php
public function getList($listProducts, ShopContextInterface $context)
{
    //...
    //select all SEO category IDs, indexed by product id
    $ids = $this->getCategoryIds($ids, $context);
    //...
}

private function getCategoryIds($ids, ShopContextInterface $context)
{
    $query = $this->connection->createQueryBuilder();
    $query->select(['seoCategories.article_id', 'seoCategories.category_id'])
        ->from('s_articles_categories_seo', 'seoCategories')
        ->andWhere('seoCategories.article_id IN (:productIds)')
        ->andWhere('seoCategories.shop_id = :shopId')
        ->setParameter(':shopId', $context->getShop()->getId())
        ->setParameter(':productIds', $ids, Connection::PARAM_INT_ARRAY);

    return $query->execute()->fetchAll(\PDO::FETCH_KEY_PAIR);
}
```

This query is very simple and fast because, no matter how many products are provided, the function executes only one query with an `SQL IN` condition to load all category IDs. By using the `\PDO::FETCH_KEY_PAIR` in the `fetchAll` function, the query builder generates a "key-value" array, in which the first query builder column is used for the key and the second column for the value. This creates the following structure for the return value:
```
[
    productId-1 => categoryId-1,
    productId-2 => categoryId-2,
    ...
]
```
The category data can be easily loaded over the `CategoryService::getList` of the Shopware core, which prevents the plugin from having to load category data itself. But the returned categories array of the core service is indexed by the category ID, which means that the array keys have to remapped:

```php
$ids        = $this->getCategoryIds($ids, $context);
$categories = $this->categoryService->getList($ids, $context);

$result = [];
foreach ($ids as $productId => $categoryId) {
    if (!isset($categories[$categoryId])) {
        continue;
    }
    $result[$productId] = $categories[$categoryId];
}
```

After this small `foreach`, the `$result` array contains a product ID indexed array with category structs as value.

## Plugin Views
The plugin extends two templates of the responsive template.

1. `frontend/detail/actions.tpl` (Additional link on an article detail page)

```
{* SwagPluginSystem/Views/frontend/detail/actions.tpl *}
{extends file="parent:frontend/detail/actions.tpl"}

{block name='frontend_detail_actions_voucher'}
    {$smarty.block.parent}
    
    {if $sArticle.attributes.swag_plugin_system}
        {$swagSeoAttribute = $sArticle.attributes.swag_plugin_system}
        {include file="frontend/swag_plugin_system/detail-link.tpl" seoCategory=$swagSeoAttribute->get('category')}
    {/if}
{/block}
```

2. `frontend/listing/product-box/product-badges.tpl` (New badge inside listings)

```
{* SwagPluginSystem/Views/frontend/listing/product-box/product-badges.tpl *}
{extends file="parent:frontend/listing/product-box/product-badges.tpl"}

{block name="frontend_listing_box_article_new"}
    {$smarty.block.parent}

    {if $sArticle.attributes.swag_plugin_system}
        {$swagSeoAttribute = $sArticle.attributes.swag_plugin_system}

        {include file="frontend/swag_plugin_system/listing-badge.tpl" seoCategory=$swagSeoAttribute->get('category')}
    {/if}
{/block}
```

Both templates first check if the current article contains a property named `swag_plugin_system`:

```
{if $sArticle.attributes.swag_plugin_system}
```

If this is the case, the plugin includes another template in each case:

```
{include file="frontend/swag_plugin_system/listing-badge.tpl" seoCategory=$swagSeoAttribute->get('category')}
```

```
{include file="frontend/swag_plugin_system/detail-link.tpl" seoCategory=$swagSeoAttribute->get('category')}
```

The included templates are stored inside a new template directory, which has the same name as the plugin. This has a later benefit, as the plugin templates can easily extended by local themes.

```
{block name="frontend_swag_plugin_system_detail_link"}
    <a href="{url controller=cat sCategory=$seoCategory->getId() sPage=1}"
       rel="nofollow"
       class="action--link">

        {block name="frontend_swag_plugin_system_detail_link_icon"}
            <i class="icon--comment"></i>
        {/block}

        {block name="frontend_swag_plugin_system_detail_link_text"}
            {$seoCategory->getName()}
        {/block}
    </a>
{/block}
```

```
{block name="frontend_swag_plugin_system_listing_badge"}
    <div class="product--badge badge--seo-category">
        {block name="frontend_swag_plugin_system_listing_badge_category"}
            {$seoCategory->getName()}
        {/block}
    </div>
{/block}
```
The styling of the SEO category badge, inside the listings, is done inside the `all.less` file:

```
.badge--seo-category {
    color: #fff;
    background: #000;
}
```

The color values are hardcoded here, but will be made configurable in the next steps, by using a custom theme.

## Plugin Theme
The plugin theme directory contains the following files:

* `CustomTheme/Theme.php`: Definition of theme content
* `CustomTheme/frontend/_public/src/less/all.less`: Includes the custom theme's styling.
* `CustomTheme/frontend/swag_product_extension/detail-link.tpl`: Extends the plugin template for detail page
* `CustomTheme/frontend/swag_product_extension/listing-badge.tpl`: Extends the plugin template for the listing badge

The `Theme.php` contains first the meta information inside the class properties:

```php
<?php

namespace Shopware\Themes\CustomTheme;

use Shopware\Components\Form;

class Theme extends \Shopware\Components\Theme
{
    protected $extend = 'Responsive';

    protected $name = <<<'SHOPWARE_EOD'
Plugin theme
SHOPWARE_EOD;

    protected $description = <<<'SHOPWARE_EOD'
Overrides the plugin template extension with own theme files
SHOPWARE_EOD;

    protected $author = <<<'SHOPWARE_EOD'
shopware AG
SHOPWARE_EOD;

    protected $license = <<<'SHOPWARE_EOD'
MIT
SHOPWARE_EOD;

    public function createConfig(Form\Container\TabContainer $container)
    {
        $tab = $this->createTab('swag_custom_theme', 'Custom theme');
        $container->addTab($tab);

        $fieldSet = $this->createFieldSet('swag_custom_theme_field_set', 'Badge configuration');

        $fieldSet->addElement(
            $this->createColorPickerField('badge-seo-category-bg', 'Background seo category badge', '#e74c3c')
        );
        $fieldSet->addElement(
            $this->createColorPickerField('badge-seo-category-color', 'Color seo category badge', '#fff')
        );

        $tab->addElement($fieldSet);
    }
}
```

The `createConfig` function allows adding new configuration options to a theme. First, the plugin creates a new tab and adds it to the provided container. Each tab or element's name has to be unique.

```php
$tab = $this->createTab('swag_custom_theme', 'Custom theme');
$container->addTab($tab);
```

Inside the tab, the plugin creates a new field set with two new color picker configuration fields:

```php
$fieldSet = $this->createFieldSet('swag_custom_theme_field_set', 'Badge configuration');

$fieldSet->addElement(
    $this->createColorPickerField('badge-seo-category-bg', 'Background SEO category badge', '#e74c3c')
);
$fieldSet->addElement(
    $this->createColorPickerField('badge-seo-category-color', 'Color SEO category badge', '#fff')
);
```

The first parameter of the `createColorPickerField` function is the name of the variable, which can be used in template or LESS files. The `badge-seo-category-bg` and `badge-seo-category-color` fields are used for the font and background color of the SEO category listing badge.

```php
$this->createColorPickerField('badge-seo-category-bg', ...)
$this->createColorPickerField('badge-seo-category-color', ...)
```

These variables can now be used inside the `all.less` file of the theme:

```
.badge--seo-category {
    color: @badge-seo-category-color;
    background: @badge-seo-category-bg;
}
```

Now the badge colors, which were hardcoded inside the plugin, can be configured inside the theme backend module. Additionally, the `all.less` file of the theme can override the plugin stylings. Template overrides are quite similar. The theme can override the template content of the plugin and add additional content to the badge and detail link. The plugin template `frontend/swag_plugin_system/detail-link.tpl` would look as follow:

```
{block name="frontend_swag_plugin_system_detail_link"}
    <a href="{url controller=cat sCategory=$seoCategory->getId() sPage=1}"
       rel="nofollow"
       class="action--link">

        {block name="frontend_swag_plugin_system_detail_link_icon"}
            <i class="icon--comment"></i>
        {/block}

        {block name="frontend_swag_plugin_system_detail_link_text"}
            {$seoCategory->getName()}
        {/block}
    </a>
{/block}
```

Here is a separate block, which surrounds the `<i>` tag. The theme template can now easily override this block in his own `frontend/swag_plugin_system/detail-link.tpl` template to display another icon:

```
{extends file="parent:frontend/swag_plugin_system/detail-link.tpl"}

{block name="frontend_swag_plugin_system_detail_link_icon"}
    <i class="icon--map"></i>
{/block}
```

Same concept with the listing badge. The original plugin template looks as follow:

```
{block name="frontend_swag_plugin_system_listing_badge"}
    <div class="product--badge badge--seo-category">
        {block name="frontend_swag_plugin_system_listing_badge_category"}
            {$seoCategory->getName()}
        {/block}
    </div>
{/block}
```

With a small override, the theme appends a leading "From" string to the badge:

```
{extends file="parent:frontend/swag_plugin_system/listing-badge.tpl"}

{block name="frontend_swag_plugin_system_listing_badge_category"}
    From {$seoCategory->getName()}
{/block}
```
