<?php

namespace SwagESProduct\Bundle\SearchBundle;

use Shopware\Bundle\SearchBundle\SortingInterface;

class SalesSorting implements SortingInterface
{
    public function getName()
    {
        return 'swag_es_product_sales';
    }
}
