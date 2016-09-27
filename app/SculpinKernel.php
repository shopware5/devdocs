<?php
use Bcremer\Sculpin\Bundle\CommonMarkBundle\SculpinCommonMarkBundle;
use Bcremer\Sculpin\Bundle\LessBundle\SculpinLessBundle;
use Janbuecker\Sculpin\Bundle\MetaNavigationBundle\SculpinMetaNavigationBundle;
use Mavimo\Sculpin\Bundle\RedirectBundle\SculpinRedirectBundle;
use Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel;
use Shopware\Devdocs\AlgoliaBundle\SculpinAlgoliaBundle;

class SculpinKernel extends AbstractKernel
{
    protected function getAdditionalSculpinBundles()
    {
        return [
            SculpinRedirectBundle::class,
            SculpinLessBundle::class,
            SculpinCommonMarkBundle::class,
            SculpinAlgoliaBundle::class,
            SculpinMetaNavigationBundle::class
        ];
    }
}
