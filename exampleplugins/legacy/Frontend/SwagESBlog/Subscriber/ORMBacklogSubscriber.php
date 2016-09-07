<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace ShopwarePlugins\SwagESBlog\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Shopware\Bundle\ESIndexingBundle\Struct\Backlog;
use Shopware\Components\DependencyInjection\Container;
use Shopware\Components\Model\ModelEntity;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Blog\Blog as BlogModel;

class ORMBacklogSubscriber implements EventSubscriber
{
    const EVENT_BLOG_DELETED = 'blog_deleted';
    const EVENT_BLOG_INSERTED = 'blog_inserted';
    const EVENT_BLOG_UPDATED = 'blog_updated';

    /**
     * @var Backlog[]
     */
    private $queue = [];

    /**
     * @var array
     */
    private $inserts;

    /**
     * @var bool
     */
    private $eventRegistered = false;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(Events::onFlush, Events::postFlush);
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        /** @var $em ModelManager */
        $em  = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        // Entity deletions
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $backlog = $this->getDeleteBacklog($entity);
            if (!$backlog) {
                continue;
            }
            $this->queue[] = $backlog;
        }

        // Entity Insertions
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->inserts[] = $entity;
        }

        // Entity updates
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $backlog = $this->getUpdateBacklog($entity);
            if (!$backlog) {
                continue;
            }
            $this->queue[] = $backlog;
        }
    }

    /**
     * @param PostFlushEventArgs $eventArgs
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        foreach ($this->inserts as $entity) {
            $backlog = $this->getInsertBacklog($entity);
            if (!$backlog) {
                continue;
            }
            $this->queue[] = $backlog;
        }
        $this->inserts = [];

        $this->registerShutdownListener();
    }

    private function registerShutdownListener()
    {
        if ($this->eventRegistered) {
            return;
        }

        $this->eventRegistered = true;
        $this->container->get('events')->addListener(
            'Enlight_Controller_Front_DispatchLoopShutdown',
            function () {
                $this->processQueue();
            }
        );
    }

    private function processQueue()
    {
        if (empty($this->queue)) {
            return;
        }
        $this->container->get('shopware_elastic_search.backlog_processor')->add($this->queue);
        $this->queue = [];
    }

    /**
     * @param ModelEntity $entity
     * @return Backlog
     */
    private function getDeleteBacklog($entity)
    {
        switch (true) {
            case ($entity instanceof BlogModel):
                return new Backlog(self::EVENT_BLOG_DELETED, ['id' => $entity->getId()]);
        }
    }

    /**
     * @param ModelEntity $entity
     * @return Backlog
     */
    private function getInsertBacklog($entity)
    {
        switch (true) {
            case ($entity instanceof BlogModel):
                return new Backlog(self::EVENT_BLOG_INSERTED, ['id' => $entity->getId()]);

        }
    }

    /**
     * @param ModelEntity $entity
     * @return Backlog
     */
    private function getUpdateBacklog($entity)
    {
        switch (true) {
            case ($entity instanceof BlogModel):
                return new Backlog(self::EVENT_BLOG_UPDATED, ['id' => $entity->getId()]);
        }
    }
}
