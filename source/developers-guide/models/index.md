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

The [Doctrine framework](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/) has been integrated since Shopware 4 and offers the possibility of centrally defining the database structure in PHP. In addition, Doctrine offers the ability to centrally define all queries in a single system, called `Repositories`, to be used later at various points in the system.

In order to create models for your plugin, you should create a directory named `Models` and call `$this->registerCustomModels()` in your bootstrap's `install()` method to automatically register them.

<div class="toc-list"></div>

## Namespaces

Shopware models make use of the PHP namespaces. This makes it possible to create your own article model in a plugin, without interfering with the default Shopware article model. With classes defined in other namespaces, it is not always necessary to use the full namespace. We can *include* them with an `use` statement:

```php
namespace Shopware\CustomModels\MyPluginName;

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
* `@ORM\One` **Article** Has Many **Details**  
* `@ORM\Many` **Article** Has One **Supplier**

_The name of the model is always on the left side of the model, with which the association is defined._

</div>

## Model events

In addition to the Shopware events, Doctrine also offers an event system. This event system has been implemented into Shopware to add several new features. Just as like in Shopware, there are the `pre*` and `post*` events. Naturally, the `pre*` events fire before the respective action, and the `post*` events afterwards.

To find more in depth documentation about Doctrine events, please refer to the official [Doctrine documentation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html).

### Insert event

When a model object is first added to the database, the `prePersist` and `postPersist` events on the model are triggered. These are passed on to the Shopware event system so that they can be handled.

**Event subscriber**
```php
$this->subscribeEvent(
    'Shopware\Models\Article\Article::prePersist',
    'prePersistArticle'
);

$this->subscribeEvent(
    'Shopware\Models\Article\Article::postPersist',
    'postPersistArticle'
);
```

**Event listener**
```php
public function prePersistArticle(Enlight_Event_EventArgs $arguments) {
    $modelManager = $arguments->get('entityManager');
    $model = $arguments->get('entity');
}

public function postPersistArticle(Enlight_Event_EventArgs $arguments) {
    $modelManager = $arguments->get('entityManager');
    $model = $arguments->get('entity');
}
```

### Update event
Once a model has been added to the database, the `preUpdate` and `postUpdate` events are triggered. These are passed on to the Shopware event system so that they can be handled.

**Event subscriber**
```php
$this->subscribeEvent(
    'Shopware\Models\Article\Article::preUpdate',
    'preUpdateArticle'
);

$this->subscribeEvent(
    'Shopware\Models\Article\Article::postUpdate',
    'postUpdateArticle'
);
```
**Event listener**
```php
public function preUpdateArticle(Enlight_Event_EventArgs $arguments) {
    $modelManager = $arguments->get('entityManager');
    $model = $arguments->get('entity');
}

public function postUpdateArticle(Enlight_Event_EventArgs $arguments) {
    $modelManager = $arguments->get('entityManager');
    $model = $arguments->get('entity');
}
```

### Remove event
Once a model is removed from the database, the `preRemove` and `postRemove` events are triggered on the model. These are passed on to the Shopware event system, so that they can be handled as follows:

**Event subscriber**
```php
$this->subscribeEvent(
    'Shopware\Models\Article\Article::preRemove',
    'preRemoveArticle'
);

$this->subscribeEvent(
    'Shopware\Models\Article\Article::postRemove',
    'postRemoveArticle'
);
```

**Event listener**
```php
public function preRemoveArticle(Enlight_Event_EventArgs $arguments) {
    $modelManager = $arguments->get('entityManager');
    $model = $arguments->get('entity');
}

public function postRemoveArticle(Enlight_Event_EventArgs $arguments) {
    $modelManager = $arguments->get('entityManager');
    $model = $arguments->get('entity');
}
```

## Example Plugin

You can download an example plugin here, which shows you the basic structure and registration of your own models in your plugin.

[Download SwagModelPlugin.zip](/exampleplugins/SwagModelPlugin.zip)
