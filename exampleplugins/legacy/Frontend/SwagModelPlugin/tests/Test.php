<?php

class PluginTest extends Shopware\Components\Test\Plugin\TestCase
{
    protected static $ensureLoadedPlugins = array(
        'SwagModelPlugin' => array(
        )
    );

    public function setUp()
    {
        parent::setUp();

        $helper = \TestHelper::Instance();
        $loader = $helper->Loader();


        $pluginDir = getcwd() . '/../';

        $loader->registerNamespace(
            'Shopware\\SwagModelPlugin',
            $pluginDir
        );
    }

    public function testCanCreateInstance()
    {
        /** @var Shopware_Plugins_Frontend_SwagModelPlugin_Bootstrap $plugin */
        $plugin = Shopware()->Plugins()->Frontend()->SwagModelPlugin();

        $this->assertInstanceOf('Shopware_Plugins_Frontend_SwagModelPlugin_Bootstrap', $plugin);
    }
}
