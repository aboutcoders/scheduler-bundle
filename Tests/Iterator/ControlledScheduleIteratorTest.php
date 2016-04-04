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

use Abc\Bundle\SchedulerBundle\Iterator\ControlledScheduleIterator;
use Abc\Bundle\SchedulerBundle\Iterator\ScheduleIteratorInterface;
use Abc\ProcessControl\Controller;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ControlledScheduleIteratorTest extends \PHPUnit_Framework_TestCase
{

    /** @var Controller|\PHPUnit_Framework_MockObject_MockObject */
    private $controller;
    /** @var ScheduleIteratorInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $scheduleIterator;
    /** @var ControlledScheduleIterator */
    private $subject;

    public function setUp()
    {
        $this->controller = $this->getMock('Abc\ProcessControl\Controller');
        $this->scheduleIterator  = $this->getMock('Abc\Bundle\SchedulerBundle\Iterator\ScheduleIteratorInterface');
        $this->subject = new ControlledScheduleIterator($this->controller, $this->scheduleIterator);
    }

    public function testCurrent()
    {
        $this->scheduleIterator->expects($this->once())
            ->method('current')
            ->will($this->returnValue('foo'));

        $this->assertEquals('foo', $this->subject->current());
    }

    public function testNext()
    {
        $this->scheduleIterator->expects($this->once())
            ->method('next');

        $this->subject->next();
    }

    public function testKey()
    {
        $this->scheduleIterator->expects($this->once())
            ->method('key')
            ->will($this->returnValue('foo'));

        $this->assertEquals('foo', $this->subject->key());
    }

    /**
     * @param bool $doExit
     * @param bool $valid
     * @dataProvider getBooleanArray
     */
    public function testValid($doExit, $valid)
    {
        $this->scheduleIterator->expects($this->any())
            ->method('valid')
            ->willReturn($valid);

        $this->controller->expects($this->any())
            ->method('doExit')
            ->willReturn($doExit);

        if($doExit)
        {
            $this->assertEquals(false, $this->subject->valid());
        }
        else
        {
            $this->assertEquals($valid, $this->subject->valid());
        }
    }

    public function testRewind()
    {
        $this->scheduleIterator->expects($this->once())
            ->method('rewind');

        $this->subject->rewind();
    }

    public function testGetManager()
    {
        $this->scheduleIterator->expects($this->once())
            ->method('getManager')
            ->will($this->returnValue('foo'));

        $this->assertEquals('foo', $this->subject->getManager());
    }

    /**
     * @return array
     */
    public static function getBooleanArray()
    {
        return array(
            array(true, true),
            array(true, false),
            array(false, false),
            array(false, true)
        );
    }
}
 