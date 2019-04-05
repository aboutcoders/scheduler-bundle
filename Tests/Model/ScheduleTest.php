<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Tests\Model;

use Abc\Bundle\SchedulerBundle\Model\Schedule;
use PHPUnit\Framework\TestCase;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleTest extends TestCase
{
    public function testExpression()
    {
        $schedule = $this->getSchedule();
        $this->assertNull($schedule->getExpression());

        $expression = '* * * * *';
        $schedule->setExpression($expression);
        $this->assertEquals($expression, $schedule->getExpression());
    }

    public function testScheduledAt()
    {
        $schedule = $this->getSchedule();
        $this->assertNull($schedule->getExpression());

        $scheduledAt = new \DateTime();
        $schedule->setScheduledAt($scheduledAt);
        $this->assertEquals($scheduledAt, $schedule->getScheduledAt());
    }

    /**
     * @return Schedule
     */
    protected function getSchedule()
    {
        return new Schedule();
    }

    public function testClone()
    {
        $schedule = new Schedule();
        $schedule->setScheduledAt(new \DateTime);

        $clone = clone $schedule;

        $this->assertNull($clone->getScheduledAt());
    }
}