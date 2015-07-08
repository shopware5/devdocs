<?php

namespace ShopwarePlugins\SwagESBlog\ESIndexingBundle;

use Shopware\Bundle\ESIndexingBundle\FieldMappingInterface;
use Shopware\Bundle\ESIndexingBundle\MappingInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\Shop;

class BlogMapping implements MappingInterface
{
    /**
     * @var FieldMappingInterface
     */
    private $fieldMapping;

    /**
     * @param FieldMappingInterface $fieldMapping
     */
    public function __construct(FieldMappingInterface $fieldMapping)
    {
        $this->fieldMapping = $fieldMapping;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'blog';
    }

    /**
     * @param Shop $shop
     * @return array
     */
    public function get(Shop $shop)
    {
        return [
            'properties' => [
                'id' => ['type' => 'long'],
                'title' => $this->fieldMapping->getLanguageField($shop),
                'shortDescription' => $this->fieldMapping->getLanguageField($shop),
                'longDescription' => $this->fieldMapping->getLanguageField($shop),
                'metaTitle' => $this->fieldMapping->getLanguageField($shop),
                'metaKeywords' => $this->fieldMapping->getLanguageField($shop),
                'metaDescription' => $this->fieldMapping->getLanguageField($shop)
            ]
        ];
    }
}