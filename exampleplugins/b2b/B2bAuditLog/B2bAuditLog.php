<?php declare(strict_types=1);

namespace B2bAuditLog;

use Shopware\B2B\Common\B2BContainerBuilder;
use Shopware\Components\Plugin;
use SwagB2bPlugin\Resources\DependencyInjection\SwagB2bPluginConfiguration;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class B2bAuditLog extends Plugin
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $containerBuilder = B2BContainerBuilder::create();
        $containerBuilder->addConfiguration(new SwagB2bPluginConfiguration());
        $containerBuilder->registerConfigurations($container);

        parent::build($container);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure' => 'onPostDispatchSecure',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onPostDispatchSecure(\Enlight_Event_EventArgs $args)
    {
        $this->container->get('Template')->addTemplateDir(
            $this->getPath() . '/Resources/views/'
        );
    }
}
