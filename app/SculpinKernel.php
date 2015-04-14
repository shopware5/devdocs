<?php
use Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel;
use Symbid\DevBlog\AuthorsBundle\SymbidAuthorsBundle;
class SculpinKernel extends AbstractKernel
{
    protected function getAdditionalSculpinBundles()
    {
        return [
            'Shopware\Devdocs\SearchBundle\ShopwareSearchBundle'
        ];
    }
}
