<?php

namespace ShopwarePlugins\SwagAttributeFilter;

use Enlight_Controller_Request_RequestHttp as Request;
use Shopware\Bundle\SearchBundle\Condition\ProductAttributeCondition;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaRequestHandlerInterface;
use Shopware\Bundle\SearchBundle\Facet\ProductAttributeFacet;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

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
        /*
         * Checks if the filter is active
         */
        if ($request->has('productAttributesForm')) {
            $params = $request->getParams();
            $searchParams = explode('|', $params['productAttributesForm']);

            $criteria->addCondition(
                new ProductAttributeCondition(
                    'attr1',
                    ProductAttributeCondition::OPERATOR_IN,
                    $searchParams
                )
            );
        }

        /**
         * adds the attributes facet
         */
        $criteria->addFacet(new ProductAttributeFacet(
            'attr1', //attribute field
            ProductAttributeFacet::MODE_VALUE_LIST_RESULT, //filter facet
            'productAttributesForm', //form name
            'attributes' //filter label
        ));
    }
}
