<?php

namespace Shopware\Themes\SummerTheme;

use Shopware\Components\Form as Form;

class Theme extends \Shopware\Components\Theme
{
    protected $extend = 'CustomTheme';

    protected $name = <<<'SHOPWARE_EOD'
Summer theme
SHOPWARE_EOD;

    protected $description = <<<'SHOPWARE_EOD'
Extension of the custom theme
SHOPWARE_EOD;

    protected $author = <<<'SHOPWARE_EOD'
shopware AG
SHOPWARE_EOD;

    protected $license = <<<'SHOPWARE_EOD'
MIT
SHOPWARE_EOD;

    public function createConfig(Form\Container\TabContainer $container)
    {
    }
}