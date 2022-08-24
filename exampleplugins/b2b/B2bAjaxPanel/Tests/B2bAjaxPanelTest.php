<?php declare(strict_types=1);

namespace B2bAcl\Tests;

use Shopware\B2BTest\Common\WebTestCase;

class B2bAjaxPanelTest extends \PHPUnit_Framework_TestCase
{
    use WebTestCase;

    public function test_index_action_is_accessible()
    {
        $client = self::createClient();

        $client->request('GET', '/B2bAjaxPanel/index');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertContains('B2bAjaxPanel/nav', $client->getResponse()->getContent());
        self::assertContains('B2bAjaxPanel/sub', $client->getResponse()->getContent());
    }

    public function test_nav_action_is_accessible()
    {
        $client = self::createClient();

        $client->request('GET', '/B2bAjaxPanel/nav');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertContains('btn', $client->getResponse()->getContent());
        self::assertContains('b2b--tab-link', $client->getResponse()->getContent());
        self::assertContains('name/Carl', $client->getResponse()->getContent());
        self::assertContains('name/Tom', $client->getResponse()->getContent());
        self::assertContains('B2bAjaxPanel/sub', $client->getResponse()->getContent());
    }

    public function test_sub_action_is_accessible()
    {
        $client = self::createClient();

        $client->request('GET', '/B2bAjaxPanel/sub');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertContains('nobody', $client->getResponse()->getContent());
    }

    public function test_sub_action_is_post_accessible()
    {
        $client = self::createClient();

        $client->request('GET', '/B2bAjaxPanel/sub/name/somebody');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertContains('somebody', $client->getResponse()->getContent());
    }
}
