<?php

namespace SwagCustomProductBoxLayout\Subscriber;

use Enlight\Event\SubscriberInterface;

class Backend implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDirectory;

    /**
     * @param $pluginDirectory
     */
    public function __construct($pluginDirectory)
    {
        $this->pluginDirectory = $pluginDirectory;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Base' => 'onPostDispatchBackendBase',
        );
    }

    public function onPostDispatchBackendBase(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Customer $controller */
        $controller = $args->getSubject();
        $view = $controller->View();

        $view->addTemplateDir($this->pluginDirectory . '/Resources/views');
        $view->extendsTemplate('backend/swag_custom_product_box_layout/store/product_box_layout.js');
    }
}
