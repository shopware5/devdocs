<?php

namespace ShopwarePlugins\SwagESProduct\ESIndexingBundle;

use Shopware\Bundle\ESIndexingBundle\MappingInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\Shop;

class ProductMapping implements MappingInterface
{
    /**
     * @var MappingInterface
     */
    private $coreMapping;

    /**
     * @param MappingInterface $coreMapping
     */
    public function __construct(MappingInterface $coreMapping)
    {
        $this->coreMapping = $coreMapping;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->coreMapping->getType();
    }

    /**
     * @param Shop $shop
     * @return array
     */
    public function get(Shop $shop)
    {
        $mapping = $this->coreMapping->get($shop);
        $mapping['properties']['attributes']['properties']['swag_es_product'] = [
            'properties' => [
                'my_name' => ['type' => 'string']
            ]
        ];
        return $mapping;
    }
}
