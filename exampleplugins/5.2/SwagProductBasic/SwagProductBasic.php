<?php

namespace SwagProductBasic;

use Doctrine\ORM\Tools\SchemaTool;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use SwagProductBasic\Models\Product;

class SwagProductBasic extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public function install(InstallContext $installContext)
    {
        $this->createDatabase();

        $this->addDemoData();
    }

    /**
     * {@inheritdoc}
     */
    public function activate(ActivateContext $activateContext)
    {
        $activateContext->scheduleClearCache(InstallContext::CACHE_LIST_ALL);
    }

    public function uninstall(UninstallContext $uninstallContext)
    {
        if (!$uninstallContext->keepUserData()) {
            $this->removeDatabase();
        }
    }

    private function createDatabase()
    {
        $modelManager = $this->container->get('models');
        $tool = new SchemaTool($modelManager);

        $classes = [
            $modelManager->getClassMetadata(Product::class)
        ];

        $tool->updateSchema($classes, true); // make sure to use the save mode
    }

    private function removeDatabase()
    {
        $modelManager = $this->container->get('models');
        $tool = new SchemaTool($modelManager);

        $classes = [
            $modelManager->getClassMetadata(Product::class)
        ];

        $tool->dropSchema($classes);
    }

    private function addDemoData()
    {
        $sql = "
            INSERT IGNORE INTO s_product (`name`, active, description, descriptionLong, lastStock, createDate)
            SELECT
                a.name,
                a.active,
                a.description,
                a.description_long as descriptionLong,
                a.laststock as lastStock,
                a.datum as createDate
            FROM s_articles a
        ";

        $this->container->get('dbal_connection')->exec($sql);
    }
}