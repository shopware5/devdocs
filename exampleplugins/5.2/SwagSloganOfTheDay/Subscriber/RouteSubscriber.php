<?php
namespace SwagSloganOfTheDay\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Plugin\ConfigReader;
use SwagSloganOfTheDay\Components\SloganPrinter;

class RouteSubscriber implements SubscriberInterface
{
    private $pluginDirectory;
    private $sloganPrinter;
    private $config;

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onPostDispatch'
        ];
    }

    public function __construct($pluginName, $pluginDirectory, SloganPrinter $sloganPrinter, ConfigReader $configReader)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->sloganPrinter = $sloganPrinter;

        $this->config = $configReader->getByPluginName($pluginName);
    }

    public function onPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        /** @var \Enlight_Controller_Action $controller */
        $controller = $args->get('subject');
        $view = $controller->View();

        $view->addTemplateDir($this->pluginDirectory . '/Resources/views');

        $view->assign('fontSize', $this->config['fontSize']);
        $view->assign('italic', $this->config['italic']);
        $view->assign('slogan', $this->config['slogan']);

        if (!$this->config['slogan']) {
            $view->assign('slogan', $this->sloganPrinter->getSlogan());
        }
    }
}
