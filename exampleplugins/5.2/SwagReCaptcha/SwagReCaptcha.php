<?php

namespace SwagReCaptcha;

use Shopware\Components\Plugin;

class SwagReCaptcha extends Plugin
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Widgets_Captcha' => 'registerTemplatePath',
        ];
    }

    public function registerTemplatePath(\Enlight_Event_EventArgs $args)
    {
        $this->container->get('template')->addTemplateDir(
            $this->getPath() . '/Resources/views/'
        );
    }
}