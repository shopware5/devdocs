<?php

namespace SwagSearchBundle\SearchBundleDBAL\Condition;

use Shopware\Bundle\SearchBundle\ConditionInterface;

class EsdCondition implements ConditionInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'swag_search_bundle_esd';
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
