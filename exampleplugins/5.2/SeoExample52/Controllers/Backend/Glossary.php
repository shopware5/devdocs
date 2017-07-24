<?php

use Doctrine\DBAL\Query\QueryBuilder;

class Shopware_Controllers_Backend_Glossary extends Shopware_Controllers_Backend_ExtJs
{
    public function generateSeoUrlAction()
    {
        $shopId = $this->Request()->getParam('shopId');

        $offset = $this->Request()->getParam('offset');
        $limit = $this->Request()->getParam('limit', 50);

        /** @var Shopware_Components_SeoIndex $seoIndex */
        $seoIndex = $this->container->get('SeoIndex');
        $seoIndex->registerShop($shopId);

        /** @var sRewriteTable $rewriteTableModule */
        $rewriteTableModule = $this->container->get('modules')->RewriteTable();
        $rewriteTableModule->baseSetup();
        $rewriteTableModule->sInsertUrl('sViewport=glossary', 'glossary/');

        /** @var QueryBuilder $dbalQueryBuilder */
        $dbalQueryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
        $words = $dbalQueryBuilder->select('glossary.id, glossary.word')
            ->from('s_glossary', 'glossary')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);

        foreach ($words as $wordId => $word) {
            $rewriteTableModule->sInsertUrl('sViewport=glossary&sAction=detail&wordId=' . $wordId, 'glossary/' . $word);
        }

        $this->View()->assign(['success' => true]);
    }
}