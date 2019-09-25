<?php

namespace Abc\SchedulerBundle;

use Abc\SchedulerBundle\DependencyInjection\Compiler\BindProcessorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AbcSchedulerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new BindProcessorPass());
    }
}
