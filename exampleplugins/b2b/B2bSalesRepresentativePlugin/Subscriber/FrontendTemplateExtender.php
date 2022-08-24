<?php declare(strict_types=1);

namespace B2bSalesRepresentativePlugin\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use Shopware\B2B\SalesRepresentative\Framework\SalesRepresentativeIdentity;
use Shopware\Components\Theme\LessDefinition;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FrontendTemplateExtender implements SubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Frontend' => ['addViewDirectories', -1],
            'Enlight_Controller_Action_PostDispatchSecure_Widgets' => ['addViewDirectories', -1],
        ];
    }

    public function addViewDirectories(\Enlight_Controller_ActionEventArgs $args)
    {
        $args->getSubject()->View()->addTemplateDir(__DIR__ . '/../Resources/views');
        $args->getSubject()->View()->addTemplateDir(__DIR__ . '/../Resources/extendedViews');
    }
}
