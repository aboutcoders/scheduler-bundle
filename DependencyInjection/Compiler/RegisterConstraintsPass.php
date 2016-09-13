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

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class RegisterConstraintsPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $registry;

    /**
     * @var string
     */
    private $tag;

    /**
     * @param string $registry Service name of the definition registry in processed container
     * @param string $tag      The tag name used for jobs
     */
    public function __construct($registry = 'abc.scheduler.constraint_registry', $tag = 'abc.scheduler.constraint')
    {
        $this->registry = $registry;
        $this->tag      = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->registry) && !$container->hasAlias($this->registry)) {
            return;
        }

        $registry = $container->findDefinition($this->registry);
        foreach ($container->findTaggedServiceIds($this->tag) as $id => $tags) {
            $definition = $container->getDefinition($id);
            foreach ($tags as $tag) {
                if (!isset($tag['type'])) {
                    throw new \InvalidArgumentException(sprintf('The service "%s" must define the attribute "type" on "%s" tags.', $id, $this->tag));
                }

                $registry->addMethodCall('register', [$tag['type'], $definition]);
            }
        }
    }
}