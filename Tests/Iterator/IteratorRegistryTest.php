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

use Abc\Bundle\SchedulerBundle\Iterator\IteratorRegistry;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class IteratorRegistryTest extends \PHPUnit_Framework_TestCase
{

    /** @var IteratorRegistry */
    private $subject;

    public function setUp()
    {
        $this->subject = new IteratorRegistry();
    }

    public function testRegister()
    {
        $iterator = $this->getMock('Abc\Bundle\SchedulerBundle\Iterator\ScheduleIteratorInterface');

        $this->subject->register('foobar', $iterator);
        $this->assertSame($iterator, $this->subject->get('foobar'));
    }

    public function testAll()
    {
        $iterator1 = $this->getMock('Abc\Bundle\SchedulerBundle\Iterator\ScheduleIteratorInterface');
        $iterator2 = $this->getMock('Abc\Bundle\SchedulerBundle\Iterator\ScheduleIteratorInterface');

        $this->subject->register('foo', $iterator1);
        $this->subject->register('bar', $iterator2);

        $this->assertEquals(array('foo' => $iterator1, 'bar' => $iterator2), $this->subject->all());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetThrowsInvalidArgumentException()
    {
        $this->subject->get('foo');
    }
}
 