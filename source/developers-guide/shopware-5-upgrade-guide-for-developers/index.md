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

## Shopware 5.2

### System requirements changes

The required PHP version is now **PHP 5.6.4 or higher**. Please check your system configuration and update your PHP version if necessary. If you are using a PHP version prior to 5.6.4 there will be errors.

The required IonCube Loader version was bumped to 5.0 or higher.

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
        * For a complete list of template and event changes, refer to the [UPGRADE.md](https://github.com/shopware/shopware/blob/5.2/UPGRADE-5.2.md).

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
    * For a complete list of template changes, refer to the [UPGRADE.md](https://github.com/shopware/shopware/blob/5.2/UPGRADE-5.2.md).

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

To learn more about the new attribute management, refer to the [README.md](https://github.com/shopware/shopware/blob/5.2/engine/Shopware/Bundle/AttributeBundle/README.md) file in the source code.

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
        new \Shopware\Bundle\MediaBundle\Struct\MediaPosition('my_other_plugin_table', 'mediaPath', 'path');
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

#### IonCube Loader
IonCube Loader requirement has been upped to version 4.6.0. Notice that you only need the IonCube Loader if you are using plugins from the Shopware Store.

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
* Renamed `ENV` to `SHOPWARE_ENV` to avoid accidentally set `ENV` variable, please update your .htaccess if you use a custom envirenment or you are using the staging plugin
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
