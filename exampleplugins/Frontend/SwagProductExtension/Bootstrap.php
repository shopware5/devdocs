<?php

use ShopwarePlugins\SwagProductExtension\StoreFrontBundle\ListProductService;
use ShopwarePlugins\SwagProductExtension\StoreFrontBundle\SeoCategoryService;

class Shopware_Plugins_Frontend_SwagProductExtension_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{
    public function getLabel()
    {
        return 'Produkterweiterung';
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

        $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Frontend', 'addTemplateDir');
        $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Widgets',  'addTemplateDir');
        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace(
            'ShopwarePlugins\SwagProductExtension',
            $this->Path()
        );
    }

    public function addTemplateDir()
    {
        Shopware()->Container()->get('template')->addTemplateDir($this->Path() . 'Views/');
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
