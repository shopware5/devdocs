---
layout: default
title: Extension of the statistics module
github_link: developers-guide/backend-statistics-extension/index.md
tags:
  - extjs
  - plugin
  - extend
  - override
  - backend
indexed: true
group: Developer Guides
subgroup: Backend and ExtJS
menu_title: Statistics extension
menu_order: 75
---

This tutorial shows the extension of the statistics module with own statistics.

<div class="toc-list"></div>

## PHP implementation

### Plugin base class

SwagCustomStatistics/SwagCustomStatistics.php

```php
<?php

namespace SwagCustomStatistics;

use Enlight_Controller_ActionEventArgs as ActionEventArgs;
use Shopware\Components\Plugin;

class SwagCustomStatistics extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Analytics' => 'onPostDispatchBackendAnalytics',
        ];
    }

    /**
     * @param ActionEventArgs $args
     */
    public function onPostDispatchBackendAnalytics(ActionEventArgs $args)
    {
        $request = $args->getRequest();
        $view = $args->getSubject()->View();

        $view->addTemplateDir($this->getPath() . '/Resources/views/');

        if ($request->getActionName() === 'index') {
            $view->extendsTemplate('backend/analytics/swag_custom_statistics/app.js');
        }

        if ($request->getActionName() === 'load') {
            $view->extendsTemplate('backend/analytics/swag_custom_statistics/store/navigation.js');
        }
    }
}

```

Subscribe to the event which is fired, when the backend analytics controller is called.
In the callback method, add your plugin template directory.
Load your custom ExtJs files by using the different actions.
Have a look at [this article](/developers-guide/backend-extension/#extending), to learn more about that.

### Backend controller

SwagCustomStatistics/Controllers/Backend/SwagCustomStatistics.php

```php
<?php

class Shopware_Controllers_Backend_SwagCustomStatistics extends Shopware_Controllers_Backend_ExtJs
{
    public function getVoucherStatisticsAction()
    {
        $connection = $this->container->get('dbal_connection');
        $query = $connection->createQueryBuilder();
        $query->select(['COUNT(codes.cashed) as amount', 'vouchers.description as name'])
            ->from('s_emarketing_voucher_codes', 'codes')
            ->innerJoin('codes', 's_emarketing_vouchers', 'vouchers', 'vouchers.id = codes.voucherID')
            ->where('codes.cashed = 1')
            ->groupBy('vouchers.id');

        $idList = (string) $this->Request()->getParam('selectedShops');
        if (!empty($idList)) {
            $selectedShopIds = explode(',', $idList);

            foreach ($selectedShopIds as $shopId) {
                $query->addSelect('SUM(IF(vouchers.subshopID = ' . $connection->quote($shopId) . ', codes.cashed, 0)) as amount' . $shopId);
            }
        }

        $data = $query->execute()->fetchAll();

        $this->View()->assign([
            'success' => true,
            'data' => $data,
            'count' => count($data)
        ]);
    }
}
```

Select the used individual vouchers in the `getVoucherStatisticsAction`.
As it is possible to select different shops in the analytics module, consider the selected shops from the request. They are separated by commas.
The action is called by the [ExtJs](#custom-statistics-store) store to gather the data.

## ExJs implementation

### Custom statistics store

SwagCustomStatistics/Resources/views/backend/analytics/swag_custom_statistics/store/navigation/voucher.js

```javascript
Ext.define('Shopware.apps.Analytics.swagCustomStatistics.store.navigation.Voucher', {
    extend: 'Ext.data.Store',
    alias: 'widget.analytics-store-voucher',
    remoteSort: true,

    fields: [
        'amount',
        'name'
    ],

    proxy: {
        type: 'ajax',

        url: '{url controller=SwagCustomStatistics action=getVoucherStatistics}',

        reader: {
            type: 'json',
            root: 'data',
            totalProperty: 'total'
        }
    },

    constructor: function(config) {
        var me = this;
        config.fields = me.fields;

        if (config.shopStore) {
            config.shopStore.each(function(shop) {
                config.fields.push('amount' + shop.data.id);
            });
        }

        me.callParent(arguments);
    }
});
```

In this store you can define the fields, which you want to access later in the grid/chart.
Also define the proxy. It defines from where the data comes. Use your [backend controller](#backend-controller) with its action for that.

### Navigation store extension

SwagCustomStatistics/Resources/views/backend/analytics/swag_custom_statistics/store/navigation.js

```javascript
// {block name="backend/analytics/store/navigation/items"}
// {$smarty.block.parent}
{
    id: 'voucher',
    text: 'Gutscheine',
    store: 'analytics-store-voucher',
    iconCls: 'sprite-ticket',
    comparable: true,
    leaf: true,
    multiShop: true
},
// {/block}
```

Extends the navigation store of the statistics module.
This is not only used for displaying a new menu item, but also for the linking to the right grid/chart via the ID.

### Custom statistics grid

SwagCustomStatistics/Resources/views/backend/analytics/swag_custom_statistics/view/table/voucher.js

```javascript
Ext.define('Shopware.apps.Analytics.swagCustomStatistics.view.table.Voucher', {
    extend: 'Shopware.apps.Analytics.view.main.Table',
    alias: 'widget.analytics-table-voucher',

    initComponent: function() {
        var me = this;

        me.columns = {
            items: me.getColumns(),
            defaults: {
                flex: 1,
                sortable: false
            }
        };

        me.initStoreIndices('amount', 'Anzahl: [0]');

        me.callParent(arguments);
    },

    getColumns: function() {
        return [
            {
                dataIndex: 'name',
                text: 'Name'
            },
            {
                dataIndex: 'amount',
                text: 'Anzahl'
            }
        ];
    }
});
```

This grid shows us our data in a table.
As we extend from a ready-made Shopware component, we don't have to write anything on our own.
Just configure the columns and the data fields defined in the [store](#custom-statistics-store).
To set the linking correct, mind the right alias `widget.analytics-table-<ID>`.
The last part is the ID of the menu item you defined [here](#navigation-store-extension)

### Custom statistics chart

SwagCustomStatistics/Resources/views/backend/analytics/swag_custom_statistics/view/chart/voucher.js

```javascript
Ext.define('Shopware.apps.Analytics.swagCustomStatistics.view.chart.Voucher', {
    extend: 'Shopware.apps.Analytics.view.main.Chart',
    alias: 'widget.analytics-chart-voucher',
    animate: true,
    shadows: true,

    legend: {
        position: 'right'
    },

    initComponent: function() {
        var me = this;

        me.series = [];

        me.axes = [
            {
                type: 'Numeric',
                position: 'left',
                fields: me.getAxesFields('amount'),
                title: 'Eingelöst',
                grid: true,
                minimum: 0
            },
            {
                type: 'Category',
                position: 'bottom',
                fields: ['name'],
                title: 'Gutscheine'
            }
        ];

        this.series = [
            {
                type: 'column',
                axis: 'left',
                gutter: 80,
                xField: 'name',
                yField: me.getAxesFields('amount'),
                title: me.getAxesTitles('{s name=chart/country/sum}Total sales{/s}'),
                stacked: true,
                label: {
                    display: 'insideEnd',
                    field: 'amount',
                    orientation: 'horizontal',
                    'text-anchor': 'middle'
                },
                tips: {
                    trackMouse: true,
                    width: 300,
                    height: 60,
                    renderer: function(storeItem, barItem) {
                        var name = storeItem.get('name'),
                            field = barItem.yField;

                        this.setTitle(name + ' : ' + storeItem.get(field));
                    }
                }
            }
        ];

        me.callParent(arguments);
    }
});
```

The last part is the visualization of our data with a bar chart.
Again extend from the ready-made Shopware component.
Also check for the right alias here `widget.analytics-chart-<ID>`.

## Download
The full example can be [downloaded here](/exampleplugins/SwagCustomStatistics.zip).
