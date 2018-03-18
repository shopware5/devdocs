<?php

namespace SwagVimeoElement\Bootstrap;

use Shopware\Components\Emotion\ComponentInstaller;

class EmotionElementInstaller
{
    /**
     * @var ComponentInstaller
     */
    private $emotionComponentInstaller;

    /**
     * @var string
     */
    private $pluginName;

    /**
     * @param string $pluginName
     * @param ComponentInstaller $emotionComponentInstaller
     */
    public function __construct($pluginName, ComponentInstaller $emotionComponentInstaller)
    {
        $this->emotionComponentInstaller = $emotionComponentInstaller;
        $this->pluginName = $pluginName;
    }

    public function install()
    {
        $vimeoElement = $this->emotionComponentInstaller->createOrUpdate(
            $this->pluginName,
            'SwagVimeoElement',
            [
                'name' => 'Vimeo Video',
                'xtype' => 'emotion-components-vimeo',
                'template' => 'emotion_vimeo',
                'cls' => 'emotion-vimeo-element',
                'description' => 'A simple vimeo video element for the shopping worlds.'
            ]
        );

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
    }
}