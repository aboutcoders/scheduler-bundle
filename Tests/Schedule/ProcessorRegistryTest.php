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

use Abc\Bundle\SchedulerBundle\Schedule\ProcessorRegistry;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ProcessorRegistryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ProcessorRegistry */
    private $subject;

    public function setUp()
    {
        $this->subject = new ProcessorRegistry();
    }

    public function testRegister()
    {
        $processor = $this->getMock('Abc\Bundle\SchedulerBundle\Schedule\ProcessorInterface');

        $this->subject->register('foobar', $processor);
        $this->assertSame($processor, $this->subject->get('foobar'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetThrowsInvalidArgumentException()
    {
        $this->subject->get('foo');
    }
}