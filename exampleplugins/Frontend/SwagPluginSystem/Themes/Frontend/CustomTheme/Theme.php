<?php

namespace Shopware\Themes\CustomTheme;

use Shopware\Components\Form as Form;

class Theme extends \Shopware\Components\Theme
{
    protected $extend = 'Responsive';

    protected $name = <<<'SHOPWARE_EOD'
Plugin theme
SHOPWARE_EOD;

    protected $description = <<<'SHOPWARE_EOD'
Overrides the plugin template extension with own theme files
SHOPWARE_EOD;

    protected $author = <<<'SHOPWARE_EOD'
shopware AG
SHOPWARE_EOD;

    protected $license = <<<'SHOPWARE_EOD'
MIT
SHOPWARE_EOD;

    public function createConfig(Form\Container\TabContainer $container)
    {
        $tab = $this->createTab('swag_custom_theme', 'Custom theme');
        $container->addTab($tab);

        $fieldSet = $this->createFieldSet('swag_custom_theme_field_set', 'Badge configuration');

        $fieldSet->addElement(
            $this->createColorPickerField('badge-seo-category-bg', 'Background seo category badge', '#e74c3c')
        );
        $fieldSet->addElement(
            $this->createColorPickerField('badge-seo-category-color', 'Color seo category badge', '#fff')
        );

        $tab->addElement($fieldSet);
    }
}
