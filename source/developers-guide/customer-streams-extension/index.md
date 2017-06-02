---
layout: default
title: Customer - Search and Streams
github_link: developers-guide/customer-streams-extension/index.md
indexed: true
group: Developer Guides
subgroup: General Resources
menu_title: Customer - Search & Streams
menu_order: 500
---

This article describes how to extend the customer stream and customer search module in shopware. In most cases, the purpose of a plugin is to provide more conditions or to add data to the search. You find simple examples which describe how this is archived below.

## Add own condition

Since the customer search is based on the same functions as the product search, the way of extending the search is pretty similar. The customer search is given a criteria object in which the different search conditions are summarized. The first step is to define your own condition:

```
<?php

namespace SwagCustomerSearchExtension\Bundle\CustomerSearchBundle;

use Shopware\Bundle\SearchBundle\ConditionInterface;

class ActiveCondition implements ConditionInterface
{
    /**
     * @var bool
     */
    protected $active;

    /**
     * @param bool $active
     */
    public function __construct($active)
    {
        $this->active = $active;
    }

    public function getName()
    {
        return 'ActiveCondition';
    }

    public function onlyActive()
    {
        return $this->active;
    }
}
```

The condition above only describes on an abstract level what is to be searched for. The actual processing of the condition happens in the corresponding implementation of the search. Currently the customer search in Shopware is only executed in SQL. The concept is based on SearchBundleDBAL and SearchBundleES. An actual handler class which handles this condition in SQL could look like this:

```
<?php

namespace SwagCustomerSearchExtension\Bundle\CustomerSearchBundleDBAL;

use Shopware\Bundle\CustomerSearchBundleDBAL\ConditionHandlerInterface;
use Shopware\Bundle\SearchBundle\ConditionInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilder;
use SwagCustomerSearchExtension\Bundle\CustomerSearchBundle\ActiveCondition;

class ActiveConditionHandler implements ConditionHandlerInterface
{
    public function supports(ConditionInterface $condition)
    {
        return $condition instanceof ActiveCondition;
    }

    public function handle(ConditionInterface $condition, QueryBuilder $query)
    {
        $query->andWhere('customer.active = :active');

        /** @var ActiveCondition $condition */
        $query->setParameter(':active', $condition->onlyActive());
    }
}
```

The handler can be registered with a compiler tag, named `customer_search.condition_handler`:

```
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="swag_customer_search_extension.active_condition_handler"
                 class="SwagCustomerSearchExtension\Bundle\CustomerSearchBundleDBAL\ActiveConditionHandler">

            <tag name="customer_search.condition_handler"/>
        </service>

    </services>
</container>
```

## Backend Module extension

To support the condition in the backend, it is necessary to extend the customer module via ExtJS. The module can be extended over the PostDispatch event of the backend customer controller:

```
<?php

namespace SwagCustomerSearchExtension;

use Shopware\Components\Plugin;

class SwagCustomerSearchExtension extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Backend_Customer' => 'extendCustomerStream'
        ];
    }

    public function extendCustomerStream(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Customer $subject */
        $subject = $args->getSubject();

        $subject->View()->addTemplateDir(__DIR__ . '/Resources/views');

        $subject->View()->extendsTemplate('backend/customer/swag_customer_stream_extension.js');
    }
}
```

The extended `swag_customer_stream_extension.js` contains all overrides for the backend module:

```
// {block name="backend/customer/view/customer_stream/condition_panel"}

// {$smarty.block.parent}

Ext.define('Shopware.apps.Customer.SwagCustomerStreamExtension', {
    override: 'Shopware.apps.Customer.view.customer_stream.ConditionPanel',

    registerHandlers: function() {
        var me = this,
            //fetch original handlers
            handlers = me.callParent(arguments);

        //push own handler into
        handlers.push(Ext.create('Shopware.apps.Customer.swag_customer_stream_extension.ActiveCondition'));

        //return modified handlers array
        return handlers;
    }
});


//definition of your own condition
Ext.define('Shopware.apps.Customer.swag_customer_stream_extension.ActiveCondition', {

    getLabel: function() {
        return 'My active condition';
    },

    supports: function(conditionClass) {
        return (conditionClass == 'SwagCustomerSearchExtension\\Bundle\\CustomerSearchBundle\\ActiveCondition');
    },

    create: function(callback) {
        callback(this._create());
    },

    load: function(conditionClass, items, callback) {
        callback(this._create());
    },

    _create: function() {
        return {
            title: this.getLabel(),
            conditionClass: 'SwagCustomerSearchExtension\\Bundle\\CustomerSearchBundle\\ActiveCondition',
            items: [{
                xtype: 'checkbox',
                name: 'active',
                boxLabel: 'Activate for active customers, deactivate for inactive customers',
                inputValue: true,
                uncheckedValue: false
            }]
        };
    }
});

// {/block}
```

The first part hooks into the customer stream condition panel and registers the plugin condition. The second part contains the whole logic to handle the condition for load and create actions. 

The create and load function have to return an object with the following data:

* `title` - Used for the panel title
* `conditionClass` - Used for class generation - Aside, used for singleton detection
* `items` - Contains a list of parameters, which used for `__construct` call

## Search Indexing

The `CustomerSearchBundleDBAL` uses an aggregated table which allows fast filtering and sorting, even on large data sets. This table is generated by the `Shopware\Bundle\CustomerSearchBundleDBAL\Indexing\SearchIndexer` class.  If a plugin wants to filter and sort additional aggregated data, it can hook into the indexing process to collect additional data.
The following `services.xml` shows how to decorate the `customer_search.dbal.indexing.indexer`.

```
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="swag_customer_search_extension.search_indexer"
                 class="SwagCustomerSearchExtension\Bundle\CustomerSearchBundleDBAL\SearchIndexer"
                 decorates="customer_search.dbal.indexing.indexer">

            <argument id="swag_customer_search_extension.search_indexer.inner" type="service"/>
            <argument id="dbal_connection" type="service"/>
        </service>
    </services>
</container>
```

Shopware expects that a class is found under this service name, which implements the interface `SearchIndexerInterface`:

```
<?php

namespace SwagCustomerSearchExtension\Bundle\CustomerSearchBundleDBAL;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\CustomerSearchBundleDBAL\Indexing\SearchIndexerInterface;

class SearchIndexer implements SearchIndexerInterface
{
    /**
     * @var SearchIndexerInterface
     */
    private $coreIndexer;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param SearchIndexerInterface $coreIndexer
     * @param Connection $connection
     */
    public function __construct(SearchIndexerInterface $coreIndexer, Connection $connection)
    {
        $this->coreIndexer = $coreIndexer;
        $this->connection = $connection;
    }

    public function populate(array $ids)
    {
        $this->coreIndexer->populate($ids);

        //fetch data
        $rows = $this->connection->createQueryBuilder()->execute()->fetchAll();

        //create prepared statement for fast inserts
        $statement = $this->connection->prepare("INSERT INTO test-table");

        //iterate rows and insert data
        foreach ($rows as $row) {
            $statement->execute($row);
        }
    }

    public function clearIndex()
    {
        $this->coreIndexer->clearIndex();
        $this->connection->executeUpdate("DELETE FROM test-table");
    }

    public function cleanupIndex()
    {
        $this->coreIndexer->cleanupIndex();
    }
}
```

The best way to index additional data is to aggregate the data beforehand and save it into a separate table, which is in a 1:1 relation to the original search_index table. The condition handler can access these indexed data quickly to allow a fluent expierence.
You download the example plugin above <a href="{{ site.url }}/exampleplugins/SwagCustomerSearchExtension.zip">here</a>.
