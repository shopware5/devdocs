---
layout: default
title: Cheat-Sheet for developers
github_link: developers-guide/cheat-sheet/index.md
shopware_version: 5.3.0
tags:
  - cheat-sheet
  - developers
  - beginner
indexed: true
group: Developer Guides
subgroup: General Resources
menu_title: Cheat-Sheet
menu_order: 1
---

<div class="toc-list"></div>

## Introduction

This article will give you brief information about often used functionalities, methods and good practises for developers, who are working on shopware.
<div class="alert alert-warning">
This list is not complete. If you miss something important, feel free to open a pull request with the GitHub link in the top.
</div>

## Templating

Have a look here for a <a href="http://community.shopware.com/files/downloads/templatecheatsheeten-12249471.pdf" target="_blank">Smarty Cheat-Sheet</a>

### Disable Smarty Rendering

```php
$this->Front()->Plugins()->ViewRenderer()->setNoRender();
```

### Add json Rendering

Useful for ajax calls
```php
$this->Front()->Plugins()->Json()->setRenderer();
```

### Dynamic snippet names / namespaces
```smarty
{"Snippet Content"|snippet:$dynamicName:$dynamicNamespace}
```

## Events and hooks

Use your main plugin class or a subscriber class which implements the `\Enlight\Event\SubscriberInterface` to register new events or hooks

### Events

```php
public static function getSubscribedEvents()
{
    return [
        'EVENT_NAME1' => 'EVENT_LISTENER1',
        'EVENT_NAME2' => ['EVENT_LISTENER2', POSITION],
        'EVENT_NAME3' => [
            ['EVENT_LISTENER3_0'],
            ['EVENT_LISTENER3_1', POSITION],
        ],
    ];
}
```
The `POSITION` defines the execution order of the event listener methods. The default value is `0`. The higher the number, the later the event listener method is executed. For example a position like `-10` will be executed quite early. On the contrary a position like `10` quite late.

### Hooks

types: before / after / replace
```php
public static function getSubscribedEvents()
{
    return [
        'CLASS::FUNCTION::TYPE' => 'LISTENER',
    ];
}
```

## Attributes

### Creating a new attribute / update an existing attribute

```php
$attributeCrudService = $this->container->get('shopware_attribute.crud_service');
$attributeCrudService->update(
    's_articles_attributes',
    'swag_test_attribute',
    \Shopware\Bundle\AttributeBundle\Service\TypeMapping::TYPE_STRING,
    [
        'displayInBackend' => true,
        'position' => 10,
        'custom' => true,
        'defaultValue' => 'test'
    ]
    
);
```

### Naming - Attribute generation

If you get the `Error: Unrecognized field: my_field` when querying for your attribute it could be that you forgot to generate the attribute models:
```php
$this->container()->get('models')->generateAttributeModels();
```
or ran into naming issues:
* the field name gets __lower cased__ before added to the database
* when using underscores(`_`) in field names they must be queried in __camel case__

### Delete existing attribute

```php
$attributeCrudService = $this->container->get('shopware_attribute.crud_service');
$attributeCrudService->delete('s_articles_attributes', 'swag_test_attribute');
```

### Translate label, help text, support text

create `SwagTest\Resources\snippets\backend\attribute_columns.ini`
```
[en_GB]
s_articles_attributes_swag_test_attribute_label = "English label"
s_articles_attributes_swag_test_attribute_supportText = "English support text"
s_articles_attributes_swag_test_attribute_helpText = "English help text"

[de_DE]
s_articles_attributes_swag_test_attribute_label = "Deutsches Label"
s_articles_attributes_swag_test_attribute_supportText = "Deutscher Supporttext"
s_articles_attributes_swag_test_attribute_helpText = "Deutscher Hilfetext"
```

### Load attributes when they are missing in the template

Sometimes the attributes from an Entity are missing in the template on certain actions. To load them, just register a `PostDispatch` event on the controller or module in question to add your custom logic. You can then extract the id of the entity you are interested in, load it's attributes and assign them to the view.

Say you want to load the attributes of an order on the account's order page:

```php
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Account' => 'onFrontendPostDispatchAccount'
        ];
    }

    public function onFrontendPostDispatchAccount(\Enlight_Controller_ActionEventArgs $args)
    {
        // We only need to load the attributes if we're on the 'order' page
        if ($args->getRequest()->getActionName() !== 'orders') {
            return;
        }
        
        // Retrieve the controller-object from the event arguments to access the view parameters
        $controller = $args->get('subject');
        $view = $controller->View();

        // Fetch the order information from the template
        $orders = $view->getAssign('sOpenOrders')

        // This service allows easy loading of attributes
        $service = $this->container->get('shopware_attribute.data_loader');

        $attributes = [];
        foreach ($orders as $order) {
            // We use the service to load the attributes of each order by the order's id from the table 's_order_attributes' and store it in an array
            $attributes[$order['id']] = $service->load('s_order_attributes', $order['id']);
        }

        $view->assign('order_attributes', $attributes);
    }
}
```

## Plugin configuration

see [this article](/developers-guide/plugin-system/#plugin-configuration-/-forms)

## DI container configuration

see [this article](/developers-guide/plugin-system/#container-configuration)

## Create menu item

see [this article](/developers-guide/plugin-system/#backend-menu-items)

## Database queries

### Select with queryBuilder

```php
$queryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
$queryBuilder->select('*')
    ->from('s_articles')
    ->where('active = :active')
    ->setParameter('active', true);
    
$data = $queryBuilder->execute()->fetchAll();
```

with fetch mode
```php
$queryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
$queryBuilder->select('variants.ordernumber')
    ->from('s_articles_details', 'variants')
    ->where('variants.kind = :kind')
    ->setParameter('kind', 1);

$data = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);
```

### Select with plain SQL

```php
$connection = $this->container->get('dbal_connection');
$sql = 'SELECT * FROM s_articles WHERE active = :active';
$data = $connection->fetchAll($sql, [':active' => true]);
```

## Doctrine

### Custom model

create `SwagTest\Models\TestCustomModel`
```php
<?php
namespace SwagTest\Models;

use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="swag_test_table", options={"collate"="utf8_unicode_ci"})
 */
class TestCustomModel extends ModelEntity
{
    /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer", nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $id;
    
    /**
    * @var string
    * @ORM\Column(name="test_name", type="string")
    */
    private $testName;
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $testName
     */
    public function setName($testName)
    {
        $this->testName = $testName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->testName;
    }
}
```

### Create database table from model

Make sure that you use the same database collation as Shopware in your custom model, if you have relations to Shopware default tables
```php
$em = $this->container->get('models');
$tool = new \Doctrine\ORM\Tools\SchemaTool($em);
$classes = [$em->getClassMetadata(\SwagTest\Models\TestCustomModel::class)];
$tool->createSchema($classes);
```

### Delete database table

```php
$em = $this->container->get('models');
$tool = new \Doctrine\ORM\Tools\SchemaTool($em);
$classes = [$em->getClassMetadata(\SwagTest\Models\TestCustomModel::class)];
$tool->dropSchema($classes);
```

### QueryBuilder

Select some data
```php
$builder = $this->container->get('models')->createQueryBuilder();
$builder->select(['product', 'mainVariant'])
    ->from(\Shopware\Models\Article\Article::class, 'product')
    ->innerJoin('product.mainDetail', 'mainVariant')
    ->where('product.id = :productId')
    ->setParameter('productId', 2);
    
// Array with \Shopware\Models\Article\Article objects
$objectData = $builder->getQuery()->getResult();

// Array with arrays
$arrayData = $builder->getQuery()->getArrayResult();
```
