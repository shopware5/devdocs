<?php declare(strict_types=1);

namespace B2bContingentsBudgets\Tests;

use B2bContingentsBudgets\ContingentRules\RuleCartAccessFactory;
use PHPUnit\Framework\TestCase;
use Shopware\B2B\Cart\Framework\WhiteListCartAccess;
use Shopware\B2BTest\Common\KernelTestCaseTrait;
use Shopware\B2BTest\Debtor\DebtorFactoryTrait;

/**
 * @coversDefaultClass \B2bContingentsBudgets\ContingentRules\RuleCartAccessFactory
 */
class RuleCartAccessFactoryTest extends TestCase
{
    use KernelTestCaseTrait;
    use DebtorFactoryTrait;

    private function createRuleCartAccessFactory(): RuleCartAccessFactory
    {
        return self::getKernel()
            ->getContainer()
            ->get('b2b_contingent_rule.rule_cart_access_factory');
    }

    /**
     * @covers ::createCartAccessForIdentity
     */
    public function test_it_creates_whitelist()
    {
        $whitelist = $this->createRuleCartAccessFactory()
            ->createCartAccessForIdentity(self::createContactIdentity(), 'does-not-matter-in-this-test-scenario');

        self::assertInstanceOf(WhiteListCartAccess::class, $whitelist);
    }

    /**
     * @covers ::createCartAccessForIdentity
     */
    public function test_it_creates_whitelist_if_no_contingent_groups_rules_exist()
    {
        self::getKernel()->getContainer()->get('dbal_connection')->exec('DELETE FROM b2b_contingent_group_rule');

        $whitelist = $this->createRuleCartAccessFactory()
            ->createCartAccessForIdentity(self::createContactIdentity(), 'does-not-matter-in-this-test-scenario');

        self::assertInstanceOf(WhiteListCartAccess::class, $whitelist);
    }

    /**
     * @covers ::createCartAccessForIdentity
     */
    public function test_it_creates_whitelist_if_no_contingent_groups_exist()
    {
        self::getKernel()->getContainer()->get('dbal_connection')->exec('DELETE FROM b2b_contingent_group');

        $whitelist = $this->createRuleCartAccessFactory()
            ->createCartAccessForIdentity(self::createContactIdentity(), 'does-not-matter-in-this-test-scenario');

        self::assertInstanceOf(WhiteListCartAccess::class, $whitelist);
    }

    /**
     * @covers ::createCartAccessForIdentity
     */
    public function test_it_creates_whitelist_if_no_contingent_group_rule_time_restriction_exist()
    {
        self::getKernel()->getContainer()->get('dbal_connection')->exec('DELETE FROM b2b_contingent_group_rule_time_restriction');

        $whitelist = $this->createRuleCartAccessFactory()
            ->createCartAccessForIdentity(self::createContactIdentity(), 'does-not-matter-in-this-test-scenario');

        self::assertInstanceOf(WhiteListCartAccess::class, $whitelist);
    }
}
