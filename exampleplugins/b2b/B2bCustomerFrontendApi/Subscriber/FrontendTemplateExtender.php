<?php declare(strict_types=1);

namespace B2bCustomerFrontendApi\Subscriber;

use Enlight\Event\SubscriberInterface;

class FrontendTemplateExtender implements SubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Frontend_B2bCustomerApi' => 'addViewDirectories',
            'Enlight_Controller_Action_PreDispatch_Frontend_B2bCustomerDirectApi' => 'addViewDirectories',
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function addViewDirectories(\Enlight_Controller_ActionEventArgs $args)
    {
        $args->getSubject()->View()->addTemplateDir(__DIR__ . '/../Resources/views');
    }
}
