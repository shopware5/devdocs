<?php

use SwagProductBasic\Models\Product;

class Shopware_Controllers_Backend_SwagProductBasic extends \Shopware_Controllers_Backend_Application
{
    protected $model = Product::class;
    protected $alias = 'product';
}
