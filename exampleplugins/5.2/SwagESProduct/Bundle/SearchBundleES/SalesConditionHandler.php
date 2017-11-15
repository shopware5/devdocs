<?php

namespace SwagESProduct\Bundle\SearchBundleES;

use ONGR\ElasticsearchDSL\Filter\RangeFilter;
use ONGR\ElasticsearchDSL\Search;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaPartInterface;
use Shopware\Bundle\SearchBundleES\HandlerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use SwagESProduct\Bundle\SearchBundle\SalesCondition;

class SalesConditionHandler implements HandlerInterface
{
    public function supports(CriteriaPartInterface $criteriaPart)
    {
        return ($criteriaPart instanceof SalesCondition);
    }

    public function handle(
        CriteriaPartInterface $criteriaPart,
        Criteria $criteria,
        Search $search,
        ShopContextInterface $context
    ) {
        $range = [];

        /** @var SalesCondition $criteriaPart */
        if ($criteriaPart->getMin() > 0) {
            $range['gte'] = (int) $criteriaPart->getMin();
        }
        if ($criteriaPart->getMax() > 0) {
            $range['lte'] = (int) $criteriaPart->getMax();
        }
        $filter = new RangeFilter('sales', $range);

        if ($criteria->hasBaseCondition($criteriaPart->getName())) {
            $search->addFilter($filter);
        } else {
            $search->addPostFilter($filter);
        }
    }
}
