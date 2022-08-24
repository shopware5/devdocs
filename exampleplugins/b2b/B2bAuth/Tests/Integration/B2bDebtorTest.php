<?php declare(strict_types=1);

namespace B2bAuth\Tests;

use Shopware\B2B\Contact\Framework\ContactIdentity;
use Shopware\B2B\Debtor\Framework\DebtorIdentity;
use Shopware\B2BTest\Common\WebTestCase;

class B2bDebtorTest extends \PHPUnit_Framework_TestCase
{
    use WebTestCase;

    public function test_contact_action()
    {
        $this->disableCommonFixtures(false);
        $this->importFixtures($this->loadCommonFixtureSql());

        $client = self::createClient();
        $this->performB2bContactLogin();

        $client->request('GET', '/b2bauth/contact');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertContains(ContactIdentity::class, $client->getResponse()->getContent());
        self::assertContains(DebtorIdentity::class, $client->getResponse()->getContent());
    }

    public function test_debtor_action()
    {
        $this->disableCommonFixtures(false);
        $this->importFixtures($this->loadCommonFixtureSql());

        $client = self::createClient();
        $this->performB2bDebtorLogin();

        $client->request('GET', '/b2bauth/debtor');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertContains(DebtorIdentity::class, $client->getResponse()->getContent());
    }
}
