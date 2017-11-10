<?php

use SwagProductListing\Models\Product;

class Shopware_Controllers_Backend_SwagProductListing extends Shopware_Controllers_Backend_Application
{
    protected $model = Product::class;
    protected $alias = 'product';
}
