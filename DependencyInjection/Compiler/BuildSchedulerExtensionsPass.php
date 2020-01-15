<?php

namespace Abc\SchedulerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class BuildSchedulerExtensionsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $extensionsId = 'abc.scheduler.extensions';

        if (false == $container->hasDefinition($extensionsId)) {
            throw new \LogicException(sprintf('Service "%s" not found', $extensionsId));
        }

        $tags = $container->findTaggedServiceIds('abc.scheduler.extension');

        $groupByPriority = [];
        foreach ($tags as $serviceId => $tagAttributes) {
            foreach ($tagAttributes as $tagAttribute) {

                $priority = (int) ($tagAttribute['priority'] ?? 0);

                $groupByPriority[$priority][] = new Reference($serviceId);
            }
        }

        krsort($groupByPriority, SORT_NUMERIC);

        $flatExtensions = [];
        foreach ($groupByPriority as $extension) {
            $flatExtensions = array_merge($flatExtensions, $extension);
        }

        $extensionsService = $container->getDefinition($extensionsId);
        $extensionsService->replaceArgument(0, array_merge($extensionsService->getArgument(0), $flatExtensions));
    }
}
