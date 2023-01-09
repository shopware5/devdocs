<?php
use Bcremer\Sculpin\Bundle\CommonMarkBundle\SculpinCommonMarkBundle;
use Bcremer\Sculpin\Bundle\LessBundle\SculpinLessBundle;
use Janbuecker\Sculpin\Bundle\MetaNavigationBundle\SculpinMetaNavigationBundle;
use Mavimo\Sculpin\Bundle\RedirectBundle\SculpinRedirectBundle;
use Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel;
use Shopware\Devdocs\GitHistoryBundle\SculpinGitHistoryBundle;

class SculpinKernel extends AbstractKernel
{
    protected function getAdditionalSculpinBundles(): array
    {
        return [
            SculpinLessBundle::class,
            SculpinCommonMarkBundle::class,
            SculpinMetaNavigationBundle::class,
            SculpinGitHistoryBundle::class
        ];
    }
}
