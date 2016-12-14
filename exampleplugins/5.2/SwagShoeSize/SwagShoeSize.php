<?php

namespace SwagShoeSize;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;

/**
 * A simple plugin that shows the usage of attributes in the frontend.
 *
 * @package SwagShoeSize
 */
class SwagShoeSize extends Plugin
{
    /**
     * @param InstallContext $context
     * @return bool
     */
    public function install(InstallContext $context)
    {
        $service = $this->container->get('shopware_attribute.crud_service');

        $service->update('s_user_attributes', 'swag_shoesize', 'string', [
            'label' => 'Shoesize',
            'displayInBackend' => true
        ]);

        return true;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onFrontendPostDispatch'
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function onFrontendPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        $view = $args->getSubject()->View();
        $view->addTemplateDir($this->getPath() . '/Resources/views');
    }
}
