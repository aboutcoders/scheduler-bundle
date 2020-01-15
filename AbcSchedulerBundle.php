<?php

namespace Abc\SchedulerBundle;

use Abc\SchedulerBundle\DependencyInjection\Compiler\BindProcessorPass;
use Abc\SchedulerBundle\DependencyInjection\Compiler\BuildSchedulerExtensionsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AbcSchedulerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new BindProcessorPass());
        $container->addCompilerPass(new BuildSchedulerExtensionsPass());
    }
}
