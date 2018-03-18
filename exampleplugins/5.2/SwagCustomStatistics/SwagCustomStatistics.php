<?php

namespace SwagCustomStatistics;

use Enlight_Controller_ActionEventArgs as ActionEventArgs;
use Shopware\Components\Plugin;

class SwagCustomStatistics extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Analytics' => 'onPostDispatchBackendAnalytics',
        ];
    }

    /**
     * @param ActionEventArgs $args
     */
    public function onPostDispatchBackendAnalytics(ActionEventArgs $args)
    {
        $request = $args->getRequest();
        $view = $args->getSubject()->View();

        $view->addTemplateDir($this->getPath() . '/Resources/views/');

        if ($request->getActionName() === 'index') {
            $view->extendsTemplate('backend/analytics/swag_custom_statistics/app.js');
        }

        if ($request->getActionName() === 'load') {
            $view->extendsTemplate('backend/analytics/swag_custom_statistics/store/navigation.js');
        }
    }
}
