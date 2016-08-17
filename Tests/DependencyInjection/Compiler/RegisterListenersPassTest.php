<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Tests\DependencyInjection\Compiler;

use Abc\Bundle\SchedulerBundle\DependencyInjection\Compiler\RegisterListenersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class RegisterListenersPassTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests that event subscribers not implementing EventSubscriberInterface
     * trigger an exception.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testEventSubscriberWithoutInterface()
    {
        // one service, not implementing any interface
        $services = array(
            'my_event_subscriber' => array(0 => array()),
        );

        $definition = $this->getMock(Definition::class);

        $definition->expects($this->atLeastOnce())
            ->method('isPublic')
            ->will($this->returnValue(true));

        $definition->expects($this->atLeastOnce())
            ->method('getClass')
            ->will($this->returnValue('stdClass'));

        $builder = $this->getMock(ContainerBuilder::class);

        $builder->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(true));

        // We don't test abc_scheduler.event_listener here
        $builder->expects($this->atLeastOnce())
            ->method('findTaggedServiceIds')
            ->will($this->onConsecutiveCalls(array(), $services));

        $builder->expects($this->atLeastOnce())
            ->method('getDefinition')
            ->will($this->returnValue($definition));

        $registerListenersPass = new RegisterListenersPass();
        $registerListenersPass->process($builder);
    }


    public function testProcessBindsValidEventSubscriber()
    {
        $services = array(
            'my_event_subscriber' => array(0 => array()),
        );

        $definition = $this->getMock(Definition::class);

        $definition->expects($this->atLeastOnce())
            ->method('isPublic')
            ->will($this->returnValue(true));

        $definition->expects($this->atLeastOnce())
            ->method('getClass')
            ->will($this->returnValue(SubscriberService::class));

        $builder = $this->getMock(ContainerBuilder::class);

        $builder->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(true));

        // We don't test abc_scheduler.event_listener here
        $builder->expects($this->atLeastOnce())
            ->method('findTaggedServiceIds')
            ->will($this->onConsecutiveCalls(array(), $services));

        $builder->expects($this->atLeastOnce())
            ->method('getDefinition')
            ->will($this->returnValue($definition));

        $builder->expects($this->atLeastOnce())
            ->method('findDefinition')
            ->will($this->returnValue($definition));

        $registerListenersPass = new RegisterListenersPass();
        $registerListenersPass->process($builder);
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The service "foo" must be public as event listeners are lazy-loaded.
     */
    public function testProcessThrowsExceptionOnPrivateEventListener()
    {
        $container = new ContainerBuilder();
        $container->register('foo', 'stdClass')->setPublic(false)->addTag('abc.scheduler.event_listener', array());
        $container->register('event_dispatcher', 'stdClass');

        $registerListenersPass = new RegisterListenersPass();
        $registerListenersPass->process($container);
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The service "foo" must be public as event subscribers are lazy-loaded.
     */
    public function testProcessThrowsExceptionOnPrivateEventSubscriber()
    {
        $container = new ContainerBuilder();
        $container->register('foo', 'stdClass')->setPublic(false)->addTag('abc.scheduler.event_subscriber', array());
        $container->register('event_dispatcher', 'stdClass');

        $registerListenersPass = new RegisterListenersPass();
        $registerListenersPass->process($container);
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The service "foo" must not be abstract as event listeners are lazy-loaded.
     */
    public function testProcessThrowsExceptionOnAbstractEventListener()
    {
        $container = new ContainerBuilder();
        $container->register('foo', 'stdClass')->setAbstract(true)->addTag('abc.scheduler.event_listener', array());
        $container->register('event_dispatcher', 'stdClass');

        $registerListenersPass = new RegisterListenersPass();
        $registerListenersPass->process($container);
    }
}

class SubscriberService implements EventSubscriberInterface
{
    public static function getSubscribedEvents() { }
}