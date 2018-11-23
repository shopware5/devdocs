<?php

namespace SwagCustomProductBoxLayout\Tests;

use SwagCustomProductBoxLayout\SwagCustomProductBoxLayout as Plugin;
use Shopware\Components\Test\Plugin\TestCase;

class PluginTest extends TestCase
{
    protected static $ensureLoadedPlugins = [
        'SwagCustomProductBoxLayout' => []
    ];

    public function testCanCreateInstance()
    {
        /** @var Plugin $plugin */
        $plugin = Shopware()->Container()->get('kernel')->getPlugins()['SwagCustomProductBoxLayout'];

        $this->assertInstanceOf(Plugin::class, $plugin);
    }
}
