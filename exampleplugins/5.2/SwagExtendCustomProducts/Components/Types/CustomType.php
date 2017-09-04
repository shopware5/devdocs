<?php

namespace SwagExtendCustomProducts\Components\Types;

use ShopwarePlugins\SwagCustomProducts\Components\Types\TypeInterface;

class CustomType implements TypeInterface
{
    const TYPE = 'customType';
    const COULD_CONTAIN_VALUES = false;

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function couldContainValues()
    {
        return self::COULD_CONTAIN_VALUES;
    }
}
