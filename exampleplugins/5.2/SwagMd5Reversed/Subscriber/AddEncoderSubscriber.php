<?php

namespace SwagMd5Reversed\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use SwagMd5Reversed\Components\Md5ReversedEncoder;

class AddEncoderSubscriber implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Components_Password_Manager_AddEncoder' => 'onAddEncoder'
        ];
    }

    /**
     * Add the encoder to the internal encoder collection
     *
     * @param Enlight_Event_EventArgs $args
     * @return array
     */
    public function onAddEncoder(Enlight_Event_EventArgs $args)
    {
        $passwordHashHandler = $args->getReturn();

        $passwordHashHandler[] = new Md5ReversedEncoder();

        return $passwordHashHandler;
    }
}