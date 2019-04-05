<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Tests\Schedule;

use Abc\Bundle\SchedulerBundle\Event\SchedulerEvent;
use Abc\Bundle\SchedulerBundle\Event\SchedulerEvents;
use Abc\Bundle\SchedulerBundle\Iterator\ScheduleManagerScheduleIterator;
use Abc\Bundle\SchedulerBundle\Model\Schedule;
use Abc\Bundle\SchedulerBundle\Schedule\Exception\ScheduleException;
use Abc\Bundle\SchedulerBundle\Schedule\Exception\SchedulerException;
use Abc\Bundle\SchedulerBundle\Schedule\ProcessorRegistry;
use Abc\Bundle\SchedulerBundle\Schedule\Scheduler;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Abc\Bundle\SchedulerBundle\Schedule\ProcessorRegistryInterface;
use Abc\Bundle\SchedulerBundle\Schedule\ProcessorInterface;
use Abc\Bundle\SchedulerBundle\Model\ScheduleManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class SchedulerTest extends TestCase
{
    /**
     * @var EventDispatcherInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dispatcher;

    /**
     * @var ProcessorRegistryInterface
     */
    private $registry;

    /**
     * @var ProcessorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $processor;

    /**
     * @var ScheduleManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $manager;

    /**
     * @var Scheduler
     */
    private $subject;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->manager    = $this->createMock(ScheduleManagerInterface::class);
        $this->processor  = $this->createMock(ProcessorInterface::class);
        $this->registry   = new ProcessorRegistry();
        $this->registry->register('type', $this->processor);

        $this->subject = new Scheduler($this->registry, $this->dispatcher);
    }

    public function testProcess()
    {
        $schedules = $this->createSchedules(5);
        $this->initManager($schedules);

        $this->processor->expects($this->exactly(5))
            ->method('process')
            ->will($this->returnValue(true));

        $this->dispatcher->expects($this->exactly(5))
            ->method('dispatch')
            ->with(SchedulerEvents::SCHEDULE, $this->isInstanceOf(SchedulerEvent::class));

        $this->manager->expects($this->exactly(5))
            ->method('save')
            ->with($this->isInstanceOf(Schedule::class));

        $numOfScheduled = $this->subject->process(new ScheduleManagerScheduleIterator($this->manager));

        $this->assertEquals(5, $numOfScheduled);
    }

    public function testProcessSkipsNonDue()
    {
        $schedules = $this->createSchedules(1);
        $this->initManager($schedules);

        $this->processor->expects($this->once())
            ->method('process')
            ->will($this->returnValue(false));

        $this->dispatcher->expects($this->never())
            ->method('dispatch');

        $this->manager->expects($this->never())
            ->method('save');

        $numOfScheduled = $this->subject->process(new ScheduleManagerScheduleIterator($this->manager));

        $this->assertEquals(0, $numOfScheduled);
    }

    public function testProcessHandlesExceptions()
    {
        $schedules = $this->createSchedules(2);
        $this->initManager($schedules);

        $exception = new \Exception();

        $this->processor->expects($this->any())
            ->method('process')
            ->will($this->returnValue(true));

        $this->processor->expects($this->at(0))
            ->method('process')
            ->will($this->throwException($exception));

        $this->dispatcher->expects($this->once())
            ->method('dispatch');

        $this->manager->expects($this->once())
            ->method('save');

        try
        {
            $this->subject->process(new ScheduleManagerScheduleIterator($this->manager));
            $this->fail('throws SchedulerException');
        }
        catch(SchedulerException $e)
        {
            $this->assertEquals(1, $e->getNumOfProcessed());

            $schedulerExceptions = $e->getScheduleExceptions();

            $this->assertCount(1, $schedulerExceptions);

            /** @var ScheduleException $schedulerException */
            $schedulerException = $schedulerExceptions[0];

            $this->assertSame($exception, $schedulerException->getException());

            // elements are popped, that's why the last element of $schedules must be the same
            $this->assertSame($schedules[1], $schedulerException->getSchedule());
        }
    }

    private function initManager(array $schedules)
    {
        $this->manager->expects($this->any())
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

    private function createSchedules($num)
    {
        $schedules = array();
        for($i = 0; $i < $num; $i++)
        {
            $schedule = new Schedule();
            $schedule->setType('type');

            $schedules[] = $schedule;
        }

        return $schedules;
    }
}