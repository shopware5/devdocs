---
layout: default
title: Dependency Injection Tags
github_link: developers-guide/dic-tags/index.md
indexed: true
tags:
  - dependency-injection
  - container
  - tags
group: Developer Guides
subgroup: General Resources
menu_title: Dependency Injection Tags
menu_order: 110
---

Dependency Injection Tags are little strings that can be applied to a service to "flag" it to be used in some special way. For example, if you have a service that you would like to register as a subscriber, you can flag it with the `shopware.event_subscriber` tag.

Below is information about all of the tags available inside Shopware.

| Tag Name | Usage |
|------------------|------------------|
| [attribute_search_repository](#attribute_search_repository)           | Add a custom entity repository |
| [condition_handler_dbal](#condition_handler_dbal) | Add SQL handler for a condition |
| [console.command](#console.command) | Add a command |
| [criteria_request_handler](#criteria_request_handler) | Add a criteria request handler modify the search |
| [customer_search.condition_handler](#customer_search.condition_handler) | Add a SQL handler for a customer condition |
| [customer_search.sorting_handler](#customer_search.sorting_handler) | Add a SQL handler for a customer sorting |
| [facet_handler_dbal](#facet_handler_dbal) | Add handler for a facet |
| [shopware.captcha](#shopware.captcha) | Add a captcha mechanism |
| [shopware_emotion.component_handler](#shopware_emotion.component_handler) | Process data for an emotion element |
| [shopware.emotion.preset_component_handler](#shopware.emotion.preset_component_handler) | Process element data on import / export |
| [shopware.event_subscriber](#shopware.event_subscriber) | To subscribe to a set of different events/hooks in Shopware |
| [shopware.event_listener](#shopware.event_listener) | Listen to different events/hooks in Shopware |
| [shopware_elastic_search.data_indexer](#shopware_elastic_search.data_indexer)  | Add an Elasticsearch indexer |
| [shopware_elastic_search.mapping](#shopware_elastic_search.mapping) | Add an Elasticsearch field mapping |
| [shopware_elastic_search.settings](#shopware_elastic_search.settings) | Create Elasticsearch index settings |
| [shopware_elastic_search.synchronizer](#shopware_elastic_search.synchronizer) | Create an Elasticsearch index synchronizer |
| [shopware_search_es.search_handler](#shopware_search_es.search_handler) | Add an Elasticsearch handler for a condition |
| [shopware_media.adapter](#shopware_media.adapter) | Add a media adapter |
| [shopware_media.optimizer](#shopware_media.optimizer) | Add a media optimizer |
| [sorting_handler_dbal](#sorting_handler_dbal) | Add SQL handler for a sorting |

## attribute_search_repository

**Purpose**: Add a custom entity repository

Custom attribute search repositories are services to search for entities in an optimized way. By default, the most entities are searched through a generic repository which applies the given filter term on every field in the entity.

If you want to search through related entities, you have to add a custom search repository, which extends the filter to join the related entities.

For example, the CustomerReader to search for addresses and customer group.

```php
class CustomerReader extends GenericReader
{
    protected function createListQuery()
    {
        $query = $this->entityManager->createQueryBuilder();
        $query->select([
            'entity.id',
            'entity.email',
            'entity.active',
            'billing.firstName',
            'billing.lastName',
            'billing.company',
            'entity.number',
            'grp.name as customerGroup',
        ]);
        $query->from(Customer::class, 'entity', $this->getIdentifierField());
        $query->innerJoin('entity.billing', 'billing');
        $query->innerJoin('entity.group', 'grp');

        return $query;
    }
}
```

And registered in the DIC:

```xml
<service id="shopware_attribute.customer_repository" class="Shopware\Bundle\AttributeBundle\Repository\GenericRepository">
    <tag name="attribute_search_repository" />
    <argument>Shopware\Models\Customer\Customer</argument>
    <argument type="service" id="models" />
    <argument type="service" id="shopware_attribute.customer_reader" />
    <argument type="service" id="shopware_attribute.customer_searcher" />
</service>
```

## shopware_media.adapter

**Purpose**: Add a media adapter

For details on registering a new media adapter, read [Media Service - Build your own adapter](/developers-guide/shopware-5-media-service/#build-your-own-adapter).

## shopware_media.optimizer

**Purpose**: Add a media optimizer

For details on registering a new media optimizer, read [Media Optimizer - Create optimizer using a HTTP API](/developers-guide/media-optimizer/#example:-create-optimizer-using-a-http-api).

## shopware_elastic_search.data_indexer

**Purpose**: Add an Elasticsearch indexer

After the data mapping is defined, the data can be indexed using the `Shopware\Bundle\ESIndexingBundle\DataIndexerInterface` interface. The `populate` method is responsible for loading all relevant data entries into Elasticsearch for the provided shop.

For details on Elasticsearch, read [Elasticsearch development](/developers-guide/Elasticsearch/).

## shopware_elastic_search.mapping

**Purpose**: Add an Elasticsearch field mapping

The entity properties must be mapped to Elasticsearch fields using the `Shopware\Bundle\ESIndexingBundle\MappingInterface` interface.

For details on Elasticsearch, read [Elasticsearch development](/developers-guide/Elasticsearch/).

## shopware_elastic_search.settings

**Purpose**: Add custom Elasticsearch anaylzers
 
For details on Elasticsearch, read [Elasticsearch development](/developers-guide/Elasticsearch/).

## shopware_elastic_search.synchronizer

**Purpose**: Create an Elasticsearch index synchronizer

Handles backlog queue to synchronize entities which are added by the `ORMBacklogSubscriber`.

For details on Elasticsearch, read [Elasticsearch development](/developers-guide/Elasticsearch/).

## shopware_search_es.search_handler

**Purpose**: Add an Elasticsearch handler for a condition

Analog to the DBAL condition handlers, you have to translate the abstract condition to an Elasticsearch query.

For details on Elasticsearch, read [Elasticsearch development](/developers-guide/Elasticsearch/).

## criteria_request_handler

**Purpose**: Add a criteria request handler modify the search

To add conditions to the product listing search request, if a specific parameter is set.

## facet_handler_dbal

**Purpose**: Add handler for a product facet

Generates the facet data for the passed query, criteria and context object. For details on facets, read [SearchBundle - Concept Facets](/developers-guide/shopware-5-search-bundle/#concept-facets).

## condition_handler_dbal

**Purpose**: Add SQL handler for a product condition

Your handler must implement the `Shopware\Bundle\SearchBundleDBAL\ConditionHandlerInterface` interface and be registered in your `services.xml`.

```php
<service id="swag_plugin.foo_condition_handler" class="SwagPlugin\FooConditionHandler">
    <tag name="condition_handler_dbal" />
</service>
```

For understanding the concept of conditions for products and customers, read [SearchBundle - Full implementation with condition](/developers-guide/shopware-5-search-bundle/#full-implementation-with-condition-(with-dbal))

## sorting_handler_dbal

**Purpose**: Add SQL handler for a product sorting

Each sorting class can be used for ascending or descending sorting. The direction is specified in the class constructor. Your handler must implement the `Shopware\Bundle\SearchBundleDBAL\SortingHandlerInterface` interface and be registered in your `services.xml`.

You should use `addOrderBy()` on the query to prevent overwriting of other sortings.

```php
<service id="swag_plugin.foo_sorting_handler" class="SwagPlugin\FooSortingHandler">
    <tag name="sorting_handler_dbal" />
</service>
```

For understanding the concept of sortings for products and customers, read [SearchBundle - List of conditions and sortings](/developers-guide/shopware-5-search-bundle/#list-of-conditions-and-sortings)

## console.command

**Purpose**: Add a command to the application

For details on registering your own commands in the service container, read [How to Define Commands as Services](https://symfony.com/doc/current/console/commands_as_services.html).

## shopware.event_subscriber

**Purpose**: To subscribe to a set of different events/hooks in Shopware

To enable a custom subscriber, add it as a regular service in your `services.xml` file and tag it with `shopware.event_subscriber`:

```xml
<service id="swag_plugin.custom_subscriber" class="SwagPlugin\CustomSubscriber">
    <tag name="shopware.event_subscriber" />
</service>
```

<div class="alert alert-info"><b>Hint!</b> Your service must implement the <code>EventSubscriberInterface</code> interface.</div>

## shopware.event_listener

**Purpose**: Listen to different events/hooks in Shopware

During the execution of Shopware, different events are triggered and you can also dispatch custom events. This tag allows you to hook your own classes into any of those events.

For a full example of this listener, read the [Shopware Events](https://developers.shopware.com/developers-guide/event-guide/) guide.

## shopware.captcha

**Purpose**: Add a captcha mechanism

For details on creating a custom captcha mechanism, read [Implementing your own captcha](/developers-guide/implementing-your-own-captcha).

## customer_search.sorting_handler

**Purpose**: Add a SQL handler for a customer sorting

Each sorting class can be used for ascending or descending sorting. The direction is specified in the class constructor. Your handler must implement the `Shopware\Bundle\CustomerSearchBundleDBAL\SortingHandlerInterface` interface and be registered in your `services.xml`.

You should use `addOrderBy()` on the query to prevent overwriting of other sortings.

```php
<service id="swag_plugin.foo_sorting_handler" class="SwagPlugin\FooSortingHandler">
    <tag name="customer_search.sorting_handler" />
</service>
```

For understanding the concept of sortings for products and customers, read [SearchBundle - List of conditions and sortings](/developers-guide/shopware-5-search-bundle/#list-of-conditions-and-sortings)

## customer_search.condition_handler

**Purpose**: Add a SQL handler for a customer condition

Your handler must implement the `Shopware\Bundle\CustomerSearchBundleDBAL\ConditionHandlerInterface` interface and be registered in your `services.xml`.

```php
<service id="swag_plugin.foo_condition_handler" class="SwagPlugin\FooConditionHandler">
    <tag name="customer_search.condition_handler" />
</service>
```

For understanding the concept of conditions for products and customers, read [SearchBundle - Full implementation with condition](/developers-guide/shopware-5-search-bundle/#full-implementation-with-condition-(with-dbal))

## shopware_emotion.component_handler

**Purpose**: Process data for an emotion element

The prepare step collects product numbers or criteria objects which will be resolved across all elements at once. The handle step provides a collection with resolved products and can be filled into your element for later usage.

For details on creating your own emotion component handler, read [Custom shopping world elements](/developers-guide/custom-shopping-world-elements/#process-the-element-data-before-output).

## shopware.emotion.preset_component_handler

**Purpose**: Process element data on import / export

During export and import processing, the `PresetDataSynchronizer` loops through all elements of a shopping world and checks if there is a handler which can handle the component.

For details on creating your own preset handler, read [Custom shopping world elements](/developers-guide/custom-shopping-world-elements/#adding-a-custom-component-handler-for-export).