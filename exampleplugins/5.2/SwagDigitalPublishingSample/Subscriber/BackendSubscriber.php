<?php

namespace SwagDigitalPublishingSample\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Action;

/**
 * Class Resources
 * @package Shopware\DigitalPublishingSample\Subscriber
 */
class BackendSubscriber implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginBaseDirectory;

    /**
     * Subscriber class constructor.
     *
     * @param string $pluginBaseDirectory
     */
    public function __construct($pluginBaseDirectory)
    {
        $this->pluginBaseDirectory = $pluginBaseDirectory;
    }

    /**
     * Returns an array of events you want to subscribe to
     * and the names of the corresponding callback methods.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Action_PostDispatchSecure_Backend_SwagDigitalPublishing' => 'onPostDispatchBackend',
        );
    }

    /**
     * Extends the backend templates with the necessary template files.
     *
     * @param \Enlight_Event_EventArgs $args
     */
    public function onPostDispatchBackend(\Enlight_Event_EventArgs $args)
    {
        /** @var Enlight_Controller_Action $subject */
        $subject = $args->getSubject();
        $view = $subject->View();

        $view->addTemplateDir($this->pluginBaseDirectory . '/Resources/views/');
        $view->extendsTemplate('backend/swag_digital_publishing_sample/view/editor/extension.js');
        $view->extendsTemplate('backend/swag_digital_publishing_sample/view/editor/elements/youtube_element_handler.js');
    }
}
