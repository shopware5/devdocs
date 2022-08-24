<?php declare(strict_types = 1);

namespace B2bPrices\Tests;

use B2bPrice\Price\PriceRepository;
use Shopware\B2B\Price\Framework\PriceEntity;

class PriceRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function test_cart_access_instance()
    {
        $repository = new PriceRepository();

        self::assertContainsOnlyInstancesOf(
            PriceEntity::class,
            $repository->fetchPricesByDebtorIdAndOrderNumber(250, ['SW10001', 'SW10002'])
        );
    }
}
