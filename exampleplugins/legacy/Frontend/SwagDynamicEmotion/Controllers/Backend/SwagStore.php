<?php
use Shopware\CustomModels\SwagDynamicEmotion\Repository;

/**
 * Backend controllers extending from Shopware_Controllers_Backend_Application do support the new backend components
 */
class Shopware_Controllers_Backend_SwagStore extends Shopware_Controllers_Backend_Application
{
    protected $model = 'Shopware\CustomModels\SwagDynamicEmotion\Store';
    protected $alias = 'store';

    public function loadAction()
    {
        parent::loadAction();
        /** @var Repository $repo */
        $repo = Shopware()->Models()->getRepository('Shopware\CustomModels\SwagDynamicEmotion\Store');
        // this will make our emotion id available in the (smarty) template of our backend application
        $this->View()->assign('storeTemplateEmotionId', $repo->getStoreEmotionId());
    }

}
