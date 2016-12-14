<?php

use ShopwarePlugins\SwagPluginSystem\StoreFrontBundle\ListProductService;
use ShopwarePlugins\SwagPluginSystem\StoreFrontBundle\SeoCategoryService;

class Shopware_Plugins_Frontend_SwagPluginSystem_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getLabel()
    {
        return 'Shopware 5 - Big picture of the plugin system';
    }

    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_InitResource_shopware_storefront.seo_category_service',
            'registerSeoCategoryService'
        );

        $this->subscribeEvent(
            'Enlight_Bootstrap_AfterInitResource_shopware_storefront.list_product_service',
            'registerListProductService'
        );

        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend',
            'addTemplateDir'
        );

        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Widgets',
            'addTemplateDir'
        );

        $this->subscribeEvent(
            'Theme_Compiler_Collect_Plugin_Less',
            'onCollectLessFiles'
        );

        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace(
            'ShopwarePlugins\SwagPluginSystem',
            $this->Path()
        );
    }

    /**
     * @return \Shopware\Components\Theme\LessDefinition
     */
    public function onCollectLessFiles()
    {
        return new \Shopware\Components\Theme\LessDefinition(
            [],
            [__DIR__ . '/Views/frontend/_public/src/less/all.less']
        );
    }

    public function addTemplateDir()
    {
        Shopware()->Container()->get('template')->addTemplateDir(__DIR__ . '/Views/');
    }

    public function registerSeoCategoryService()
    {
        $seoCategoryService = new SeoCategoryService(
            Shopware()->Container()->get('dbal_connection'),
            Shopware()->Container()->get('shopware_storefront.category_service')
        );
        Shopware()->Container()->set('shopware_storefront.seo_category_service', $seoCategoryService);
    }

    public function registerListProductService()
    {
        Shopware()->Container()->set(
            'shopware_storefront.list_product_service',
            new ListProductService(
                Shopware()->Container()->get('shopware_storefront.list_product_service'),
                Shopware()->Container()->get('shopware_storefront.seo_category_service')
            )
        );
    }
}
