<?php

namespace SwagTestExample\Service;

use Shopware\Bundle\StoreFrontBundle\Service\Core\ContextService;
use Shopware\Bundle\StoreFrontBundle\Service\Core\ListProductService;
use Shopware\Bundle\StoreFrontBundle\Struct\ListProduct;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContext;

class TestExampleService
{
    /**
     * @var ListProductService
     */
    private $listProductService;

    /**
     * @var ContextService
     */
    private $contextService;

    /**
     * @param ListProductService $listProductService
     * @param ContextService     $contextService
     */
    public function __construct(ListProductService $listProductService, ContextService $contextService)
    {
        $this->listProductService = $listProductService;
        $this->contextService = $contextService;
    }

    public function getFancyRedSunGlasses()
    {
        /** @var ShopContext $context */
        $context = $this->contextService->getContext();

        return $this->listProductService->get('SW10170', $context);
    }
}
