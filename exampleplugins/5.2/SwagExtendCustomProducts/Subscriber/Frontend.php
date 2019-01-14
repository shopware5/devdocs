<?php

namespace SwagExtendCustomProducts\Subscriber;

use Enlight\Event\SubscriberInterface;

class Frontend implements SubscriberInterface
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
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => 'extendFrontendDetail',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $arguments
     */
    public function extendFrontendDetail(\Enlight_Event_EventArgs $arguments)
    {
        /** @var \Shopware_Controllers_Frontend_Detail $subject */
        $subject = $arguments->get('subject');

        $view = $subject->View();

        $view->addTemplateDir($this->path . '/Resources/Views/');
    }
}
