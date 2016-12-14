<?php

use Shopware\CustomModels\SwagDynamicEmotion\Repository;

class Shopware_Controllers_Frontend_Store extends Enlight_Controller_Action
{
    public function indexAction()
    {
        /** @var Repository $repo */
        $repo = Shopware()->Models()->getRepository('Shopware\CustomModels\SwagDynamicEmotion\Store');
        $stores = $repo->findAll();

        // all stores
        $this->View()->assign('stores', $stores);

        // curent store or null
        $this->View()->assign(
            'currentStore',
            $this->Request()->getParam('store', empty($stores) ? null : $stores[0]->getId())
        );

        // store template emotion id
        $this->View()->assign('storeEmotionId', $repo->getStoreEmotionId());
    }
}
