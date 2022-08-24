<?php declare(strict_types=1);

namespace B2bCustomerFrontendApi;

use Shopware\B2B\Common\B2BContainerBuilder;
use Shopware\B2B\Contact\Framework\DependencyInjection\ContactFrameworkConfiguration;
use Shopware\B2B\StoreFrontAuthentication\Framework\DependencyInjection\StoreFrontAuthenticationFrameworkConfiguration;
use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class B2bCustomerFrontendApi extends Plugin
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $containerBuilder = B2BContainerBuilder::create();
        $containerBuilder->addConfiguration(new StoreFrontAuthenticationFrameworkConfiguration());
        $containerBuilder->addConfiguration(new ContactFrameworkConfiguration());
        $containerBuilder->registerConfigurations($container);

        parent::build($container);
    }
}
