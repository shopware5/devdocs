<?php declare(strict_types=1);

namespace B2bLogin\Subscriber;

use Enlight\Event\SubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FrontendTemplateExtender implements SubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Frontend' => 'addViewDirectories',
            'Enlight_Controller_Action_PostDispatchSecure_Widgets' => 'addViewDirectories',
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function addViewDirectories(\Enlight_Controller_ActionEventArgs $args)
    {
        $args->getSubject()->View()->addTemplateDir(__DIR__ . '/../Resources/views');
        $args->getSubject()->View()->addTemplateDir(__DIR__ . '/../Resources/extendedViews');
    }
}
