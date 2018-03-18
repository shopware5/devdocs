<?php

use SwagProductDetail\Models\Product;

class Shopware_Controllers_Backend_SwagProductDetail extends Shopware_Controllers_Backend_Application
{
    protected $model = Product::class;
    protected $alias = 'product';
}
