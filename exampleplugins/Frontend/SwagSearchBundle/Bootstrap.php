<?php

use ShopwarePlugins\SwagSearchBundle\Subscribers\SearchBundleSubscriber;

class Shopware_Plugins_Frontend_SwagSearchBundle_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{
    public function install()
    {
        $this->subscribeEvent('Enlight_Controller_Front_StartDispatch', 'registerSubscriber');
        $this->subscribeEvent('Shopware_Console_Add_Command', 'registerSubscriber');
        return true;
    }

    public function afterInit()
    {
        $this->get('Loader')->registerNamespace(
            'ShopwarePlugins\SwagSearchBundle',
            $this->Path()
        );
    }

    public function registerSubscriber()
    {
        $this->get('events')->addSubscriber(
            new SearchBundleSubscriber(
                Shopware()->Container(),
                $this->Path()
            )
        );
    }
}
