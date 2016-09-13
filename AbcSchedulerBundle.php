<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle;

use Abc\Bundle\SchedulerBundle\DependencyInjection\Compiler\RegisterIteratorPass;
use Abc\Bundle\SchedulerBundle\DependencyInjection\Compiler\RegisterListenersPass;
use Abc\Bundle\SchedulerBundle\DependencyInjection\Compiler\RegisterProcessorPass;
use Abc\Bundle\SchedulerBundle\DependencyInjection\Compiler\RegisterConstraintsPass;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class AbcSchedulerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterListenersPass());
        $container->addCompilerPass(new RegisterProcessorPass());
        $container->addCompilerPass(new RegisterIteratorPass());
        $container->addCompilerPass(new RegisterConstraintsPass());

        $this->addRegisterMappingsPass($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        $mappings = array(
            realpath(__DIR__ . '/Resources/config/doctrine-mapping') => 'Abc\Bundle\SchedulerBundle\Model',
        );

        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, array('abc.scheduler.model_manager_name'), 'abc.scheduler.backend_type_orm'));

    }
}