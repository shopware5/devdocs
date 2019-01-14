<?php

namespace SwagProductListingExtension\Models;

use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="SwagProductListingExtension\Models\Product", inversedBy="variants")
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
