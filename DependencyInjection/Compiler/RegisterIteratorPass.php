<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers services tagged as expression checkers in the expression checker registry.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class RegisterIteratorPass implements CompilerPassInterface
{

    /** @var string */
    protected $registryService;
    /** @var string */
    protected $tagName;


    /**
     * @param string $registryServiceName
     * @param string $tagName
     */
    public function __construct($registryServiceName = 'abc.scheduler.iterator_registry', $tagName = 'abc.scheduler.iterator')
    {
        $this->registryService = $registryServiceName;
        $this->tagName         = $tagName;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->registryService) && !$container->hasAlias($this->registryService))
        {
            return;
        }

        $definition = $container->findDefinition($this->registryService);

        foreach ($container->findTaggedServiceIds($this->tagName) as $id => $tags)
        {
            foreach ($tags as $tag)
            {
                $definition->addMethodCall('register',  array($id, new Reference($id)));
            }
        }
    }
}