<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace SwagReCaptcha;

use Enlight_Controller_Request_Request;
use Shopware\Components\Captcha\CaptchaInterface;

class ReCaptcha implements CaptchaInterface
{

    /**
     * @var \Shopware\Components\HttpClient\GuzzleFactory
     */
    private $guzzle;

    /**
     * @var \Shopware_Components_Config
     */
    private $config;

    /**
     * @param \Shopware\Components\HttpClient\GuzzleFactory $guzzle
     * @param \Shopware_Components_Config $config
     */
    public function __construct(
        \Shopware\Components\HttpClient\GuzzleFactory $guzzle,
        \Shopware_Components_Config $config
    ) {
        $this->guzzle = $guzzle;
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Enlight_Controller_Request_Request $request)
    {
        $recaptchaUserInput = $request->get('g-recaptcha-response');

        if (empty($recaptchaUserInput)) {
            return false;
        }

        $secret = $this->config->getByNamespace('SwagReCaptcha', 'secret');

        if (empty($secret)) {
            return false;
        }

        /** @var \GuzzleHttp\ClientInterface $client */
        $client = $this->guzzle->createClient();

        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $secret,
                'response' => $recaptchaUserInput,
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $body = $response->getBody();
        $content = json_decode($body->getContents(), true);

        return is_array($content) &&
        array_key_exists('success', $content) &&
        $content['success'] === true;
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplateData()
    {
        $sitekey = $this->config->getByNamespace('SwagReCaptcha', 'sitekey');

        return [
            'sitekey' => $sitekey
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'recaptcha';
    }
}
