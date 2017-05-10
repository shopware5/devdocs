<?php

use Shopware\SwagDigitalPublishingSample120\Subscriber\ElementHandler;
use Shopware\SwagDigitalPublishingSample120\Subscriber\Resources;

class Shopware_Plugins_Backend_SwagDigitalPublishingSample120_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getVersion()
    {
        return '1.0.0';
    }

    public function getLabel()
    {
        return 'Digital Publishing Sample';
    }

    public function install()
    {
        $this->subscribeEvent('Enlight_Controller_Front_StartDispatch', 'registerSubscriber');
        $this->subscribeEvent('Shopware_Console_Add_Command', 'registerSubscriber');

        return true;
    }

    public function uninstall()
    {
        return true;
    }

    public function enable()
    {
        return array('success' => true, 'invalidateCache' => array('frontend', 'backend', 'theme'));
    }

    public function registerSubscriber()
    {
        $this->registerPluginNamespace();
        $this->Application()->Events()->addSubscriber(new Resources($this));
        $this->Application()->Events()->addSubscriber(new ElementHandler());
    }

    public function registerPluginNamespace()
    {
        $this->Application()->Loader()->registerNamespace(
            'Shopware\SwagDigitalPublishingSample120',
            $this->Path()
        );
    }
}