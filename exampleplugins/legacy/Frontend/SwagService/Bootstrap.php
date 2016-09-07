<?php

/**
 * The Bootstrap class is the main entry point of any shopware plugin.
 *
 * Short function reference
 * - install: Called a single time during (re)installation. Here you can trigger install-time actions like
 *   - creating the menu
 *   - creating attributes
 *   - creating database tables
 *   You need to return "true" or array('success' => true, 'invalidateCache' => array()) in order to let the installation
 *   be successfull
 *
 * - update: Triggered when the user updates the plugin. You will get passes the former version of the plugin as param
 *   In order to let the update be successful, return "true"
 *
 * - uninstall: Triggered when the plugin is reinstalled or uninstalled. Clean up your tables here.
 */
class Shopware_Plugins_Frontend_SwagService_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Register the namespace of our plugin globally
     */
    public function afterInit()
    {
        $this->Application()->Loader()->registerNamespace(
            'ShopwarePlugins\SwagService',
            $this->Path()
        );
    }

    /**
     * Return the version of the plugin.
     *
     * @return mixed
     * @throws Exception
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * Return the label of the plugin
     *
     * @return string
     */
    public function getLabel()
    {
        return 'SwagService';
    }

    /**
     * Register Service + an example controller PreDispatch method
     *
     * @return bool
     */
    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Bootstrap_InitResource_swag_service_plugin.tax_calculator',
            'onInitTaxCalculator'
        );

        $this->subscribeEvent(
            'Enlight_Controller_Action_PreDispatch_Frontend',
            'onFrontendPreDispatch'
        );

        return true;
    }

    /**
     * Return an instance of our new service class
     *
     * @return \ShopwarePlugins\SwagService\Component\TaxCalculator
     */
    public function onInitTaxCalculator()
    {
        return new \ShopwarePlugins\SwagService\Component\TaxCalculator(
            $this->get('pluginlogger')
        );
    }

    /**
     * This will call the service on any Frontend PreDispatch event
     * You should see the log message in your /var/log or /logs log file
     */
    public function onFrontendPreDispatch()
    {
        /** @var  \ShopwarePlugins\SwagService\Component\TaxCalculator $taxCalculator */
        $taxCalculator = $this->get('swag_service_plugin.tax_calculator');
        $taxCalculator->calculate(13.99, 1.19);
    }
}
