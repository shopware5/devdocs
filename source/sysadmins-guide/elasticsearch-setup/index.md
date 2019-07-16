---
layout: default
title: Elasticsearch setup
github_link: sysadmins-guide/elasticsearch-setup/index.md
shopware_version: 5.1.0
tags:
  - performance
  - elasticsearch
redirect:
  - sysadmins-guide/elastic-search-setup/
indexed: true
group: System Guides
menu_title: Elasticsearch setup
menu_order: 60
---

<div class="toc-list"></div>

## Introduction

For shops that contain millions of different products, Shopware 5 requires an alternative to MySQL to be able to provide an optimal user experience in terms of both functionality and speed.

[Elasticsearch](https://www.elastic.co/products/elasticsearch) is an open source search engine, built to handle such scenarios, where a set of millions of entries needs to be queried in just a few milliseconds.

Shopware 5.0 is able to provide a seamless Elasticsearch integration that will greatly benefit those shops.

<div class="alert alert-info" role="alert">
    <strong>Note:</strong> Elasticsearch integration should be considered an advanced Shopware feature. It requires the installation and configuration of Elasticsearch itself as well as technical personal to monitor and maintain the synchronization continuously. This might not be possible on all hosting plans or providers.</br>
    Additionally, it will mostly benefit shops containing hundreds of thousands or millions of items. On smaller shops, its usage is not recommended, as you might not experience any visible benefits from it.
</div>

## Installation and configuration

To enable Elasticsearch integration, you must configure both your server and your Shopware 5 installation. 
Elasticsearch 2.0 or newer is required. For Elasticsearch 6.0 Shopware 5.5 is required


### Elasticsearch installation and configuration

Elasticsearch installation and configuration greatly depends on your operating system and hosting provider. You will find extensive documentation online regarding the installation and configuration of Elasticsearch on most common Linux distributions. Some hosting providers might also provide specific documentation regarding this subject. Installation on Mac OSX or Windows is also possible, but not officially supported.

The current Shopware 5 integration is designed to work with the out-of-the-box configuration of Elasticsearch. This does not mean, of course, that these are the best settings for a production environment. Although they will affect performance and security, the settings you choose to use on your Elasticsearch setup will be mostly transparent to your Shopware installation. The best setting constellation for your shop will greatly depend on your server setup, number and structure of products, replication requirements , to name a few. These settings fall out of the scope of this document, but you can refer to the official [Elasticsearch documentation page](https://www.elastic.co/guide/index.html) for more info.

### Shopware configuration

By default, Elasticsearch integration is disabled in Shopware, as most shops won't benefit from it. Like mentioned before, Elasticsearch should only be used in shops containing a large set of items.

To enable Elasticsearch (provided it's already installed, configured and running), edit your `config.php` file, adding the following array:
```
...
    'es' => [
        'enabled' => true,
        'number_of_replicas' => null,
        'number_of_shards' => null,
        'version' => '5.6.5',
        'dynamic_mapping_enabled' => true,
        'client' => [
            'hosts' => [
                'localhost:9200'
            ]
        ]
    ],
    // Other configuration settings...
...
```

Your config.php should look like this now:
```
<?php
return [
    'db' => [
        'username' => 'dbuser',
        'password' => 'dbpw',
        'dbname' => 'dbname',
        'host' => 'localhost',
        'port' => '3306',  
    ],
    'es' => [
        'enabled' => true,
        'number_of_replicas' => null,
        'number_of_shards' => null,
        'version' => '5.6.5',
        'dynamic_mapping_enabled' => true,
        'client' => [
            'hosts' => [
                'localhost:9200'
            ]
        ]
    ],
    // Other configuration settings...
];
```

Shopware 5 communicates with Elasticsearch using the latter's REST API. The `hosts` array accepts multiple address syntaxes, about which you can read more [here](https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_configuration.html#_host_configuration). The version defines the used Elasticsearch Version. If not set in the config, Shopware will detect it automatically. The `number_of_shards` and `number_of_replicas` parameter provided to the generated index. A `null` configuration allows to use the elastic search server configuration of this parameters.

<div class="alert alert-info" role="alert">
    <strong>Note:</strong> For a single node configuration, which is sufficient for a development environment, it is necessary to configure a `number_of_replicas` of `0`, otherwise the indexing process would wait for cluster health `green`, which can't be reached if no replicas can be applied.
</div>

### Defining Elasticsearch version to reduce calls

By default, Shopware makes an `info` request to the Elasticsearch backend to be able to determine the version of Elasticsearch that is being used. In high load environments, this can create unnecessary additional load on all services due to the slight overhead these requests create.

Starting with Shopware 5.5.5, it is possible to define the version of Elasticsearch being used in the `config.php` like described below. Doing so will keep Shopware from making these `info` requests.

```php
<?php
return [
    ...
    'es' => [
        ...
        'version' => '5.6.5',
    ]
];
```


### Define Elasticsaerch Dynamic Mapping

By default, Shopware enables the dynamic mapping option.
By this, fields can be added dynamically to a document or to inner objects within a document, just by indexing a document containing the new field.

The problem with this option is, that Shopware provides floats and integers to Elasticsearch.
So Elasticsearch uses the first detect field mapping for dynamic fields and can't change it then.
This is the reason why sometimes products are indexed and sometimes not.

With disabling dynamicMapping, you have to provide a mapping for every Field which is used in Elasticsearch. Only then the field will be indexed.
This will fix the type issues and product will get indexed.

Take a look [here](https://www.elastic.co/guide/en/elasticsearch/reference/current/dynamic.html) for more information.

```php
<?php
return [
    ...
    'es' => [
        ...
        'dynamic_mapping_enabled' => true,
    ]
];
```

### Initial data import

<div class="alert alert-info" role="alert">
    <strong>Note:</strong> If your shop has no articles, you can skip this step.
</div>

Once both Elasticsearch and Shopware 5 are configured and running, you should execute the following Shopware CLI command:

```
php bin/console sw:es:index:populate
```

This command will reindex your shops data in Elasticsearch. Keep in mind that, should your shops already have a large amount of article data, this process can take a considerable amount of time. If you wish, you can limit this process per shop. Refer to the command's documentation for more info.

### Maintaining data consistency

In order to ensure data consistency between your MySQL database and your Elasticsearch indexes, there are two CLI commands you need to periodically execute:


#### Live synchronization

The `sw:es:backlog:sync` command ensures your latest changes are propagated into Elasticsearch. It uses a queueing system, and it's execution time may greatly vary, depending on the pending operation list content. This command should be executed periodically to ensure data consistency.

```
php bin/console sw:es:backlog:sync
```

#### Data reindexing

To ensure your MySQL database and your Elasticsearch instance are synchronized, we recommend running a complete reindexing of your articles daily. This can be done by setting up a cron job that runs the following command:

```
php bin/console sw:es:index:populate
```

We recommend running this command every 24 hours, at a time when you server expects less traffic (typically during the night). There will be no downtime for both Shopware and Elasticsearch during its execution.

## Elasticsearch integration details

While this guide is not meant to cover the technical details of the integration implementation in depth, there are some concepts that you need to keep in mind when configuring the integration between Shopware and Elasticsearch. The first, and probably most important, is that Elasticsearch is NOT a MySQL replacement. Although they provide, to some extent, similar features, Elasticsearch is and should be seen as a complement to a DBMS, not as a replacement. As such, you will still require a running MySQL instance, and its configuration will still greatly affect Shopware's performance in most actions.

Another vital detail you should keep in mind when using Elasticsearch with Shopware is that the data stored in Elasticsearch is a duplicate of the data already present in your Shopware 5 database. Whenever changes are made to your data (for example, you edit an article description), that information is saved to MySQL and, only later, to Elasticsearch.

### Using and understanding the data synchronization mechanism

Some changes shop owners perform in the backend may affect a great number of entries on your database. For example, a change in your default tax rate could theoretically affect all your products. MySQL data is not greatly affected by this, as the gross value calculation is done when the article is loaded from the database. However, Elasticsearch may store gross values, to speed up performance. This has the obvious downside that, if your tax rate changes, you need to update all your products prices. If your shop has hundreds of thousands or millions of products, this operation can take a significant amount of time, even on a high performance application like Elasticsearch. To handle these scenarios, the Elasticsearch integration in Shopware 5 includes support for asynchronous data propagation to Elasticsearch.

#### The queueing system

The asynchronous propagation of change to Elasticsearch is implemented using an operation queuing system. Operations are stored in the `s_es_backlog` by hooking into Doctrine's `postPersist`, `postUpdate` and `postRemove` events for selected entities. The events store the entity type, its ID, the operation to be executed.

#### The synchronization process

These events are handled by a CLI command included in Shopware, which you can execute using the following line:

```
php bin/console sw:es:backlog:sync
```

When executed, this command loads all operations from the queue which have not yet been executed. It then executes them, in the order in which they were queued.

It's highly recommended that you set up a cron job in your system to periodically execute this command, in order to ensure your MySQL database and Elasticsearch have consistent data. The configuration of this cron task will depend on your system's operating system, and is not part of the scope of this guide.

#### Event handling workflow

Shopware detects data changes over the doctrine ORM event system. This events triggered if doctrine persist changes into the database.

Traced models:
- `Shopware\Models\Article\Supplier`
- `Shopware\Models\Tax\Tax`
- `Shopware\Models\Property\Option`
- `Shopware\Models\Property\Value`
- `Shopware\Models\Article\Article`
- `Shopware\Models\Article\Vote`
- `Shopware\Models\Article\Detail`
- `Shopware\Models\Article\Price`

When changes to entities belonging to these models are done, they are immediately saved in the MySQL database, but propagation to Elasticsearch is delayed until the next execution of the `sw:es:backlog:sync` command.

### Rebuilding the search index

As discussed before, the synchronization process between Shopware/MySQL and Elasticsearch traces only changes of defined entities. Global system changes like `created a new shop`, `created new customer groups` are not detected by the synchronisation and only be considered at indexing time. The `sw:es:index:populate` command does just that.

Internally, Elasticsearch uses [indexes](https://www.elastic.co/guide/en/elasticsearch/reference/current/glossary.html#glossary-index) to store your data. If you are new to Elasticsearch, think of indexes as MySQL databases: they contain your data, and you can read from and write to them. Fully regenerating an index can take a significant amount of time, just as loading a database from an `.sql` file can, during which your data is unavailable.

#### Cycling indexes

To avoid downtime, even when you are reindexing your shop's data, Shopware uses multiple indexes instead of just one. When you trigger the reindexing process, Shopware will index your data into a completely new index. While your shop is being indexed, if a customer queries your shop, the old index will be used to provide the results. As such, there's no downtime. Once your indexing process is finished, Shopware will automatically start using the new one. The old index is not deleted. Should you detect a problem with the new index, you can revert to the old one, instead of having to wait for a full reindexing of your shop. Shopware provides a tool to switch back to this old indices:
```bash
php bin/console sw:es:switch:alias --shopId=1 --index=sw_shop1_TIMESTAMP
```
Again, this is done so that, no matter what happens, there's no downtime for your customers.

#### Concurrent usage

While your data is being indexed, the shop owner might make some changes to its products. This could cause your new index to be inconsistent, but Shopware handles this too, ensuring your newly created index includes even the changes made while it was being created.

As mentioned before, Shopware uses a queuing system to asynchronously handle changes to your data. But this queue is not used only for this. When the indexing process starts, Shopware records the current position of the queue end. As the indexing takes place, the shop owner may manipulate data, resulting in operations being added to the queue. When the indexing is finished, Shopware is able to determine if new operations were carried out while the data was being indexed. Should that be the case, the new operations are re-executed on the new index, ensuring consistency.

#### Index cleanup

After a new index is created, the old one is no longer used, but is not deleted. Should your new index be corrupted, you can just replace it with the old one, and have your shop running again without downtime. However, as new indexes should be created often (recommended every 24 hours), old indexes can accumulate and start taking up a significant amount of resources. Shopware provides a tool to cleanup those old indexes:

```bash
php bin/console sw:es:index:cleanup
```

This command will delete every old version of an index, but keep the latest. As such, it's safe to run this command even on production environments, provided you have ensured that your current index is working as intended.

#### Backlog cleanup

Like the old indices, the processed backlog queue is never deleted which allows to reproduce data changes of the system in case of a index rollback. Shopware provides a tool to cleanup those processed backlog queue:

```bash
php bin/console sw:es:backlog:clear
```

As such, it's safe to run this command even on production environments, provided you have ensured that your current index is working as intended.

## Elasticsearch in Backend

Since Shopware 5.5 it is also possible to use Elasticsearch in the backend for listing and search operations for products, orders and customers. To use this you need to adjust your `config.php` to

```php
return [
    'db' => [
        'username' => 'dbuser',
        'password' => 'dbpw',
        'dbname' => 'dbname',
        'host' => 'localhost',
        'port' => '3306',  
    ],
    'es' => [
        'enabled' => true,
        'number_of_replicas' => null,
        'number_of_shards' => null,
        'client' => [
            'hosts' => [
                'localhost:9200'
            ]
        ],
        'backend' => [
            'write_backlog' => true,
            'enabled' => true,
        ],
    ],
];
```

### Initial data import

Once Shopware is configured for using Elasticsearch in the backend, you should execute the following Shopware CLI command:

```
php bin/console sw:es:backend:index:populate
```

#### Live synchronization

The `sw:es:backend:sync` command ensures your latest changes are propagated into Elasticsearch. It uses a queueing system, and it's execution time may greatly vary, depending on the pending operation list content. This command should be executed periodically to ensure data consistency.

```
php bin/console sw:es:backend:sync
```

#### Index cleanup

After a new index is created, the old one is no longer used, but is not deleted. Should your new index be corrupted, you can just replace it with the old one, and have your shop running again without downtime. However, as new indexes should be created often (recommended every 24 hours), old indexes can accumulate and start taking up a significant amount of resources. Shopware provides a tool to cleanup those old indexes:

```bash
php sw:es:backend:index:cleanup
```
