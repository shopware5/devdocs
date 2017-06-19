<?php

namespace SwagAttributeSlider;

use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;

class SwagAttributeSlider extends Plugin
{
    /**
     * @param InstallContext $context
     */
    public function install(InstallContext $context)
    {
        /** @var CrudService $crud */
        $crud = $this->container->get('shopware_attribute.crud_service');

        $crud->update('s_user_attributes', 'recommendedVariants', 'multi_selection', [
            'displayInBackend' => true,
            'label' => 'Recommended variants',
            'entity' => 'Shopware\Models\Article\Detail',
        ]);
        $crud->update('s_user_attributes', 'recommendedStream', 'single_selection', [
            'displayInBackend' => true,
            'label' => 'Recommended stream',
            'entity' => 'Shopware\Models\ProductStream\ProductStream',
        ]);
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Frontend_Account' => 'onPostDispatchAccount',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onPostDispatchAccount(\Enlight_Event_EventArgs $args)
    {
        /** @var $controller \Shopware_Controllers_Frontend_Account */
        $controller = $args->get('subject');
        $controller->View()->addTemplateDir($this->getPath() . '/Resources/views');
    }
}
