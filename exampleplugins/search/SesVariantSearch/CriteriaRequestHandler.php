<?php declare(strict_types=1);

namespace SesVariantSearch;

use Enlight_Controller_Request_RequestHttp as Request;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaRequestHandlerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class CriteriaRequestHandler implements CriteriaRequestHandlerInterface
{
    public function handleRequest(
        Request $request,
        Criteria $criteria,
        ShopContextInterface $context
    ) {
        if ($request->getControllerName() === 'search') {
            $criteria->addBaseCondition(new VariantCondition());
        }

        if ($request->getControllerName() !== 'suggest') {
            $criteria->addBaseCondition(new VariantCondition());
        }
    }
}
