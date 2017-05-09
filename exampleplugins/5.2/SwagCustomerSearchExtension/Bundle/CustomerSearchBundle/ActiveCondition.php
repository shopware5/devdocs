<?php

namespace SwagCustomerSearchExtension\Bundle\CustomerSearchBundle;

use Shopware\Bundle\SearchBundle\ConditionInterface;

class ActiveCondition implements ConditionInterface
{
    /**
     * @var bool
     */
    protected $active;

    /**
     * @param bool $active
     */
    public function __construct($active)
    {
        $this->active = $active;
    }

    public function getName()
    {
        return 'ActiveCondition';
    }

    public function onlyActive()
    {
        return $this->active;
    }
}