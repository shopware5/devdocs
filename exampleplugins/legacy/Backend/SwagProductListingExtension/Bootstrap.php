<?php

class Shopware_Plugins_Backend_SwagProductListingExtension_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getInfo()
    {
        return array(
            'label' => 'Shopware Product Overview - Listing Extension'
        );
    }

    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_SwagProduct',
            'getBackendController'
        );

        $this->createMenuItem(array(
            'label' => 'Shopware Product Overview - Listing Extension',
            'controller' => 'SwagProduct',
            'class' => 'sprite-application-block',
            'action' => 'Index',
            'active' => 1,
            'parent' => $this->Menu()->findOneBy(['label' => 'Marketing'])
        ));

        $this->updateSchema();

        return true;
    }

    protected function updateSchema()
    {
        $this->registerCustomModels();

        $em = $this->Application()->Models();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array(
            $em->getClassMetadata('Shopware\CustomModels\Product\Product'),
            $em->getClassMetadata('Shopware\CustomModels\Product\Variant'),
            $em->getClassMetadata('Shopware\CustomModels\Product\Attribute')
        );

        try {
            $tool->dropSchema($classes);
        } catch (Exception $e) {
            //ignore
        }
        $tool->createSchema($classes);

        $this->addDemoData();
    }

    public function uninstall()
    {
        $this->registerCustomModels();

        $em = $this->Application()->Models();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array(
            $em->getClassMetadata('Shopware\CustomModels\Product\Product'),
            $em->getClassMetadata('Shopware\CustomModels\Product\Variant'),
            $em->getClassMetadata('Shopware\CustomModels\Product\Attribute')
        );
        $tool->dropSchema($classes);

        return true;
    }

    public function getBackendController(Enlight_Event_EventArgs $args)
    {
        $this->Application()->Template()->addTemplateDir(
            $this->Path() . 'Views/'
        );

        $this->registerCustomModels();

        return $this->Path() . '/Controllers/Backend/SwagProduct.php';
    }

    protected function addDemoData()
    {
        $sql = "
            INSERT IGNORE INTO s_product (id, name, active, description, descriptionLong, lastStock, createDate, tax_id)
            SELECT
                a.id,
                a.name,
                a.active,
                a.description,
                a.description_long as descriptionLong,
                a.laststock as lastStock,
                a.datum as createDate,
                a.taxID as tax_id
            FROM s_articles a
        ";
        Shopware()->Db()->query($sql);

        $sql = "
            SET FOREIGN_KEY_CHECKS = 0;
            INSERT IGNORE INTO s_product_variant (id, product_id, number, additionalText, active, inStock, stockMin, weight)
            SELECT
              a.id,
              a.articleID,
              a.ordernumber,
              a.additionaltext,
              a.active,
              a.instock,
              a.stockmin,
              a.weight
            FROM s_articles_details a
        ";
        Shopware()->Db()->query($sql);

        $sql = "
            SET FOREIGN_KEY_CHECKS = 0;
            INSERT IGNORE INTO s_product_attribute
            SELECT
              a.id,
              a.articleID as product_id,
              a.attr1,
              a.attr2,
              a.attr3,
              a.attr4,
              a.attr5
            FROM s_articles_attributes a
        ";
        Shopware()->Db()->query($sql);

        $sql = "
            SET FOREIGN_KEY_CHECKS = 0;
            INSERT IGNORE INTO s_product_categories (product_id, category_id)
            SELECT
              a.articleID as product_id,
              a.categoryID as category_id
            FROM s_articles_categories a
        ";
        Shopware()->Db()->query($sql);
    }
}
