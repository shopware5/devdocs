<?php declare(strict_types=1);

namespace B2bContingentRuleItem\Tests;

use B2bContingents\Contingent\Cart\CartAccessFactory;
use Shopware\B2B\Cart\Framework\WhiteListCartAccess;
use Shopware\B2BTest\Debtor\DebtorFactoryTrait;

class CartAccessFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test_cart_access_instance()
    {
        $object = new CartAccessFactory();

        self::assertInstanceOf(
            WhiteListCartAccess::class,
            $object->createCartAccessForIdentity(DebtorFactoryTrait::createDebtorIdentity())
        );
    }
}
