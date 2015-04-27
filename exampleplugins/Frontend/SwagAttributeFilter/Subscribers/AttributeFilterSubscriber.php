<?php

namespace ShopwarePlugins\SwagAttributeFilter\Subscribers;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\DependencyInjection\Container;
use ShopwarePlugins\SwagAttributeFilter\CriteriaRequestHandler;

class AttributeFilterSubscriber implements SubscriberInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var string
     */
    private $pluginDir;

    /**
     * @param Container $container
     * @param $pluginDir
     */
    public function __construct(Container $container, $pluginDir)
    {
        $this->container = $container;
        $this->pluginDir = $pluginDir;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Shopware_SearchBundleDBAL_Collect_Facet_Handlers' => 'registerFacetHandler',
            'Shopware_SearchBundle_Collect_Criteria_Request_Handlers' => 'registerRequestHandler',

            'Shopware_SearchBundleDBAL_Collect_Condition_Handlers' => 'registerConditionHandler',
        ];
    }

    public function registerRequestHandler()
    {
        return new CriteriaRequestHandler();
    }
}
