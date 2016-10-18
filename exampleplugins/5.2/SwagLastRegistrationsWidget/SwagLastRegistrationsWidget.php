<?php

namespace SwagLastRegistrationsWidget;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Models\Widget\Widget;

class SwagLastRegistrationsWidget extends Plugin
{

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Backend_SwagLastRegistrationsWidget' => 'onGetBackendControllerPath',
            'Enlight_Controller_Action_PostDispatch_Backend_Index' => 'onPostDispatchBackendIndex'
        ];
    }

    /**
     * @return string
     */
    public function onGetBackendControllerPath()
    {
        return __DIR__ . '/Controllers/Backend/SwagLastRegistrationsWidget.php';
    }

    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        $plugin = $context->getPlugin();
        $widget = new Widget();

        $widget->setName('swag-last-registrations');
        $widget->setPlugin($plugin);
        $plugin->getWidgets()->add($widget);

        parent::install($context);
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context)
    {
        $plugin = $context->getPlugin();
        $em = $this->container->get('models');
        $widget = $plugin->getWidgets()->first();

        $em->remove($widget);
        $em->flush();

        parent::uninstall($context);
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function onPostDispatchBackendIndex(\Enlight_Controller_ActionEventArgs $args)
    {
        $request = $args->getRequest();
        $view = $args->getSubject()->View();

        $view->addTemplateDir($this->getPath() . '/Resources/views');

        // if the controller action name equals "index" we have to extend the backend article application
        if ($request->getActionName() === 'index') {
            $view->extendsTemplate('backend/index/swag_last_registrations/app.js');
        }
    }
}
