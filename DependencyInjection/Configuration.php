<?php

namespace Abc\SchedulerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    private $debug;

    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (method_exists(TreeBuilder::class, 'getRootNode')) {
            $tb = new TreeBuilder('abc_scheduler');
            $rootNode = $tb->getRootNode();
        } else {
            $tb = new TreeBuilder();
            $rootNode = $tb->root('abc_scheduler');
        }

        // @formatter:off
        $rootNode
            ->useAttributeAsKey('key')
            ->arrayPrototype()
            ->children()
                ->arrayNode('extensions')->addDefaultsIfNotSet()->children()
                    ->booleanNode('signal_extension')->defaultValue(function_exists('pcntl_signal_dispatch'))->end()
                ->end()->end()
            ->end()

        ;
        // @formatter:on

        return $tb;
    }
}
