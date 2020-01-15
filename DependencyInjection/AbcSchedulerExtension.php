<?php

namespace Abc\SchedulerBundle\DependencyInjection;

use Abc\Scheduler\ChainExtension;
use Abc\Scheduler\Extension\CheckCronExpressionExtension;
use Abc\Scheduler\Extension\SignalExtension;
use Abc\Scheduler\Extension\SingleIterationExtension;
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

        $schedulerExtensionsDefinition = $container->register('abc.scheduler.extensions', ChainExtension::class)
            ->addArgument([])
        ;

        $container->getDefinition('abc.scheduler')
            ->setArguments([
                $schedulerExtensionsDefinition,
                [],
                new Reference('logger'),
            ])
        ;

        $this->loadSingleIterationExtension($config, $container);
        $this->loadSignalExtension($config, $container);
        $this->loadCheckCronExtension($config, $container);
    }

    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        $rc = new \ReflectionClass(Configuration::class);

        $container->addResource(new FileResource($rc->getFileName()));

        return new Configuration($container->getParameter('kernel.debug'));
    }

    private function loadSingleIterationExtension(array $config, ContainerBuilder $container): void
    {
        $extension = $container->register('abc.scheduler.single_iteration_extension', SingleIterationExtension::class);
        $extension->addTag('abc.scheduler.extension');
    }

    private function loadCheckCronExtension(array $config, ContainerBuilder $container): void
    {
        $extension = $container->register('abc.scheduler.check_cron_expression_extension', CheckCronExpressionExtension::class);
        $extension->addTag('abc.scheduler.extension');
    }

    private function loadSignalExtension(array $config, ContainerBuilder $container): void
    {
        if ($config['extensions']['signal_extension']) {
            $extension = $container->register('abc.scheduler.signal_extension', SignalExtension::class);
            $extension->addTag('abc.scheduler.extension');
        }
    }
}
