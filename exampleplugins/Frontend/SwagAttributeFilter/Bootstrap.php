<?php

use ShopwarePlugins\SwagAttributeFilter\Subscribers\AttributeFilterSubscriber;

class Shopware_Plugins_Frontend_SwagAttributeFilter_Bootstrap
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
            'ShopwarePlugins\SwagAttributeFilter',
            $this->Path()
        );
    }

    public function startDispatch()
    {
        $this->get('events')->addSubscriber(
            new AttributeFilterSubscriber(
                Shopware()->Container(),
                $this->Path()
            )
        );
    }
}
