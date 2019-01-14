<?php

namespace SwagCustomProductBoxLayout\Subscriber;

use Enlight\Event\SubscriberInterface;

class Frontend implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDirectory;

    /**
     * @param $pluginDirectory
     */
    public function __construct($pluginDirectory)
    {
        $this->pluginDirectory = $pluginDirectory;
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            'Theme_Inheritance_Template_Directories_Collected' => 'onCollectTemplateDirectories',
        );
    }

    public function onCollectTemplateDirectories(\Enlight_Event_EventArgs $args)
    {
        /** @var $controller \Enlight_Controller_Action */
        $directories = $args->getReturn();
        $directories[] = $this->pluginDirectory . '/Resources/views';
        $args->setReturn($directories);
    }
}