<?php

namespace Shopware\SwagDigitalPublishingSample\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Theme\LessDefinition;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Resources
 * @package Shopware\DigitalPublishingSample\Subscriber
 */
class Resources implements SubscriberInterface
{
    /**
     * @var \Shopware_Plugins_Backend_SwagDigitalPublishingSample_Bootstrap
     */
    protected $bootstrap;

    /**
     * Subscriber class constructor.
     *
     * @param \Shopware_Plugins_Backend_SwagDigitalPublishingSample_Bootstrap $bootstrap
     */
    public function __construct(\Shopware_Plugins_Backend_SwagDigitalPublishingSample_Bootstrap $bootstrap)
    {
        /**
         * Set the bootstrap class for later use.
         */
        $this->bootstrap = $bootstrap;
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
            'Enlight_Controller_Action_PostDispatchSecure_Widgets_SwagDigitalPublishing' => 'onPostDispatchFrontend',
            'Enlight_Controller_Action_PostDispatchSecure_Widgets_Emotion' => 'onPostDispatchFrontend',
            'SwagDigitalPublishing_ContentBanner_FilterResult' => 'onContentBannerFilter',
            'Theme_Compiler_Collect_Plugin_Less' => 'onAddLessFiles',
        );
    }

    /**
     * Extends the backend templates with the necessary template files.
     *
     * @param \Enlight_Event_EventArgs $args
     */
    public function onPostDispatchBackend(\Enlight_Event_EventArgs $args)
    {
        $subject = $args->getSubject();
        $view = $subject->View();

        $view->addTemplateDir($this->bootstrap->Path() . 'Views/');
        $view->extendsTemplate('backend/swag_digital_publishing_sample/view/editor/extension.js');
        $view->extendsTemplate('backend/swag_digital_publishing_sample/view/editor/elements/youtube_element_handler.js');
    }

    /**
     * Adds the template directory of the plugin to extend the frontend templates.
     *
     * @param \Enlight_Event_EventArgs $args
     */
    public function onPostDispatchFrontend(\Enlight_Event_EventArgs $args)
    {
        $subject = $args->getSubject();
        $view = $subject->View();

        $view->addTemplateDir($this->bootstrap->Path() . 'Views/');
    }

    /**
     * Returns a collection of less files you want to add to the theme compiler.
     *
     * @return ArrayCollection
     */
    public function onAddLessFiles()
    {
        $less = new LessDefinition(array(), array( __DIR__ . '/../Views/frontend/_public/src/less/all.less'), __DIR__);

        return new ArrayCollection(array($less));
    }

    /**
     * Filter event for the banner elements of the Digital Publishing module.
     * Enables you to manipulate the banner data before it gets passed to the frontend.
     *
     * @param \Enlight_Event_EventArgs $args
     * @return mixed
     */
    public function onContentBannerFilter(\Enlight_Event_EventArgs $args)
    {
        $banner = $args->getReturn();

        // Do some magic data manipulation

        return $banner;
    }
}