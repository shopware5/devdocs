<?php

namespace ShopwarePlugins\SwagSearchBundle\Subscribers;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\DependencyInjection\Container;
use ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL\Condition\EsdConditionHandler;
use ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL\CriteriaRequestHandler;
use ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL\Sorting\RandomSortingHandler;
use ShopwarePlugins\SwagSearchBundle\SearchBundleDBAL\Facet\EsdFacetHandler;

class SearchBundleSubscriber implements SubscriberInterface
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

            'Shopware_SearchBundleDBAL_Collect_Sorting_Handlers' => 'registerSortingHandler',
            'Shopware_SearchBundleDBAL_Collect_Condition_Handlers' => 'registerConditionHandler',



            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'extendListingTemplate'
        ];
    }

    public function registerRequestHandler()
    {
        return new CriteriaRequestHandler();
    }

    public function registerConditionHandler()
    {
        return new EsdConditionHandler();
    }

    public function registerSortingHandler()
    {
        return new RandomSortingHandler();
    }

    public function registerFacetHandler()
    {
        return new EsdFacetHandler(
            $this->container->get('shopware_searchdbal.dbal_query_builder_factory'),
            $this->container->get('snippets')
        );
    }

    public function extendListingTemplate(\Enlight_Event_EventArgs $args)
    {
        $args->getSubject()->View()->addTemplateDir(
            $this->pluginDir . '/Views/'
        );
    }
}
