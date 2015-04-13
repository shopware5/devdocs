<?php
use Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel;

class SculpinKernel extends AbstractKernel
{
    protected function getAdditionalSculpinBundles()
    {
        return [
            'Shopware\Devdocs\SearchBundle\ShopwareSearchBundle',
            'Bcremer\Sculpin\Bundle\CommonMarkBundle\SculpinCommonMarkBundle',
        ];
    }
}
