<?php

namespace SwagProductAssoc\Models;

use Shopware\Components\Model\ModelEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="s_product")
 */
class Product extends ModelEntity
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
     * @var string $name
     *
     * @ORM\Column()
     */
    private $name;

    /**
     * @var integer $active
     *
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    /**
     * @var string $description
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description = null;

    /**
     * @var string $descriptionLong
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptionLong = null;

    /**
     * @var integer $lastStock
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $lastStock = false;

    /**
     * @var \DateTime $added
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $createDate = null;

    /**
     * @var Variant[]
     * @ORM\OneToMany(
     *      targetEntity="SwagProductAssoc\Models\Variant",
     *      mappedBy="product",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $variants;

    /**
     * @var Attribute
     * @ORM\OneToOne(
     *      targetEntity="SwagProductAssoc\Models\Attribute",
     *      mappedBy="product",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $attribute;

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

    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @param \DateTime $createDate
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $descriptionLong
     */
    public function setDescriptionLong($descriptionLong)
    {
        $this->descriptionLong = $descriptionLong;
    }

    /**
     * @return string
     */
    public function getDescriptionLong()
    {
        return $this->descriptionLong;
    }

    /**
     * @param int $lastStock
     */
    public function setLastStock($lastStock)
    {
        $this->lastStock = $lastStock;
    }

    /**
     * @return int
     */
    public function getLastStock()
    {
        return $this->lastStock;
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

    /**
     * @return Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param Attribute $attribute
     * @return \Shopware\Components\Model\ModelEntity
     */
    public function setAttribute($attribute)
    {
        return $this->setOneToOne(
            $attribute,
            Attribute::class,
            'attribute',
            'product'
        );
    }

    /**
     * @return Variant[]
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param Variant[] $variants
     * @return \Shopware\Components\Model\ModelEntity
     */
    public function setVariants($variants)
    {
        return $this->setOneToMany(
            $variants,
            Variant::class,
            'variants',
            'product'
        );
    }

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
}
