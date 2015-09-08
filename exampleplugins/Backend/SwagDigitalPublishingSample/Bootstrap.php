<?php

use Shopware\SwagDigitalPublishingSample\Subscriber\Resources;

class Shopware_Plugins_Backend_SwagDigitalPublishingSample_Bootstrap extends Shopware_Components_Plugin_Bootstrap
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
        $this->subscribeEvent('Enlight_Controller_Action_PreDispatch', 'registerSubscriber');

        return true;
    }

    public function uninstall() {
        return true;
    }

    public function enable()
    {
        return array('success' => true, 'invalidateCache' => array('frontend', 'backend', 'theme'));
    }

    public function registerSubscriber()
    {
        $this->registerPluginNamespace();

        $subscribers = array(
            new Resources($this)
        );

        foreach ($subscribers as $subscriber) {
            $this->Application()->Events()->addSubscriber($subscriber);
        }
    }

    public function registerPluginNamespace()
    {
        $this->Application()->Loader()->registerNamespace(
            'Shopware\SwagDigitalPublishingSample',
            $this->Path()
        );
    }

}