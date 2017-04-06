<?php

namespace ShopwarePlugins\SwagESBlog\SearchBundleES;

use Elasticsearch\Client;
use ONGR\ElasticsearchDSL\Query\MultiMatchQuery;
use ONGR\ElasticsearchDSL\Search;
use Shopware\Bundle\ESIndexingBundle\IndexFactoryInterface;
use Shopware\Bundle\SearchBundle\Condition\SearchTermCondition;
use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\ProductSearchInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;
use ShopwarePlugins\SwagESBlog\ESIndexingBundle\Struct\Blog;

class BlogSearch implements ProductSearchInterface
{
    /**
     * @var ProductSearchInterface
     */
    private $coreService;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var IndexFactoryInterface
     */
    private $indexFactory;

    /**
     * @param Client $client
     * @param ProductSearchInterface $coreService
     * @param IndexFactoryInterface $indexFactory
     */
    public function __construct(
        Client $client,
        ProductSearchInterface $coreService,
        IndexFactoryInterface $indexFactory
    ) {
        $this->coreService = $coreService;
        $this->client = $client;
        $this->indexFactory = $indexFactory;
    }

    public function search(Criteria $criteria, Struct\ProductContextInterface $context)
    {
        $result = $this->coreService->search($criteria, $context);

        if ($criteria->hasCondition('search')) {
            $blog = $this->searchBlog($criteria, $context);

            $result->addAttribute(
                'swag_elastic_search',
                new Struct\Attribute(['blog' => $blog])
            );
        }

        return $result;
    }

    private function searchBlog(Criteria $criteria, Struct\ProductContextInterface $context)
    {
        /**@var $condition SearchTermCondition*/
        $condition = $criteria->getCondition('search');
        $query = $this->createMultiMatchQuery($condition);

        $search = new Search();
        $search->addQuery($query);
        $search->setFrom(0)->setSize(5);

        $index = $this->indexFactory->createShopIndex($context->getShop());
        $params = [
            'index' => $index->getName(),
            'type'  => 'blog',
            'body'  => $search->toArray()
        ];

        $raw = $this->client->search($params);

        return $this->createBlogStructs($raw);
    }

    /**
     * @param SearchTermCondition $condition
     * @return MultiMatchQuery
     */
    private function createMultiMatchQuery(SearchTermCondition $condition)
    {
        return new MultiMatchQuery(
            ['title', 'shortDescription', 'longDescription'],
            $condition->getTerm(),
            ['operator' => 'AND']
        );
    }

    /**
     * @param $raw
     * @return array
     */
    private function createBlogStructs($raw)
    {
        $result = [];
        foreach ($raw['hits']['hits'] as $hit) {
            $source = $hit['_source'];

            $blog = new Blog($source['id'], $source['title']);
            $blog->setShortDescription($source['shortDescription']);
            $blog->setLongDescription($source['longDescription']);
            $blog->setMetaTitle($source['metaTitle']);
            $blog->setMetaKeywords($source['metaKeywords']);
            $blog->setMetaDescription($source['metaDescription']);

            $result[] = $blog;
        }
        return $result;
    }
}
