<?php

namespace YousignBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('yousign');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->children()
                    ->scalarNode('baseUrl')->defaultValue('https://api.yousign.com')->cannotBeEmpty()->isRequired()->end()
                    ->scalarNode('apiKey')->cannotBeEmpty()->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
