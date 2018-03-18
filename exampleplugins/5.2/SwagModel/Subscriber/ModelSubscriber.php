<?php

namespace SwagModel\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Shopware\Models\Article\Article;
use Doctrine\ORM\Events;

class ModelSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
           Events::preUpdate,
           Events::postUpdate,
        ];
    }

    /**
     * @param LifecycleEventArgs $arguments
     */
    public function preUpdate(LifecycleEventArgs $arguments)
    {
        /** @var EntityManager $modelManager */
        $modelManager = $arguments->getEntityManager();

        $model = $arguments->getEntity();

        if(!$model instanceof Article) {
            return;
        }

        // modify product data
    }

    /**
     * @param LifecycleEventArgs $arguments
     */
    public function postUpdate(LifecycleEventArgs $arguments)
    {
        /** @var EntityManager $modelManager */
        $modelManager = $arguments->getEntityManager();

        $model = $arguments->getEntity();

        // modify models or do some other fancy stuff
    }
}