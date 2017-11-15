<?php

namespace SwagESProduct\Bundle\SearchBundleES;

use ONGR\ElasticsearchDSL\Search;
use ONGR\ElasticsearchDSL\Sort\Sort;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaPartInterface;
use Shopware\Bundle\SearchBundleES\HandlerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use SwagESProduct\Bundle\SearchBundle\SalesSorting;

class SalesSortingHandler implements HandlerInterface
{
    public function supports(CriteriaPartInterface $criteriaPart)
    {
        return ($criteriaPart instanceof SalesSorting);
    }

    public function handle(
        CriteriaPartInterface $criteriaPart,
        Criteria $criteria,
        Search $search,
        ShopContextInterface $context
    ) {
        $sort = new Sort('sales', 'desc');
        $search->addSort($sort);
    }
}
