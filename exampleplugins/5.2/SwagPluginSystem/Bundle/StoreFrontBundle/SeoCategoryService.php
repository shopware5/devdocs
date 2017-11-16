<?php

namespace SwagPluginSystem\Bundle\StoreFrontBundle;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\StoreFrontBundle\Service\CategoryServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct\Category;
use Shopware\Bundle\StoreFrontBundle\Struct\ListProduct;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class SeoCategoryService
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var CategoryServiceInterface
     */
    private $categoryService;

    /**
     * @param Connection $connection
     * @param CategoryServiceInterface $categoryService
     */
    public function __construct(Connection $connection, CategoryServiceInterface $categoryService)
    {
        $this->connection = $connection;
        $this->categoryService = $categoryService;
    }

    /**
     * @param ListProduct[] $listProducts
     * @param ShopContextInterface $context
     * @return Category[] indexed by product id
     */
    public function getList($listProducts, ShopContextInterface $context)
    {
        $ids = array_map(function (ListProduct $product) {
            return $product->getId();
        }, $listProducts);

        //select all seo category ids, indexed by product id
        $ids = $this->getCategoryIds($ids, $context);

        //now select all category data for the selected ids
        $categories = $this->categoryService->getList($ids, $context);

        $result = [];
        foreach ($ids as $productId => $categoryId) {
            if (!isset($categories[$categoryId])) {
                continue;
            }
            $result[$productId] = $categories[$categoryId];
        }

        return $result;
    }

    /**
     * @param $ids
     * @param $context
     * @return array
     */
    private function getCategoryIds($ids, ShopContextInterface $context)
    {
        $query = $this->connection->createQueryBuilder();
        $query->select(['seoCategories.article_id', 'seoCategories.category_id'])
            ->from('s_articles_categories_seo', 'seoCategories')
            ->andWhere('seoCategories.article_id IN (:productIds)')
            ->andWhere('seoCategories.shop_id = :shopId')
            ->setParameter(':shopId', $context->getShop()->getId())
            ->setParameter(':productIds', $ids, Connection::PARAM_INT_ARRAY);

        return $query->execute()->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
