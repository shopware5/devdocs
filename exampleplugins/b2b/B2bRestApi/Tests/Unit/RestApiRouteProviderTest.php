<?php declare(strict_types=1);

namespace B2bRestApi\Tests\Unit;

use B2bRestApi\RestApi\Routing\RestApiRouteProvider;

class RestApiRouteProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigReturnsArray()
    {
        $routes = new RestApiRouteProvider();
        self::assertInternalType('array', $routes->getRoutes());
    }
}
