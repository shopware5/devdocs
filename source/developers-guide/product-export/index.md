---
layout: default
title: Extending product exports
github_link: developers-guide/product-export/index.md
shopware_version: 5.3.0
tags:
  - product
  - export
  - developers
  - beginner
indexed: true
group: Developer Guides
subgroup: General Resources
menu_title: Product exports
menu_order: 600
---

<div class="toc-list"></div>

## Introduction
This article will show examples for extending product exports in shopware

## Modify the article variables
The `Shopware_Modules_Export_ExportResult_Filter_Fixed` event can be used to modify the exported  product data. A use case could be adding new variables to every product or edit one of the existing variables. You can also create your own filter to remove products which meet certain criteria. Here is a basic example how to use that event in a plugin base file:
```php
public static function getSubscribedEvents()
{
    return [
        'Shopware_Modules_Export_ExportResult_Filter_Fixed' => 'onFilterExportResult',
    ];
}

public function onFilterExportResult(\Enlight_Event_EventArgs $args)
{
    $products = $args->getReturn();
    /** This is the id of the feed being exported */
    $feedId = $args->get('feedId');
    /** @var \sExport $sExport */
    $sExport = $args->get('subject');
    
    /**
     * Here is the instance of the sExport class which can be used to add new variables to smarty for example
     */
    $sExport->sSmarty->assign('newVariable', ['custom' => 'This is a custom variable available in the export template']);
    
    /**
     * in $products are all the products as array which can be modified here
     */
    foreach($products as &$product) {
        $product['randomNumber'] = random_int(1, 100);
    }
    
    return $products;
}
```
