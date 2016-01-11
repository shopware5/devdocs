<?php
namespace Shopware\Devdocs\AlgoliaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder;

        $rootNode = $treeBuilder->root('sculpin_algolia');

        $rootNode
            ->children()
                ->scalarNode('application_id')->isRequired()->end()
                ->scalarNode('index_name')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
