<?php

class Shopware_Controllers_Frontend_SwagControllerTest extends Enlight_Controller_Action
{
    public function indexAction()
    {
        /**
         * The template will be automatically be loaded from the module name and the controller name:
         *
         * module => frontend
         * controller => SwagControllerTest becomes swag_controller_test
         * action => index
         *
         * So Shopware will look for a template called frontend/swag_controller_test/index.tpl in your Views folder
         *
         * You can load another template using $this->loadTemplate();
         *
         */
        $this->View()->assign('someNumber', 5);
    }
}
