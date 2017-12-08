<?php

namespace SwagSearchBundle\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\DependencyInjection\Container;
use SwagSearchBundle\SearchBundleDBAL\Condition\EsdConditionHandler;
use SwagSearchBundle\SearchBundleDBAL\CriteriaRequestHandler;
use SwagSearchBundle\SearchBundleDBAL\Sorting\RandomSortingHandler;
use SwagSearchBundle\SearchBundleDBAL\Facet\EsdFacetHandler;

class SearchBundleSubscriber implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDir;

    /**
     * @param $pluginDir
     */
    public function __construct($pluginDir)
    {
        $this->pluginDir = $pluginDir;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'extendListingTemplate'
        ];
    }

    /**
     * @param $args \Enlight_Event_EventArgs
     */
    public function extendListingTemplate(\Enlight_Event_EventArgs $args)
    {
        $args->get('subject')->View()->addTemplateDir(
            $this->pluginDir . '/Resources/views'
        );
    }
}
