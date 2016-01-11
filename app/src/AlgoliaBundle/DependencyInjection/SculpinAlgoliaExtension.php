<?php
namespace Shopware\Devdocs\AlgoliaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SculpinAlgoliaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        if (!$container->hasParameter('algolia_enabled')) {
            return;
        }

        if (!$container->hasParameter('algolia_api_key')) {
            throw new \RuntimeException('The paramter algolia_api_key is missing. Can be set using the SYMFONY__ALGOLIA_API_KEY environment variable.');
        }

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('algolia.application_id', $config['application_id']);
        $container->setParameter('algolia.index_name',     $config['index_name']);
    }
}
