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

use Abc\Bundle\SchedulerBundle\Schedule\ProcessorInterface;
use Abc\Bundle\SchedulerBundle\Schedule\ProcessorRegistry;
use PHPUnit\Framework\TestCase;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ProcessorRegistryTest extends TestCase
{
    /**
     * @var ProcessorRegistry
     */
    private $subject;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->subject = new ProcessorRegistry();
    }

    public function testRegister()
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $this->subject->register('foobar', $processor);
        $this->assertSame($processor, $this->subject->get('foobar'));
    }

    public function testHas()
    {
        $this->assertFalse($this->subject->has('foobar'));
        $this->subject->register('foobar', $this->createMock(ProcessorInterface::class));
        $this->assertTrue($this->subject->has('foobar'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetThrowsInvalidArgumentException()
    {
        $this->subject->get('foo');
    }
}