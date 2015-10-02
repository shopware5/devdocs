<?php

namespace Shopware\Themes\CustomListing;

use Shopware\Components\Form as Form;

class Theme extends \Shopware\Components\Theme
{
    // Meta data
    protected $extend = 'Responsive';
    protected $name = 'Responsive with custom category page';
    protected $description = 'This theme creates an additional listing page template which can be used on specific categories.';
    protected $author = 'shopware AG';
    protected $license = 'MIT';

    public function createConfig(Form\Container\TabContainer $container)
    {
    }
}