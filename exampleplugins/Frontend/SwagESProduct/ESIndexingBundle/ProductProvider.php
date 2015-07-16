<?php

namespace ShopwarePlugins\SwagESProduct\ESIndexingBundle;

use Shopware\Bundle\ESIndexingBundle\Product\ProductProviderInterface;
use Shopware\Bundle\ESIndexingBundle\Struct\Product;
use Shopware\Bundle\StoreFrontBundle\Struct\Attribute;
use Shopware\Bundle\StoreFrontBundle\Struct\Shop;

class ProductProvider implements ProductProviderInterface
{
    /**
     * @var ProductProviderInterface
     */
    private $coreService;

    /**
     * @param ProductProviderInterface $coreService
     */
    public function __construct(ProductProviderInterface $coreService)
    {
        $this->coreService = $coreService;
    }

    /**
     * @param Shop $shop
     * @param string[] $numbers
     * @return Product[]
     */
    public function get(Shop $shop, $numbers)
    {
        $products = $this->coreService->get($shop, $numbers);

        foreach ($products as $product) {
            $attribute = new Attribute(['my_name' => $product->getName() . ' / ' . $product->getKeywords()]);
            $product->addAttribute('swag_es_product', $attribute);
        }

        return $products;
    }
}
