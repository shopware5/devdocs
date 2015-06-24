<?php

namespace ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL\Condition;

use Shopware\Bundle\SearchBundle\ConditionInterface;
use Shopware\Bundle\SearchBundleDBAL\ConditionHandlerInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilder;
use Shopware\Bundle\StoreFrontBundle\Struct;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class EsdConditionHandler implements ConditionHandlerInterface
{
    const STATE_ESD_INCLUDED = 'esd_include';

    /**
     * @inheritdoc
     */
    public function supportsCondition(ConditionInterface $condition)
    {
        return ($condition instanceof EsdCondition);
    }

    /**
     * @inheritdoc
     */
    public function generateCondition(
        ConditionInterface $condition,
        QueryBuilder $query,
        ShopContextInterface $context
    ) {
        if (!$query->hasState(self::STATE_ESD_INCLUDED)) {
            $query->innerJoin(
                'product',
                's_articles_esd',
                'esd',
                'esd.articleID = product.id'
            );
            $query->addState(self::STATE_ESD_INCLUDED);
        }
    }
}
