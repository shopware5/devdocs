<?php

class Shopware_Plugins_Backend_SwagExtendCustomer_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getVersion()
    {
        return '1.0.0';
    }

    public function getLabel()
    {
        return 'Extend customer module';
    }

    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Customer',
            'onCustomerPostDispatch'
        );

        return true;
    }

    public function onCustomerPostDispatch(Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->getSubject();
        $view = $controller->View();
        $request = $controller->Request();

        $view->addTemplateDir(__DIR__ . '/Views');

        if ($request->getActionName() == 'index') {
            $view->extendsTemplate('backend/swag_extend_customer/app.js');
        }

        if ($request->getActionName() == 'load') {
            $view->extendsTemplate('backend/swag_extend_customer/view/detail/billing.js');
            $view->extendsTemplate('backend/swag_extend_customer/view/detail/window.js');
        }
    }

}