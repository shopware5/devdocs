<?php

use Shopware\Components\Model\ModelManager;
use ShopwarePlugins\SwagESBlog\ESIndexingBundle\BlogDataIndexer;
use ShopwarePlugins\SwagESBlog\ESIndexingBundle\BlogMapping;
use ShopwarePlugins\SwagESBlog\ESIndexingBundle\BlogProvider;
use ShopwarePlugins\SwagESBlog\ESIndexingBundle\BlogSettings;
use ShopwarePlugins\SwagESBlog\ESIndexingBundle\BlogSynchronizer;
use ShopwarePlugins\SwagESBlog\SearchBundleES\BlogSearch;
use ShopwarePlugins\SwagESBlog\Subscriber\ORMBacklogSubscriber;

class Shopware_Plugins_Frontend_SwagESBlog_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent('Enlight_Bootstrap_InitResource_swag_es_blog_search.blog_indexer', 'registerIndexerService');
        $this->subscribeEvent('Shopware_ESIndexingBundle_Collect_Indexer', 'addIndexer');
        $this->subscribeEvent('Shopware_ESIndexingBundle_Collect_Mapping', 'addMapping');
        $this->subscribeEvent('Shopware_ESIndexingBundle_Collect_Synchronizer', 'addSynchronizer');
        $this->subscribeEvent('Enlight_Controller_Front_StartDispatch', 'addBacklogSubscriber');
        $this->subscribeEvent('Enlight_Bootstrap_AfterInitResource_shopware_search.product_search', 'decorateProductSearch');
        $this->subscribeEvent('Shopware_ESIndexingBundle_Collect_Settings', 'addSettings');
        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace('ShopwarePlugins\SwagESBlog', $this->Path());
    }

    public function addSettings()
    {
        return new BlogSettings();
    }


    public function registerIndexerService()
    {
        return new BlogDataIndexer(
            $this->get('dbal_connection'),
            $this->get('shopware_elastic_search.client'),
            new BlogProvider($this->get('dbal_connection'))
        );
    }

    public function addIndexer()
    {
        return $this->get('swag_es_blog_search.blog_indexer');
    }

    public function addMapping()
    {
        return new BlogMapping($this->get('shopware_elastic_search.field_mapping'));
    }

    public function addBacklogSubscriber()
    {
        $subscriber = new ORMBacklogSubscriber(Shopware()->Container());

        /** @var ModelManager $entityManager */
        $entityManager = $this->get('models');
        $entityManager->getEventManager()->addEventSubscriber($subscriber);
    }

    public function addSynchronizer()
    {
        return new BlogSynchronizer(
            $this->get('swag_es_blog_search.blog_indexer'),
            $this->get('dbal_connection')
        );
    }

    public function decorateProductSearch()
    {
        $service = new BlogSearch(
            $this->get('shopware_elastic_search.client'),
            $this->get('shopware_search.product_search'),
            $this->get('shopware_elastic_search.index_factory')
        );
        Shopware()->Container()->set('shopware_search.product_search', $service);
    }
}