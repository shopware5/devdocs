<?php

use SwagProductAssoc\Models\Product;

class Shopware_Controllers_Backend_SwagProductAssoc extends Shopware_Controllers_Backend_Application
{
    protected $model = Product::class;
    protected $alias = 'product';

    protected function getListQuery()
    {
        $builder = parent::getListQuery();

        $builder->leftJoin('product.tax', 'tax');
        $builder->addSelect(array('tax'));

        return $builder;
    }

    protected function getDetailQuery($id)
    {
        $builder = parent::getDetailQuery($id);

        $builder->leftJoin('product.tax', 'tax')
                ->leftJoin('product.attribute', 'attribute');

        $builder->addSelect(array('tax', 'attribute'));

        return $builder;
    }

    protected function getAdditionalDetailData(array $data)
    {
        $data['categories'] = $this->getCategories($data['id']);
        $data['variants'] = array();
        return $data;
    }

    protected function getCategories($productId)
    {
        $builder = $this->getManager()->createQueryBuilder();
        $builder->select(array('products', 'categories'))
                ->from(Product::class, 'products')
                ->innerJoin('products.categories', 'categories')
                ->where('products.id = :id')
                ->setParameter('id', $productId);

        $paginator = $this->getQueryPaginator($builder);

        $data = $paginator->getIterator()->current();

        return $data['categories'];
    }
}
