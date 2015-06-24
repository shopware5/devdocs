<?php

namespace ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL;

use Enlight_Controller_Request_RequestHttp as Request;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaRequestHandlerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL\Condition\EsdCondition;
use ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL\Facet\EsdFacet;
use ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL\Sorting\RandomSorting;

class CriteriaRequestHandler implements CriteriaRequestHandlerInterface
{
    /**
     * @inheritdoc
     */
    public function handleRequest(
        Request $request,
        Criteria $criteria,
        ShopContextInterface $context
    ) {
        if ($request->has('esd')) {
            $criteria->addCondition(
                new EsdCondition()
            );
        }

        if ($request->get('sSort') == 'random') {
            $criteria->addSorting(new RandomSorting());
        }

        $criteria->addFacet(new EsdFacet());
    }
}
