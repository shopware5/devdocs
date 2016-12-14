<?php

namespace Shopware\CustomModels\SwagDynamicEmotion;

use Doctrine\ORM\Mapping as ORM;
use Shopware\Components\Model\ModelEntity;

/**
 * @ORM\Table(name="swag_store")
 * @ORM\Entity(repositoryClass="Repository")
 */
class Store extends ModelEntity
{
    /**
     * Primary Key - autoincrement value
     *
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
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string $name
     *
     * @ORM\Column(name="address", type="string", nullable=false)
     */
    private $address;


    /**
     * @var string $name
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;


    /**
     * @var string $name
     *
     * @ORM\Column(name="open_info", type="string", nullable=false)
     */
    private $openInfo;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
    public function getOpenInfo()
    {
        return $this->openInfo;
    }

    /**
     * @param string $openInfo
     */
    public function setOpenInfo($openInfo)
    {
        $this->openInfo = $openInfo;
    }
}
