<?php declare(strict_types=1);

namespace B2bContingentRuleItem\Tests;

use B2bContingentRuleItem\RuleItem\WeekdayRuleEntity;
use Shopware\B2B\ContingentRule\Framework\ContingentRuleEntity;

class WeekdayRuleEntityTest extends \PHPUnit_Framework_TestCase
{
    public function testModelInstance()
    {
        $entity = new WeekdayRuleEntity('Weekday');

        self::assertInstanceOf(ContingentRuleEntity::class, $entity);
    }
}
