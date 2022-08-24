<?php declare(strict_types=1);

namespace B2bAuditLog\Tests;

use Shopware\B2BTest\Common\WebTestCase;

class B2bAuditLogTest extends \PHPUnit_Framework_TestCase
{
    use WebTestCase;

    public function test_index_action()
    {
        $this->disableCommonFixtures(false);
        $this->importFixtures($this->loadCommonFixtureSql());
        $this->performB2bDebtorLogin();

        $client = self::createClient();
        $client->request('GET', '/b2bauditlog/');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertContains('b2bauditlog', $client->getResponse()->getContent());
    }

    public function test_create_action()
    {
        $this->disableCommonFixtures(false);
        $this->importFixtures($this->loadCommonFixtureSql());
        $this->performB2bDebtorLogin();

        $client = self::createClient();
        $client->request('GET', '/b2bauditlog/create');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertContains('Audit Log successfully created!', $client->getResponse()->getContent());
    }
}
