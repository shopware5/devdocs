<?php declare(strict_types=1);
use SwagB2bPlugin\Extension\LoginProtectedControllerProxy;

class Shopware_Controllers_Frontend_B2bContingentRuleWeekday extends LoginProtectedControllerProxy
{
    /**
     * @return string
     */
    protected function getControllerDiKey(): string
    {
        return 'b2b_contingent_rule_item.controller';
    }
}
