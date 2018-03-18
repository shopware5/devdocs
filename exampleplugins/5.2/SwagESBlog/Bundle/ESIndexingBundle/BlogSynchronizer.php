<?php

namespace SwagESBlog\Bundle\ESIndexingBundle;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\ESIndexingBundle\Struct\ShopIndex;
use Shopware\Bundle\ESIndexingBundle\SynchronizerInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\Shop;
use SwagESBlog\Subscriber\ORMBacklogSubscriber;

class BlogSynchronizer implements SynchronizerInterface
{
    /**
     * @var BlogDataIndexer
     */
    private $indexer;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param BlogDataIndexer $indexer
     * @param Connection $connection
     */
    public function __construct(BlogDataIndexer $indexer, Connection $connection)
    {
        $this->indexer = $indexer;
        $this->connection = $connection;
    }

    public function synchronize(ShopIndex $shopIndex, $backlogs)
    {
        $ids = [];
        foreach ($backlogs as $backlog) {
            switch ($backlog->getEvent()) {
                case ORMBacklogSubscriber::EVENT_BLOG_UPDATED:
                case ORMBacklogSubscriber::EVENT_BLOG_DELETED:
                case ORMBacklogSubscriber::EVENT_BLOG_INSERTED:
                    $payload = $backlog->getPayload();
                    $ids[] = $payload['id'];
                    break;
                default:
                    continue;
            }
        }

        $blogIds = $this->filterShopBlog($shopIndex->getShop(), $ids);
        if (empty($blogIds)) {
            return;
        }
        $this->indexer->index($shopIndex, $blogIds);
    }

    private function filterShopBlog(Shop $shop, $ids)
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('blog.id')
            ->from('s_blog', 'blog')
            ->innerJoin('blog', 's_categories', 'category', 'category.id = blog.category_id AND category.path LIKE :path')
            ->andWhere('blog.id IN (:ids)')
            ->setParameter(':path', '%|' . (int)$shop->getCategory()->getId() . '|%')
            ->setParameter(':ids', $ids, Connection::PARAM_INT_ARRAY);

        return $query->execute()->fetchAll(\PDO::FETCH_COLUMN);
    }
}
