<?php

namespace Shopware\SwagDynamicEmotion\Subscriber;

class Controllers implements \Enlight\Event\SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_Store' => 'addStoreController',
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_SwagStore' => 'addBackendStoreController',
        );
    }

    public function addBackendStoreController()
    {
        return __DIR__ . '/../Controllers/Backend/SwagStore.php';

    }

    public function addStoreController(\Enlight_Event_EventArgs $args)
    {
        return __DIR__ . '/../Controllers/Frontend/Store.php';
    }
}