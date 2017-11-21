<?php

namespace Shopware\Themes\CustomDetail;

class Theme extends \Shopware\Components\Theme
{
    // Meta data
    protected $extend = 'Responsive';
    protected $name = 'Responsive with custom detail page';
    protected $description = 'This theme creates an additional detail page template which can be used on specific products.';
    protected $author = 'shopware AG';
    protected $license = 'MIT';

    // Additional javascript
    protected $javascript = ['src/js/jquery.custom-detail.js'];
}
