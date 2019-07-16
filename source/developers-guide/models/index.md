---
layout: default
title: Shopware models
github_link: developers-guide/models/index.md
tags:
  - models
  - mvc
indexed: true
menu_title: Models
menu_order: 40
group: Developer Guides
subgroup: Developing plugins
---

The [Doctrine framework](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/) has been integrated since Shopware 4 and offers the possibility of centrally defining the database structure in PHP.
In addition, Doctrine offers the ability to centrally define all queries in a single system, called `Repositories`, to be used later at various points in the system.

In order to create models for your plugin, you should create a directory named `Models`.

<div class="toc-list"></div>

## Namespaces

Shopware models make use of the PHP namespaces. This makes it possible to create your own product model in a plugin, without interfering with the default Shopware product model. With classes defined in other namespaces, it is not always necessary to use the full namespace. We can *include* them with an `use` statement:

```php
namespace MyPluginName\Models;

use Symfony\Component\Validator\Constraints as Assert,
    Doctrine\Common\Collections\ArrayCollection,
    Shopware\Components\Model\ModelEntity,
    Doctrine\ORM\Mapping AS ORM;
```

Now the class `Shopware\Components\Model\ModelEntity` is available directly with the name **ModelEntity**. 

The complete namespace **Doctrine\ORM\Mapping** is provided in the line `Doctrine\ORM\Mapping as ORM` with the alias `ORM`. Instead of adding classes to the namespace via `new Doctrine\ORM\Mapping\Entity()`, we can instance them directly via `new ORM\Entity()`.

## Annotations

Doctrine uses annotations to define the data structure. The annotation structure is prefixed in Shopware with `@ORM`. As the models reflect the database structure, each model must also define which database table it reflects. This is done as follows:

```php
/**
 * @ORM\Entity(repositoryClass="Repository")
 * @ORM\Table(name="s_articles")
 */
class Article extends ModelEntity
```

The Shopware models are all derived from the `Shopware\Components\Model\ModelEntity` class, which offers certain standard functions. 

With `@ORM\Entity` we can define that this class is a Doctrine model. With the optional parameter **repositoryClass="Repository"** we can define that we would like to use our own repository class for this model, which should be named `Repository` (feel free to use a different name if you wish) and created in the same namespace as the model class. To read more about the different Doctrine annotations, please refer to the official [Doctrine documentation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/annotations-reference.html).
 
<div class="alert alert-error">
    <strong>Note:</strong> A repository class should only be concerned with one model class. To avoid potential problems, we recommend that each repository class is not shared by multiple models.
</div>

## Associations

With associations, the links between the different models can be defined in Doctrine. You can also define which events are triggered when certain actions are performed on specified models. To read more about associations, please refer to the official [Doctrine documentation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/working-with-associations.html).

<div class="alert alert-info">

<strong>Quick Tip for Associations</strong><br/>
If you have difficulty deciding when to use `@ORM\OneToMany` or when to use `@ORM\ManyToOne`, there is a little trick that can help you. In the `@ORM\OneToMany` and `@ORM\ManyToOne` associations, you can simply enter the model names and replace the word "_To_" with "_Has_".

**Examples**  
* `@ORM\Many` **Article** Has Many **Details**  
* `@ORM\One` **Article** Has One **Supplier**

_The name of the model is always on the left side of the model, with which the association is defined._

</div>

## Model events

In addition to the Shopware events, Doctrine also offers an event system. This event system has been implemented into Shopware to add several new features. Just as like in Shopware, there are the `pre*` and `post*` events. Naturally, the `pre*` events fire before the respective action, and the `post*` events afterwards.

To find more in depth documentation about Doctrine events, please refer to the official [Doctrine documentation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html).

### Insert event

When a model object is first added to the database, the `prePersist` and `postPersist` events on the model are triggered. These are passed on to the Shopware event system so that they can be handled.

```php
<?php

namespace SwagModel\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Article\Article;

class ModelSubscriber implements EventSubscriber
{
    /**
     * Event subscriber
     * 
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
           Events::prePersist,
           Events::postPersist,
        ];
    }
    
    /**
     * Event listeners
     */

    /**
     * @param LifecycleEventArgs $arguments
     */
    public function prePersist(LifecycleEventArgs $arguments)
    {
        /** @var ModelManager $modelManager */
        $modelManager = $arguments->getEntityManager();

        $model = $arguments->getEntity();

        if(!$model instanceof Article) {
            return;
        }

        // modify product data
    }

    /**
     * @param LifecycleEventArgs $arguments
     */
    public function postPersist(LifecycleEventArgs $arguments)
    {
        /** @var ModelManager $modelManager */
        $modelManager = $arguments->getEntityManager();

        $model = $arguments->getEntity();

        // modify models or do some other fancy stuff
    }
}
```

### Update event
Once a model has been added to the database, the `preUpdate` and `postUpdate` events are triggered. These are passed on to the Shopware event system so that they can be handled.

**Event subscriber**
```php
public function getSubscribedEvents()
{
    return [
       Events::preUpdate,
       Events::postUpdate,
    ];
}
```
**Event listener**
```php
/**
 * @param LifecycleEventArgs $arguments
 */
public function preUpdate(LifecycleEventArgs $arguments)
{
    /** @var ModelManager $modelManager */
    $modelManager = $arguments->getEntityManager();

    $model = $arguments->getEntity();

    if(!$model instanceof Article) {
        return;
    }

    // modify product data
}

/**
 * @param LifecycleEventArgs $arguments
 */
public function postUpdate(LifecycleEventArgs $arguments)
{
    /** @var ModelManager $modelManager */
    $modelManager = $arguments->getEntityManager();

    $model = $arguments->getEntity();

    // modify models or do some other fancy stuff
}
```

### Remove event
Once a model is removed from the database, the `preRemove` and `postRemove` events are triggered on the model. These are passed on to the Shopware event system, so that they can be handled as follows:

**Event subscriber**
```php
 /**
 * {@inheritdoc}
 */
public function getSubscribedEvents()
{
    return [
       Events::preRemove,
       Events::postRemove,
    ];
}

```

**Event listener**
```php
    /**
     * @param LifecycleEventArgs $arguments
     */
    public function preRemove(LifecycleEventArgs $arguments)
    {
        /** @var ModelManager $modelManager */
        $modelManager = $arguments->getEntityManager();

        $model = $arguments->getEntity();

        if(!$model instanceof Article) {
            return;
        }

        // modify product data
    }

    /**
     * @param LifecycleEventArgs $arguments
     */
    public function postRemove(LifecycleEventArgs $arguments)
    {
        /** @var ModelManager $modelManager */
        $modelManager = $arguments->getEntityManager();

        $model = $arguments->getEntity();

        // modify models or do some other fancy stuff
    }
```
 
Each event subscriber class is registered in the `services.xml` with the `doctrine.event_subscriber` tag.

```xml
<service id="swag_model.subscriber.models_subscriber" class="SwagModel\Subscriber\ModelSubscriber">
    <tag name="doctrine.event_subscriber"/>
</service>
```

## Example Plugin

You can download an example plugin here, which shows you the basic structure and registration of your own models in your plugin.

[Download SwagModelPlugin.zip](/exampleplugins/SwagModel.zip)
