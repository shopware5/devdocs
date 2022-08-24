<?php declare(strict_types=1);

namespace Shopware\B2BTest\SwagB2bPlugin\Functional\Environment;

use Shopware\B2B\Contact\Framework\ContactIdentity;
use Shopware\B2B\Debtor\Framework\DebtorIdentity;
use Shopware\B2BTest\Common\WebTestCase;

class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    use WebTestCase;

    public function setUp()
    {
        $this->importFixturesFileOnce(__DIR__ . '/../test_fixtures.sql');
    }

    public function test_fails_for_unknown_user()
    {
        $client = self::createClient();
        self::getKernel()->getContainer()->get('front')->setResponse('Enlight_Controller_Response_ResponseTestCase');
        $client->request(
            'POST',
            'account/login/sTarget/account/sTargetAction/index',
            [
                'staffId' => 'foo',
                'password' => 'bar',
            ]
        );

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertContains(
            'Ihre Zugangsdaten konnten keinem Benutzer zugeordnet werden',
            $client->getResponse()->getContent()
        );

        self::assertEquals(1, $client->getResponse()->headers->has('B2b-no-login'));
        self::assertFalse($client->getResponse()->headers->has('B2b-login'));
        self::assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function test_success_login_for_debtor()
    {
        self::getKernel()->getContainer()->get('front')->setResponse('Enlight_Controller_Response_ResponseTestCase');
        $client = self::createClient();
        $client->request(
            'POST',
            'account/login/sTarget/account/sTargetAction/index',
            [
                'staffId' => 'B-1',
                'password' => 'shopware',
            ]
        );
        self::assertEquals(302, $client->getResponse()->getStatusCode());

        self::getKernel()->getContainer()->get('front')->setResponse('Enlight_Controller_Response_ResponseTestCase');
        $client->request('GET', 'account');
        self::assertNotContains(
            'Ihre Zugangsdaten konnten keinem Benutzer zugeordnet werden',
            $client->getResponse()->getContent()
        );
        self::assertEquals(
            302,
            $client->getResponse()->getStatusCode(),
            print_r($client->getResponse()->getStatusCode(), true)
        );


        self::assertEquals(null, $client->getResponse()->headers->get('B2b-login'));
        self::assertTrue(
            self::getKernel()->getContainer()->get('b2b_front_auth.authentication_service')->is(
                DebtorIdentity::class
            )
        );
    }

    public function test_success_login_for_existing_contact()
    {
        $client = self::createClient();
        self::getKernel()->getContainer()->get('front')->setResponse('Enlight_Controller_Response_ResponseTestCase');
        $client->request(
            'POST',
            'account/login/sTarget/account/sTargetAction/index',
            [
                'staffId' => 'A-1',
                'password' => 'shopware',
            ]
        );
        self::assertEquals(302, $client->getResponse()->getStatusCode());

        self::getKernel()->getContainer()->get('front')->setResponse('Enlight_Controller_Response_ResponseTestCase');
        $client->request('GET', 'account');
        self::assertEquals(302, $client->getResponse()->getStatusCode());
        self::assertNotContains(
            'Ihre Zugangsdaten konnten keinem Benutzer zugeordnet werden',
            $client->getResponse()->getContent()
        );
        self::assertTrue(
            self::getKernel()->getContainer()->get('b2b_front_auth.authentication_service')->is(
                ContactIdentity::class
            )
        );
    }

    public function test_success_login_for_new_contact()
    {
        $client = self::createClient();
        self::getKernel()->getContainer()->get('front')->setResponse('Enlight_Controller_Response_ResponseTestCase');
        $client->request(
            'POST',
            'account/login/sTarget/account/sTargetAction/index',
            [
                'staffId' => 'A-3',
                'password' => 'shopware',
            ]
        );

        self::getKernel()->getContainer()->get('front')->setResponse('Enlight_Controller_Response_ResponseTestCase');
        $client->request('GET', 'account');
        self::assertNotContains(
            'Ihre Zugangsdaten konnten keinem Benutzer zugeordnet werden',
            $client->getResponse()->getContent()
        );
        self::assertTrue(
            self::getKernel()->getContainer()->get('b2b_front_auth.authentication_service')
                ->is(ContactIdentity::class)
        );
    }
}
