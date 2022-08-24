<?php declare(strict_types=1);

namespace SesVariantSearch;

use Shopware\Bundle\SearchBundle\ConditionInterface;

class VariantCondition implements ConditionInterface
{
    const NAME = 'variant';

    public function getName()
    {
        return self::NAME;
    }
}
