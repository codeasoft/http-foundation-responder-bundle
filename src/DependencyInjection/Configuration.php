<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('tuzex');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('responder')
                    ->children()
                        ->arrayNode('middlewares')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
