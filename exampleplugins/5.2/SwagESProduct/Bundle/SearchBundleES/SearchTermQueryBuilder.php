<?php

namespace SwagESProduct\Bundle\SearchBundleES;

use ONGR\ElasticsearchDSL\Query\BoolQuery;
use ONGR\ElasticsearchDSL\Query\MultiMatchQuery;
use Shopware\Bundle\SearchBundleES\SearchTermQueryBuilderInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class SearchTermQueryBuilder implements SearchTermQueryBuilderInterface
{
    /**
     * @var SearchTermQueryBuilderInterface
     */
    private $decoratedQueryBuilder;

    /**
     * @param SearchTermQueryBuilderInterface $decoratedQueryBuilder
     */
    public function __construct(SearchTermQueryBuilderInterface $decoratedQueryBuilder)
    {
        $this->decoratedQueryBuilder = $decoratedQueryBuilder;
    }

    /**
     * @param ShopContextInterface $context
     * @param $term
     * @return BoolQuery
     */
    public function buildQuery(ShopContextInterface $context, $term)
    {
        $query = $this->decoratedQueryBuilder->buildQuery($context, $term);

        $matchQuery = new MultiMatchQuery(['attributes.properties.swag_es_product.my_name'], $term);
        $query->add($matchQuery, BoolQuery::SHOULD);

        return $query;
    }
}
