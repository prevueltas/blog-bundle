<?php

namespace Prh\BlogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('prh_blog');

        $rootNode
            ->children()
                ->integerNode('image_size_small')->defaultValue(175)->end()
                ->integerNode('image_size_medium')->defaultValue(375)->end()
                ->integerNode('image_size_big')->defaultValue(750)->end()
                ->integerNode('image_size_max')->defaultValue(1200)->end()
            ->end();

        return $treeBuilder;
    }
}
