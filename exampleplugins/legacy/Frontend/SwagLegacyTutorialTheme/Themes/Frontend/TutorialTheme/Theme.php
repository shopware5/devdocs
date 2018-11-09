<?php

namespace Shopware\Themes\TutorialTheme;

use Shopware\Components\Form as Form;

class Theme extends \Shopware\Components\Theme
{
    protected $extend = 'Responsive';

    protected $name = <<<'SHOPWARE_EOD'
TutorialTheme
SHOPWARE_EOD;

    protected $description = <<<'SHOPWARE_EOD'
Shopware Devdocs Example Theme
SHOPWARE_EOD;

    protected $author = <<<'SHOPWARE_EOD'
shopware AG
SHOPWARE_EOD;

    protected $license = <<<'SHOPWARE_EOD'
AGPL
SHOPWARE_EOD;

    public function createConfig(Form\Container\TabContainer $container)
    {
    }
}