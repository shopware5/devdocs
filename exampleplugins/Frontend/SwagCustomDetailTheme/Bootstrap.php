<?php
class Shopware_Plugins_Frontend_SwagCustomDetailTheme_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Returns a marketing friendly name of the plugin.
     */
    public function getLabel()
    {
        return 'Responsive theme with custom detail page';
    }

    /**
     * Returns the version of the plugin
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * Returns the general information which is shown in the plugin manager
     */
    public function getInfo()
    {
        return array(
            'version' => $this->getVersion(),
            'label' => $this->getLabel(),
            'supplier' => 'shopware AG',
            'description' => 'This theme creates an additional detail page template which can be used on specific products. When you have activated the plugin you should see the new theme inside your Theme Manager.',
            'link' => 'https://developers.shopware.com'
        );
    }
}