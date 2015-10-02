<?php

namespace Shopware\Themes\CustomDetail;

use Shopware\Components\Form as Form;

class Theme extends \Shopware\Components\Theme
{
    // Meta data
    protected $extend = 'Responsive';
    protected $name = 'Responsive with custom detail page';
    protected $description = 'This theme creates an additional detail page template which can be used on specific products.';
    protected $author = 'shopware AG';
    protected $license = 'MIT';

    // Additional javascript
    protected $javascript = array(
        'src/js/jquery.custom-detail.js'
    );

    public function createConfig(Form\Container\TabContainer $container)
    {
    }
}