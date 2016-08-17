<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Tests\Integration;

use Abc\Bundle\SchedulerBundle\Iterator\IteratorRegistryInterface;
use Abc\Bundle\SchedulerBundle\Schedule\SchedulerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ServiceTest extends KernelTestCase
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();

        $this->container   = static::$kernel->getContainer();
        $this->application = new Application(static::$kernel);
        $this->application->setAutoExit(false);
        $this->application->setCatchExceptions(false);
    }

    /**
     * @param string $service
     * @param string $type
     * @dataProvider getServices
     */
    public function testGetFromContainer($service, $type)
    {
        $subject = $this->container->get($service);

        $this->assertInstanceOf($type, $subject);
    }

    /**
     * @return array
     */
    public function getServices()
    {
        return array(
            ['abc.scheduler.scheduler', SchedulerInterface::class],
            ['abc.scheduler.iterator_registry', IteratorRegistryInterface::class]
        );
    }
}