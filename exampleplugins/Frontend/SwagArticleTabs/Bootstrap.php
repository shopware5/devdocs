<?php

class Shopware_Plugins_Frontend_SwagArticleTabs_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getLabel()
    {
        return 'Article detail tabs';
    }

    public function getInfo()
    {
        return array(
            'label' => $this->getLabel(),
            'version' => $this->getVersion(),
            'link' => 'http://www.shopware.de'
        );
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function install()
    {
        $this->createConfiguration();

        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatch_Frontend_Detail',
            'onPostDispatchDetail'
        );
        return true;
    }

    private function createConfiguration()
    {
        $attributes = $this->getArticleAttributes();
        foreach ($attributes as &$attribute) {
            $attribute = array($attribute, 'Freitextfeld - ' . $attribute);
        }

        $store = array(array('0', 'Keins'));
        $store = array_merge($store, $attributes);

        $form = $this->Form();

        for ($i = 1; $i < 11; $i++) {
            $form->setElement('text', 'tab' . $i . '-headline', array(
                'label' => 'Tab ' . $i . ' Headline'
            ));

            $form->setElement('select', 'tab' . $i,
                array('label' => 'Tab ' . $i . ' Mapping', 'store' => $store, 'style' => 'margin-bottom: 20px')
            );
        }
    }

    private function getArticleAttributes()
    {
        $sql = "SELECT * FROM s_articles_attributes LIMIT 0, 1";
        $cols = Shopware()->Db()->fetchRow($sql);
        $fields = array();

        foreach ($cols as $col => $value) {
            if (strpos('col_' . $col, 'attr') !== false) {
                $fields[] = $col;
            }
        }

        return $fields;
    }

    public function onPostDispatchDetail(Enlight_Event_EventArgs $arguments)
    {
        /**@var $controller Shopware_Controllers_Frontend_Detail */
        $controller = $arguments->getSubject();

        $view = $controller->View();

        //Add plugin template directory
        $view->addTemplateDir($this->Path() . 'Views/');

        $config = $this->Config()->toArray();
        $data = array();

        $sArticle = $view->getAssign('sArticle');

        for ($i = 1; $i < 10; $i++) {
            $key = 'tab' . $i;
            $data[$key] = array('headline' => $config[$key . '-headline'], 'content' => $sArticle[$config[$key]]);
        }

        $view->assign('swagArticleTabConfiguration', $data);
    }
}