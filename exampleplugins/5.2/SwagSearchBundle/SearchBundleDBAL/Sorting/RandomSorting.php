<?php

namespace SwagSearchBundle\SearchBundleDBAL\Sorting;

use Shopware\Bundle\SearchBundle\SortingInterface;

class RandomSorting implements SortingInterface
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'swag_search_bundle_random';
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
