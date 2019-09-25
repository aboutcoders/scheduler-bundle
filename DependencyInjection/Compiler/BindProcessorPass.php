<?php

namespace Abc\SchedulerBundle\DependencyInjection\Compiler;

use Abc\Scheduler\ProcessorInterface;
use Abc\Scheduler\ProviderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BindProcessorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $schedulerDefinition = $container->getDefinition('abc.scheduler');

        $tag = 'abc.scheduler.schedule_processor';

        foreach ($container->findTaggedServiceIds($tag) as $serviceId => $tagAttributes) {

            $processorDefinition = $container->getDefinition($serviceId);
            $processorClass = $processorDefinition->getClass();
            if (false == class_exists($processorClass)) {
                throw new \LogicException(sprintf('The processor class "%s" could not be found.', $processorClass));
            }

            if (false == is_subclass_of($processorClass, ProcessorInterface::class)) {
                throw new \LogicException(sprintf('A processor must implement "%s" interface to be used with the tag "%s"', ProcessorInterface::class, $tag));
            }

            foreach ($tagAttributes as $tagAttribute) {
                if (! array_key_exists('provider', $tagAttribute)) {
                    throw new \LogicException(sprintf('The attribute "%s" must be provided with the tag "%s"', 'provider', $tag));
                }

                $providerServiceId = $tagAttribute['provider'];
                if (! $container->hasDefinition($providerServiceId)) {
                    throw new \LogicException(sprintf('A service with the id "%s" could not be found', $providerServiceId));
                }

                $providerDefinition = $container->getDefinition($providerServiceId);
                $providerClass = $providerDefinition->getClass();

                if (false == class_exists($providerClass)) {
                    throw new \LogicException(sprintf('The provider class "%s" could not be found.', $providerClass));
                }

                if (false == is_subclass_of($providerClass, ProviderInterface::class)) {
                    throw new \LogicException(sprintf('A provider must implement "%s" interface to be used with the tag "%s"', ProviderInterface::class, $tag));
                }

                $schedulerDefinition->addMethodCall('bindProcessor', [
                    $providerDefinition,
                    $processorDefinition,
                ]);
            }
        }
    }
}
