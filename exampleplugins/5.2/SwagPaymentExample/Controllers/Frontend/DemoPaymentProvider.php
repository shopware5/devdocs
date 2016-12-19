<?php
class Shopware_Controllers_Frontend_DemoPaymentProvider extends Enlight_Controller_Action
{
    public function preDispatch()
    {
        /** @var \Shopware\Components\Plugin $plugin */
        $plugin = $this->get('kernel')->getPlugins()['SwagPaymentExample'];

        $this->get('template')->addTemplateDir($plugin->getPath() . '/Resources/views/');
    }

    public function payAction()
    {
        $cancelUrl = $this->Request()->getParam('cancelUrl') . '?' . http_build_query([
            'status' => 'canceled',
            'token' => $this->Request()->getParam('token'),
            'transactionId' => random_int(0, 1000)
        ]);

        $returnUrl = $this->Request()->getParam('returnUrl') . '?' . http_build_query([
            'status' => 'accepted',
            'token' => $this->Request()->getParam('token'),
            'transactionId' => random_int(0, 1000)
        ]);

        $this->View()->assign([
            'firstName' => $this->Request()->getParam('firstName'),
            'lastName' => $this->Request()->getParam('lastName'),
            'amount' => $this->Request()->getParam('amount'),
            'currency' => $this->Request()->getParam('currency'),
            'returnUrl' => $returnUrl,
            'cancelUrl' => $cancelUrl
        ]);
    }
}
