<?php declare(strict_types=1);
class Shopware_Controllers_Frontend_B2bAjaxPanel extends Enlight_Controller_Action
{
    public function preDispatch()
    {
        $path = __DIR__ . '/../B2bAjaxPanel';
        $this->get('template')->addTemplateDir($path . '/Resources/views/');
        $this->get('snippets')->addConfigDir($path . '/Resources/snippets/');
    }

    public function indexAction()
    {
        // nothing to do
    }

    public function navAction()
    {
        // nothing to do
    }

    public function subAction()
    {
        $this->View()->assign('isPost', $this->Request()->isPost());
        $this->View()->assign('name', $this->Request()->getParam('name', 'nobody'));
    }
}
