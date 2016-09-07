<?php

namespace ShopwarePlugins\SwagESProduct\SearchBundle;

use Shopware\Bundle\SearchBundle\SortingInterface;

class SalesSorting implements SortingInterface
{
    public function getName()
    {
        return 'swag_es_product_sales';
    }
}
