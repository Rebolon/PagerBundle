<?php

namespace Rebolon\Bundle\Pager\DependencyInjection;

use \Symfony\Component\Config\Definition\Builder\TreeBuilder;
use \Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rebolon_pager');

        $rootNode
            ->children()
                ->scalarNode('suffixname')->defaultValue('pager')->end()
                ->scalarNode('itemperpage')->defaultValue(5)->end()
                ->scalarNode('maxpageritem')->defaultValue(5)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
