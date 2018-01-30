<?php

class Shopware_Controllers_Frontend_Glossary extends Enlight_Controller_Action
{
    public function preDispatch()
    {
        $pluginBasePath = $this->container->getParameter('seo_example.plugin_dir');
        $this->View()->addTemplateDir($pluginBasePath . '/Resources/views');
    }

    public function indexAction()
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();

        $wordData = $queryBuilder->select('glossary.word, glossary.description')
            ->from('s_glossary', 'glossary')
            ->execute()
            ->fetchAll();

        $this->View()->assign('words', $wordData);
    }

    public function detailAction()
    {
        $wordId = $this->Request()->getParam('wordId');

        /** @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();

        $wordData = $queryBuilder->select('glossary.word, glossary.description')
            ->from('s_glossary', 'glossary')
            ->where('glossary.id = :id')
            ->setParameter(':id', $wordId)
            ->execute()
            ->fetch();

        $this->View()->assign($wordData);
    }
}