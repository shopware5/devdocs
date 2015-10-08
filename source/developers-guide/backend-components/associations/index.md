---
layout: default
title: Shopware Backend Components Associations
github_link: developers-guide/backend-components/associations.md
tags:
  - backend
  - extjs
  - standard components
indexed: true
---

Im letzten Tutorial [Shopware Backend Komponenten - Detailansicht](/developers-guide/backend-components/detail) wurden die Möglichkeiten der Detailansicht erläutert. Dieses Tutorial enthält die Grundlegenden Informationen über die Implementierung von Assoziationen in den Shopware Backend Komponenten.

Als Grundlage für dieses Tutorial dient das folgende Plugin: [Plugin herunterladen](http://community.shopware.com/files/downloads/swagproduct-14172882.zip)

Dieses Plugin ist das Ergebnis aus dem [Shopware Backend Komponenten - Basics](/developers-guide/backend-components/) Tutorial.

Die Detail- und Listingansicht ist in dem Plugin bereits definiert, jedoch noch nicht individualisiert, so dass standardmäßig alle Felder angezeigt werden:

<div class="img-container">
[IMG|0|original]
</div>

## PHP Implementierung

In den bisherigen Tutorials wurde die Darstellung von einfachen Datenstrukturen mit den Shopware Backend Komponenten erklärt. In diesem Tutorial soll die Datenstruktur des Produkt Beispiel Models um folgende Bestandteile erweitert werden:

* Verknüpfung von Produkten und Steuersätzen (`ManyToOne`)
* Verknüpfung von Produkten und Kategorien (`ManyToMany`)
* Verknüpfung von Produkten und Attributen (`OneToOne`)
* Verknüpfung von Produkten und Varianten (`OneToMany`)

Bevor wir die Verknüpfungen jedoch im Ext JS Teil implementieren können benötigen wir die PHP Seitige Implementierung da sonst keine Daten im Ext JS Backend ankommen. 
Daher werden zunächst sämtliche PHP Implementierung für diese Verknüpfungen vorgenommen bevor es an die Ext JS Seitige Implementierung geht.

### Verknüpfung von Produkten und Steuersätzen (<b>ManyToOne</b>)
Um jedem Produkt einen Steuersatz zuweisen zu können, wird eine `@ORM\ManyToOne` Assoziation auf dem `Shopware\CustomModels\Product\Product Model` benötigt. Warum eine `@ORM\ManyToOne`? Da mehrere Produkte dem gleichen Steuersatz zugewiesen sein können, jedoch ein Produkt nur einem Steuersatz zugewiesen werden kann. Die Implementierung dieser Assoziation sieht wie folgt aus:

```php
/**
 * @ORM\Entity
 * @ORM\Table(name="s_product")
 */
class Product extends ModelEntity
{
    ...

    /**
     * @var
     * @ORM\Column(name="tax_id", type="integer")
     */
    protected $taxId;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Shopware\Models\Tax\Tax")
     * @ORM\JoinColumn(name="tax_id", referencedColumnName="id")
     */
    protected $tax;

    ...

    /**
     * @return mixed
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param mixed $tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
    }

    ...
}
```
Was wurde hier implementiert? Zum einen wurde durch die Definition des `$taxId` Properties eine neue Spalte `tax_id` hinzugefügt. 

<div class="alert alert-info">
<strong>Wichtig</strong>
<p>Dieses Property darf keine Getter und Setter Funktionen besitzen, da sonst inkonsistente Daten entstehen können wenn das `$taxId` Property eine andere Id besitzt als das `Shopware\Models\Tax\Tax` Objekt das in dem Property `$tax` hinterlegt ist)</p>
</div>

Zudem wurde über das Property `$tax` definiert, dass das Produkt einem Tax Objekt zugewiesen sein kann.
Die Annotation <code>@ORM\ManyToOne(targetEntity="Shopware\Models\Tax\Tax")</code> definiert mit welchem Doctrine Model das Produkt Model verknüpft wird.
Die Annotation <code>@ORM\JoinColumn(name="tax_id", referencedColumnName="id")</code> definiert, über welche Spalten die beiden Models Verknüpft werden.

### Verknüpfung von Produkten und Kategorien (**ManyToMany**)
Damit ein Produkt mehreren Kategorien zugeordnet werden kann und eine Kategorie auch mehrere Produkte beinhalten kann, muss eine `@ORM\ManyToMany` Assoziation im Produkt Model implementiert werden.
Die Implementierung dieser Assoziation sieht wie folgt aus:

```php
/**
 * @ORM\Entity
 * @ORM\Table(name="s_product")
 */
class Product extends ModelEntity
{
    ...

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Shopware\Models\Category\Category")
     * @ORM\JoinTable(name="s_product_categories",
     *      joinColumns={
     *          @ORM\JoinColumn(
     *              name="product_id",
     *              referencedColumnName="id"
     *          )
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(
     *              name="category_id",
     *              referencedColumnName="id"
     *          )
     *      }
     * )
     */
    protected $categories;

    ...

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }
    
    ...
}
```
Durch die Annotation `@ORM\ManyToMany` wird definiert mit welchem anderen Doctrine Model das Produkt verknüpft werden soll. Zusätzlich muss definiert werden, über welche Tabelle die Daten verknüpft werden, da bei einer `@ORM\ManyToMany` Assoziation immer eine sogenannte Mapping Tabelle verwendet werden muss. In dieser Mapping Tabelle werden nur die Primary Keys der Doctrine Models gespeichert. Diese Mapping Tabelle wird über die Annotation `@ORM\JoinTable` definiert. In dieser Annotation wird zunächst definiert, wie der Tabellenname lautet: <code>@ORM\JoinTable(name="s_product_categories", </code>. Anschließend wird über `joinColumns` und `inverseJoinColumns` definiert, wie die Spalten in der in der Mapping Tabelle und in den anderen Tabellen heißen. In beiden Properties wird eine `@ORM\JoinColumn` gesetzt, bei der das Property `name` immer den Spaltennamen beinhaltet, wie er in der Mapping Tabelle angelegt werden soll. Das Property `referencedColumnName` beinhaltet immer den Spaltennamen, wie er in der verknüpften Tabelle bereits definiert ist (`s_product.id` / `s_categories.id`).

<div class="alert alert-info">
<strong>Wichtig</strong><br/>
Im Klassenkonstruktor müssen `@ORM\ManyToMany` Properties immer mit einer Array Collection initialisiert werden. Dies wird gemacht um sicher zu stellen, dass die hinterlegten Daten immer in einem Array Format vorliegen.
</div>

### Verknüpfung von Produkten und Attributen (**OneToOne**)

Um jedem Produkt einen Attribute Datensatz zuzuweisen und diesen auch in der Backend Applikation editierbar zu gestalten, wird für die Verknüpfung zwischen Produkten und Attributen eine `@ORM\OneToOne` Assoziation benötigt. Diese Assoziation wird wie folgt definiert:

```php
/**
 * @ORM\Entity
 * @ORM\Table(name="s_product")
 */
class Product extends ModelEntity
{
    ...

    /**
     * @var Attribute
     * @ORM\OneToOne(
     *      targetEntity="Attribute",
     *      mappedBy="product",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $attribute;

    ...

    /**
     * @return \Shopware\CustomModels\Product\Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param \Shopware\CustomModels\Product\Attribute $attribute
     * @return \Shopware\Components\Model\ModelEntity
     */
    public function setAttribute($attribute)
    {
        return $this->setOneToOne(
            $attribute,
            '\Shopware\CustomModels\Product\Attribute',
            'attribute',
            'product'
        );
    }
    
    ...
}
```

Die Annotation `@ORM\OneToOne` des Properties `$attribute` definiert dass es sich bei dieser Assoziation um eine 1 zu 1 Verknüpfung zwischen den beiden Models handelt. Dies bedeutet dass jedes Attribute nur einem Produkt zugeordnet sein kann und ein Produkt wiederum auch nur ein Attribute besitzen kann.
Zusätzlich zur Definition des Verknüpften Models über die Eigenschaft `targetEntity`, wurde bei dieser Assoziation die Eigenschaft `mappedBy` und `cascade` gesetzt.

Die `mappedBy` Eigenschaft definiert, dass es sich bei dieser Assoziation um eine Bi-Directionale Assoziation handelt. Dies bedeutet nichts anderes als "Die Models kennen Sich gegenseitig". Bei den Assoziationen der Kategorien und der Steuersätze handelte es sich um eine Uni-Directionale Assoziation, wodurch zwar das Produkt Model das Kategorie und Tax Model kennt, jedoch anders herum nicht.

Die `cascade` Eigenschaft definiert, welche Aktionen die auf dem Produkt Model ausgeführt werden, an das Attribute Model ebenfalls weiter gereicht werden sollen. Durch das setzen von <code>cascade={"persist", "remove"}</code> wird definiert, dass das Speichern und Löschen eines Produkt Models ebenfalls an das Attribute Model weiter gereicht werden soll.

#### Shopware\Components\Model\ModelEntity setOneToOne
Als letzte Besonderheit dieser Assoziation ist der Funktionsaufruf <code>$this->setOneToOne(...)</code> in der Setter Funktion des `$attribute` Properties. Dies ist eine Shopware Helfer Funktion, welche es ermöglicht, übergebene Array Daten automatisiert auf das Verknüpfte Attribute Model zu mappen. Übergeben werden die folgenden Parameter:

* Array Daten die gesetzt werden sollen
* Voller Namespace + Klassenname des Verknüpften Models
* Name des Properties das befüllt werden soll 
* Name des `mappedBy` Feldes im Verknüpften Models 

Die `setOneToOne` Funktion wurde implementiert um letztigen Boilerplate Source Code aus den Backend Controllern zu entfernen. Ohne diese Funktion müsste für jede `@ORM\OneToOne` Assoziation folgendes im Backend Controller implementiert werden:

* Wurden Daten für die Assoziation gesendet?
* Existiert bereits ein Model? Dann dies mit Daten befüllen
* Sollte kein Verknüpftes Model bereits existieren, muss ein neues Model angelegt werden.

Das sieht auf den ersten Blick nach wenig aus, wenn jedoch das ganze noch Rekursiv gemacht werden soll (Das Attribute Model besitzt wieder eine `@ORM\OneToOne` Assoziation), so befinden sich in kürzester Zeit riesige Mengen an Boilerplate Code im Backend Controller. 

#### Implementierung SwagProduct\CustomModels\Product\Attribute
Zusätzlich zur Definition der Assoziation im Produkt Model, muss das neue Attribute Model der Produkte implementiert werden.
Hierfür wird folgender Source Code in die neue Datei `SwagProduct/Models/Product/Attribute.php` eingefügt:

```php
<?php
namespace Shopware\CustomModels\Product;

use Shopware\Components\Model\ModelEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="s_product_attribute")
 */
class Attribute extends ModelEntity
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var string $attr1
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $attr1 = null;


    /**
     * @var string $attr2
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $attr2 = null;


    /**
     * @var string $attr3
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $attr3 = null;


    /**
     * @var string $attr4
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $attr4 = null;


    /**
     * @var string $attr5
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $attr5 = null;

    /**
     * @var
     * @ORM\OneToOne(targetEntity="Product", inversedBy="attribute")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param string $attr1
     */
    public function setAttr1($attr1)
    {
        $this->attr1 = $attr1;
    }

    /**
     * @return string
     */
    public function getAttr1()
    {
        return $this->attr1;
    }

    /**
     * @param string $attr2
     */
    public function setAttr2($attr2)
    {
        $this->attr2 = $attr2;
    }

    /**
     * @return string
     */
    public function getAttr2()
    {
        return $this->attr2;
    }

    /**
     * @param string $attr3
     */
    public function setAttr3($attr3)
    {
        $this->attr3 = $attr3;
    }

    /**
     * @return string
     */
    public function getAttr3()
    {
        return $this->attr3;
    }

    /**
     * @param string $attr4
     */
    public function setAttr4($attr4)
    {
        $this->attr4 = $attr4;
    }

    /**
     * @return string
     */
    public function getAttr4()
    {
        return $this->attr4;
    }

    /**
     * @param string $attr5
     */
    public function setAttr5($attr5)
    {
        $this->attr5 = $attr5;
    }

    /**
     * @return string
     */
    public function getAttr5()
    {
        return $this->attr5;
    }
}
?>
```

Im Attribute Model wird die Gegenseite der `@ORM\OneToOne` Assoziation, des Produkt Models, definiert.
Anders als bei der Assoziation des Produkt Models wird hier statt der `mappedBy` Eigenschaft die Eigenschaft `inversedBy` gesetzt. Dadurch weiß Doctrine dass sich der Fremdschlüssel (ForeignKey > s_product_attribute.product_id) sich in dem Attribute Model befindet. Das `mappedBy` Property wird also immer auf die Seite gesetzt, die nicht den Fremdschlüssel besitzt.

### Verknüpfung von Produkten und Varianten (**OneToMany**)

Um dem Produkt mehere Varianten zuordnen zu können und diese auch über das Backend editierbar zu gestalten, ist eine `@ORM\OneToMany` Assoziation nötig. Diese wird wieder im `SwagProduct/Models/Product/Product.php` Model implementiert:

```php
/**
 * @ORM\Entity
 * @ORM\Table(name="s_product")
 */
class Product extends ModelEntity
{
    ...

    /**
     * @var Variant[]
     * @ORM\OneToMany(
     *      targetEntity="Variant",
     *      mappedBy="product",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $variants;

    ...

    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    /**
     * @return \Shopware\CustomModels\Product\Variant[]
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param \Shopware\CustomModels\Product\Variant[] $variants
     * @return \Shopware\Components\Model\ModelEntity
     */
    public function setVariants($variants)
    {
        return $this->setOneToMany(
            $variants,
            '\Shopware\CustomModels\Product\Variant',
            'variants',
            'product'
        );
    }

    ...
}
```
Die Definition der `@ORM\OneToMany` Assoziation unterscheidet sich nicht groß von den bereits angesprochenden Assoziationstypen. 

#### Shopware\Components\Model\ModelEntity setOneToMany

Wie bei der `@ORM\OneToOne` Assoziation besitzt auch die `@ORM\OneToMany` Assoziation eine Helfer Funktion um den Boilerplate Code aus dem Backend Controller fern zu halten. Diese Funktion muss im Vergleich zur `setOneToOne()` Funktion noch weitere Bedingungen prüfen. Denn in dem internen Property `$variants` können sich bereits existierende Verknüpfungen befinden, welche wiederrum mit den übergebenen Daten aktualisiert werden müssen. Einträge, die jedoch schon hinterlegt sind aber nicht übergeben wurden, müssen wiederrum entfernt werden.

#### Implementierung SwagProduct\CustomModels\Product\Variant
Wie bei der Implementierung der Verknüpfung zwischen Produkten und Attributen, wird bei der Verknüpfung zu den Produkt Varianten, ein eigens Model benötigt.

Hierfür wird folgender Source Code in die neue Datei `SwagProduct/Models/Product/Variant.php` eingefügt:

```php
<?php

namespace Shopware\CustomModels\Product;

use Shopware\Components\Model\ModelEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="s_product_variant")
 */
class Variant extends ModelEntity
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="product_id", type="integer")
     */
    protected $productId;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $number;

    /**
     * @var string $additionalText
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $additionalText = null;

    /**
     * @var integer $active
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $active = false;

    /**
     * @var integer $inStock
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $inStock = null;

    /**
     * @var integer $stockMin
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stockMin = null;

    /**
     * @var float $weight
     *
     * @ORM\Column(type="decimal", nullable=true, precision=3)
     */
    private $weight = null;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="variants")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param int $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param string $additionalText
     */
    public function setAdditionalText($additionalText)
    {
        $this->additionalText = $additionalText;
    }

    /**
     * @return string
     */
    public function getAdditionalText()
    {
        return $this->additionalText;
    }

    /**
     * @param int $inStock
     */
    public function setInStock($inStock)
    {
        $this->inStock = $inStock;
    }

    /**
     * @return int
     */
    public function getInStock()
    {
        return $this->inStock;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $stockMin
     */
    public function setStockMin($stockMin)
    {
        $this->stockMin = $stockMin;
    }

    /**
     * @return int
     */
    public function getStockMin()
    {
        return $this->stockMin;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }
}
?>
```

Da im Produkt Model eine `@ORM\OneToMany` Assoziation definiert wurde, muss auf der Gegenseite der Assoziation eine `@ORM\ManyToOne` Assoziation definiert werden. 

### PHP Controller Erweiterung

Damit die Daten auch an das Backend Module weiter gereicht werden, muss der PHP Controller des Plugins angepasst werden (`SwagProduct/Controllers/Backend/Product.php`).
Um nun die Assozierten Daten sauber an die Backend Applikation zu reichen, müssen die Query für die Detailansicht und Listingansicht erweitert werden.

Für beide Queries gibt es im Standard Controller eine eigene Funktion, welche für die Generierung des Query Builders zuständig ist. Dies ist die `getListQuery()` und `getDetailQuery()` Funktion. Diese Funktionen können einfach überschrieben und so erweitert werden.

Damit die Standard Funktionen von Shopware nicht verloren gehen, sollte immer zuerst die entsprechende parent Funktion aufgerufen werden:

```php
class Shopware_Controllers_Backend_SwagProduct extends Shopware_Controllers_Backend_Application
{
    protected $model = 'Shopware\CustomModels\Product\Product';
    protected $alias = 'product';

    protected function getListQuery()
    {
        $builder = parent::getListQuery();

        $builder->leftJoin('product.tax', 'tax');
        $builder->addSelect(array('tax'));

        return $builder;
    }

    protected function getDetailQuery($id)
    {
        $builder = parent::getDetailQuery($id);

        $builder->leftJoin('product.tax', 'tax')
                ->leftJoin('product.attribute', 'attribute')
                ->leftJoin('product.categories', 'categories')
                ->leftJoin('product.variants', 'variants');

        $builder->addSelect(array('tax', 'categories', 'variants', 'attribute'));

        return $builder;
    }
}
```

Da die Detail Query in manchen Szenarien schnell wachsen kann, ist es ratsam `@ORM\ManyToMany` und `@ORM\OneToMany` Assoziationen nicht in der selben Query zu selektieren. Da jedoch die `getDetailQuery()` Funktion immer einen Query Builder zurück geben muss, ist dies in dieser Funktion nicht möglich. Für solche Fälle kann jedoch die Funktion `getAdditionalDetailData()` verwendet werden. Diese Funktion bekommt das selektierte Daten Array der `getDetailQuery()` übergeben, in der zusätzliche Daten hinterlegt oder bearbeitet werden können:

```php
class Shopware_Controllers_Backend_SwagProduct extends Shopware_Controllers_Backend_Application
{
    protected $model = 'Shopware\CustomModels\Product\Product';
    protected $alias = 'product';

    protected function getListQuery()
    {
        $builder = parent::getListQuery();

        $builder->leftJoin('product.tax', 'tax');
        $builder->addSelect(array('tax'));

        return $builder;
    }

    protected function getDetailQuery($id)
    {
        $builder = parent::getDetailQuery($id);

        $builder->leftJoin('product.tax', 'tax')
                ->leftJoin('product.attribute', 'attribute')
                ->leftJoin('product.variants', 'variants');

        $builder->addSelect(array('tax', 'variants', 'attribute'));

        return $builder;
    }

    protected function getAdditionalDetailData(array $data)
    {
        $data['categories'] = $this->getCategories($data['id']);
        return $data;
    }

    protected function getCategories($productId)
    {
        $builder = $this->getManager()->createQueryBuilder();
        $builder->select(array('products', 'categories'))
                ->from('Shopware\CustomModels\Product\Product', 'products')
                ->innerJoin('products.categories', 'categories')
                ->where('products.id = :id')
                ->setParameter('id', $productId);

        $paginator = $this->getQueryPaginator($builder);

        $data = $paginator->getIterator()->current();

        return $data['categories'];
    }
}
```

### Generierung der Tabellen

Die Daten werden nun im Backend Controller selektiert und an die Backend Applikation weiter gegeben. Damit die Tabellen für das Variant und Attribute Model erzeugt werden, müssen diese noch in der `install()` und `uninstall()` Funktion in das Metadaten Array hinzugefügt werden:

```php
class Shopware_Plugins_Backend_SwagProduct_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    ...

    protected function updateSchema()
    {
        $this->registerCustomModels();

        $em = $this->Application()->Models();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array(
            $em->getClassMetadata('Shopware\CustomModels\Product\Product'),
            $em->getClassMetadata('Shopware\CustomModels\Product\Variant'),
            $em->getClassMetadata('Shopware\CustomModels\Product\Attribute')
        );

        try {
            $tool->dropSchema($classes);
        } catch (Exception $e) {
            //ignore
        }
        $tool->createSchema($classes);

        $this->addDemoData();
    }

    public function uninstall()
    {
        $this->registerCustomModels();

        $em = $this->Application()->Models();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array(
            $em->getClassMetadata('Shopware\CustomModels\Product\Product'),
            $em->getClassMetadata('Shopware\CustomModels\Product\Variant'),
            $em->getClassMetadata('Shopware\CustomModels\Product\Attribute')
        );
        $tool->dropSchema($classes);

        return true;
    }
    ...
}
```

### Demo Daten einspielen

Nun müssen nur noch entsprechende Demodaten für diese Tabellen erzeugt werden. Dafür wird die Funktion addDemoData um folgende Sourcen ergänzt:

```php
class Shopware_Plugins_Backend_SwagProduct_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    ...


    protected function addDemoData()
    {
        $sql = "
            INSERT IGNORE INTO s_product 
                (id, name, active, description, descriptionLong, lastStock, createDate, tax_id)
            SELECT
                a.id,
                a.name,
                a.active,
                a.description,
                a.description_long as descriptionLong,
                a.laststock as lastStock,
                a.datum as createDate,
                a.taxID as tax_id
            FROM s_articles a
        ";
        Shopware()->Db()->query($sql);

        $sql = "
            SET FOREIGN_KEY_CHECKS = 0;
            INSERT IGNORE INTO s_product_variant 
                (id, product_id, number, additionalText, active, inStock, stockMin, weight)
            SELECT
              a.id,
              a.articleID,
              a.ordernumber,
              a.additionaltext,
              a.active,
              a.instock,
              a.stockmin,
              a.weight
            FROM s_articles_details a
        ";
        Shopware()->Db()->query($sql);

        $sql = "
            SET FOREIGN_KEY_CHECKS = 0;
            INSERT IGNORE INTO s_product_attribute
            SELECT
              a.id,
              a.articleID as product_id,
              a.attr1,
              a.attr2,
              a.attr3,
              a.attr4,
              a.attr5
            FROM s_articles_attributes a
        ";
        Shopware()->Db()->query($sql);

        $sql = "
            SET FOREIGN_KEY_CHECKS = 0;
            INSERT IGNORE INTO s_product_categories (product_id, category_id)
            SELECT
              a.articleID as product_id,
              a.categoryID as category_id
            FROM s_articles_categories a
        ";
        Shopware()->Db()->query($sql);

    }
    ...
}
```

Damit wäre die PHP-seitige Implementierung fertig gestellt. Als nächstes werden die Assoziationen im Ext JS Module implementiert. 

## Ext JS Implementierung

Da nun die Daten im Ext JS Module ankommen muss auch im Produkt Model definiert werden, dass diese Assoziationen existieren. Zudem muss definiert werden, um welchen Typen es sich bei den Assoziationen handelt und wo und wie die Assoziationen angezeigt werden sollen.

In den nachfolgenden Bereichen dieses Tutorials werden stets neue Views und Models angelegt. Diese müssen stets in der app.js zunächst registiert werden, damit diese in der Applikation zur Verfügung stehen.
Um nicht für jedes Beispiel erneut die gesamte app.js zeigen zu müssen wird hier einmal die gesamte app.js dargestellt. Die nachfolgend angelegten Komponenten sind hier auskommentiert und können einfach am Ende jedes Beispiels einkommentiert werden:

```php
Ext.define('Shopware.apps.SwagProduct', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.SwagProduct',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.Product',

        'detail.Product',
        'detail.Window',

//        'detail.Category',
//        'detail.Attribute',
//        'detail.Variant'
    ],

    models: [
        'Product',
//        'Category',
//        'Attribute',
//        'Variant'
    ],
    stores: [
        'Product',
//        'Variant'
    ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});
```

### Zusammenbau der Detailansicht

Um Assoziationen in der Detailseite implementieren zu können, muss man zunächst verstehen, wie die Detailansicht die verschiedenen Komponenten zusammenbaut.

Die Definition wie eine Datenquelle (Model) in einer spezifischen Detailansicht dargestellt werden soll, setzt sich aus der Definition des Assoziation und der konfigurierten View Komponenten im verknüpften Model zusammen.

Eine solche Definition wurde bereits in dem ersten Tutorial der Shopware Backend Komponenten getätigt. Dabei handelte es sich um die Detailansicht des Produkt-Models:

```php
Ext.define('Shopware.apps.SwagProduct.model.Product', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            detail: 'Shopware.apps.SwagProduct.view.detail.Product'
        };
    },
    ...
});
```

Hier wurde definiert, dass für die detaillierte Ansicht des Produkt-Models immer die `Shopware.apps.SwagProduct.view.detail.Product` Komponente verwendet werden soll. 

Wird nun an ein `Shopware.window.Detail`(Detailansicht) ein `Shopware.apps.SwagProduct.model.Product` Datensatz übergeben, erkennt die Detailansicht, dass es sich hierbei um den Haupt-Datensatz handelt und prüft welche View in dem `detail` Parameter konfiguriert ist.

Genauso funktionieren auch die Assoziationen, mit dem kleinen Zusatz, dass zunächst geschaut werden muss, um was für eine Art der Verknüpfung es sich handelt.
Es existieren vier mögliche Verknüpfungsarten (Nachfolgend bereits, für die Verständlichkeit, mit einem Model definiert):

```php
Ext.define('Shopware.apps.SwagProduct.model.Product', {
    extend: 'Shopware.data.Model',
    ...

    associations: [{
        relation: 'ManyToOne',
        model: 'Shopware.apps.Base.model.Tax',
    }, {
        relation: 'ManyToMany',
        model: 'Shopware.apps.SwagProduct.model.Category',
    }, {
        relation: 'OneToOne',
        model: 'Shopware.apps.SwagProduct.model.Attribute'
    }, {
        relation: 'OneToMany',
        model: 'Shopware.apps.SwagProduct.model.Variant'
    }]
});
```

Für jede diese Verknüpfungsarten existiert ein entsprechender Konfigurationsparameter / View Definition im `Shopware.data.Model`.

* `field` <> ManyToOne
* `related` <> ManyToMany
* `detail` <> OneToOne  /  Ebenfalls für das an die Detailansicht übergebe Haupt-Model
* `listing` <> OneToMany

Soll nun die `ManyToOne` Assoziation, aus dem obigen Beispiel, in einer Detailansicht dargestellt werden, so prüft die Detailansicht welche Komponenten in dem `field` Parameter des verknüpften `Shopware.apps.Base.model.Tax` konfiguriert wurde.

Soll die `ManyToMany` Assoziation in einer Detailansicht dargestellt werden, so prüft die Detailansicht welche Komponente in dem `related` Parameter des verknüpften `Shopware.apps.SwagProduct.model.Category` konfiguriert wurde. Usw.

Da Assoziationen oftmals gleich dargestellt werden, bei einer `ManyToOne` Assoziation (Bsp.: Product & Tax) wird immer eine Auswahlbox angezeigt, sind in dem `Shopware.data.Model` (Wovon jedes Model extended) bereits die entsprechenden Views vorkonfiguriert:

```php
Ext.define('Shopware.data.Model', {
    extend: 'Ext.data.Model',

    statics: {
        displayConfig: {
            listing: 'Shopware.grid.Panel',
            detail:  'Shopware.model.Container',
            related: 'Shopware.grid.Association',
            field:   'Shopware.form.field.Search',
        },
        ...
    }
    ...
});
```

Sollten diese jedoch nicht dem entsprechen was gefordert ist, können diese einfach überschrieben werden.

### ManyToOne Assoziation - Produkte & Steuersätze
Für die Implementierung einer `ManyToOne` Assoziation mit den Shopware Backend Komponenten müssen die folgenden Dinge in der Backend Applikation implementiert werden:

* Implementierung der `taxId` Spalte.
* Einbindung der Ext JS Assoziation im Produkt-Model
* Konfiguration des Assoziationstypen & Foreign Keys


Die `ManyToOne` Assoziation ist die einfachste der vier Assoziationstypen. Diese Assoziation wird meistens verwendet, um Applikations übergreifend Daten miteinander zu verknüpfen. Daher existiert in den meisten Fällen das zu verknüpfende Model bereits und muss daher nicht selbst implementiert werden.

Um nun die Produkte mit einem Steuersatz zu verknüpfen, wird das Produkt-Model wie folgt erweitert:

```php
Ext.define('Shopware.apps.SwagProduct.model.Product', {
    extend: 'Shopware.data.Model',

    configure: function() {
        ...
    },

    fields: [
        { name : 'id', type: 'int' },
        { name : 'taxId', type: 'int' },
        ...
    ],

    associations: [{
        relation: 'ManyToOne',
        field: 'taxId',

        type: 'hasMany',
        model: 'Shopware.apps.Base.model.Tax',
        name: 'getTax',
        associationKey: 'tax'
    }]
});
```

[IMG|1|5]

**Was wurde hier gemacht?**  
Zunächst wurde durch die Zeile `{ name : 'taxId', type: 'int' },` ein neues Feld dem Produkt hinzugefügt. Diese Spalte beinhaltet die ID des hinterlegten Steuersatzes (Foreign Key). 

Zudem wurde eine neue Assoziation definiert, welche eine Verknüpfung zum `Shopware.apps.Base.model.Tax` erzeugt. 
Durch die Definition `relation: 'ManyToOne',` wurde der Assoziationstyp definiert, welcher für die Standard Backend Komponenten zwigend notwendig ist.

Damit die Daten nicht seperat zur `taxId` angezeigt werden, wurde durch die Konfiguration `field: 'taxId',` definiert, dass die Daten in dem Feld `taxId` angezeigt werden sollen. Dieser Konfigurationsparameter steht nur für `ManyToOne` Assoziationen zur Verfügung.

#### Shopware.form.field.Search
Für `ManyToOne` Assoziationen wird in der Detailansicht ein Shopware.form.field.Search, nachfolgend auch Searchfield genannt, Element erzeugt. Dieses Element ist eine Erweiterung der Ext.form.field.ComboBox. 
Dem Searchfield wird ein dynamisch erstellter Store zugewiesen, welcher wie folgt zusammen gebaut wird: (Beispiel Tax)

```php
return Ext.create('Ext.data.Store', {
    model: 'Shopware.apps.Base.model.Tax',
    proxy: {
        type: 'ajax',
        url: '{url controller="SwagProduct" action="searchAssociation"}',
        reader: { type: 'json', root: 'data', totalProperty: 'total' },
        extraParams: { association: 'tax' }
    }
});
```
[IMG|2|5]

Die Tax Daten werden über einen Ajax Request auf die Plugin Controller Funktion `searchAssociationAction()` ermittelt.

Da alle assoziierten Daten über diese Funktion ermittelt werden, wird als Identifikator der Propertyname der Assoziation mit gesendet, in diesem Beispiel `tax`.

Zusätzlich zur Abfrage eines Offsets an Daten, unterstützt das `Shopware.form.field.Search` eine Suchfunktionalität. Diese ermöglicht es, Suchbegriffe in das Suchfeld einzugeben, nach denen über alle Model Felder gesucht werden soll.

### ManyToMany Assoziation - Produkte & Kategorien
Für die Implementierung einer ManyToMany Assoziation mit den Shopware Backend Komponenten müssen die folgenden Anpassungen an der Backend Applikation vorgenommen werden:

* Definition der Assoziation im Produkt Model
* Erstellung eines Kategorie Models
* Definition der Kategorie-View
* Definition wo die Kategorie-View angezeigt werden soll

Zunächst wird das Produkt-Model um die Assoziation der Kategorien erweitert. Im gleichen Zuge wird ein neues Kategorie Model in der Datei `SwagProduct/Views/backend/swag_product/model/category.js` angelegt:

**SwagProduct/Views/backend/swag_product/model/product.js**
```php
Ext.define('Shopware.apps.SwagProduct.model.Product', {
   extend: 'Shopware.data.Model',
   configure: function() { ... },
   fields: [ ... ],

   associations: [{
     relation: 'ManyToMany',

     type: 'hasMany',
     model: 'Shopware.apps.SwagProduct.model.Category',
     name: 'getCategory',
     associationKey: 'categories'
   } ... ]
});
```

**SwagProduct/Views/backend/swag_product/model/category.js**
```php
Ext.define('Shopware.apps.SwagProduct.model.Category', {

     extend: 'Shopware.apps.Base.model.Category',

     configure: function() { 
        return {
           related: 'Shopware.apps.SwagProduct.view.detail.Category'
        }
     }
});
```

Da im Kategorie-Model nur die View neu konfiguriert werden soll, kann hier ganz einfach vom Standard Kategorie-Model abgeleitet werden, welches global zur Verfügung steht. Dies bietet den Vorteil, dass die Felder des Models nicht neu definiert werden müssen.
Da es sich bei der Assoziation um eine `ManyToMany` Assoziation handelt, wird als View Definition die Eigenschaft `related` verwendet (siehe **Zusammenbau der Detailansicht**).

Dieser Eigenschaft wurde eine neue View Komponente zugewiesen die in der Datei `SwagProduct/Views/backend/swag_product/view/detail/category.js` implementiert wird:

```php
Ext.define('Shopware.apps.SwagProduct.view.detail.Category', {
    extend: 'Shopware.grid.Association',
    alias: 'widget.product-view-detail-category',
    height: 300,
    title: 'Category',

    configure: function() {
        return {
            controller: 'SwagProduct',
            columns: {
                name: {}
            }
        };
    }
});
```

*Was wurde hier implementiert?*  
`ManyToMany` Assoziationen benutzen standardmäßig die Shopware.grid.Association Komponente um die verknüpften Daten darzustellen. Damit die Standardfunktionalität nicht verloren geht, wird die eigene Kategorie-View von dieser Komponente abgeleitet und leicht modifiziert. Eine genauere Erklärung der `Shopware.grid.Association` Komponenten finden Sie [hier](#Shopware.grid.Association).

Nun muss den Backend Komponenten noch mitgeteilt werden, wo diese Assoziation dargstellt werden soll.
Hier gibt es zwei mögliche Stellen, welche die Standard Komponenten unterstützen:

* `Shopware.window.Detail` - Als eigener Reiter auf der Detailseite
* `Shopware.model.Container` - Innerhalb einer bestehenden Detailansicht eines Models

Beide Stellen besitzen dieselbe API um Assoziationen innerhalb der Komponenten abzubilden. Dafür wird in der `configure()` Funktion die Eigenschaft `associations` verwendet. In dieser Eigenschaft kann sich ein Array mit den Namen der Assoziationen befinden. Diese entsprechend jeweils dem Namen des Doctrine Properties der Assoziation.

Um die Kategorie-View nun als eigener Reiter darzustellen, muss das Detail-Window um folgenden Source Code erweitert werden (`SwagProduct/Views/backend/swag_product/view/detail/window.js`):

```php
Ext.define('Shopware.apps.SwagProduct.view.detail.Window', {
    extend: 'Shopware.window.Detail',
    alias: 'widget.product-detail-window',
    title : '{s name=title}Product details{/s}',
    height: 270,
    width: 680,
    configure: function() {
        return {
            associations: [ 'categories' ]
        }
    }
});
```
[IMG|3|5]

Die Kategorie-View kann auch direkt im Produkt-Container dargestellt werden. Dafür wird der Produkt-Container um die folgenden Sourcen ergänzt (`SwagProduct/Views/backend/swag_product/view/detail/product.js`):

```php
Ext.define('Shopware.apps.SwagProduct.view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',
    padding: 20,

    configure: function() {
        return {
            controller: 'SwagProduct',
            associations: [ 'categories' ]
        };
    }
});
```
[IMG|4|5]

<div class="alert alert-info">Damit die Änderungen sichtbar werden, müssen die neuen Elemente in der app.js einkommentiert werden</div>

#### Shopware.grid.Association
Für die Darstellung von `ManyToMany` Assoziationen in der Detailansicht wird standardmäßig ein `Shopware.grid.Association`, nachfolgend auch Assoziation-Grid genannt, erstellt. Diese Komponente ist eine Ableitung `des Shopware.grid.Panel`, jedoch mit bereits deaktivierten Eigenschaften die für den speziellen Gebrauch nicht benötigt werden. So besitzt das Assoziation-Grid zum Beispiel keinen editieren oder hinzufügen Button. Wie beim `Shopware.grid.Panel` wird dem Assoziation-Grid ein `Ext.data.Store` übergeben, anhand dessen die Spalten des Grid erstellt werden.

Das Assoziation-Grid beinhaltet zusätzlich in der Toolbar ein `Shopware.form.field.Search` Element um neue Datensätze suchen und hinzufügen zu können ([Shopware.form.field.Search Dokumentation](#Shopware.form.field.Search)).

<div class="alert alert-info">
Wichtig bei der Konfiguration des Assoziation-Grid ist die Konfiguration des `controller` Properties, da dieser für den Search-Request verwendet wird, wenn in dem `Shopware.form.field.Search` ein Begriff eingegeben wird oder die ComboBox über den Handler geöffnet wird.
</div>

### OneToOne Assoziation - Produkte & Attribute
Für die Implementierung einer OneToOne Assoziationen mit den Shopware Backend Komponenten müssen die folgenden Anpassungen an den Sourcen der Backend Applikation vorgenommen werden:

* Implementierung der Assoziation im Produkt-Model
* Einbindung eines Attribute Models
* Definition der Attribute-View
* Definition wo die Attribute-View dargstellt werden soll

Zunächst wird wieder das Produkt-Model um die Assoziation erweitert und das neue Attribute Model in der Datei `SwagProduct/Views/backend/swag_product/model/attribute.js` implementiert:

**SwagProduct/Views/backend/swag_product/model/product.js**
```php
Ext.define('Shopware.apps.SwagProduct.model.Product', {
    extend: 'Shopware.data.Model',
    configure: function() { ... },
    fields: [ ... ],

    associations: [{
      relation: 'OneToOne',

      type: 'hasMany',
      model: 'Shopware.apps.SwagProduct.model.Attribute',
      name: 'getAttribute',
      associationKey: 'attribute'
    } ... ]
});
```

**SwagProduct/Views/backend/swag_product/model/attribute.js<**
```php
Ext.define('Shopware.apps.SwagProduct.model.Attribute', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            detail: 'Shopware.apps.SwagProduct.view.detail.Attribute'
        };
    },

    fields: [
        { name: 'id', type: 'int' },
        { name: 'attr1', type: 'string' },
        { name: 'attr2', type: 'string' },
        { name: 'attr3', type: 'string' },
        { name: 'attr4', type: 'string' },
        { name: 'attr5', type: 'string' },
        { name: 'attr6', type: 'string' },
        { name: 'attr7', type: 'string' }
    ]
});
```

Da es sich bei dem Attribute-Model um ein Plugin-Model handelt, muss dieses vollständig implementiert werden, da dieses noch nicht im System existiert.

Das Attribute-Model wurde über eine `OneToOne` Assoziation verknüpft, daher wird die entsprechende Attribute-View in der Eigenschaft `detail` hinterlegt. 

Die hier hinterlegte Attribute-View wird in der neuen Datei `SwagProduct/Views/backend/swag_product/view/detail/attribute.js` definiert:

```php
Ext.define('Shopware.apps.SwagProduct.view.detail.Attribute', {
    extend: 'Shopware.model.Container',
    padding: 20,

    configure: function() {
        return {
            fieldAlias: 'attribute'
        }
    }
});
```

*Was wurde hier implementiert?*  
Für die Darstellung von `OneToOne` Assoziationen wird standardmäßig ein `Shopware.model.Container` erzeugt. Shopware.model.Container welche für Assoziationen verwendet werden, benötigen immer einen `fieldAlias`. Dieser alias entspricht dem Propertynamen der Doctrine Assoziation (Also genauso wie bei der Definition wo Assoziationen dargestellt werden sollen). Dieser `fieldAlias` wird, benötigt um Felder von mehreren Models in demselben Form Panel darzustellen. Die Felder des Attribute Models werden dadurch wie folgt benannt:

* attr1 > attribute[attr1]
* attr2 > attribute[attr2]
* attr3 > attribute[attr3]
* ...

So ist es möglich, die Felder verschiedenster Model in ein und demselben Form Panel darzustellen ohne Konflikte bei der Datenindexierung zu erhalten.

Zuletzt muss noch definiert werden, wo die Attribute Daten in der Detailansicht dargestellt werden sollen. Wie bei der ManyToMany Assoziation gibt es auch hier zwei verschiedene Möglichkeiten:

* `Shopware.window.Detail` - Als eigener Reiter auf der Detailseite
* `Shopware.model.Container` - Innerhalb einer bestehenden Detailansicht eines Models


Für die Darstellung der Attribute Daten in einem eigenen Tab Reiter wird wieder das `associations` Property im Detail Window angepasst (`SwagProduct/Views/backend/swag_product/view/detail/window.js`):

```php
Ext.define('Shopware.apps.SwagProduct.view.detail.Window', {
    extend: 'Shopware.window.Detail',
    alias: 'widget.product-detail-window',
    title : '{s name=title}Product details{/s}',
    height: 270,
    width: 680,
    configure: function() {
        return {
            associations: [ 'categories', 'attribute' ]
        }
    }
});
```
3905777056ddf43fccffe28a62ec3e63_5.jpg

Alternativ könnte die Attribute-View auch in einem `Shopware.model.Container` dargestellt werden. Daher könnte die Attribute-View auch direkt im Produkt-Container dargestellt werden. Hierfür wird die Assoziation einfach vom Detail-Window in den Produkt-Container geschoben (`SwagProduct/Views/backend/swag_product/view/detail/product.js`):

```php
Ext.define('Shopware.apps.SwagProduct.view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',
    padding: 20,

    configure: function() {
        return {
            controller: 'SwagProduct',
            associations: [ 'attribute' ]
        };
    }
});
```
10ec0d27544e3eba3875a53da5d020b6_5.jpg

<div class="alert alert-info">
Damit die Änderungen sichtbar werden, müssen die neuen Elemente in der app.js einkommentiert werden
</div>

### OneToMany Assoziation - Produkte & Varianten
Für die Implementierung einer `OneToMany` Assoziationen mit den Shopware Backend Komponenten müssen die folgenden Anpassungen an den Sourcen der Backend Applikation vorgenommen werden: 

* Implementierung der Assoziation im Produkt-Model
* Einbindung des Varianten-Model
* Definition der Varianten-View
* Definition wo die Varianten-View dargestellt werden soll

Wie zuvor auch, wird zunächst das Produkt-Model um die Varianten-Assoziation erweitert und das neue Varianten-Model wird in der Datei `SwagProduct/Views/backend/swag_product/model/variant.js` implementiert:

**SwagProduct/Views/backend/swag_product/model/product.js**
```php
Ext.define('Shopware.apps.SwagProduct.model.Product', {
    extend: 'Shopware.data.Model',

    configure: function() { ... },
    fields: [ ... ],

    associations: [{
        relation: 'OneToMany',

        type: 'hasMany',
        model: 'Shopware.apps.SwagProduct.model.Variant',
        name: 'getVariants',
        associationKey: 'variants'
    } ...]
});
```

**SwagProduct/Views/backend/swag_product/model/variant.js**
```php
Ext.define('Shopware.apps.SwagProduct.model.Variant', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            listing: 'Shopware.apps.SwagProduct.view.detail.Variant'
        };
    },

    fields: [
        { name: 'id', type: 'int' },
        { name: 'productId', type: 'int' },
        { name: 'number', type: 'string' },
        { name: 'additionalText', type: 'string' },
        { name: 'active', type: 'boolean' },
        { name: 'inStock', type: 'int' },
        { name: 'stockMin', type: 'int' },
        { name: 'weight', type: 'float' }
    ]
});
```

Da die Varianten als `OneToMany` Assoziation implementiert wurden, wir für die Definition der Varianten-View die konfigurierte View aus dem Property `listing` verwendet. Die hier hinterlegte Varianten-View wird in der Datei `SwagProduct/Views/backend/swag_product/view/detail/variant.js` definiert:

```php
Ext.define('Shopware.apps.SwagProduct.view.detail.Variant', {
    extend: 'Shopware.grid.Panel',
    alias: 'widget.shopware-product-variant-grid',
    title: 'Variant',
    height: 300
});
```
*Was wurde hier implementiert?*  
Für die Darstellung von `OneToMany` Assoziationen wird standardmäßig ein `Shopware.grid.Panel` erzeugt. Diesem Grid Panel kann wieder rum eine eigene Detailansicht zugewiesen werden um die Varianten editieren zu können. Alternativ kann ein <a href="http://docs.sencha.com/extjs/4.1.3/#!/api/Ext.grid.plugin.RowEditing" target="_blank">Ext.grid.plugin.RowEditing</a> implementiert werden um die Daten direkt im Grid editieren zu können.

Wie bei der OneToMany und OneToOne Assoziation muss nun noch definiert werden, wo die Varianten-View dargestellt werden soll. Nicht anders als bei den bisherigen Darstellungsdefinition der Assoziationen kann auch diese Assoziation in einem eigenen Tab Reiter oder in einem Model Container dargestellt werden.

Für die Darstellung in einem eigenen Tab Reiter müssen die Sourcen des Detail Window wie folgt erweitert werden: (`SwagProduct/Views/backend/swag_product/view/detail/window.js`)

```php
Ext.define('Shopware.apps.SwagProduct.view.detail.Window', {
    extend: 'Shopware.window.Detail',
    alias: 'widget.product-detail-window',
    title : '{s name=title}Product details{/s}',
    height: 270,
    width: 680,
    configure: function() {
        return {
            associations: [ 'attribute', 'categories', 'variants' ]
        }
    }
});
```
d5c8bff395f6bb9152eb6d88729224af_5.jpg

Alternativ können die Varianten auch direkt bei den Produkt Stammdaten dargstellt werden. Hierfür wird die Assoziation einfach im Detail Window entfernt und in den Produkt-Container gesetzt: (`SwagProduct/Views/backend/swag_product/view/detail/product.js`)

```php
Ext.define('Shopware.apps.SwagProduct.view.detail.Product', {
    extend: 'Shopware.model.Container',
    alias: 'widget.product-detail-container',
    padding: 20,

    configure: function() {
        return {
            controller: 'SwagProduct',
            associations: [ 'variants' ]
        };
    }
});
```
811ff54631b7d1c0b671f75f7d18cf20_5.jpg

<div class="alert alert-info">
Damit die Änderungen sichtbar werden, müssen die neuen Elemente in der app.js einkommentiert werden.
</div>

#### Lazy Loading
Bisher wurden die Daten der Detailansicht immer vollständig im getDetailQuery() selektiert. Dies kann jedoch zu Performance Bussen führen, was unnötig ist wenn die assoziierten Daten nur benötigt werden, wenn der entsprechende Reiter aktiv wird. Bestes Beispiel sind die Produkt-Varianten. Der Benutzer würde eigentlich nur die Stammdaten editieren, muss jedoch warten bis das gesammte Listing der Varianten Daten geladen wurde. 
Für diese Problematik bieten die Shopware Backend Komponenten eine eigene Lösung: <i>Lazy-Loading-Assoziations</i>. 

Damit das Varianten Grid erst geladen wird, sobald dieses aktiv geschaltet wird, sind folgende Anpassungen notwendig:

* Erweiterung der Varianten-Assoziation
* Implementierung eines Assoziation-Stores
* Entfernen der Varianten Selektierung im PHP Controller


Zunächst wird die Varianten-Assoziation im Produkt-Model wie folgt erweitert:

```php
Ext.define('Shopware.apps.SwagProduct.model.Product', {
    extend: 'Shopware.data.Model',

    configure: function() { ... },
    fields: [ ... ],

    associations: [{
        relation: 'OneToMany',
        storeClass: 'Shopware.apps.SwagProduct.store.Variant',
        lazyLoading: true,

        type: 'hasMany',
        model: 'Shopware.apps.SwagProduct.model.Variant',
        name: 'getVariants',
        associationKey: 'variants'
    } ...]
});
```

Das Flag `lazyLoading` definiert, dass die Assoziation erst geladen werden soll, sobald das Varianten-Grid in den Sichtbaren Bereich gelangt.

Die Eigenschaft `storeClass` definiert, welche Store Klasse für diese Assoziation erstellt werden soll. Standardmäßig erstellt Ext JS einen `Ext.data.Store` für jede Assoziation, jedoch ohne konfigurierten Proxy. Da zum Laden der Daten jedoch ein Proxy benötigt wird, muss hier eine eigene Store Klasse definiert werden.

Die hier hinterlegte Store Klasse wird in der Datei `SwagProduct/Views/backend/swag_product/store/variant.js` implementiert:

```php
Ext.define('Shopware.apps.SwagProduct.store.Variant', {
    extend: 'Shopware.store.Association',
    model: 'Shopware.apps.SwagProduct.model.Variant',
    configure: function() {
        return {
            controller: 'SwagProduct'
        };
    }
});
```

Bei OneToMany-Assoziation-Stores ist es wichtig, vom `Shopware.store.Association` abzuleiten, da sonst nicht alle Listing Funktionalitäten der Varianten-View supportet werden.

Da die Daten nun per Lazy Loading geladen werden, muss die Selektierung der Varianten Daten in der `getDetailQuery()` Funktion des PHP Controllers entfernt werden.
Damit Ext JS jedoch einen Store für die Assoziation anlegt, muss zumindest ein leeres Array für die Varianten zurück gegeben werden. Dies kann einfach in der `getAdditionalDetailData()` Funktion umgesetzt werden:

```php
class Shopware_Controllers_Backend_SwagProduct extends Shopware_Controllers_Backend_Application
{
    protected $model = 'Shopware\CustomModels\Product\Product';
    protected $alias = 'product';

    protected function getListQuery()
    {
        ...
    }
 
    protected function getDetailQuery($id)
    {
        $builder = parent::getDetailQuery($id);

        $builder->leftJoin('product.tax', 'tax')
                ->leftJoin('product.attribute', 'attribute');

        $builder->addSelect(array('tax', 'attribute'));

        return $builder;
    }

    protected function getAdditionalDetailData(array $data)
    {
        $data['categories'] = $this->getCategories($data['id']);
        $data['variants'] = array();
        return $data;
    }

    protected function getCategories($productId)
    {
        ...
    }
}
```

## Plugin Download - [SwagProduct.zip](http://community.shopware.com/files/downloads/swagproduct-14172593.zip)

Damit haben Sie die Grundlagen zu den Assoziationen in den Shopware Backend Komponenten erlernt.
Die Grundlange der Backend Entwicklung mit den Shopware Backend Komponenten ist damit abgeschlossen. Sie haben somit alles Wissenswerte kennengelernt, was notwendig ist, um eigene Backend Applikationen mit den Standard Komponenten zu entwickeln.


### Weitere Tutorials

In dem nächsten Tutorial werden die von Shopware zur Verfügung gestellten Listing Extensions erklärt und für das Produktlisting implementiert
<a href="http://community.shopware.com/Shopware-Backend-Komponenten-Listing-Extensions_detail_1418_871.html">Shopware Backend Komponenten - Listing Extensions</a>.