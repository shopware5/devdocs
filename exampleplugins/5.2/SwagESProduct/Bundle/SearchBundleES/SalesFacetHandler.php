<?php

namespace SwagESProduct\Bundle\SearchBundleES;

use ONGR\ElasticsearchDSL\Aggregation\StatsAggregation;
use ONGR\ElasticsearchDSL\Search;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaPartInterface;
use Shopware\Bundle\SearchBundle\FacetResult\RangeFacetResult;
use Shopware\Bundle\SearchBundle\ProductNumberSearchResult;
use Shopware\Bundle\SearchBundleES\HandlerInterface;
use Shopware\Bundle\SearchBundleES\ResultHydratorInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;
use SwagESProduct\Bundle\SearchBundle\SalesCondition;
use SwagESProduct\Bundle\SearchBundle\SalesFacet;

class SalesFacetHandler implements HandlerInterface, ResultHydratorInterface
{
    public function supports(CriteriaPartInterface $criteriaPart)
    {
        return ($criteriaPart instanceof SalesFacet);
    }

    public function handle(
        CriteriaPartInterface $criteriaPart,
        Criteria $criteria,
        Search $search,
        ShopContextInterface $context
    ) {
        $statsAgg = new StatsAggregation('sales');
        $statsAgg->setField('sales');
        $search->addAggregation($statsAgg);
    }

    public function hydrate(
        array $elasticResult,
        ProductNumberSearchResult $result,
        Criteria $criteria,
        ShopContextInterface $context
    ) {
        if (!isset($elasticResult['aggregations']['agg_sales'])) {
            return;
        }

        $data = $elasticResult['aggregations']['agg_sales'];

        $actives = $this->getActiveValues($criteria, $data);

        $facetResult = new RangeFacetResult(
            'swag_product_es_sales',
            $criteria->hasCondition('swag_es_product_sales'),
            'Sales',
            $data['min'],
            $data['max'],
            $actives['min'],
            $actives['max'],
            'minSales',
            'maxSales'
        );

        $result->addFacet($facetResult);
    }

    /**
     * @param Criteria $criteria
     * @param $data
     * @return array
     */
    private function getActiveValues(Criteria $criteria, $data)
    {
        $actives = [
            'min' => $data['min'],
            'max' => $data['max']
        ];

        /** @var SalesCondition $condition */
        if (!($condition = $criteria->getCondition('swag_es_product_sales'))) {
            return $actives;
        }

        if ($condition->getMin()) {
            $actives['min'] = $condition->getMin();
        }

        if ($condition->getMax()) {
            $actives['max'] = $condition->getMax();
        }
        return $actives;
    }
}
