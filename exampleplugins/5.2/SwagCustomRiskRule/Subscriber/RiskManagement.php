<?php

namespace SwagCustomRiskRule\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;

class RiskManagement implements SubscriberInterface
{
    /*
     * @var  $pluginPath string
     */
    private $pluginPath;

    /**
     * @param $pluginPath
     */
    public function __construct($pluginPath)
    {
        $this->pluginPath = $pluginPath;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Backend_RiskManagement' => 'onRiskManagementBackend'
        ];
    }


    public function onRiskManagementBackend(Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Customer $controller */
        $controller = $args->get('subject');

        $view = $controller->View();
        $request = $controller->Request();

        $view->addTemplateDir($this->pluginPath . '/Resources/views');

        if ($request->getActionName() == 'load') {
            $view->extendsTemplate('backend/risk_management/store/risks.js');
        }
    }
}
