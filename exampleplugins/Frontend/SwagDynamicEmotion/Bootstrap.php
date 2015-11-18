<?php

/**
 * The Bootstrap class is the main entry point of any shopware plugin.
 *
 * Short function reference
 * - install: Called a single time during (re)installation. Here you can trigger install-time actions like
 *   - creating the menu
 *   - creating attributes
 *   - creating database tables
 *   You need to return "true" or array('success' => true, 'invalidateCache' => array()) in order to let the installation
 *   be successfull
 *
 * - update: Triggered when the user updates the plugin. You will get passes the former version of the plugin as param
 *   In order to let the update be successful, return "true"
 *
 * - uninstall: Triggered when the plugin is reinstalled or uninstalled. Clean up your tables here.
 */
class Shopware_Plugins_Frontend_SwagDynamicEmotion_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getVersion()
    {
        $info = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'plugin.json'), true);
        if ($info) {
            return $info['currentVersion'];
        } else {
            throw new Exception('The plugin has an invalid version file.');
        }
    }

    public function getLabel()
    {
        return 'SwagDynamicEmotion';
    }


    /**
     * Setup everything we need
     *
     * @return bool
     */
    public function install()
    {
        if (!$this->assertMinimumVersion('4.3.0')) {
            throw new \RuntimeException('At least Shopware 4.3.0 is required');
        }

        $this->addAttribute();
        $this->registerMyComponents();
        $this->registerCustomModels();
        (new \Shopware\SwagDynamicEmotion\Bootstrap\EmotionHelper($this))->create();

        $this->createMyMenu();
        $this->registerBaseEvent();

        $this->updateSchema();
        (new \Shopware\SwagDynamicEmotion\Bootstrap\StoreDemoData())->create();
        return true;
    }

    /**
     * Creates the database scheme from an existing doctrine model.
     *
     * Will remove the table first, so handle with care.
     */
    protected function updateSchema()
    {
        $this->registerCustomModels();

        $em = Shopware()->Models();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array(
            $em->getClassMetadata('Shopware\CustomModels\SwagDynamicEmotion\Store')
        );

        try {
            $tool->dropSchema($classes);
        } catch (Exception $e) {
            //ignore
        }
        $tool->createSchema($classes);
    }

    /**
     * Remove custom table
     *
     * @return bool
     */
    public function uninstall()
    {
        $this->registerCustomModels();

        $em = Shopware()->Models();
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $classes = array(
            $em->getClassMetadata('Shopware\CustomModels\SwagDynamicEmotion\Store')
        );
        $tool->dropSchema($classes);

        return true;
    }

    /**
     * This callback function is triggered at the very beginning of the dispatch process and allows
     * us to register additional events on the fly. This way you won't ever need to reinstall you
     * plugin for new events - any event and hook can simply be registerend in the event subscribers
     */
    public function onStartDispatch(Enlight_Event_EventArgs $args)
    {
        $this->registerMyComponents();
        $this->registerCustomModels();

        Shopware()->Template()->addTemplateDir(__DIR__ . '/Views');


        $subscribers = array(
            new \Shopware\SwagDynamicEmotion\Subscriber\Emotion(),
            new \Shopware\SwagDynamicEmotion\Subscriber\Container(Shopware()->Container()),
            new \Shopware\SwagDynamicEmotion\Subscriber\Controllers()

        );

        foreach ($subscribers as $subscriber) {
            Shopware()->Events()->addSubscriber($subscriber);
        }
    }

    /**
     * Composer + Loader
     */
    public function registerMyComponents()
    {
        Shopware()->Loader()->registerNamespace(
            'Shopware\SwagDynamicEmotion',
            $this->Path()
        );
    }


    /**
     * This attribute will indicate, which shopping world is the "store" one
     */
    protected function addAttribute()
    {
        Shopware()->Models()->addAttribute(
            's_emotion_attributes',
            'swag',
            'shop_template',
            'INT(1)'
        );
        Shopware()->Models()->generateAttributeModels(
            [
                's_emotion_attributes'
            ]
        );
    }

    /**
     * The backend menu entry to start the store manager
     */
    protected function createMyMenu()
    {
        $this->createMenuItem(
            array(
                'label' => 'Store Manager',
                'controller' => 'SwagStore',
                'class' => 'sprite-application-block',
                'action' => 'Index',
                'active' => 1,
                'parent' => $this->Menu()->findOneBy('controller', 'content')
            )
        );
    }

    /**
     * Register early event in order to register subscribers then
     */
    protected function registerBaseEvent()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Front_DispatchLoopStartup',
            'onStartDispatch'
        );
    }
}
