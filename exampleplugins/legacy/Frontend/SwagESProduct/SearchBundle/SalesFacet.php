<?php

namespace ShopwarePlugins\SwagESProduct\SearchBundle;

use Shopware\Bundle\SearchBundle\FacetInterface;

class SalesFacet implements FacetInterface
{
    public function getName()
    {
        return 'swag_es_product_sales';
    }
}
