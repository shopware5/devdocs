<?php

class Shopware_Plugins_Frontend_SwagSloganOfTheDay_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getVersion()
    {
        return '1.0.0';
    }

    public function getLabel()
    {
        return 'Slogan of the day';
    }

    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend',
            'onFrontendPostDispatch'
        );

        $this->createConfig();

        return true;
    }

    private function createConfig()
    {
        $this->Form()->setElement(
            'select',
            'font-size',
            array(
                'label' => 'Font size',
                'store' => array(
                    array(12, '12px'),
                    array(18, '18px'),
                    array(25, '25px')
                ),
                'value' => 12
            )
        );

        $this->Form()->setElement('boolean', 'italic', array(
            'value' => true,
            'label' => 'Italic'
        ));
    }

    public function onFrontendPostDispatch(Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();

        $view->addTemplateDir(
            __DIR__ . '/Views'
        );

        $view->assign('sloganSize', $this->Config()->get('font-size'));
        $view->assign('italic', $this->Config()->get('italic'));
        $view->assign('slogan', $this->getSlogan());
    }

    public function getSlogan()
    {
        return array_rand(
            array_flip(
                array(
                    'An apple a day keeps the doctor away',
                    'Let’s get ready to rumble',
                    'A rolling stone gathers no moss',
                )
            )
        );
    }
}
