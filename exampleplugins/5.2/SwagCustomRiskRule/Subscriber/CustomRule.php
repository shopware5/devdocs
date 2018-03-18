<?php

namespace SwagCustomRiskRule\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;

class CustomRule implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Modules_Admin_Execute_Risk_Rule_sRiskMyCustomRule' => 'onMyCustomRule'
        ];
    }

    /**
     * @param Enlight_Event_EventArgs $args
     * @return bool
     */
    public function onMyCustomRule(Enlight_Event_EventArgs $args)
    {
        $rule   = $args->get('rule');
        $user   = $args->get('user');
        $basket = $args->get('basket');
        $value  = $args->get('value');

        if ($basket['AmountNumeric'] > $value) {
            return true; // it's a risky customer
        }

        return false;
    }
}
