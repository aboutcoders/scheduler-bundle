<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Tests\Command;

use Abc\Bundle\SchedulerBundle\Command\SchedulerProcessCommand;
use Abc\Bundle\SchedulerBundle\Iterator\IteratorRegistry;
use Abc\Bundle\SchedulerBundle\Iterator\ScheduleManagerScheduleIterator;
use Abc\Bundle\SchedulerBundle\Model\Schedule;
use Abc\Bundle\SchedulerBundle\Model\ScheduleManagerInterface;
use Abc\Bundle\SchedulerBundle\Schedule\Exception\ScheduleException;
use Abc\Bundle\SchedulerBundle\Schedule\Exception\SchedulerException;
use Abc\Bundle\SchedulerBundle\Schedule\SchedulerInterface;
use Abc\ProcessControl\ControllerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class SchedulerProcessCommandTest extends TestCase
{
    /**
     * @var ContainerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $container;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var SchedulerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $scheduler;

    /**
     * @var ControllerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $controller;

    /**
     * @var IteratorRegistry
     */
    private $registry;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $controller = $this->createMock(ControllerInterface::class);
        $scheduler        = $this->createMock(SchedulerInterface::class);
        $registry         = new IteratorRegistry();

        $this->container = $this->createMock(ContainerInterface::class);

        $this->container->expects($this->any())
            ->method('get')
            ->will(
                $this->returnCallback(
                    function ($key) use ($scheduler, $registry, $controller)
                    {
                        if($key == 'abc.scheduler.scheduler')
                        {
                            return $scheduler;
                        }
                        elseif($key == 'abc.scheduler.iterator_registry')
                        {
                            return $registry;
                        }
                        elseif($key == 'abc.process_control.controller')
                        {
                            return $controller;
                        }
                    }
                )
            );

        $controller->expects($this->any())
            ->method('doStop')
            ->willReturn(false);

        $command = new SchedulerProcessCommand();
        $command->setContainer($this->container);

        $kernel = $this->createMock(KernelInterface::class);

        $this->application = new Application($kernel);
        $this->application->add($command);

        $this->scheduler = $scheduler;
        $this->registry  = $registry;
        $this->controller = $controller;
    }


    public function testExecuteProcessesSchedules()
    {
        $fooIterator = $this->buildIterator(2);
        $barIterator = $this->buildIterator(3);

        $this->registry->register('foo', $fooIterator);
        $this->registry->register('bar', $barIterator);

        $this->scheduler->expects($this->exactly(2))
            ->method('process')
            ->will(
                $this->returnCallback(
                    function ($iterator) use ($fooIterator, $barIterator)
                    {
                        return $iterator === $fooIterator ? 2 : 3;
                    }
                )
            );

        $input = array('--iteration' => 1);

        $command       = $this->application->find('abc:scheduler:process');
        $commandTester = new CommandTester($command);
        $commandTester->execute($input);
    }

    public function testExecuteProcessesHandlesSchedulerException()
    {
        $fooIterator = $this->buildIterator(2);

        $this->registry->register('foo', $fooIterator);

        $this->scheduler->expects($this->at(0))
            ->method('process')
            ->will($this->throwException(new SchedulerException(1, array(new ScheduleException(new Schedule(), new \Exception())))));

        $input = array('--iteration' => 1);

        $command       = $this->application->find('abc:scheduler:process');
        $commandTester = new CommandTester($command);
        $commandTester->execute($input);
    }

    /**
     *
     */
    public function testExecuteHandlesException()
    {
        $this->registry->register('foo', $this->buildIterator(2));

        $this->scheduler->expects($this->any())
            ->method('process')
            ->will($this->throwException(new \Exception()));

        $input = array('--iteration' => 1);

        $command       = $this->application->find('abc:scheduler:process');
        $commandTester = new CommandTester($command);

        try {
            $commandTester->execute($input);
            $this->assertTrue(true, 'handles exceptions');
        }
        catch (\Exception $e)
        {
            $this->assertTrue(false, 'handles exceptions');
        }
    }

    /**
     * @param $numOfSchedules
     * @return ScheduleManagerScheduleIterator
     */
    private function buildIterator($numOfSchedules)
    {
        $schedules = $this->createSchedules($numOfSchedules);
        $manager   = $this->createMock(ScheduleManagerInterface::class);
        $this->initManager($manager, $schedules);

        return new ScheduleManagerScheduleIterator($manager);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $manager
     * @param array                                    $schedules
     */
    private function initManager(\PHPUnit\Framework\MockObject\MockObject $manager, array $schedules)
    {
        $manager->expects($this->any())
            ->method('findSchedules')
            ->will(
                $this->returnCallback(
                    function ($limit = null, $offset = null) use ($schedules)
                    {
                        return array_slice($schedules, $offset, $limit);
                    }
                )
            );
    }

    /**
     * @param $num
     * @return array
     */
    private function createSchedules($num)
    {
        $schedules = array();
        for($i = 0; $i < $num; $i++)
        {
            $schedule    = new Schedule();
            $schedules[] = $schedule;
        }

        return $schedules;
    }
}