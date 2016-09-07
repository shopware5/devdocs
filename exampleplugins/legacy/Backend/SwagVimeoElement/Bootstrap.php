<?php

/**
 * Shopware VimeoElement Example Plugin - Bootstrap
 *
 * This is an example plugin from https://developers.shopware.com/developers-guide/custom-shopping-world-elements/
 *
 * @copyright Copyright (c) shopware AG (https://de.shopware.com/)
 */
class Shopware_Plugins_Backend_SwagVimeoElement_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getLabel()
    {
        return 'Vimeo Element';
    }

    public function getVersion()
    {
        return "1.0.0";
    }

    public function install()
    {
        /**
         * Create the main component for the emotion element.
         */
        $vimeoElement = $this->createEmotionComponent([
            'name' => 'Vimeo Video',
            'xtype' => 'emotion-components-vimeo',
            'template' => 'emotion_vimeo',
            'cls' => 'emotion-vimeo-element',
            'description' => 'A simple vimeo video element for the shopping worlds.'
        ]);

        $vimeoElement->createTextField([
            'name' => 'vimeo_video_id',
            'fieldLabel' => 'Video ID',
            'supportText' => 'Enter the ID of the video you want to embed.',
            'allowBlank' => false
        ]);

        $vimeoElement->createHiddenField([
            'name' => 'vimeo_video_thumbnail'
        ]);

        $vimeoElement->createTextField([
            'name' => 'vimeo_interface_color',
            'fieldLabel' => 'Interface Color',
            'supportText' => 'Enter the #hex color code for the video player interface.',
            'defaultValue' => '#0096FF'
        ]);

        $vimeoElement->createCheckboxField([
            'name' => 'vimeo_autoplay',
            'fieldLabel' => 'Autoplay',
            'defaultValue' => false
        ]);

        $vimeoElement->createCheckboxField([
            'name' => 'vimeo_loop',
            'fieldLabel' => 'Loop',
            'defaultValue' => false
        ]);

        $vimeoElement->createCheckboxField([
            'name' => 'vimeo_show_title',
            'fieldLabel' => 'Show title',
            'defaultValue' => false
        ]);

        $vimeoElement->createCheckboxField([
            'name' => 'vimeo_show_portrait',
            'fieldLabel' => 'Show portrait',
            'defaultValue' => false
        ]);

        $vimeoElement->createCheckboxField([
            'name' => 'vimeo_show_author',
            'fieldLabel' => 'Show author',
            'defaultValue' => false
        ]);

        /**
         * Subscribe to the post dispatch event of the emotion backend module to extend the components.
         */
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Emotion',
            'onPostDispatchBackendEmotion'
        );

        return true;
    }

    /**
     * Extends the backend template to add the grid component for the emotion designer.
     *
     * @param Enlight_Controller_ActionEventArgs $args
     */
    public function onPostDispatchBackendEmotion(Enlight_Controller_ActionEventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Emotion $controller */
        $controller = $args->getSubject();
        $view = $controller->View();

        $view->addTemplateDir($this->Path() . 'Views/');
        $view->extendsTemplate('backend/emotion/swag_vimeo_element/view/detail/elements/vimeo_video.js');
    }

    public function uninstall()
    {
        return true;
    }

    public function enable()
    {
        return [
            'success' => true,
            'invalidateCache' => ['backend', 'frontend', 'theme']
        ];
    }

    public function disable()
    {
        return [
            'success' => true,
            'invalidateCache' => ['backend', 'frontend', 'theme']
        ];
    }
}