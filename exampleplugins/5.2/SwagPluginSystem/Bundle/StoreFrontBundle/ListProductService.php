<?php

namespace SwagPluginSystem\Bundle\StoreFrontBundle;

use Shopware\Bundle\StoreFrontBundle\Service\ListProductServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

class ListProductService implements ListProductServiceInterface
{
    /**
     * @var ListProductServiceInterface
     */
    private $service;

    /**
     * @var SeoCategoryService
     */
    private $seoCategoryService;

    /**
     * @param ListProductServiceInterface $service
     * @param SeoCategoryService $seoCategoryService
     */
    public function __construct(ListProductServiceInterface $service, SeoCategoryService $seoCategoryService)
    {
        $this->service = $service;
        $this->seoCategoryService = $seoCategoryService;
    }

    /**
     * @inheritdoc
     */
    public function getList(array $numbers, Struct\ProductContextInterface $context)
    {
        $products = $this->service->getList($numbers, $context);

        $categories = $this->seoCategoryService->getList($products, $context);

        /**@var $product Struct\ListProduct*/
        foreach ($products as $product) {
            $productId = $product->getId();
            if (!isset($categories[$productId])) {
                continue;
            }

            $attribute = new Struct\Attribute(['category' => $categories[$productId]]);
            $product->addAttribute('swag_plugin_system', $attribute);
        }
        return $products;
    }

    /**
     * @inheritdoc
     */
    public function get($number, Struct\ProductContextInterface $context)
    {
        $products = $this->getList([$number], $context);
        return array_shift($products);
    }
}
