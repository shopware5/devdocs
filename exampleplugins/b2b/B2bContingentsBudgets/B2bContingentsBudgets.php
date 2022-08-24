<?php declare(strict_types=1);

namespace B2bContingentsBudgets;

use Shopware\B2B\Common\B2BContainerBuilder;
use Shopware\Components\Plugin;
use SwagB2bPlugin\Resources\DependencyInjection\SwagB2bPluginFrontendConfiguration;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class B2bContingentsBudgets extends Plugin
{
    public function build(ContainerBuilder $container)
    {
        $containerBuilder = B2BContainerBuilder::create();
        $containerBuilder->addConfiguration(new SwagB2bPluginFrontendConfiguration());
        $containerBuilder->registerConfigurations($container);

        parent::build($container);
    }
}
