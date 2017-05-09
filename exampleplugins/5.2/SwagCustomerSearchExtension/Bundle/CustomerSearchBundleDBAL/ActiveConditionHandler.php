<?php

namespace SwagCustomerSearchExtension\Bundle\CustomerSearchBundleDBAL;

use Shopware\Bundle\CustomerSearchBundleDBAL\ConditionHandlerInterface;
use Shopware\Bundle\SearchBundle\ConditionInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilder;
use SwagCustomerSearchExtension\Bundle\CustomerSearchBundle\ActiveCondition;

class ActiveConditionHandler implements ConditionHandlerInterface
{
    public function supports(ConditionInterface $condition)
    {
        return $condition instanceof ActiveCondition;
    }

    public function handle(ConditionInterface $condition, QueryBuilder $query)
    {
        $query->andWhere('customer.active = :active');

        /** @var ActiveCondition $condition */
        $query->setParameter(':active', $condition->onlyActive());
    }
}