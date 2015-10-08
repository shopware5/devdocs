<?php

class Shopware_Plugins_Backend_SwagProduct_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getInfo()
    {
        return array(
            'label' => 'Shopware Produktübersicht'
        );
    }

    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_SwagProduct',
            'getBackendController'
        );

        $this->createMenuItem(array(
            'label' => 'Shopware Produktübersicht',
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
            $em->getClassMetadata('Shopware\CustomModels\Product\Product')
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
            $em->getClassMetadata('Shopware\CustomModels\Product\Product')
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
            INSERT IGNORE INTO s_product (id, name, active, description, descriptionLong, lastStock, createDate)
            SELECT
                a.id,
                a.name,
                a.active,
                a.description,
                a.description_long as descriptionLong,
                a.laststock as lastStock,
                a.datum as createDate
            FROM s_articles a
        ";
        Shopware()->Db()->query($sql);
    }
}