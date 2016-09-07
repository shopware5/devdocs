<?php


namespace Shopware\SwagDynamicEmotion\Subscriber;

use Shopware\Components\DependencyInjection\Container as DIC;
use Shopware\SwagDynamicEmotion\Components\CustomComponents;

class Container implements \Enlight\Event\SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Bootstrap_InitResource_swag_dynamic_emotion.custom_components' => 'createCustomComponentService'
        );
    }

    public function createCustomComponentService()
    {
        return new CustomComponents(
            Shopware()->Plugins()->Frontend()->SwagDynamicEmotion()->getId(),
            Shopware()->Container()->get('dbal_connection')
        );
    }
}