<?php

namespace Abc\SchedulerBundle\DependencyInjection;

use Abc\Scheduler\ChainExtension;
use Abc\Scheduler\Extension\CheckCronExpressionExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class AbcSchedulerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration($configs, $container), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $cronExtensionDefinition = $container->register('abc.scheduler.extension.check_cron_expression', CheckCronExpressionExtension::class);

        $chainExtensionDefinition = $container->register('abc.scheduler.extension', ChainExtension::class);
        $chainExtensionDefinition->setArguments([
            [$cronExtensionDefinition]
        ]);

        $container->getDefinition('abc.scheduler')->setArguments([
            $chainExtensionDefinition,
            [],
            new Reference('logger')
        ]);
    }

    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        $rc = new \ReflectionClass(Configuration::class);

        $container->addResource(new FileResource($rc->getFileName()));

        return new Configuration($container->getParameter('kernel.debug'));
    }
}
