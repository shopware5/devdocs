<?php

namespace Shopware\CustomModels\SwagDynamicEmotion;

use Shopware\Components\Model\ModelRepository;
use Shopware\Models\Attribute\Emotion;

class Repository extends ModelRepository
{
    public function getStoreEmotionId()
    {
        $attributeEmotionRepo = $this->getEntityManager()->getRepository('Shopware\Models\Attribute\Emotion');

        /** @var Emotion $attribute */
        $attribute = $attributeEmotionRepo->findOneBy(['swagShopTemplate' => 1]);
        if ($attribute && $attribute->getEmotion()) {
            return $attribute->getEmotionId();
        }

        return null;
    }
}
