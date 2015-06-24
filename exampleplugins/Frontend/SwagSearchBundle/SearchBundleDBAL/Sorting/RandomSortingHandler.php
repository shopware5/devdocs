<?php

namespace ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL\Sorting;

use Shopware\Bundle\SearchBundle\SortingInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilder;
use Shopware\Bundle\SearchBundleDBAL\SortingHandlerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class RandomSortingHandler implements SortingHandlerInterface
{
    /**
     * @inheritdoc
     */
    public function supportsSorting(SortingInterface $sorting)
    {
        return ($sorting instanceof RandomSorting);
    }

    /**
     * @inheritdoc
     */
    public function generateSorting(
        SortingInterface $sorting,
        QueryBuilder $query,
        ShopContextInterface $context
    ) {
        $query->orderBy('RAND()');
    }
}
