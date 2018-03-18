<?php

namespace SwagGlobalVariables;

use Shopware\Components\Plugin;

class SwagGlobalVariables extends Plugin
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch',
            'Enlight_Controller_Action_PostDispatchSecure_Widgets' => 'onPostDispatch'
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function onPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        $args->getSubject()->View()->assign('sUserLoggedIn', Shopware()->Modules()->Admin()->sCheckUser());
    }
}
