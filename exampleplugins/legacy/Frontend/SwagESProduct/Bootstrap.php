<?php

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Bundle\ESIndexingBundle\Product\ProductProviderInterface;
use ShopwarePlugins\SwagESProduct\ESIndexingBundle\ProductMapping;
use ShopwarePlugins\SwagESProduct\ESIndexingBundle\ProductProvider;
use ShopwarePlugins\SwagESProduct\SearchBundle\CriteriaRequestHandler;
use ShopwarePlugins\SwagESProduct\SearchBundleES\SalesConditionHandler;
use ShopwarePlugins\SwagESProduct\SearchBundleES\SalesFacetHandler;
use ShopwarePlugins\SwagESProduct\SearchBundleES\SalesSortingHandler;
use ShopwarePlugins\SwagESProduct\SearchBundleES\SearchTermQueryBuilder;

class Shopware_Plugins_Frontend_SwagESProduct_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent('Enlight_Bootstrap_AfterInitResource_shopware_elastic_search.product_mapping', 'decorateProductMapping');
        $this->subscribeEvent('Enlight_Bootstrap_AfterInitResource_shopware_elastic_search.product_provider', 'decorateProductProvider');
        $this->subscribeEvent('Enlight_Bootstrap_AfterInitResource_shopware_search_es.search_term_query_builder', 'decorateSearchTermQueryBuilder');

        $this->subscribeEvent('Shopware_SearchBundle_Collect_Criteria_Request_Handlers', 'addCriteriaRequestHandler');
        $this->subscribeEvent('Shopware_SearchBundleES_Collect_Handlers', 'addSearchHandlers');

        return true;
    }

    public function addCriteriaRequestHandler()
    {
        return new CriteriaRequestHandler();
    }

    public function addSearchHandlers()
    {
        return new ArrayCollection([
            new SalesFacetHandler(),
            new SalesConditionHandler(),
            new SalesSortingHandler()
        ]);
    }

    public function decorateSearchTermQueryBuilder()
    {
        $searchTermQueryBuilder = new SearchTermQueryBuilder(
            $this->get('shopware_search_es.search_term_query_builder')
        );

        Shopware()->Container()->set(
            'shopware_search_es.search_term_query_builder',
            $searchTermQueryBuilder
        );
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace('ShopwarePlugins\SwagESProduct', $this->Path());
    }

    public function decorateProductMapping()
    {
        /** @var \Shopware\Bundle\ESIndexingBundle\MappingInterface $mapping */
        $mapping = $this->get('shopware_elastic_search.product_mapping');
        Shopware()->Container()->set(
            'shopware_elastic_search.product_mapping',
            new ProductMapping($mapping)
        );
    }

    public function decorateProductProvider()
    {
        /** @var ProductProviderInterface $provider */
        $provider = $this->get('shopware_elastic_search.product_provider');

        Shopware()->Container()->set(
            'shopware_elastic_search.product_provider',
            new ProductProvider($provider)
        );
    }
}
