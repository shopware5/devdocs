<?php

namespace SwagDigitalPublishingSample\Subscriber;

use Enlight\Event\SubscriberInterface;

/**
 * Class Resources
 * @package Shopware\DigitalPublishingSample\Subscriber
 */
class FilterSubscriber implements SubscriberInterface
{
    /**
     * Returns an array of events you want to subscribe to
     * and the names of the corresponding callback methods.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'SwagDigitalPublishing_ContentBanner_FilterResult' => 'onContentBannerFilter'
        ];
    }

    /**
     * Filter event for the banner elements of the Digital Publishing module.
     * Enables you to manipulate the banner data before it gets passed to the frontend.
     *
     * @param \Enlight_Event_EventArgs $args
     * @return mixed
     */
    public function onContentBannerFilter(\Enlight_Event_EventArgs $args)
    {
        $banner = $args->getReturn();

        // Do some magic data manipulation

        return $banner;
    }
}
