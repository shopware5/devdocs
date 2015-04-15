<?php
use Bcremer\Sculpin\Bundle\LessBundle\SculpinLessBundle;
use Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel;

class SculpinKernel extends AbstractKernel
{
    protected function getAdditionalSculpinBundles()
    {
        return [
            SculpinLessBundle::class_name
        ];
    }
}
