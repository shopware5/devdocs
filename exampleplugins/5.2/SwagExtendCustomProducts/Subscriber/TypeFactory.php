<?php

namespace SwagExtendCustomProducts\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use SwagExtendCustomProducts\Components\Types\CustomType;

class TypeFactory implements SubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'SwagCustomProduct_Collect_Types' => 'onCollectTypes',
        ];
    }

    /**
     * Returns our new type(s) as ArrayCollection
     *
     * @return ArrayCollection
     */
    public function onCollectTypes()
    {
        return new ArrayCollection(
            [
                CustomType::TYPE => new CustomType(),
            ]
        );
    }
}
