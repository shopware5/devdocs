<?php

namespace SwagESBlog\Bundle\ESIndexingBundle;

use Doctrine\DBAL\Connection;
use Elasticsearch\Client;
use Shopware\Bundle\ESIndexingBundle\Console\ProgressHelperInterface;
use Shopware\Bundle\ESIndexingBundle\DataIndexerInterface;
use Shopware\Bundle\ESIndexingBundle\Struct\ShopIndex;
use Shopware\Bundle\StoreFrontBundle\Struct\Shop;

class BlogDataIndexer implements DataIndexerInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var BlogProvider
     */
    private $provider;

    /**
     * @param Connection $connection
     * @param Client $client
     * @param BlogProvider $provider
     */
    public function __construct(
        Connection $connection,
        Client $client,
        BlogProvider $provider
    ) {
        $this->connection = $connection;
        $this->client = $client;
        $this->provider = $provider;
    }

    /**
     * @param ShopIndex $index
     * @param ProgressHelperInterface $progress
     */
    public function populate(ShopIndex $index, ProgressHelperInterface $progress)
    {
        $ids = $this->getBlogIds($index->getShop());
        $progress->start(count($ids), 'Indexing blog');

        $chunks = array_chunk($ids, 100);
        foreach ($chunks as $chunk) {
            $this->index($index, $chunk);
            $progress->advance(100);
        }

        $progress->finish();
    }

    /**
     * @param ShopIndex $index
     * @param int[] $ids
     */
    public function index(ShopIndex $index, $ids)
    {
        if (empty($ids)) {
            return;
        }

        $blog = $this->provider->get($ids);
        $remove = array_diff($ids, array_keys($blog));

        $documents = [];
        foreach ($blog as $row) {
            $documents[] = ['index' => ['_id' => $row->getId()]];
            $documents[] = $row;
        }

        foreach ($remove as $id) {
            $documents[] = ['delete' => ['_id' => $id]];
        }

        if (empty($documents)) {
            return;
        }

        $this->client->bulk([
            'index' => $index->getName(),
            'type'  => 'blog',
            'body'  => $documents
        ]);
    }

    private function getBlogIds(Shop $shop)
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('blog.id')
            ->from('s_blog', 'blog')
            ->innerJoin('blog', 's_categories', 'category', 'category.id = blog.category_id AND category.path LIKE :path')
            ->setParameter(':path', '%|'.(int)$shop->getCategory()->getId().'|%');

        return $query->execute()->fetchAll(\PDO::FETCH_COLUMN);
    }
}
