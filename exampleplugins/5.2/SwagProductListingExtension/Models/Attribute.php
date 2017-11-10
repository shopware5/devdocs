<?php

namespace SwagProductListingExtension\Models;

use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\OneToOne(targetEntity="SwagProductListingExtension\Models\Product", inversedBy="attribute")
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
