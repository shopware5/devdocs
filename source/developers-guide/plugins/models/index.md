---
layout: default
title: Shopware models
github_link: developers-guide/plugins/models/index.md
tags:
  - models
  - mvc
indexed: true
---

Now that you’ve already had a first look at the Shopware event and hooks system, we can continue with Shopware 4 models, the last part of the academic section of this tutorial. The Doctrine framework has been integrated into Shopware 4 and offers the possibility of centrally defining the database structure in PHP. In addition, Doctrine offers the ability to centrally define all queries in a single system, called Repositories, to be used later at various points in the system.

<div class="toc-list"></div>

# Structure
First off, we’re going to have a look at the Shopware models, which are all stored under <b>engine/Shopware/Models/</b>. The following example models have been compressed in order to clearly display them (this means that the comments, properties, associations and line breaks have been removed).
A simple model could appear as follows:
```php
<?php

namespace Shopware\Models\Article;

use Symfony\Component\Validator\Constraints as Assert,
        Doctrine\Common\Collections\ArrayCollection,
        Shopware\Components\Model\ModelEntity,
        Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="Repository")
 * @ORM\Table(name="s_articles")
 */
class Article extends ModelEntity
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * INVERSE SIDE
     *
     * @var \Shopware\Models\Attribute\Article
     *
     * @ORM\OneToOne(
     *      targetEntity="Shopware\Models\Attribute\Article", 
     *      mappedBy="article"
     * )
     */
    protected $attribute;
    
    /**
     * INVERSE SIDE
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Shopware\Models\Article\Detail",
     *      mappedBy="article",
     *      orphanRemoval=true
     * )
     */
    protected $details;

    /**
     * OWNING SIDE
     *
     * @var \Shopware\Models\Article\Supplier $supplier
     *
     * @ORM\ManyToOne(
     *      targetEntity="Shopware\Models\Article\Supplier", 
     *      inversedBy="articles", 
     * )
     * @ORM\JoinColumn(
     *      name="supplierID", 
     *      referencedColumnName="id"
     * )
     */
    protected $supplier;
    
    //class constructor, used to initial the internal collections
    public function __construct() {
        $this->details = new ArrayCollection();
        return $this;
    }

    //id getter and setter
    public function getId() {
        return $this->id;
    }

    //name getter and setter
    public function setName($name) {
        $this->name = trim($name);
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    //attribute getter and setter
    public function getAttribute() {
        return $this->attribute;
    }

    public function setAttribute($attribute) {
        return $this->setOneToOne(
            $attribute,
            '\Shopware\Models\Attribute\Article',
            'attribute',
            'article'
        );
    }

    //details getter and setter
    public function getDetails() {
        return $this->details;
    }

    public function setDetails($details) {
        return $this->setOneToMany(
            $details,
            '\Shopware\Models\Article\Detail',
            'details',
            'article'
        );
    }

    //supplier getter and setter
    public function getSupplier() {
        return $this->supplier;
    }

    public function setSupplier($supplier) {
        return $this->setManyToOne(
            $supplier, 
            '\Shopware\Models\Article\Supplier', 
            'supplier'
        );
    }
}
```

## Namespaces

In this model, you can find the basic components of a model.
Now let's have a look at the various source lines and see what they have been defined for.
The new Shopware models make use of the PHP namespaces, so a definition can be found in every model that defines in which namespace the model is located.

```php
namespace Shopware\Models\Article;
```

This makes it possible to create your own products model in a plugin, without interfering with the standard Shopware item models. With classes defined in other namespaces, it is not always necessary to use the full namespace. We can "include" them with **PHP USE**:

```php
use Symfony\Component\Validator\Constraints as Assert,
    Doctrine\Common\Collections\ArrayCollection,
    Shopware\Components\Model\ModelEntity,
    Doctrine\ORM\Mapping AS ORM;
```

Now the class [Shopware\Components\Model\ModelEntity](http://community.shopware.com/_doc-sw4/classes/Shopware.Components.Model.ModelEntity.html) is available directly under the name **ModelEntity**. 

The complete namespace **Doctrine\ORM\Mapping** is provided in the line `Doctrine\ORM\Mapping AS ORM` with the alias `ORM`. Instead of adding classes to the namespace via `new Doctrine\ORM\Mapping\Entity()`, we can instance them directly via `new ORM\Entity()`.

## Annotations

Doctrine uses annotations to define the data structure. The annotation structure is prefixed in Shopware with `@ORM`. As the models reflect the database structure, each model must also define which database table it reflects. This is done as follows:

```php
/**
 * @ORM\Entity(repositoryClass="Repository")
 * @ORM\Table(name="s_articles")
 */
class Article extends ModelEntity
```

### Entity and Table

With `@ORM\Entity` we can define that there is a model for this class. With the parameter **repositoryClass="Repository"** we can define that we would like to use our own repository class for this model, which is created in the same namespace with the name **Repository**.
 
By defining `@ORM\Table`, we can decide which database table the model has to obtain its data. In this case `s_articles`.

The last line defines the class. The Shopware models are all derived from the `Shopware\Components\Model\ModelEntity` class, which offers certain standard functions.

<div class="alert alert-error">
	<strong>Note:</strong> A repository class must only be concerned with one model class. To avoid problems, make sure that a repository class is not shared by multiple models.
</div>

### Columns

Next, the table columns are going to be mapped. The annotation tag <b>@ORM\Column</b> is used for this. 

```php
/**
 * @var integer $id
 *
 * @ORM\Column(name="id", type="integer", nullable=false)
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="IDENTITY")
 */
private $id;

/**
 * @var string $name
 *
 * @ORM\Column(name="name", type="string", length=100, nullable=false)
 */
private $name;
```

Here we can see two mapped columns. At first,  `id` - this is used as a unique identifier. This is why this class property, in addition to `@ORM\Column`, has two additional annotations. The `@ORM\Id` annotation tells Doctrine that this column is used for the unique identification of the model.

It is also possible to tell Doctrine the generation types of unique IDs with the annotation `@ORM\GeneratedValue(strategy="...")`. The generation type "IDENTITY" corresponds to the **AUTO_INCREMENT** type of MySQL.

The additional possibilities offered by `@ORM\Column` will be explained later in [Column annotations](#column-annotation).

### Relations

After mapping the columns, the links are defined under the models. Three annotations are used for this:

* `@ORM\OneToOne`
* `@ORM\OneToMany`
* `@ORM\ManyToMany`

These annotations define e.g. the relationship between an item model and its manufacturer model or its variants. You can also define whether executed functions should be passed to the associated models on the item model or not.

```php
/**
 * INVERSE SIDE
 *
 * @var \Shopware\Models\Attribute\Article
 *
 * @ORM\OneToOne(
 *      targetEntity="Shopware\Models\Attribute\Article", 
 *      mappedBy="article", 
 * )
 */
protected $attribute;

/**
 * INVERSE SIDE
 *
 * @var \Doctrine\Common\Collections\ArrayCollection
 *
 * @ORM\OneToMany(
 *      targetEntity="Shopware\Models\Article\Detail",
 *      mappedBy="article",
 *      orphanRemoval=true
 * )
 */
protected $details;

/**
 * OWNING SIDE
 *
 * @var \Shopware\Models\Article\Supplier $supplier
 *
 * @ORM\ManyToOne(
 *      targetEntity="Shopware\Models\Article\Supplier", 
 *      inversedBy="articles", 
 * )
 * @ORM\JoinColumn(
 *      name="supplierID", 
 *      referencedColumnName="id"
 * )
 */
protected $supplier;
```

In this example, we're going to look at three defined associations. The **first** association defines the link between the item model ([`Shopware\Models\Article\Article`](http://community.shopware.com/_doc-sw4/classes/Shopware.Models.Article.Article.html)) and its attributes (Shopware\Models\Attribute\Article). When an item only has one attribute, a `@ORM\OneToOne` association is defined. 

The **second** association defines the link between the item and its variants ([`Shopware\Models\Article\Detail`](http://community.shopware.com/_doc-sw4/classes/Shopware.Models.Article.Detail.html)). Since an item can have multiple variants, a `@ORM\OneToMany` association is defined.

The **third** association defines the relationship between the manufacturer ([`Shopware\Models\Article\Article`](http://community.shopware.com/_doc-sw4/classes/Shopware.Models.Article.Supplier.html)) and its items. A manufacturer can have multiple items, so again we'll use the `@ORM\OneToMany` association. However, because we are in the item model and not the manufacturer model, we don't use the `@ORM\OneToMany` association, but its counterpart, the `@ORM\ManyToOne` association.

#### Quick-Tip
If you have difficulty deciding when to use `@ORM\OneToMany` or when to use `@ORM\ManyToOne`, there is a little trick that can help you. In the `@ORM\OneToMany` and `@ORM\ManyToOne` associations, you can simply enter the model names and replace the word "_To_" with "_Has_".

**Examples**  
* `@ORM\One` **Article** Has Many **Details**  
* `@ORM\Many` **Article** Has One **Supplier**

_The name of the model is always on the left side of the model, with which the association is defined._

### Column types

The getter and setter of the various properties follow according to the definition of the model associations. The property `$supplierId` has no getter and setter, which would otherwise cause problems with the associations. These properties are only defined in the doctrine queries so that the entire link does not need to be joined in order to be able to restrict to a foreign key.

The `@ORM\Column` annotation makes it possible to define a class property as the column. As a result, this value is written to the database when the model persists. For the definition of a column, the following parameters are available:

* Name - The <b>Name</b> parameter contains the names of the columns, as they are defined in the database.
* Type - The <b>Type</b> parameter defines which data type is defined in the database for this column. The following types can be used:

| Type | Description |
|----------|----------|
|  String  |  Can be used for the mapping of SQL `VARCHAR`.  |
|  Integer  |  Can be used for the mapping of SQL `INT`.  |
|  Smallint  |  Can be used for the mapping of SQL `SMALLINT`.  |
|  Bigint  |  Can be used for the mapping of SQL `BIGINT`. Type that maps a database to a PHP string.  |
|  Boolean  |  An SQL `BOOLEAN` can be used for mapping. Type that maps an SQL to a PHP boolean.  |
|  Decimal  |  An SQL `DECIMAL` can be used for mapping. Type that maps an SQL to a PHP string.  |
|  Date  |  An SQL `DATETIME` can be used for mapping. Type that maps an SQL  to a PHP DateTime object.  |
|  Time  |  An SQL `TIME` can be used for mapping. Type that maps an SQL to a PHP DateTime object.  |
|  Datetime  |  An SQL `DATETIME`/`TIMESTAMP` can be used for mapping. Type that maps an SQL  to a PHP DateTime object.  |
|  Text  |  An SQL `CLOB` can be used for mapping. The value is converted into a PHP string. Type that maps an SQL to a PHP string.  |
|  Object  |  An SQL `CLOB` can be used for mapping. The value is saved and read out using serialize() und unserialize().  |
|  Array  |  An SQL `CLOB` can be used for mapping. The value is saved and read out using serialize() und unserialize().  |
|  Float  |  An SQL `FLOAT` can be used for mapping.  |

**Additional Parameters**
* Length - The <b>Length</b> parameter defines how long a text field should be.
* Unique - The <b>Unique</b> parameter defines whether the column is unique.
* Nullable - The <b>Nullable</b> parameter defines whether the column can have a value of 0.
* Precision - The <b>Precision</b> parameter defines pre-decimal point positions of a float value.
* Scale - The <b>Scale</b> parameter defines the decimal places of a value.


## Associations
With associations, the links between the different models can be defined in doctrine. You can also define which events are triggered when certain actions are performed on specified models.

### OneToOne
A OneToOne association defines that model B is associated only once with model A.

**Example: A customer has one billing address.**

```php
<?php
class Customer extends ModelEntity
{
    /**
     * @var \Shopware\Models\Customer\Billing
     * @ORM\OneToOne(
     *      targetEntity="Shopware\Models\Customer\Billing", 
     *      mappedBy="customer", 
     *      orphanRemoval=true
     * )
     */
    protected $billing;
}

class Billing extends ModelEntity
{
    /**
     * @var integer $customerId
     * @ORM\Column(name="userID", type="integer", nullable=false)
     */
    protected $customerId;
    
    /**
     * @ORM\OneToOne(
     *      targetEntity="Shopware\Models\Customer\Customer", 
     *      inversedBy="billing"
     * )
     * 
     * @ORM\JoinColumn(name="userID", referencedColumnName="id")
     * @var \Shopware\Models\Customer\Customer
     */
    protected $customer;
}
```
There are two classes, `Shopware\Models\Customer\Customer` and `Shopware\Models\Customer\Billing`. The billing class is the **OWNING SITE** of the association, which means that the foreign key can be found in this class/table. This is defined with `@ORM\JoinColumn`. The `Customer` class is the **INVERSE SITE** of the association, thus the parameter **mappedBy="customer"** is set in the association, which means that the link is defined via the `Billing` class.

### OneToMany / ManyToOne

A `@ORM\OneToMany` and `@ORM\ManyToOne` association defines that Model A can have more than one Model B. 

**Example: A single item has several variants.**

```php
<?php
class Article extends ModelEntity
{
    /**
     * @ORM\OneToMany(
     *      targetEntity="Shopware\Models\Article\Detail", 
     *      mappedBy="article"
     * )
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $details;
    
    public function __construct()
    {
        $this->details = new ArrayCollection();
    }
}

class Detail extends ModelEntity
{
    /**
     * @ORM\ManyToOne(
     *      targetEntity="Shopware\Models\Article\Article", 
     *      inversedBy="details"
     * )
     * @ORM\JoinColumn(name="articleID", referencedColumnName="id")
     */
    protected $article;
}
```
In this association, we can see that the `@ORM\OneToMany` association is used with the item pages and, as with OneToOne association, has the property **mappedBy**. This is the INVERSE SITE of the association (the foreign key is not found in this class/table). In addition we can see that the association is initialized in the class constructor. This is an essential part of `@ORM\OneToMany` associations. Doctrine uses array collections for `@ORM\OneToMany` associations, in which the models are stored. If this collection is not initialized during the instancing of the class, the following code source will result in an error:

```php
$article = new Shopware\Models\Article\Article();
$variant = new Shopware\Models\Article\Detail();
$article->getDetails()->add($variant);
```

### ManyToMany

A `@ORM\ManyToMany` association defines that model A can be assigned to multiple model Bs, but also multiple model Bs can be assigned to several model As.

**Example:  An article can be assigned to multiple categories. Multiple items can be assigned to a category.**

```php
<?php
class Article extends ModelEntity
{
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\ManyToMany(targetEntity="Shopware\Models\Category\Category")
     * @ORM\JoinTable(name="s_articles_categories",
     *      joinColumns={
     *          @ORM\JoinColumn(name="articleID", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="categoryID", referencedColumnName="id")
     *      }
     * )
     */
    protected $categories;
}

class Category extends ModelEntity
{
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Shopware\Models\Article\Article")
     * @ORM\JoinTable(name="s_articles_categories",
     *      joinColumns={
     *          @ORM\JoinColumn(name="categoryID", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="articleID", referencedColumnName="id")
     *      }
     * )
     */
    protected $articles;
}
```
As with previous associations, both of the defined `@ORM\ManyToMany` associations have the properties **mappedBy** and **inversedBy**. Unlike with the `@ORM\OneToOne` and `@ORM\OneToMany` associations, there are no **OWNING and INVERSE SITE** with `@ORM\ManyToMany` associations, as the foreign keys can be found in an extra table, `s_articles_categories`. The `s_articles_categories` is therefore not stored as a model. The table is mapped automatically by the `@ORM\ManyToMany` association. Since the foreign keys are stored in an extra table, the join condition appears differently than in other associations. A complete table is defined via `@ORM\JoinTable` as a link.

**JoinColumns** defines the class in relation to the mapping table. Thus, the following is found in the item class:

```php
*      joinColumns={
*          @ORM\JoinColumn(name="articleID", referencedColumnName="id")
*      },
```

As the first parameter, the column of the mapping table is specified here: `s_articles_categories.articleID`. The second parameter defines how the column is referred to within the table: `s_articles.id`.

### Cascading

Doctrine allows you to pass certain model actions on to other associated models. This is called cascading. So it is possible, for example, to delete all associated models of the main model, or to use the next persist of the model to save all models that are not yet that stored in the database. The keywords for doing so are **orphanRemoval** and **cascade**.

**Example:  If the customer is deleted, the billing address will also be deleted.**

```php
<?php
class Customer extends ModelEntity
{
    /**
     * @var \Shopware\Models\Customer\Billing
     * @ORM\OneToOne(
     *      targetEntity="Shopware\Models\Customer\Billing", 
     *      mappedBy="customer", 
     *      orphanRemoval=true, 
     *      cascade={"persist", "update"}
     * )
     */
    protected $billing;
}
```

The parameter **"orphanRemoval=true"** deletes the associated billing address (`Shopware\Models\Customer\Billing`), if the customer is deleted. Alternatively, **cascade={"persist", "update"}** a new/existing billing address is persisted/updated directly in the database when the customer is saved.

### Sorted associations

To read associations presorted from the database, it is possible to define sorting parameters in the associations:

```php
class Article extends ModelEntity 
{
    /**
     * @ORM\OneToMany(
     *      targetEntity="Shopware\Models\Article\Image", 
     *      mappedBy="article", 
     *      orphanRemoval=true, 
     *      cascade={"persist", "update"}
     * )
     * 
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $images;
}

class Image extends ModelEntity 
{
    /**
     * @ORM\ManyToOne(targetEntity="Shopware\Models\Article\Article", inversedBy="images")
     * @ORM\JoinColumn(name="articleID", referencedColumnName="id")
     */
    protected $article;
}
```

Multiple values can also be specified in the `@ORM\OrderBy`:

```php
@ORM\OrderBy({"position" = "ASC", "id" = "DESC"})
```

## Model events

In addition to the Shopware events explained already, Doctrine also comes standard with an event system. This event system has been implemented into Shopware, adding several new features. As in Shopware, there are the <b>pre</b>* and <b>post*</b> events. Naturally, the <b>pre*</b> events come before the respective action, and the <b>post*</b> events come afterwards.

### Insert event

Once a model has been added to the database, the prePersist and postPersist events on the model are triggered. These are passed on to the Shopware event system so that these events can be intercepted.

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
    $model = $arguments->get('model');
}

public function postPersistArticle(Enlight_Event_EventArgs $arguments) {
    $modelManager = $arguments->get('entityManager');
    $model = $arguments->get('model');
}
```

### Update event
Once a model has been added to the database, the events preUpdate and postUpdate are triggered. These are passed on to the Shopware event system so that these events can be intercepted.

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
    $model = $arguments->get('model');
}

public function postUpdateArticle(Enlight_Event_EventArgs $arguments) {
    $modelManager = $arguments->get('entityManager');
    $model = $arguments->get('model');
}
```

### Remove event
Once a model is removed from the database, the preRemove and postRemove events are triggered on the model. These are passed on to the Shopware event system, so that they can be intercepted in plugins as follows:

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
    $model = $arguments->get('model');
}

public function postRemoveArticle(Enlight_Event_EventArgs $arguments) {
    $modelManager = $arguments->get('entityManager');
    $model = $arguments->get('model');
}
```

## Validation annotations
Doctrine offers the possibility of using annotations to validate models. To do so, the annotation namespace must first be included in the model, the same as with ORM annotations.

The validation is then performed as follows:

**Definition**
```php
use Shopware\Components\Model\ModelEntity,
    Doctrine\ORM\Mapping AS ORM,
    Symfony\Component\Validator\Constraints as Assert;

class Article extends ModelEntity
{
    /**
     * @var string $name
     * @Assert\NotBlank
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;
}
```   

**Validation**
```php
$article = new \Shopware\Models\Article\Article();
$exceptions = Shopware()->Models()->validate($article);
```

**Dump**
```php
stdClass Object
(
    [__CLASS__] => Symfony\Component\Validator\ConstraintViolationList
    [violations] => Array
        (
            [0] => stdClass Object
                (
                    [__CLASS__] => Symfony\Component\Validator\ConstraintViolation
                    [messageTemplate] => This value should not be blank
                    [messageParameters] => Array(0)
                    [root] => Shopware\Models\Article\Article
                    [propertyPath] => name
                    [invalidValue] => 
                )

            [1] => stdClass Object
                (
                    [__CLASS__] => Symfony\Component\Validator\ConstraintViolation
                    [messageTemplate] => This value should not be blank
                    [messageParameters] => Array(0)
                    [root] => Shopware\Models\Article\Article
                    [propertyPath] => tax
                    [invalidValue] => 
                )

            [2] => stdClass Object
                (
                    [__CLASS__] => Symfony\Component\Validator\ConstraintViolation
                    [messageTemplate] => This value should not be blank
                    [messageParameters] => Array(0)
                    [root] => Shopware\Models\Article\Article
                    [propertyPath] => mainDetail
                    [invalidValue] => 
                )

        )

)
```


Now that you’ve become familiar with the Shopware models, the academic section of the tutorial is complete. 

Proceed to **<a href="/developers-guide/plugins/license/">create License check</a>**