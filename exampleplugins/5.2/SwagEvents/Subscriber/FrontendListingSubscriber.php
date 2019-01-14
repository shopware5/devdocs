<?php

namespace SwagEvents\Subscriber;

use Enlight\Event\SubscriberInterface;

class FrontendListingSubscriber implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'onFrontendListing'
        ];
    }

    public function onFrontendListing(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Frontend_Listing $subject */
        $subject = $args->getSubject();

        // Do some magic with the listing data
        
        // TODO: Remove after debug
        echo '<pre>';
        var_export($subject->View()->getAssign());
        echo '<br />';
        die();
        // TODO: Remove after debug
    }
}