<?php


namespace ShopwarePlugins\SwagESBlog\ESIndexingBundle;

use Doctrine\DBAL\Connection;
use ShopwarePlugins\SwagESBlog\ESIndexingBundle\Struct\Blog;

class BlogProvider
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int[] $ids
     * @return Blog[]
     */
    public function get($ids)
    {
        $query = $this->getQuery($ids);
        $data = $query->execute()->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($data as $row) {
            $blog = new Blog((int) $row['id'], $row['title']);
            $blog->setShortDescription($row['short_description']);
            $blog->setLongDescription($row['description']);
            $blog->setMetaTitle($row['meta_title']);
            $blog->setMetaKeywords($row['meta_keywords']);
            $blog->setMetaDescription($row['meta_description']);
            $result[$blog->getId()] = $blog;
        }

        return $result;
    }

    private function getQuery($ids)
    {
        return $this->connection->createQueryBuilder()
            ->select(['blog.id', 'blog.title', 'blog.short_description', 'blog.description', 'blog.views', 'blog.meta_keywords', 'blog.meta_description', 'blog.meta_title'])
            ->from('s_blog', 'blog')
            ->where('blog.id IN (:ids)')
            ->setParameter(':ids', $ids, Connection::PARAM_INT_ARRAY);
    }
}
