<?php

namespace SwagSearchBundle\SearchBundleDBAL\Facet;

use Shopware\Bundle\SearchBundle\Criteria;
use Shopware\Bundle\SearchBundle\FacetInterface;
use Shopware\Bundle\SearchBundle\FacetResult\BooleanFacetResult;
use Shopware\Bundle\SearchBundleDBAL\FacetHandlerInterface;
use Shopware\Bundle\SearchBundleDBAL\QueryBuilderFactory;
use Shopware\Bundle\StoreFrontBundle\Struct;

class EsdFacetHandler implements FacetHandlerInterface
{
    /**
     * @var QueryBuilderFactory
     */
    private $queryBuilderFactory;

    /**
     * @var \Shopware_Components_Snippet_Manager
     */
    private $snippetManager;

    /**
     * @param QueryBuilderFactory $queryBuilderFactory
     * @param \Shopware_Components_Snippet_Manager $snippetManager
     */
    public function __construct(
        QueryBuilderFactory $queryBuilderFactory,
        \Shopware_Components_Snippet_Manager $snippetManager
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->snippetManager = $snippetManager;
    }

    /**
     * @inheritdoc
     */
    public function supportsFacet(FacetInterface $facet)
    {
        return ($facet instanceof EsdFacet);
    }


    /**
     * @inheritdoc
     */
    public function generateFacet(
        FacetInterface $facet,
        Criteria $criteria,
        Struct\ShopContextInterface $context
    ) {
        //resets all conditions of the criteria to execute a facet query without user filters.
        $queryCriteria = clone $criteria;
        $queryCriteria->resetConditions();
        $queryCriteria->resetSorting();

        $query = $this->queryBuilderFactory->createQuery($queryCriteria, $context);

        $query->innerJoin(
            'product',
            's_articles_esd',
            'esd',
            'esd.articleID = product.id'
        );

        $query->select('product.id')->setMaxResults(1);

        /**@var $statement \Doctrine\DBAL\Driver\ResultStatement */
        $statement = $query->execute();

        $total = $statement->fetch(\PDO::FETCH_COLUMN);

        //found some esd products?
        if ($total <= 0) {
            return null;
        }

        $snippetNamespace = $this->snippetManager->getNamespace('frontend/listing/facet_labels');

        return new BooleanFacetResult(
            $facet->getName(),
            'esd',
            $criteria->hasCondition($facet->getName()),
            $snippetNamespace->get('swag_search_bundle_esd_only', 'Only downloads')
        );
    }
}
