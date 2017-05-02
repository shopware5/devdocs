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
|------------------|
| [attribute_search_repository](#attribute_search_repository)           | Add a custom entity repository |
| [shopware_media.adapter](#shopware_media.adapter) | Add a media adapter |
| [shopware_media.optimizer](#shopware_media.optimizer) | Add a media optimizer |
| [shopware_elastic_search.data_indexer](#shopware_elastic_search.data_indexer)  | Add an elasticsearch indexer |
| [shopware_elastic_search.mapping](#shopware_elastic_search.mapping) | Add an elasticsearch field mapping |
| [shopware_elastic_search.settings](#shopware_elastic_search.settings) | Create elasticsearch index settings |
| [shopware_elastic_search.synchronizer](#shopware_elastic_search.synchronizer) | Create an elasticsearch index synchronizer |
| [shopware_search_es.search_handler](#shopware_search_es.search_handler) | Add an elasticsearch handler for a condition |
| [criteria_request_handler](#criteria_request_handler) | Add a criteria request handler modify the search |
| [facet_handler_dbal](#facet_handler_dbal) | Add handler for a facet |
| [condition_handler_dbal](#condition_handler_dbal) | Add SQL handler for a condition |
| [sorting_handler_dbal](#sorting_handler_dbal) | Add SQL handler for a sorting |
| [console.command](#console.command) | Add a command |
| [shopware.event_subscriber](#shopware.event_subscriber) | To subscribe to a set of different events/hooks in Shopware |
| [shopware.event_listener](#shopware.event_listener) | Listen to different events/hooks in Shopware |

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

foo bar


## shopware_media.optimizer

**Purpose**: Add a media optimizer

foo bar

## shopware_elastic_search.data_indexer

**Purpose**: Add an elasticsearch indexer

foo bar

## shopware_elastic_search.mapping

**Purpose**: Add an elasticsearch field mapping

foo bar


## shopware_elastic_search.settings

**Purpose**: Create elasticsearch index settings

foo bar


## shopware_elastic_search.synchronizer

**Purpose**: Create an elasticsearch index synchronizer

foo bar


## shopware_search_es.search_handler

**Purpose**: Add an elasticsearch handler for a condition

foo bar

## criteria_request_handler

**Purpose**: Add a criteria request handler modify the search

foo bar

## facet_handler_dbal

**Purpose**: Add handler for a facet

foo bar

## condition_handler_dbal

**Purpose**: Add SQL handler for a condition

foo bar

## sorting_handler_dbal

**Purpose**: Add SQL handler for a sorting

foo bar

## console.command

**Purpose**: Add a command

foo bar

## shopware.event_subscriber

**Purpose**: To subscribe to a set of different events/hooks in Shopware

foo bar

## shopware.event_listener

**Purpose**: Listen to different events/hooks in Shopware

foo bar
