<?php declare(strict_types=1);

namespace B2bRestApi\Test\Integration;

use Shopware\B2BTest\Common\ApiTestCase;

class B2bRestApiTest extends \PHPUnit_Framework_TestCase
{
    use ApiTestCase;

    public function test_it_routes_to_root()
    {
        $result = $this->query('GET', '/rest/api/enterprise');

        self::assertArrayHasKey('message', $result);
        self::assertEquals('hello enterprise', $result['message']);
    }
}
