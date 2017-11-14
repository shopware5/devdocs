<?php

namespace SwagDigitalPublishingSample\Subscriber;

use Enlight\Event\SubscriberInterface;

/**
 * Class Resources
 * @package Shopware\DigitalPublishingSample\Subscriber
 */
class WidgetSubscriber implements SubscriberInterface
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
        /**
         * Set the bootstrap class for later use.
         */
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
            'Enlight_Controller_Action_PostDispatchSecure_Widgets_SwagDigitalPublishing' => 'onPostDispatchWidget',
            'Enlight_Controller_Action_PostDispatchSecure_Widgets_Emotion' => 'onPostDispatchWidget',
        );
    }

    /**
     * Adds the template directory of the plugin to extend the frontend templates.
     *
     * @param \Enlight_Event_EventArgs $args
     */
    public function onPostDispatchWidget(\Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Action $subject */
        $subject = $args->getSubject();
        $view = $subject->View();

        $view->addTemplateDir($this->pluginBaseDirectory . '/Resources/views/');
    }
}
