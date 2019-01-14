<?php

namespace SwagExtendCustomProducts\Subscriber;

use Enlight\Event\SubscriberInterface;

class Backend implements SubscriberInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $pluginPath
     */
    public function __construct($pluginPath)
    {
        $this->path = $pluginPath;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Backend_SwagCustomProducts' => 'extendBackendModule',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $arguments
     */
    public function extendBackendModule(\Enlight_Event_EventArgs $arguments)
    {
        /** @var \Shopware_Controllers_Backend_SwagCustomProducts $subject */
        $subject = $arguments->get('subject');

        $view = $subject->View();

        $view->addTemplateDir($this->path . '/Resources/Views/');

        if ($arguments->get('request')->getActionName() === 'index') {
            $view->extendsTemplate('backend/swag_custom_products/view/option/types/custom_type.js');
        }

        if ($arguments->get('request')->getActionName() === 'load') {
            $view->extendsTemplate('backend/swag_extend_custom_products/swag_custom_products/view/components/type_translator.js');
        }
    }
}
