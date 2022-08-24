<?php declare(strict_types=1);

namespace B2bSalesRepresentativePlugin;

use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use SwagB2bPlugin\SwagB2bPlugin;

class B2bSalesRepresentativePlugin extends SwagB2bPlugin
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->setParameter($this->getContainerPrefix() . '.plugin_dir', $this->getPath());
        $container->setParameter($this->getContainerPrefix() . '.plugin_name', $this->getName());
        $this->loadFiles($container);
    }
}
