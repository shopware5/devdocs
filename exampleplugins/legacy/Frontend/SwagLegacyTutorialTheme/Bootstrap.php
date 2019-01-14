<?php
class Shopware_Plugins_Frontend_SwagTutorialTheme_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Returns a marketing friendly name of the plugin.
     */
    public function getLabel()
    {
        return 'Your custom theme as a plugin';
    }

    /**
     * Returns the version of the plugin
     */
    public function getVersion()
    {
        return '1.0.0';
    }
}