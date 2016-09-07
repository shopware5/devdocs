<?php

namespace Shopware\SwagDynamicEmotion\Bootstrap;

/**
 * Class StoreDemoData creates some demo data
 * @package Shopware\SwagDynamicEmotion\Bootstrap
 */
class StoreDemoData
{
    public function create()
    {
        $stores = [
            ['description' => '', 'name' => 'Berlin', 'address' => 'Kurfüstendamm Berlin', 'openInfo' => "Mon-Fri: 10:00 - 18:00\n<br>Sat: 08:00 - 20:00"],
            ['description' => '', 'name' => 'Schöppingen', 'address' => 'Schöppingen Shopware', 'openInfo' => "Mon-Fri: 12:00 - 18:00\n<br>Sat: 10:00 - 20:00"],
            ['description' => '', 'name' => 'Münster', 'address' => 'Frauenstraße Münster', 'openInfo' => "Mon-Fri: 09:00 - 18:00\n<br>Sat: 10:00 - 18:00"],
        ];

        foreach ($stores as $store) {
            $storeModel = new \Shopware\CustomModels\SwagDynamicEmotion\Store();
            $storeModel->fromArray($store);
            Shopware()->Models()->persist($storeModel);
        }
        Shopware()->Models()->flush();
    }
}