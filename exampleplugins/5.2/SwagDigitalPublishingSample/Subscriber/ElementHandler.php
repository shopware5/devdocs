<?php

namespace SwagDigitalPublishingSample\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use SwagDigitalPublishingSample\Components\YouTubeHandler;

class ElementHandler implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'Shopware_DigitalPublishing_Collect_ElementHandler' => 'collectElementHandler'
        );
    }

    /**
     * @return ArrayCollection
     */
    public function collectElementHandler()
    {
        return new ArrayCollection(
            array(new YouTubeHandler())
        );
    }
}