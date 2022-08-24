<?php declare(strict_types=1);

namespace B2bContingentRuleItem\Tests;

use InvalidArgumentException;
use Shopware\B2BTest\Common\WebTestCase;

class B2bRuleItemTest extends \PHPUnit_Framework_TestCase
{
    use WebTestCase;

    public function test_new_action()
    {
        $client = self::createClient();

        $this->performB2bDebtorLogin();

        $client->request('GET', '/b2bcontingentruleweekday/new');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertContains('Weekday', $client->getResponse()->getContent());
    }

    public function test_edit_action_is_accessible()
    {
        $client = self::createClient();

        $this->performB2bDebtorLogin();

        $this->expectException(InvalidArgumentException::class);
        $client->request('GET', '/b2bcontingentruleweekday/edit');
    }
}
