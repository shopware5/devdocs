<?php

use ShopwarePlugins\SwagSearchBundle\Subscribers\SearchBundleSubscriber;

class Shopware_Plugins_Frontend_SwagSearchBundle_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent('Enlight_Controller_Front_StartDispatch', 'startDispatch');
        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace(
            'ShopwarePlugins\SwagSearchBundle',
            $this->Path()
        );
    }

    public function startDispatch()
    {
        $this->get('events')->addSubscriber(
            new SearchBundleSubscriber(
                Shopware()->Container(),
                $this->Path()
            )
        );
    }
}
