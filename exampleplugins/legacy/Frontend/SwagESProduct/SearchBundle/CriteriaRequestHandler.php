<?php

namespace ShopwarePlugins\SwagESProduct\SearchBundle;

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
        $minSales = $request->getParam('minSales', null);
        $maxSales = $request->getParam('maxSales', null);

        if ($minSales || $maxSales) {
            $criteria->addCondition(
                new SalesCondition($minSales, $maxSales)
            );
        }

        $criteria->addFacet(new SalesFacet());

        if ($request->getParams('sSort') == 'sales') {
            $criteria->resetSorting();
            $criteria->addSorting(new SalesSorting());
        }
    }
}
