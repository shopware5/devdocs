<?php

namespace SwagTestExample\Tests;

use Shopware\Bundle\StoreFrontBundle\Struct\ListProduct;
use Shopware\Components\Test\Plugin\TestCase;
use SwagTestExample\Service\TestExampleService;

class SwagExampleTest extends TestCase
{
    public function testSomething()
    {
        /** @var TestExampleService $service */
        $service = Shopware()->Container()->get('swag_test_example.test_service');
        /** @var ListProduct $product */
        $product = $service->getFancyRedSunGlasses();

        $this->assertInstanceOf(ListProduct::class, $product);
        $this->assertEquals('Sonnenbrille "Red"', $product->getName());
    }
}
