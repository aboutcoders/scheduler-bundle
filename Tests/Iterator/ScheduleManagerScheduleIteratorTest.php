<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Tests\Iterator;

use Abc\Bundle\SchedulerBundle\Iterator\ScheduleManagerScheduleIterator;
use Abc\Bundle\SchedulerBundle\Model\ScheduleManagerInterface;
use Abc\DemoBundle\Entity\Schedule;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleManagerScheduleIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ScheduleManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $manager;

    /**
     * @var ScheduleManagerScheduleIterator
     */
    private $subject;

    public function setUp()
    {
        $this->manager = $this->getMock(ScheduleManagerInterface::class);
        $this->subject = new ScheduleManagerScheduleIterator($this->manager, 2);
    }

    public function testGetManager()
    {
        $this->assertSame($this->manager, $this->subject->getManager());
    }

    /**
     * @param $numOfSchedules
     * @param $pageSize
     * @dataProvider getIteratorData
     */
    public function testIterator($numOfSchedules, $pageSize)
    {
        $expectationMap = $this->buildExpectationMap($numOfSchedules);

        $subject   = new ScheduleManagerScheduleIterator($this->manager, $pageSize);
        $schedules = $this->createSchedules($numOfSchedules);

        $this->initManager($schedules);

        foreach($subject as $schedule)
        {
            if(array_key_exists($schedule->getExpression(), $expectationMap))
            {
                unset($expectationMap[$schedule->getExpression()]);
            }
        }

        $this->assertEmpty($expectationMap);
    }

    public function testKey()
    {
        $subject   = new ScheduleManagerScheduleIterator($this->manager, 10);
        $schedules = $this->createSchedules(5);

        $this->initManager($schedules);

        $this->assertEmpty(0, $subject->key());
    }

    public function testValid()
    {
        $subject   = new ScheduleManagerScheduleIterator($this->manager, 10);
        $this->initManager(array());

        $this->assertFalse($subject->valid());
    }


    public static function getIteratorData()
    {
        return array(
            array(10,10),
            array(10,1),
            array(10,15),
            array(15,10),
        );
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
            $schedule->setExpression($i+1);

            $schedules[] = $schedule;
        }

        return $schedules;
    }

    private function buildExpectationMap($numOfSchedules)
    {
        $map = array();
        for($i=1; $i<=$numOfSchedules; $i++)
        {
            $map[$i] = null;
        }

        return $map;
    }
}