<?php declare(strict_types=1);

namespace SesVariantSearch;

use ONGR\ElasticsearchDSL\Search;
use Shopware\Bundle\SearchBundle\ConditionInterface;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\CriteriaPartInterface;
use Shopware\Bundle\SearchBundleDBAL\ConditionHandlerInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilder;
use Shopware\Bundle\SearchBundleES\HandlerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class VariantConditionHandler implements ConditionHandlerInterface, HandlerInterface
{
    public function supportsCondition(ConditionInterface $condition)
    {
        return $condition instanceof VariantCondition;
    }

    public function generateCondition(
        ConditionInterface $condition,
        QueryBuilder $query,
        ShopContextInterface $context
    ) {
        $query->add('groupBy', 'variant.id', false);
    }

    public function supports(CriteriaPartInterface $criteriaPart)
    {
        return $criteriaPart instanceof VariantCondition;
    }

    public function handle(
        CriteriaPartInterface $criteriaPart,
        Criteria $criteria,
        Search $search,
        ShopContextInterface $context
    ) {
        $criteria->removeCondition('isMain');
    }
}
