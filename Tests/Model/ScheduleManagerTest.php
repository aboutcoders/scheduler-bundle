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
use Abc\Bundle\SchedulerBundle\Model\ScheduleManager;
use PHPUnit\Framework\TestCase;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleManagerTest extends TestCase
{
    public function testCreate()
    {
        $manager = $this->getManager();

        $manager->expects($this->once())
            ->method('getClass')
            ->will($this->returnValue(Schedule::class));

        $schedule = $manager->create();

        $this->assertInstanceOf(Schedule::class, $schedule);
    }

    /**
     * @return ScheduleManager|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getManager()
    {
        return $this->getMockForAbstractClass(ScheduleManager::class);
    }
}