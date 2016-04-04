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

use Abc\Bundle\SchedulerBundle\Event\SchedulerEvents;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers event listeners and subscribers for schedule events.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class RegisterListenersPass implements CompilerPassInterface
{

    private $dispatcherService;
    private $listenerTag;
    private $subscriberTag;

    /**
     * Constructor.
     *
     * @param string $dispatcherServiceName Service name of the event dispatcher within the processed container
     * @param string $listenerTag
     * @param string $subscriberTag
     */
    public function __construct($dispatcherServiceName = 'event_dispatcher', $listenerTag = 'abc.scheduler.event_listener', $subscriberTag = 'abc.scheduler.event_subscriber')
    {
        $this->dispatcherService = $dispatcherServiceName;
        $this->listenerTag       = $listenerTag;
        $this->subscriberTag     = $subscriberTag;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if(!$container->hasDefinition($this->dispatcherService) && !$container->hasAlias($this->dispatcherService))
        {
            return;
        }

        $definition = $container->findDefinition($this->dispatcherService);

        foreach($container->findTaggedServiceIds($this->listenerTag) as $id => $tags)
        {
            $def = $container->getDefinition($id);

            if(!$def->isPublic())
            {
                throw new \InvalidArgumentException(sprintf('The service "%s" must be public as event listeners are lazy-loaded.', $id));
            }

            if($def->isAbstract())
            {
                throw new \InvalidArgumentException(sprintf('The service "%s" must not be abstract as event listeners are lazy-loaded.', $id));
            }

            foreach($tags as $tag)
            {
                $priority = isset($tag['priority']) ? $tag['priority'] : 0;

                if(!isset($tag['method']))
                {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must define the "method" attribute on "%s" tags.', $id, $this->listenerTag));
                }

                $definition->addMethodCall('addListenerService', array(SchedulerEvents::SCHEDULE, array($id, $tag['method']), $priority));
            }
        }

        foreach($container->findTaggedServiceIds($this->subscriberTag) as $id => $attributes)
        {
            $def = $container->getDefinition($id);
            if(!$def->isPublic())
            {
                throw new \InvalidArgumentException(sprintf('The service "%s" must be public as event subscribers are lazy-loaded.', $id));
            }

            // We must assume that the class value has been correctly filled, even if the service is created by a factory
            $class = $def->getClass();

            $refClass  = new \ReflectionClass($class);
            $interface = 'Symfony\Component\EventDispatcher\EventSubscriberInterface';
            if(!$refClass->implementsInterface($interface))
            {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, $interface));
            }

            $definition->addMethodCall('addSubscriberService', array($id, $class));
        }
    }
}