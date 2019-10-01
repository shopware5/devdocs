---
layout: default
title: REST API - Models
github_link: developers-guide/rest-api/models/index.md
menu_title: Models
menu_order: 120
indexed: true
menu_style: bullet
group: Developer Guides
subgroup: REST API
---

<div class="toc-list" data-depth="1"></div>

## Address

* **Model:** Shopware\Models\Customer\Address
* **Table:** s_user_addresses

### Structure

| Field                    | Type                  | Original object                                 |
|--------------------------|-----------------------|-------------------------------------------------|
| id                       | integer (primary key) |                                                  |                          |
| company                  | string                |                                                    |                             |
| department               | string                |                                                    |                             |
| salutation               | string                |                                                    |                             |
| title                    | string                |                                                    |                             |
| firstname                | string                |                                                    |                             |
| lastname                 | string                |                                                    |                             |
| street                   | string                |                                                    |                             |
| zipcode                  | string                |                                                    |                             |
| city                     | string                |                                                    |                             |
| phone                    | string                |                                                    |                             |
| vatId                    | string                |                                                    |                             |
| additionalAddressLine1   | string                |                                                    |                             |
| additionalAddressLine2   | string                |                                                    |                             |
| country                   | int (foreign key)       | **[Country](../models/#country)**               |                             |
| state                       | int (foreign key)       | **[State](#state)**                             |                             |
| attribute                | array                 |                                                  |                              |

## Area

* **Model:** Shopware\Models\Country\Area
* **Table:** s_core_countries_areas

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| name                | string                |                                                 |
| active                | boolean               |                                                 |
| countries           | object array          | **[Country](#country)**                         |

## Article Attribute

* **Model:** Shopware\Models\Attribute\Article
* **Table:** s_articles_attributes

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| attr1                | string                |                                                 |
| attr2                | string                |                                                 |
| attr3                | string                |                                                 |
| attr4                | string                |                                                 |
| attr5                | string                |                                                 |
| attr6                | string                |                                                 |
| attr7                | string                |                                                 |
| attr8                | string                |                                                 |
| attr9                | string                |                                                 |
| attr10                | string                |                                                 |
| attr11                | string                |                                                 |
| attr12                | string                |                                                 |
| attr13                | string                |                                                 |
| attr14                | string                |                                                 |
| attr15                | string                |                                                 |
| attr16                | string                |                                                 |
| attr17                | string                |                                                 |
| attr18                | string                |                                                 |
| attr19                | string                |                                                 |
| attr20                | string                |                                                 |
| articleId              | integer (foreign key) | **[Article](../api-resource-article/)**            |
| articleDetailId     |    integer (foreign key) | **[Detail](#article-detail)**                   |

## Article Detail

* **Model:** Shopware\Models\Article\Detail
* **Table:** s_articles_details

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| number                 | string                |                                                 |
| supplierNumber      | string                |                                                 |
| additionalText      | string                |                                                 |
| weight              | string                |                                                 |
| width               | string                |                                                 |
| len                 | string                |                                                 |
| height              | string                |                                                 |
| ean                 | string                |                                                 |
| purchaseUnit        | string                |                                                 |
| descriptionLong     | string                |                                                 |
| referenceUnit       | string                |                                                 |
| packUnit            | string                |                                                 |
| shippingTime        | string                |                                                 |
| prices              | object array          | **[Price](#price)**                             |
| configuratorOptions | object array          | **[ConfiguratorOption](#configurator-option)**  |
| attribute           | object                | **[Attribute](#article-attribute)**             |
| id                  | integer (primary key) |                                                 |
| articleId           | integer (foreign key) | **[Article](../api-resource-article/)**          |
| unitId              | integer (foreign key) |                                                 |
| kind                | integer               |                                                 |
| inStock             | integer               |                                                 |
| position            | integer               |                                                 |
| minPurchase         | integer               |                                                 |
| purchaseSteps       | integer               |                                                 |
| maxPurchase         | integer               |                                                 |
| releaseDate         | date/time             |                                                 |
| active              | boolean               |                                                 |
| shippingFree        | boolean               |                                                 |
| esd                 | object                | **[Esd](#esd)**                                 |

## Billing

* **Model:** Shopware\Models\Order\Billing
* **Table:** s_order_billingaddress

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| orderId             | integer (foreign key) |                                                 |
| customerId             | integer (foreign key) |                                                 |
| countryId             | integer (foreign key) | **[Country](#country)**                         |
| stateId             | integer (foreign key) |                                                 |
| company              | string                  |                                                    |
| department          | string                  |                                                    |
| title                | string                  |                                                    |
| salutation          | string                  |                                                    |
| number              | string                  |                                                    |
| firstName              | string                  |                                                    |
| lastName              | string                  |                                                    |
| street              | string                  |                                                    |
| zipCode              | string                  |                                                    |
| city                  | string                  |                                                    |
| additionalAddressLine1 | string                  |                                                    |
| additionalAddressLine2 | string                  |                                                    |
| phone                  | string                  |                                                    |
| vatId                  | string                  |                                                    |
| country              | object                  |    **[Country](#country)**                           |
| state              | object/null                  |    **[State](#state)**      |
| birthday              | date/time              |                                                    |
| attribute              | object                  |    **[BillingAttribute](#billing-attribute)**      |

## Billing Attribute

* **Model:** Shopware\Models\Attribute\OrderBilling
* **Table:** s_order_billingaddress_attributes

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| orderBillingId      | integer (foreign key) |                                                 |
| text1                  | string                  |                                                    |
| text2                  | string                  |                                                    |
| text3                  | string                  |                                                    |
| text4                  | string                  |                                                    |
| text5                  | string                  |                                                    |
| text6                  | string                  |                                                    |

## Category

* **Model:** Shopware\Models\Category\Category
* **Table:** s_categories

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id                    | integer (primary key) |                                                       |
| name                  | string                |                                                       |

## Configurator Group

* **Model:** Shopware\Models\Article\Configurator\Group
* **Table:** s_article_configurator_groups

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id                    | integer (primary key  |                                                       |
| description           | string                |                                                       |
| name                  | string                |                                                       |
| position              | integer               |                                                       |

## Configurator Option

* **Model:** Shopware\Models\Article\Configurator\Option
* **Table:** s_article_configurator_options

### Structure

| Field                 | Type                  | Original object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| id                     | integer (primary key) |                                                 |
| groupId               | integer (foreign key) | **[ConfiguratorGroup](#configurator-group)**    |
| name                  | string                |                                                 |
| position              | integer               |                                                 |

## Configurator Set

* **Model:** Shopware\Models\Article\Configurator\Set
* **Table:** s_article_configurator_sets

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id                    | integer (primary key  |                                                       |
| name                  | string                |                                                       |
| public                | boolean               |                                                       |
| type                  | integer               |                                                       |
| groups                | object array          | **[ConfiguratorGroup](#configurator-group)**          |

## Country

* **Model:** Shopware\Models\Country\Country
* **Table:** s_core_countries

### Structure

| Field                         | Type                  | Original object                                 |
|-----------------------------|-----------------------|-------------------------------------------------|
| id                              | integer (primary key) |                                                 |
| name                              | string                  |                                                 |
| iso                            | string                  |                                                 |
| isoName                          | string                  |                                                 |
| position                        | integer                  |                                                 |
| description                    | string                  |                                                 |
| shippingFree                    | boolean                  |                                                 |
| taxFree                        | boolean                  |                                                 |
| taxFreeUstId                    | boolean                  |                                                 |
| taxFreeUstIdChecked           | boolean                  |                                                 |
| active                        | boolean                  |                                                 |
| iso3                            | string                  |                                                 |
| displayStateInRegistration  | boolean                  |                                                 |
| forceStateInRegistration      | boolean                  |                                                 |
| areaId                      | integer    (foreign key) | **[Area](#area)**                                 |
| states                      | object array          | **[State](#state)**                             |

## Currency

* **Model:** Shopware\Models\Shop\Currency
* **Table:** s_core_currencies

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| currency                | string                  |                                                 |
| name                  | string                  |                                                 |
| default              | boolean                  |                                                 |
| factor              | double                  |                                                 |
| symbol              | string                  |                                                 |
| symbolPosition      | integer                  |                                                 |
| position              | integer                  |                                                 |

## Customer

* **Model:** Shopware\Models\Customer\Customer
* **Table:** s_user

### Structure

| Field                 | Type                  | Original object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| number                | string                |                                                 |
| groupKey              | string (foreign key)  | **[CustomerGroup](#customer-group)**            |
| paymentId             | integer (foreign key) | **[Payment](#payment)**                         |
| shopId                | string (foreign key)  | **[Shop](#shop)**                               |
| priceGroupId          | integer (foreign key) | **[PriceGroup](#price-group)**                  |
| encoderName           | string                |                                                 |
| hashPassword          | string                |                                                 |
| active                | boolean               |                                                 |
| email                 | string                |                                                 |
| firstLogin            | date/time             |                                                 |
| lastLogin             | date/time             |                                                 |
| accountMode           | integer               |                                                 |
| confirmationKey       | string                |                                                 |
| sessionId             | string                |                                                 |
| newsletter            | boolean               |                                                 |
| validation            | string                |                                                 |
| affiliate             | boolean               |                                                 |
| paymentPreset         | integer               |                                                 |
| languageId            | integer (foreign key) | **[Shop](#shop)**                               |
| referer               | string                |                                                 |
| internalComment       | string                |                                                 |
| failedLogins          | integer               |                                                 |
| lockedUntil           | date/time             |                                                 |
| salutation            | string                |                                                 |
| title                 | string                |                                                 |
| firstname             | string                |                                                 |
| lastname              | string                |                                                 |
| birthday              | date                  |                                                 |
| defaultBillingAddress | integer (foreign key) | **[Billing](#address)**                         |
| defaultShippingAddress| integer (foreign key) | **[Shipping](#address)**                        |

## Customer Attribute

* **Model:** Shopware\Models\Attribute\Customer
* **Table:** s_user_attributes

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                  | integer (primary key) |                                                 |
| customerId          | integer (foreign key) | **[Customer](#customer)**                       |

## Customer Group

* **Model:** Shopware\Models\Customer\Group
* **Table:** s_core_customergroups


### Structure

| Field                 | Type                  | Original object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| key                   | string                |                                                 |
| name                  | string                |                                                 |
| tax                   | boolean               |                                                 |
| taxInput              | boolean               |                                                 |
| mode                  | boolean               |                                                 |
| discount              | double                |                                                 |
| minimumOrder          | double                |                                                 |
| minimumOrderSurcharge | double                |                                                 |
| basePrice             | double                |                                                 |
| percent               | double                |                                                 |

## Customer Group Surcharge

* **Model:** Shopware\Models\Customer\Discount
* **Table:** s_customergroups_discounts

### Structure

| Field                 | Type                  | Original Object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| discount              | integer               |                                                 |
| value                 | integer               |                                                 |

## Debit

* **Model:** Shopware\Models\Customer\Debit
* **Table:** s_user_debit

### Structure

| Field               | Type                    | Original object                                 |
|---------------------|-------------------------|-------------------------------------------------|
| id                  | integer (primary key)   |                                                 |
| customerId          | integer (foreign key)   |                                                 |
| account             | string                  |                                                 |
| bankCode            | string                  |                                                 |
| bankName            | string                  |                                                 |
| accountHolder       | string                  |                                                 |

## Dispatch

* **Model:** Shopware\Models\Dispatch\Dispatch
* **Table:** s_premium_dispatch

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| name                    | string                  |                                                 |
| type                  | integer                  |                                                 |
| description            | string                  |                                                 |
| comment                | string                  |                                                 |
| active                | boolean                  |                                                 |
| position            | integer                  |                                                 |
| calculation            | integer                  |                                                 |
| surchargeCalculation| integer                  |                                                 |
| taxCalculation         | integer                  |                                                 |
| shippingFree           | decimal                  |                                                 |
| multiShopId            | integer (foreign key) | **[Shop](#shop)**                               |
| customerGroupId        | integer (foreign key) | **[CustomerGroup](#customer-group)**            |
| bindShippingFree       | integer                  |                                                 |
| bindTimeFrom           | integer                  |                                                 |
| bindTimeTo            | integer                  |                                                 |
| bindInStock            | integer                  |                                                 |
| bindLastStock            | integer                  |                                                 |
| bindWeekdayFrom        | integer                  |                                                 |
| bindWeekdayTo        | integer                  |                                                 |
| bindWeightFrom        | decimal                  |                                                 |
| bindWeightTo        | decimal                  |                                                 |
| bindPriceFrom        | decimal                  |                                                 |
| bindPriceTo            | decimal                  |                                                 |
| bindSql                | string                  |                                                 |
| statusLink            | string                  |                                                 |
| calculationSql         | string                  |                                                 |
| attribute         | object/null                  | **[DispatchAttribute](#dispatch-attribute)**        |

## Dispatch Attribute

* **Model:** Shopware\Models\Attribute\Dispatch
* **Table:** s_premium_dispatch_attributes

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| dispatchId      | integer (foreign key) |                                                 |

## Document

* **Model:** Shopware\Models\Order\Document\Document
* **Table:** s_order_documents

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| date                 | date/time              |                                                 |
| typeId              | integer (foreign key) | **[DocumentType](#document-type)**                |
| customerId          | integer (foreign key) | **[Customer](#customer)**                        |
| orderId              |    integer (foreign key) | **[Order](../api-resource-orders/)**                                |
| amount              | double                  |                                                 |
| documentId          | integer (foreign key) |                                                 |
| hash                  | string                   |                                                    |
| type                  | object                  |    **[DocumentType](#document-type)**                |
| attribute              | object                   |    **[DocumentAttribute](#document-attribute)**    |

## Document Attribute

* **Model:** Shopware\Models\Attribute\Document
* **Table:** s_order_documents_attributes

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| documentId             | integer (foreign key) |                                                 |

## Document Type

* **Model:** Shopware\Models\Document\Document
* **Table:** s_order_documents

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| name                 | string                  |                                                 |
| template              | string                  |                                                 |
| numbers              | string                  |                                                 |
| left                  | integer                  |                                                 |
| right                  | integer                  |                                                 |
| top                  | integer                  |                                                 |
| bottom              | integer                  |                                                 |
| pageBreak              | integer                  |                                                 |

## Download

* **Model:** Shopware\Models\Article\Download
* **Table:** s_articles_downloads

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id                    | integer (primary key) |                                                       |
| articleId             | integer (foreign key) | **[Article](../api-resource-article/)**               |
| name                  | string                |                                                       |
| file                  | string                |                                                       |
| size                  | int                   |                                                       |

## Esd

* **Model:** Shopware\Models\Article\Esd
* **Table:** s_articles_esd

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| file                  | string                |                                                       |
| reuse                 | boolean               |                                                       |
| hasSerials            | boolean               |                                                       |
| serials               | object array          | **[EsdSerial](#esd-serial)**                          |

## ESD-Serial

* **Model:** Shopware\Models\Article\EsdSerial
* **Table:** s_articles_esd_serials

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| serialnumber          | string                |                                                       |

## Image

* **Model:** Shopware\Models\Article\Image
* **Table:** s_articles_img

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id                    | integer (primary key  |                                                       |
| articleId             | integer (foreign key) | **[Article](../api-resource-article/)**               |
| articleDetailId       | integer (foreign key) | **[Detail](#article-detail)**                         |
| description           | string                |                                                       |
| path                  | string                |                                                       |
| main                  | integer               |                                                       |
| position              | integer               |                                                       |
| width                 | integer               |                                                       |
| height                | integer               |                                                       |
| relations             | string                |                                                       |
| extension             | string                |                                                       |
| parentId              | integer               |                                                         |
| mediaId               | integer               | **[Media](../api-resource-media/)**                    |

<div class="alert alert-info">

The field `path` has to be the local path to the image, seen from the root of the Shopware installation. There is an additional, internal helper field `link`, which allows to supply a URL that is being downloaded and converted to the `path` field internally. See the [product examples](https://developers.shopware.com/developers-guide/rest-api/examples/article/#further-examples] for an example.

</div>

## Link

* **Model:** Shopware\Models\Article\Link
* **Table:** s_articles_information

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id                    | integer (primary key) |                                                       |
| articleId             | integer (foreign key) | **[Article](../api-resource-article/)**               |
| name                  | string                |                                                       |
| link                  | string                |                                                       |
| target                | string                |                                                       |

## Locale

* **Model:** Shopware\Models\Shop\Locale
* **Table:** s_core_locales

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| locale                | string                  |                                                 |
| language              | string                  |                                                 |
| territory                | string                  |                                                 |

## Order Attribute

* **Model:** Shopware\Models\Attribute\OrderDetail
* **Table:** s_order_attributes

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| orderId                | integer (foreign key) |                                                 |
| attribute1          | string                  |                                                 |
| attribute2          | string                  |                                                 |
| attribute3          | string                  |                                                 |
| attribute4          | string                  |                                                 |
| attribute5          | string                  |                                                 |
| attribute6          | string                  |                                                 |

## Order Detail

* **Model:** Shopware\Models\Order\Detail
* **Table:** s_order_detail

### Structure

| Field               | Type                  | Original object                                         |
|---------------------|-----------------------|---------------------------------------------------------|
| id                    | integer (primary key) |                                                         |
| orderId             | string                  | **[Order](../api-resource-order/)**                      |
| articleId              | integer (foreign key) | **[Article](../api-resource-article/)**                   |
| taxId                  | integer (foreign key) | **[Tax](#tax)**                                            |
| taxRate              |    double                  |                                                         |
| statusId              | integer (foreign key) | **[Status](#order-status)**                                |
| number              | string (foreign key)  | **[Order](../api-resource-order/)**                        |
| articleNumber          | string (foreign key)  | **[ArticleDetail](#article-detail)**                    |
| price                  | double                  |                                                            |
| quantity              | integer               |                                                            |
| articleName          | string                     |                                                            |
| shipped              | boolean               |                                                            |
| shippedGroup          | integer               |                                                            |
| releaseDate          | date/time               |                                                            |
| mode                  | integer               |                                                            |
| esdArticle          | integer               |                                                            |
| config              | string                   |                                                            |
| ean              | string                   |                                                            |
| unit              | string                   |                                                            |
| packUnit              | string                   |                                                            |
| attribute              | object                   |    **[OrderDetailAttribute](#order-detail-attribute)**        |

## Order Detail Attribute

* **Model:** Shopware\Models\Attribute\OrderDetail
* **Table:** s_order_attributes

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| orderDetailId          | integer (foreign key) | **[OrderDetail](#order-detail)**                   |
| attribute1          | string                  |                                                    |
| attribute2          | string                  |                                                    |
| attribute3          | string                  |                                                    |
| attribute4          | string                  |                                                    |
| attribute5          | string                  |                                                    |
| attribute6          | string                  |                                                    |

## Order Status

* **Model:** Shopware\Models\Order\Status
* **Table:** s_core_states

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| name            | string                  |                                                 |
| position              | integer                  |                                                 |
| group                    | string                  |                                                 |
| sendMail              | boolean                  |                                                 |

## Payment

* **Model:** Shopware\Models\Payment\Payment
* **Table:** s_core_paymentmeans

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| name          | string |                                                 |
| description      | string                  |                                                    |
| template              | string                  |                                                    |
| hide                  | boolean                  |                                                    |
| additionalDescription                  | string                  |                                                    |
| debitPercent          | float                  |                                                    |
| surcharge              | integer                  |                                                    |
| surchargeString          | string                  |                                                    |
| position              | integer              |                                                    |
| active              | boolean              |                                                    |
| esdActive              | boolean              |                                                    |
| mobileInactive              | boolean              |                                                    |
| pluginId              | integer              |                                                    |

## Payment Data

* **Model:** Shopware\Models\Customer\PaymentData
* **Table:** s_core_payment_data

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| paymentMeanId          | integer (foreign key) |                                                 |
| useBillingData      | string                  |                                                    |
| bankName              | string                  |                                                    |
| bic                  | string                  |                                                    |
| iban                  | string                  |                                                    |
| accountNumber          | string                  |                                                    |
| bankCode              | string                  |                                                    |
| accountHolder          | string                  |                                                    |
| createdAt              | date/time              |                                                    |

## Payment Instance

* **Model:** Shopware\Models\Payment\PaymentInstance
* **Table:** s_core_payment_instance

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| firstName            | string                  |                                                 |
| lastName              | string                  |                                                 |
| address                | string                  |                                                 |
| zipCode              | string                  |                                                 |
| city                  | string                  |                                                 |
| bankName              | string                  |                                                 |
| bankCode              | string                  |                                                 |
| accountNumber          | string                  |                                                 |
| accountHolder          | string                  |                                                 |
| bic                  | string                  |                                                 |
| iban                  | string                  |                                                 |
| amount              | string                  |                                                 |
| createdAt              | date/time              |                                                 |

## Payment Status

* **Model:** Shopware\Models\Order\Status
* **Table:** s_core_states

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| name            | string                  |                                                 |
| position              | integer                  |                                                 |
| group                    | string                  |                                                 |
| sendMail              | boolean                  |                                                 |

## Price

* **Model:** Shopware\Models\Article\Price
* **Table:** s_articles_prices

### Structure

| Field               | Type                  | Original object                                       |
|---------------------|-----------------------|-------------------------------------------------------|
| customerGroupKey      | string (foreign key)  | **[CustomerGroup](#customer-group)**                   |
| customerGroup       | object                | **[CustomerGroup](#customer-group)**                   |
| articleDetailsId    | integer (foreign key) | **[Detail](#article-detail)**                         |
| articleId           | integer (foreign key) | **[Article](../api-resource-article/)**               |
| id                  | integer (primary key) |                                                       |
| from                | integer/string        |                                                       |
| to                  | string                |                                                       |
| price               | double                |                                                       |
| pseudoPrice         | double                |                                                       |
| basePrice           | double                |                                                       |
| percent             | double                |                                                       |

## Price Group

* **Table:** s_core_pricegroups

### Structure

| Field               | Type                  | Original object                                       |
|---------------------|-----------------------|-------------------------------------------------------|
| id                   | int (primary key)     |                                                       |
| description         | string                |                                                       |

## Property Group

* **Model:** Shopware\Models\Property\Group
* **Table:** s_filter

### Structure

| Field                 | Type                  | Original object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key  |                                                 |
| name                  | string                |                                                 |
| position              | integer               |                                                 |
| comparable            | boolean               |                                                 |
| sortMode              | integer               |                                                 |

## Property Group Attribute

* **Model:** Shopware\Models\Attribute\PropertyGroup
* **Table:** s_filter

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| propertyGroupId        | integer (foreign key) |                                                 |

## Property Group Option

* **Model:** Shopware\Models\Property\Option
* **Table:** s_filter_options

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| name                    | string                  |                                                 |
| filterable          | boolean                  |                                                 |

## Property Value

### Structure

| Field                 | Type                  | Original object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| valueNumeric             | double                |                                                 |
| position              | integer               |                                                 |
| optionId              | integer               |                                                 |
| id                    | integer (primary key) |                                                 |
| value                 | string                |                                                 |

## Related

* **Table:** s_articles_relationships

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id                    | integer (foreign key) | **[Article](../api-resource-article/)**               |
| name                  | string                |                                                       |

## Shipping

* **Model:** Shopware\Models\Order\Shipping
* **Table:** s_order_shippingaddress

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| orderId                    | integer (primary key) |                                                 |
| customerId             | integer (foreign key) | **[Customer](#customer)**       |
| countryId             | integer (foreign key) | **[Country](#country)**                         |
| stateId             | integer (foreign key) | **[State](#state)**                             |
| company              | string                  |                                                    |
| department          | string                  |                                                    |
| title               | string                  |                                                    |
| salutation          | string                  |                                                    |
| number              | string                  |                                                    |
| firstName              | string                  |                                                    |
| lastName              | string                  |                                                    |
| street              | string                  |                                                    |
| zipCode              | string                  |                                                    |
| city                  | string                  |                                                    |
| additionalAddressLine1 | string                  |                                                    |
| additionalAddressLine2 | string                  |                                                    |
| country              | object                  |    **[Country](#country)**        |
| state              | object                  |    **[State](#state)**        |
| attribute              | object                  |    **[ShippingAttribute](#shipping-attribute)**        |

## Shipping Attribute

* **Model:** Shopware\Models\Attribute\OrderBilling
* **Table:** s_order_shippingaddress_attributes

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| orderShippingId  | integer (foreign key) |                                                 |
| text1                  | string                  |                                                    |
| text2                  | string                  |                                                    |
| text3                  | string                  |                                                    |
| text4                  | string                  |                                                    |
| text5                  | string                  |                                                    |
| text6                  | string                  |                                                    |

## Shop

* **Model:** Shopware\Models\Shop\Shop
* **Table:** s_core_shops

### Structure

| Field               | Type                  | Original object                                 |
|---------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| mainId                | integer (foreign key) |                                                 |
| categoryId          | integer (foreign key) | **[Category](#category)**                        |
| name                    | string                  |                                                 |
| title                  | string                  |                                                 |
| position              | integer                  |                                                 |
| host                  | string                  |                                                 |
| basePath              | string                  |                                                 |
| baseUrl              | string                  |                                                 |
| hosts                  | string                  |                                                 |
| secure              | boolean                  |                                                 |
| secureHost          | string                  |                                                 |
| secureBasePath      | string                  |                                                 |
| default              | boolean                  |                                                 |
| active              | boolean                  |                                                 |
| customerScope          | boolean                  |                                                 |
| locale              | object                  | **[Locale](#locale)**                            |

**The locale is only available for languageSubShops.**

## Similar

* **Table:** s_articles_similar

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| id                    | integer (foreign key) | **[Article](../api-resource-article/)**               |
| name                  | string                |                                                       |

## State

* **Model:** Shopware\Models\Country\State
* **Table:** s_core_countries_states

### Structure

| Field                 | Type                  | Original object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| countryId             | integer (foreign key) | **[Country](#country)**       |
| position              | integer               |                                                 |
| name                  | string                |                                                 |
| shortCode             | string                |                                                 |
| active                | boolean               |                                                 |

## Supplier

* **Model:** Shopware\Models\Article\Supplier
* **Table:** s_articles_supplier

### Structure

| Field                 | Type                  | Original object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| id                    | integer (primary key) |                                                 |
| name                  | string                |                                                 |
| image                 | string                |                                                 |
| link                  | string                |                                                 |
| description           | string                |                                                 |
| metaTitle             | string                |                                                 |
| metaDescription       | string                |                                                 |
| metaKeywords          | string                |                                                 |

## Tax

* **Model:** Shopware\Models\Tax\Tax
* **Table:** s_core_tax

### Structure

| Field                 | Type                  | Original object                                 |
|-----------------------|-----------------------|-------------------------------------------------|
| id                     | integer (primary key) |                                                 |
| tax                   | string                |                                                   |
| name                  | string                |                                                 |

## Translation

* **Model:** Shopware\Models\Article\Translation
* **Table:** s_core_translation, s_articles_translations

### Structure

| Field                 | Type                  | Original object                                       |
|-----------------------|-----------------------|-------------------------------------------------------|
| metaTitle                | string                |                                                       |
| attr1                 | string                |                                                       |
| attr2                 | string                |                                                       |
| attr3                 | string                |                                                       |
| attr4                 | string                |                                                       |
| attr5                 | string                |                                                       |
| attr6                 | string                |                                                       |
| attr7                 | string                |                                                       |
| attr8                 | string                |                                                       |
| attr9                 | string                |                                                       |
| attr10                | string                |                                                       |
| attr11                | string                |                                                       |
| attr12                | string                |                                                       |
| attr13                | string                |                                                       |
| attr14                | string                |                                                       |
| attr15                | string                |                                                       |
| attr16                | string                |                                                       |
| attr17                | string                |                                                       |
| attr18                | string                |                                                       |
| attr19                | string                |                                                       |
| attr20                | string                |                                                       |
| name                  | string                |                                                       |
| description           | string                |                                                       |
| descriptionLong       | string                |                                                       |
| keywords              | string                |                                                       |
| packUnit              | string                |                                                       |
| shopId                | integer               |                                                       |
