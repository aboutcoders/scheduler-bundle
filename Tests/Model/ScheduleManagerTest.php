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

use Abc\Bundle\SchedulerBundle\Model\ScheduleManager;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleManagerTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $manager = $this->getManager();

        $manager->expects($this->once())
            ->method('getClass')
            ->will($this->returnValue('Abc\Bundle\SchedulerBundle\Model\Schedule'));

        $schedule = $manager->create();

        $this->assertInstanceOf('Abc\Bundle\SchedulerBundle\Model\Schedule', $schedule);
    }

    /**
     * @return ScheduleManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getManager()
    {
        return $this->getMockForAbstractClass('Abc\Bundle\SchedulerBundle\Model\ScheduleManager');
    }
}
 