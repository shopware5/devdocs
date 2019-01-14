<?php

namespace SeoExample;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class SeoExample extends \Shopware\Components\Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'Shopware_CronJob_RefreshSeoIndex_CreateRewriteTable' => 'createGlossaryRewriteTable',
            'sRewriteTable::sCreateRewriteTable::after' => 'createGlossaryRewriteTable',
            'Enlight_Controller_Action_PostDispatch_Backend_Performance' => 'loadPerformanceExtension',
            'Shopware_Controllers_Seo_filterCounts' => 'addGlossaryCount',
            'Shopware_Components_RewriteGenerator_FilterQuery' => 'filterParameterQuery'
        ];
    }

    public function install(InstallContext $context)
    {
        /** @var Connection $dbalConnection */
        $dbalConnection = $this->container->get('dbal_connection');
        $dbalConnection->exec(
            'CREATE TABLE IF NOT EXISTS`s_glossary` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `word` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
             `description` longtext COLLATE utf8_unicode_ci NOT NULL,
             PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
        );

        $dbalConnection->exec('INSERT IGNORE INTO `s_glossary` (`word`, `description`) VALUES
            (\'Foobar\', \'The terms foobar (/ˈfuːbɑːr/), or foo and others are used as placeholder names (also referred to as metasyntactic variables) in computer programming or computer-related documentation. They have been used to name entities such as variables, functions, and commands whose exact identity is unimportant and serve only to demonstrate a concept.\'),
            (\'Recursion\', \'Recursion occurs when a thing is defined in terms of itself or of its type. Recursion is used in a variety of disciplines ranging from linguistics to logic. The most common application of recursion is in mathematics and computer science, where a function being defined is applied within its own definition. While this apparently defines an infinite number of instances (function values), it is often done in such a way that no loop or infinite chain of references can occur.\');');

        parent::install($context);
    }

    public function uninstall(UninstallContext $context)
    {
        if (!$context->keepUserData()) {
            /** @var Connection $dbalConnection */
            $dbalConnection = $this->container->get('dbal_connection');
            $dbalConnection->exec('DROP TABLE s_glossary');
        }

        parent::uninstall($context);
    }

    public function createGlossaryRewriteTable()
    {
        /** @var \sRewriteTable $rewriteTableModule */
        $rewriteTableModule = Shopware()->Container()->get('modules')->sRewriteTable();
        $rewriteTableModule->sInsertUrl('sViewport=glossary', 'glossary/');

        /** @var QueryBuilder $dbalQueryBuilder */
        $dbalQueryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();

        $words = $dbalQueryBuilder->select('glossary.id, glossary.word')
            ->from('s_glossary', 'glossary')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);

        foreach ($words as $wordId => $word) {
            $rewriteTableModule->sInsertUrl('sViewport=glossary&sAction=detail&wordId=' . $wordId, 'glossary/' . $word);
        }
    }

    public function loadPerformanceExtension(\Enlight_Controller_ActionEventArgs $args)
    {
        $subject = $args->getSubject();
        $request = $subject->Request();

        if ($request->getActionName() !== 'load') {
            return;
        }

        $subject->View()->addTemplateDir($this->getPath() . '/Resources/views/');
        $subject->View()->extendsTemplate('backend/performance/view/glossary.js');
    }

    public function addGlossaryCount(\Enlight_Event_EventArgs $args)
    {
        $counts = $args->getReturn();

        /** @var QueryBuilder $dbalQueryBuilder */
        $dbalQueryBuilder = $this->container->get('dbal_connection')->createQueryBuilder();
        $wordsCount = $dbalQueryBuilder->select('COUNT(glossary.id)')
            ->from('s_glossary', 'glossary')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $counts['glossary'] = $wordsCount;

        return $counts;
    }

    public function filterParameterQuery(\Enlight_Event_EventArgs $args)
    {
        $orgQuery = $args->getReturn();
        $query = $args->getQuery();

        if ($query['controller'] === 'glossary' && isset($query['wordId'])) {
            $orgQuery['wordId'] = $query['wordId'];
        }

        return $orgQuery;
    }
}